<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClientResource;
use App\Models\Client;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class SiteResource extends JsonResource
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
            'name'          => $this->name,
            'client_id'     => $this->client_id,
            'open_time'     => $this->open_time,
            'fm_services'   => $this->fm_services,
            'phone_number'  => $this->phone_number,
            'address'       => $this->address,
            'client'        => new ClientResource($this->client)
        ];
    }
}
