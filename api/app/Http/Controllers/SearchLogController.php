<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSearchLogRequest;
use App\Http\Resources\SearchLogResource;
use App\Models\SearchLog;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class SearchLogController
 * @package App\Http\Controllers
 */
class SearchLogController extends Controller
{

    /**
     * Display a listing of current user search logs.
     *
     * @param GetSearchLogRequest $request
     * @param AuthManager $authManager
     * @return AnonymousResourceCollection
     */
    public function index(GetSearchLogRequest $request, AuthManager $authManager)
    {
        // Getting API Auth Guard for future usage.
        $apiGuard = $authManager->guard('api');
        // Getting all search logs associated with the current user if it is not an administrator.
        $searches = SearchLog::when(!$authManager->guard('admin')->check(), function ($query) use ($apiGuard) {
                $query->where('user_id', $apiGuard->user() !== null ? $apiGuard->id() : null);
        })->orderBy('updated_at', 'desc')->get();

        // Returning a transformed collection of SearchLogs through its own resource.
        return SearchLogResource::collection($searches);
    }
}
