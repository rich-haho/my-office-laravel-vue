<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetClientRequest;
use App\Http\Requests\SaveClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetClientRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetClientRequest $request)
    {
        $filter=$request->get('filters');
         
        $users = Client::where('name', 'like', '%'.$filter.'%')
          ->orderBy($request->get('orderProp', 'id'), $request->get('order', 'asc'))
          ->paginate($request->get('perPage'));

        return ClientResource::collection($users);
    }
    public function get(Client $client)
    {
        return new ClientResource($client);
    }
    /**
     * Save client
     * @param SaveClientRequest $request
     * @param Client|null $client
     * @return ClientResource
     */
    public function save(SaveClientRequest $request, Client $client = null)
    {
        $client = $client ?? new Client();
        $client->fill($request->all());
        $client->save();

        return new ClientResource($client);
    }


    /**
     * Delete client
     * @param Client $client
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Client $client)
    {
        $client->domains()->delete();
        $client->delete();

        return response()->json('', 204);
    }
}
