<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProductResource
 * @package App\Http\Resources
 * @method isFavorite($param)
 * @method getTranslation(string $string, $getLocaleCode)
 * @method getTranslations(string $string)
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $list = !!$request->get('list');
        if ($list === true) {
            return [
                'id'               => $this->id,
                'name'             => $this->getTranslation('name', Auth::user()->getLocaleCode()),
                'price'            => $this->price,
                'service'          => $this->service->name,
                'currency'         => $this->currency->name,
                'partner'          => $this->partner->name,
            ];
        }
        return [
            'id'                   => $this->id,
            'name'                 => $this->getTranslation('name', Auth::user()->getLocaleCode()),
            'names'                => $this->when(Auth::guard('admin')->check(), $this->getTranslations('name')),
            'description'          => $this->getTranslation('description', Auth::user()->getLocaleCode()),
            'descriptions'          => $this->when(
                Auth::guard('admin')->check(),
                $this->getTranslations('description')
            ),
            'price'                => $this->price,
            'price_reduction'      => $this->price_reduction,
            'commission_percentage' => $this->when(
                Auth::guard('admin')->check(),
                $this->commission_percentage
            ),
            'enable_custom_commission' => $this->when(
                Auth::guard('admin')->check(),
                $this->enable_custom_commission
            ),
            'manual_product'       => $this->manual_product,
            'moment_product'       => $this->moment_product,
            'client_id'            => $this->client_id,
            'partner_id'           => $this->partner_id,
            'sites'                => SiteResource::collection($this->sites),
            'service_id'           => $this->service_id,
            'favorite'             => ($this->isFavorite($request->user() ?
                                         $request->user()->id : null) ? true : false),
            'currency_id'          => $this->currency_id,
            'partner'              => new PartnerResource($this->partner),
            'assets'               => count($this->assets) ? new AssetResource($this->assets[0]) : null,
            'tags'                 => TagResource::collection($this->tags),
            'slots'                => SlotResource::collection($this->slots),
            'service'              => new ServiceResource($this->service),
            'currency'             => new CurrencyResource($this->currency),
            'bookingProducts'      => BookingProductResource::collection($this->bookingProducts),
            'ratings'              => RatingResource::collection($this->ratings)
        ];
    }
}
