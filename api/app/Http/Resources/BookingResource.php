<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\BookingProductResource;

/**
 * Class BookingResource
 * @package App\Http\Resources
 */
class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'product_id'    => $this->product_id,
            'user'          => new UserResource($this->user),
            'product'       => new BookingProductResource($this->product),
            'partner'       => new PartnerResource($this->partner),
            'date'          => $this->date,
            'quantity'      => $this->quantity,
            'status'        => $this->status,
            'manual_product'=> $this->manual_product,
        ];
    }
}
