<?php

namespace App\Http\Controllers;

use App\Enum\BookingStatus;
use App\Http\Requests\GetBookingRequest;
use App\Http\Requests\SaveBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBooking;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetBookingRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetBookingRequest $request)
    {
        $filter = $request->get('filters');

        $bookings = Booking::where('status', 'like', '%' . $filter . '%')
            ->when(!Auth::guard('admin')->check(), function ($query) {
                $query->where('user_id', Auth::user() !== null ? Auth::user()->id : null);
            })
            ->when(Auth::guard('admin')->check() && Auth::user()->can('manage my bookings only')
                && !Auth::user()->isSuperAdmin(), function ($query) {
                    $query->where('partner_id', Auth::user()->partner->id);
                })
            ->orderBy($request->get('orderProp', 'id'), $request->get('order', 'asc'))
            ->paginate($request->get('perPage'));
        return BookingResource::collection($bookings);
    }

    /**
     * Get booking
     * @param Booking $booking
     * @return BookingResource
     */
    public function get(Booking $booking)
    {
        if (Auth::user()->can('manage my bookings only')
            && !Auth::user()->isSuperAdmin()
            && $booking->partner_id !== Auth::user()->partner->id) {
            throw new UnauthorizedException(403);
        }
        return new BookingResource($booking);
    }

    /**
     * Save booking
     * @param SaveBookingRequest $request
     * @param Booking|null $booking
     * @return BookingResource
     */
    public function save(SaveBookingRequest $request, Booking $booking = null)
    {
        if ($booking && Auth::user()->can('manage my bookings only')
            && !Auth::user()->isSuperAdmin()
            && $booking->partner_id !== Auth::user()->partner->id) {
            throw new UnauthorizedException(403);
        }

        $booking = $booking ?? new Booking();
        $booking->fill($request->all());
        $booking->save();

        if ($booking->wasRecentlyCreated) {
            $parter = $booking->product->partner;
            Notification::send($parter, new NewBooking($booking));
        }
        
        return new BookingResource($booking);
    }

    /**
     * Delete booking
     * @param Booking $booking
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Booking $booking)
    {
        $booking->delete();

        return response()->json('', 204);
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return BookingStatus::getValues();
    }
}
