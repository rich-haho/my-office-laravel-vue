<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @method getTranslation(string $string, $getLocaleCode)
 * @method getTranslations(string $string)
 */
class PartnerResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'address'               => $this->address,
            'phone'                 => $this->phone,
            'email'                 => $this->email,
            'contact_name'          => $this->contact_name,
            'public_description'    => $this->getTranslation('public_description', Auth::user()->getLocaleCode()),
            'public_descriptions'   =>
                $this->when(Auth::guard('admin')->check(), $this->getTranslations('public_description')),
            'private_description'   => $this->when(Auth::guard('admin')->check(), $this->private_description),
            'commission_percentage' => $this->when(Auth::guard('admin')->check(), $this->commission_percentage),
            'connected_stripe_id'   => $this->when(Auth::guard('admin')->check(), $this->connected_stripe_id),
            'enable_stripe_connect'   => $this->when(Auth::guard('admin')->check(), $this->enable_stripe_connect),
            'sites'                 => SiteResource::collection($this->sites),
        ];
    }
}
