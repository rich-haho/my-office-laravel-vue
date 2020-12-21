<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSiteRequest;
use App\Http\Requests\SaveSiteRequest;
use App\Http\Resources\SiteResource;
use App\Models\Site;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Auth;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetSiteRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetSiteRequest $request)
    {
        $filter=$request->get('filters');

        $sites = Site::where('name', 'like', '%'.$filter.'%')
            ->when(!Auth::guard('admin')->check(), function ($query) {
                $query->when(Auth::user()->superuser === false, function ($query) {
                    $query->where('client_id', Auth::user() !== null ? Auth::user()->client_id : null);
                });
            })
            ->orderBy($request->get('orderProp', 'id'), $request->get('order', 'asc'))
            ->paginate($request->get('perPage'));

        return SiteResource::collection($sites);
    }
    public function get(Site $site)
    {
        return new SiteResource($site);
    }
    /**
     * Save site
     * @param SaveSiteRequest $request
     * @param Site|null $site
     * @return SiteResource
     */
    public function save(SaveSiteRequest $request, Site $site = null)
    {
        $site = $site ?? new Site();
        $site->fill($request->all());
        $site->save();

        return new SiteResource($site);
    }


    /**
     * Delete site
     * @param Site $site
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Site $site)
    {
        $site->delete();

        return response()->json('', 204);
    }
}
