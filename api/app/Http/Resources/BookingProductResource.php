<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class BookingProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'description'          => $this->description,
            'date_slot'            => $this->date_slot,
            'start_time'           => $this->start_time,
            'end_time'             => $this->end_time,
            'price'                => $this->price,
            'currency'             => $this->currency,
            'image'                => $this->image,
            'status'               => $this->bookings->where('user_id', Auth::user()->id)
                ->where('status', 'delivered')->count() > 0 ? 'delivered' : null
        ];
    }
}
