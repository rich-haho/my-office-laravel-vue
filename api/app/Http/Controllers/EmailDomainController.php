<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveEmailDomainRequest;
use App\Http\Resources\EmailDomainResource;
use App\Models\Client;
use App\Models\EmailDomain;
use Exception;
use Illuminate\Http\JsonResponse;

class EmailDomainController extends Controller
{
    /**
     * Save email domain
     * @param SaveEmailDomainRequest $request
     * @param EmailDomain|null $domain
     * @param Client $client
     * @return EmailDomainResource
     */
    public function save(SaveEmailDomainRequest $request, Client $client, EmailDomain $domain = null)
    {
        $domain = $domain ?? new EmailDomain();
        $domain->fill($request->all());
        $domain->client_id = $client->id;
        $domain->save();

        return new EmailDomainResource($domain);
    }


    /**
     * Delete email domain
     * @param EmailDomain $domain
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Client $client, EmailDomain $domain)
    {
        $domain->delete();

        return response()->json('', 204);
    }
}
