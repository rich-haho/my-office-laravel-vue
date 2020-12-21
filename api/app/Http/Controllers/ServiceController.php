<?php

namespace App\Http\Controllers;

use App\Events\ServiceLogging;
use App\Http\Requests\GetServiceRequest;
use App\Http\Requests\SaveServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\Asset;
use App\Models\User;
use App\Models\Locale;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Storage;
use Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetServiceRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetServiceRequest $request)
    {
        $services = Service::where('name', 'like', '%'.$request->get('filters').'%')
            ->select('services.*')
            ->when(!Auth::guard('admin')->check(), function ($query) {
                $query->whereHas('sites', function ($q) {
                    $q->where('site_id', Auth::user()->state->site_id);
                });
            })
            ->when(boolval($request->get('favorite')), function ($query) {
                $query->userFavorite(Auth::id());
            })
            ->orderBy($request->get('orderProp', 'services.id'), $request->get('order', 'asc'))
            ->paginate($request->get('perPage'))
        ;

        return ServiceResource::collection($services);
    }

    /**
     * @param Service $service
     * @return ServiceResource
     */
    public function get(Service $service)
    {
        event(new ServiceLogging($service));

        return new ServiceResource($service);
    }
    /**
     * Save service
     * @param SaveServiceRequest $request
     * @param Service|null $service
     * @return ServiceResource
     */
    public function save(SaveServiceRequest $request, Service $service = null)
    {
        $service = $service ?? new Service();
        $service->fill($request->all());
        $names = $request->input('names');
        $descriptions = $request->input('descriptions');
        foreach (Locale::all() as $locale) {
            $service->setTranslation('name', $locale->name, $names[$locale->name]);
            $service->setTranslation('description', $locale->name, $descriptions[$locale->name]);
        }
        $service->save();

        $service->sites()->sync($request->input('sites'));

        $file_assets = $request->assets ?? [];
        if (count($file_assets) && count($service->assets)) {
            $service->assets()->detach();
            $this->removeAssetFile($service->assets[0]->id);
        }
        foreach ($file_assets as $asset) {
            $path = $asset->store(Asset::SERVICES_FOLDER);
            $filename = $asset->getClientOriginalName();
            $service->assets()->save(new Asset([
                'path'      => $path,
                'filename'  => $filename,
            ]));
        }

        return new ServiceResource($service);
    }


    /**
     * Delete service
     * @param Service $service
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Service $service)
    {
        if ($service->products()->count() > 0) {
            throw new UnprocessableEntityHttpException('Des produits sont liés au service, et doivent être supprimés.');
        }
        $assets = $service->assets()->get();
        $service->assets()->detach();
        $service->sites()->detach();
        $service->delete();
        foreach ($assets as $asset) {
            $this->removeAssetFile($asset->id);
        }
        return response()->json('', 204);
    }
    /**
     * @param int $item
     */
    public function removeAssetFile($item)
    {
        $asset = Asset::findOrFail($item);
        if (Storage::disk()->exists($asset['path'])) {
            Storage::disk()->delete($asset['path']);
        }
        $asset->delete();
    }

    /**
     * add a service to service_favorites table
     * @param Service $service
     * @return JsonResponse
     * @throws Exception
     */
    public function addToFavorites(Service $service)
    {
        $serviceId = $service->id;
        /** @var User $user*/
        $user = Auth::user();
        $response = true;
        $data = $user->serviceFavorites()->toggle($serviceId);
        if (count($data['detached']) > 0) {
            $response = false;
        }
        return response()->json(['data'=> $response], 200);
    }
}
