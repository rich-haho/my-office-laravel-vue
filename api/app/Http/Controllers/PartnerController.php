<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPartnerRequest;
use App\Http\Requests\SavePartnerRequest;
use App\Http\Resources\PartnerResource;
use App\Models\Admin;
use App\Models\Partner;
use App\Models\Locale;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetPartnerRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetPartnerRequest $request)
    {
        $filter = $request->get('filters');

        $partners = Partner::where('name', 'like', '%' . $filter . '%')
            ->orwhere('address', 'like', '%' . $filter . '%')
            ->orwhere('email', 'like', '%' . $filter . '%')
            ->orwhere('phone', 'like', '%' . $filter . '%')
            ->orderBy($request->get('orderProp', 'id'), $request->get('order', 'asc'))
            ->paginate($request->get('perPage'));

        return PartnerResource::collection($partners);
    }

    /**
     * @param Partner $partner
     * @return PartnerResource
     */
    public function get(Partner $partner)
    {
        return new PartnerResource($partner);
    }

    /**
     * Save partner
     * @param SavePartnerRequest $request
     * @param Partner|null $partner
     * @return PartnerResource
     */
    public function save(SavePartnerRequest $request, Partner $partner = null)
    {
        return DB::transaction(function () use ($request, $partner) {
            $partner = $partner ?? new Partner();
            $partner->fill($request->all());

            $public_descriptions = $request->input('public_descriptions');
            foreach (Locale::all() as $locale) {
                $partner->setTranslation(
                    'public_description',
                    $locale->name,
                    $public_descriptions[$locale->name] ?? ''
                );
            }
            $partner->save();
            $partner->sites()->sync($request->input('sites'));

            return new PartnerResource($partner);
        });
    }

    /**
     * Delete partner
     * @param Partner $partner
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Partner $partner)
    {
        $partner->delete();

        return response()->json('', 204);
    }
}
