<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class UserResource extends JsonResource
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
            'email'         => $this->email,
            'date'          => date('m/d/Y', (int) strtotime($this->created_at)),
            'client_id'     => $this->client_id,
            'bookings'      => count($this->bookings),
            'active'        => $this->active ? true : false,
            'guard'         => Auth::guard('admin')->check() ? 'admin' : 'api',
            'client'        => $this->client ? new ClientResource($this->client) : null,
            'state'         => $this->state ? new StateResource($this->state) : null
        ];
    }
}
