<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @method isFavorite($param)
 * @method getTranslation(string $string, $getLocaleCode)
 * @method getTranslations(string $string)
 */
class ServiceResource extends JsonResource
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
            'name'          => $this->getTranslation('name', Auth::user()->getLocaleCode()),
            'names'         => $this->when(Auth::guard('admin')->check(), $this->getTranslations('name')),
            'description'   => $this->getTranslation('description', Auth::user()->getLocaleCode()),
            'descriptions'  => $this->when(Auth::guard('admin')->check(), $this->getTranslations('description')),
            'favorite'      => ($this->isFavorite($request->user() ? $request->user()->id : null) ? true : false),
            'assets'        => new AssetResource($this->assets->first()),
            'sites'         => SiteResource::collection($this->sites),
            'external_link' => $this->external_link,
        ];
    }
}
