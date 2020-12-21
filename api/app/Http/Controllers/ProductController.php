<?php

namespace App\Http\Controllers;

use App\Events\ProductLogging;
use App\Http\Requests\GetProductRequest;
use App\Models\Booking;
use App\Models\Site;
use App\Models\SlotBooking;
use App\Models\SlotTime;
use App\Models\Tag;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Requests\SaveProductRequest;
use App\Http\Requests\SaveRatingRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Locale;
use App\Models\Asset;
use App\Models\Slot;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetProductRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetProductRequest $request)
    {
        $filter = $request->get('filters');
        $products = Product::select('products.*')
        ->when($filter, function ($query) use ($filter) {
            /**
             * @var string
             */
            $filter = str_replace(' ', '%', $filter);
            $query->where(function ($query) use ($filter) {
                $filter = '%'.$filter.'%';
                $query
                    ->whereRaw(
                        'cast(json_unquote(json_extract(`name`, \'$."fr"\')) as CHAR) like ?',
                        [$filter]
                    )
                    ->orWhereRaw(
                        'cast(json_unquote(json_extract(`name`, \'$."en"\')) as CHAR) like ?',
                        [$filter]
                    )
                ;
            })->orWhereHas('tags', function ($q) use ($filter) {
                $q->where('name', 'like', "%{$filter}%");
            });
        })
        ->when(boolval($request->get('moment')), function ($query, $moment) {
            return $query->where('moment_product', $moment);
        })
        ->when(intval($request->get('serviceId')), function ($query, $serviceId) {
            return $query->where('service_id', $serviceId);
        })
        ->when(!Auth::guard('admin')->check(), function ($query) {
            $query->whereHas('sites', function ($q) {
                $q->where('site_id', Auth::user() !== null ? Auth::user()->state->site_id : null);
            });
        })
        ->when(Auth::guard('admin')->check() && Auth::user()->can('manage my products only')
            && !Auth::user()->isSuperAdmin(), function ($query) {
                $query->where('partner_id', Auth::user()->partner->id);
            })
        ->when(boolval($request->get('favorite')), function ($query) {
                $query->userFavorite(Auth::id());
        })
        ->orderBy($request->get('orderProp', 'products.id'), $request->get('order', 'asc'))
        ->paginate($request->get('perPage'));

        return ProductResource::collection($products);
    }

    /**
     * Display the resource.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function get(Product $product)
    {
        $isAdmin = Auth::guard('admin')->check();
        if (!$isAdmin) {
            $site_id = Auth::user()->state->site_id;
            $isMatch = in_array($site_id, $product->sites->pluck('id')->toArray());
            if (!$isMatch) {
                throw new UnauthorizedException(403, 'Ce produit n\'est pas disponible');
            }
        }

        event(new ProductLogging($product));

        return new ProductResource($product);
    }

    /**
     * Create/update product
     * @param SaveProductRequest $request
     * @param Product|null $product
     * @return ProductResource
     */
    public function save(SaveProductRequest $request, Product $product = null)
    {
        $product = DB::transaction(function () use ($product, $request) {
            $user = $request->user('admin');
            $ownProductOnly = $user->can('manage my products only') && $user->isSuperAdmin() !== true;
            $product = $ownProductOnly ? $product : $product ?? new Product();
            $formData = $request->all();

            if ($ownProductOnly !== true) {
                $product->fill($formData);
                $names = $formData['names'];
                $descriptions = $formData['descriptions'];
                foreach (Locale::all() as $locale) {
                    $product->setTranslation('name', $locale->name, $names[$locale->name]);
                    $product->setTranslation('description', $locale->name, $descriptions[$locale->name]);
                }
                $product->save();

                // save or update sites
                $product->sites()->sync($formData['sites']);
                // save or create new tags
                $tags = collect($request->tags ?? []);

                $tagIds = $tags->map(function ($tag) {
                    $tagId = $tag['id'] ?? '';
                    $tagName = $tag['name'];
                    if (!Tag::where('id', '=', $tagId)->exists()) {
                        $newTag = new Tag;
                        $newTag->name = $tagName;
                        $newTag->save();
                        $tagId = $newTag->id;
                    }
                    return $tagId;
                });

                $product->tags()->sync($tagIds);

                $file_asset = $request->assets ?? null;
                if ($file_asset) {
                    if (count($product->assets)) {
                        $product->assets()->detach();
                        $this->removeAssetFile($product->assets[0]->id);
                    }

                    $path = $file_asset->store(Asset::PRODUCTS_FOLDER);
                    $filename = $file_asset->getClientOriginalName();
                    $product->assets()->save(new Asset([
                        'path'      => $path,
                        'filename'  => $filename,
                    ]));
                }
            }

            // save or update or delete slots
            $this->saveSlots($product, $request['slots'] ?? []);
            return $product;
        });

        return new ProductResource($product);
    }

    /**
     * @param Product $product
     * @param array $slots
     */
    private function saveSlots(Product $product, array $slots = [])
    {
        $existingSlots = $product->slots()->pluck('id')->toArray();
        $diff = array_diff($existingSlots, Arr::pluck($slots, 'id'));

        if (!empty($diff)) {
            foreach ($diff as $slotId) {
                Slot::find($slotId)->delete();
            }
        }

        foreach ($slots as $slotData) {
            $slot = Slot::updateOrCreate(
                ['id' => $slotData['id']],
                ['product_id'    =>  $product->id] + $slotData
            );

            $slot->slotTimes()->delete();

            $slotTimes = $slotData['times'] ?? [];
            if (empty($slotTimes)) {
                continue;
            }

            foreach ($slotTimes as $time) {
                $slot->slotTimes()->create($time);
            }
        }
    }

    /**
     * Delete product
     * @param Product $product
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Product $product)
    {
        $product->assets()->detach();
        $product->sites()->detach();
        $product->delete();

        return response()->json('', 204);
    }

    public function removeAssetFile($item)
    {
        $asset = Asset::findOrFail($item);
        if (Storage::disk()->exists($asset['path'])) {
            Storage::disk()->delete($asset['path']);
        }
        $asset->delete();
    }

    /**
     * get all client sites for products
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getSites(Request $request)
    {
        $sites = Site::clientSites($request->get('clientId'))->get();

        return response()->json(['data'=>$sites], 200);
    }

    /**
     * add a service to products_favorites table
     * @param Product $product
     * @return JsonResponse
     * @throws Exception
     */
    public function addToFavorites(Product $product)
    {
        $productId = $product->id;
        /** @var User $user*/
        $user = Auth::user();
        $response = true;
        $data = $user->productFavorites()->toggle($productId);
        if (count($data['detached']) > 0) {
            $response = false;
        }
        return response()->json(['data'=>['status'=> $response, 'product_id'=> $productId] ], 200);
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return JsonResponse
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function payment(Product $product, Request $request)
    {
        $metadata = $request->only('metadata');
        $quantity = $request->get('quantity', 1);

        if (!$request->terms) {
            throw new UnprocessableEntityHttpException(__('validation.accept_terms'));
        }
        if ($this->checkAvailableQuantity($metadata['metadata'], $quantity, $product->id)) {
            throw new UnprocessableEntityHttpException(__('validation.command_quantity'));
        }
        if ($this->checkAvailableDate($metadata['metadata'])
            || $this->checkAvailableTime($metadata['metadata'])) {
            throw new UnprocessableEntityHttpException(__('validation.command_date'));
        }

        // Setting up Stripe's API Key
        Stripe::setApiKey($product->currency->stripe_sk);

        // Choosing commission percentage, product percentage will be selected over partner percentage if it's set.
        $commissionPercentage = $product->commission_percentage !== null
            ? $product->commission_percentage
            : $product->partner->commission_percentage;

        // Calculating commission fees
        $priceWithoutFees = ($product->price * ((100 - $commissionPercentage) / 100));
        $commissionFees = intval(round(($product->price - $priceWithoutFees) * 100, 2));

        // Configuring the payment intent with the connected stripe ID and the fees.
        $paymentIntent = $product->partner->connected_stripe_id ? [
            'application_fee_amount' => $commissionFees,
            'transfer_data' => [
                'destination' => $product->partner->connected_stripe_id,
            ],
            'capture_method' => $product->manual_product ? 'manual' : 'automatic',
        ] : [
            'capture_method' => $product->manual_product ? 'manual' : 'automatic',
        ];

        // Stripe's API won't accept application fees equal to zero, in this case, we remove the application fee.
        if ($commissionFees === 0) {
            unset($paymentIntent['application_fee_amount']);
        }

        // Stripe Session Checkout configuration with payment intent and item listing.
        $sessionConfiguration = [
            'customer_email' => Auth::user()->email,
            'payment_method_types'  => ['card'],
            'client_reference_id' => Auth::id() . '_' . $product->id . '_' . $product->partner_id . '_' .
                ($product->manual_product ? 'M' : 'A') . '_' . time(),
            'line_items'    => [[
                'name'          => $product->name,
                'description'   => $product->description,
                'images'        => count($product->assets) > 0 ? [$product->assets[0]->getFullPathAttribute()] : [],
                'amount'        => intval($product->price * 100),
                'currency'      => $product->currency->name,
                'quantity'      => $quantity
            ]],
            $metadata,
            'payment_intent_data' => $paymentIntent,
            'success_url'   => env('PLATFORM_URL').'/bookings?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'    => env('PLATFORM_URL').'/products/' . $product->id . '?reason=cancel'
        ];

        $session = Session::create($sessionConfiguration);

        return response()->json($session->id);
    }

    /**
     * Check if the quantity requested by client side is correct
     * @param array $metadata
     * @param integer $quantity
     * @param integer $productId
     * @return bool
     */
    private function checkAvailableQuantity($metadata, $quantity, $productId)
    {
        if (!array_key_exists('slot_id', $metadata)
            || !array_key_exists('date', $metadata)
            || !array_key_exists('start_time', $metadata)
            || !array_key_exists('end_time', $metadata)) {
            return true;
        }
        $slotId = $metadata['slot_id'];
        $date = $metadata['date'];
        $startTime = ($metadata['start_time'] !== null) ? $metadata['start_time'] . ':00' : null;
        $endTime = ($metadata['end_time'] !== null) ? $metadata['end_time'] . ':00' : null;

        $checkSlotIdNull = Slot::where('product_id', $productId)->exists();
        if (!$checkSlotIdNull) {
            return false;
        }

        $checkQuantity = Slot::where('id', $slotId)->value('max_participant');
        $result = SlotBooking::select(['date', 'start_time', 'end_time', \DB::raw('SUM(quantity) as quantity')])
            ->where('slot_id', $slotId)
            ->groupBy(['date', 'start_time', 'end_time'])
            ->get();

        // We browse the result of the query to deduce the quantity present
        // on the same date and the same time slots if they exist.
        foreach ($result as $value) {
            if ($value['date'] === $date) {
                if (($value['start_time'] === null && $value['end_time'] === null
                        && $startTime === null && $endTime === null)
                    || ($value['start_time'] === $startTime && $value['end_time'] === $endTime)) {
                    $checkQuantity -= $value['quantity'];
                }
            }
        }

        return ($checkQuantity < $quantity) ? true : false;
    }

    /**
     * Check if the date of slot product command is correct
     * @param array $metadata
     * @return bool
     */
    private function checkAvailableDate($metadata)
    {
        if (!array_key_exists('slot_id', $metadata)
            || !array_key_exists('date', $metadata)) {
            return true;
        }
        $slotId = $metadata['slot_id'];
        $date = $metadata['date'];
        if (empty($date) && empty($slotId)) {
            return false;
        }
        $checkStartDateNull = Slot::where('id', $slotId)->value('start_date');
        $checkEndDateNull = Slot::where('id', $slotId)->value('end_date');
        $currentDate = date('Y-m-d', strtotime('-1 day'));
        if (!$checkStartDateNull && !$checkEndDateNull) {
            return false;
        }
        if ($date < $currentDate) {
            return true;
        }
        $whereData  = [
            ['id', $slotId],
            ['start_date', '<=', $date],
            ['end_date', '>=', $date]
        ];
        $checkDate = Slot::where($whereData)->exists();

        return (!$checkDate) ? true : false;
    }

    /**
     * Check if the times of slot product command is correct
     * @param array $metadata
     * @return bool
     */
    private function checkAvailableTime($metadata)
    {
        if (!array_key_exists('slot_id', $metadata)
            || !array_key_exists('start_time', $metadata)
            || !array_key_exists('end_time', $metadata)) {
            return true;
        }
        $slotId = $metadata['slot_id'];
        $startTime = $metadata['start_time'];
        $endTime = $metadata['end_time'];
        $checkTimeExist = SlotTime::where('slot_id', $slotId)->exists();
        if ($checkTimeExist && empty($startTime) && empty($endTime)) {
            return true;
        } elseif (!$checkTimeExist && empty($startTime) && empty($endTime)) {
            return false;
        }
        $whereData = [
            ['slot_id', $slotId],
            ['start_time', '<=', $startTime],
            ['end_time', '>=', $endTime]
        ];
        $checkTimeData = SlotTime::where($whereData)->exists();

        return (!$checkTimeData) ? true : false;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @param GetProductRequest $request
     * @return AnonymousResourceCollection
     */
    public function linked(Product $product, GetProductRequest $request)
    {
        $count = $request->get('count');
        $currentTags = $product->tags->pluck('id')->toArray();
        $products = Product::whereHas('sites', function ($q) use ($product) {
            $q->whereIn('site_id', $product->sites->pluck('id'));
            $q->whereIn('site_id', Auth::user()->client->sites->pluck('id'));
        })
            ->where('service_id', $product->service->id)
            ->where('id', '!=', $product->id)->get();
        foreach ($products as $pt) {
            $pt->relation = count(array_diff($currentTags, $pt->tags->pluck('id')->toArray()));
        }
        return ProductResource::collection($products->sortBy('relation')->take($count));
    }

    /**
     * @param Product $product
     * @param SaveRatingRequest $request
     * @return ProductResource
     */
    public function rating(Product $product, SaveRatingRequest $request)
    {
        // check if product has been bought
        $bookingProducts = Booking::where('user_id', Auth::user()->id)->get('product_id');
        $goodsBought = Booking\Product::whereIn('id', $bookingProducts)->where('product_id', $product->id)->count();

        if ($goodsBought === 0) {
            throw new UnprocessableEntityHttpException(__('validation.rating.product_not_bought'));
        }

        $rating = new Rating();
        $rating->fill($request->all());
        $rating->user_id = Auth::user()->id;
        $rating->save();
        $product->save();
        return new ProductResource($product);
    }
}
