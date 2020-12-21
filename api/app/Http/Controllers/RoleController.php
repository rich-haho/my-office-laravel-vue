<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $roles = Role::get();
        return RoleResource::collection($roles);
    }
}
