<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
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
            'site_id'            => $this->site_id,
            'locale_id'          => $this->locale_id,
            'locale'             => new LocaleResource($this->locale),
        ];
    }
}
