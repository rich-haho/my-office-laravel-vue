<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * Class AdminResource
 * @package App\Http\Resources
 * @method getAllPermissions()
 */
class AdminResource extends JsonResource
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
            'permissions'   => PermissionResource::collection($this->getAllPermissions()),
            'guard'         => Auth::guard('admin')->check() ? 'admin' : 'api',
            'admin'         => $this->admin,
            'role_id'       => $this->roles->first() ? $this->roles->first()->id : '',
            'role'          => $this->roles->first() ? new RoleResource($this->roles->first()) : null
        ];
    }
}
