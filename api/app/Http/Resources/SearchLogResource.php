<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchLogResource extends JsonResource
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
            'loggable_type' => $this->loggable_type,
            'loggable_id'   => $this->loggable_id,
            'user_id'       => $this->user_id,
            'loggable'      => $this->loggable_type === 'services'
                ? new ServiceResource($this->loggable) : new ProductResource($this->loggable),
        ];
    }
}
