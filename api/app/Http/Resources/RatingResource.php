<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RatingResource
 * @package App\Http\Resources
 */
class RatingResource extends JsonResource
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
            'rate'          => $this->rate,
            'date'          => date('m/d/Y', (int)strtotime($this->created_at)),
            'user'          => new UserResource($this->user)
        ];
    }
}
