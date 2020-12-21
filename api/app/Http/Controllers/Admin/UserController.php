<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveUserRequest as AdminSaveUserRequest;
use App\Http\Requests\GetUserRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetUserRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetUserRequest $request)
    {
        $filters = json_decode($request->get('filters'));


        $users =
            User::when($filters->name, function ($query, $name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($filters->client, function ($query, $client) {
                $query->where('client_id', $client);
            })
            ->orderBy($request->get('orderProp', 'id'), $request->get('order', 'asc'))
            ->paginate($request->get('perPage'))
        ;

        return UserResource::collection($users);
    }

    /**
     * @param User $user
     * @return UserResource
     */
    public function get(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Save user
     * @param AdminSaveUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function save(AdminSaveUserRequest $request, User $user)
    {
        $user->fill($request->all());
        $user->save();
        return new UserResource($user);
    }

    /**
     * resend verification email to user
     * @param User $user
     * @return UserResource
     */
    public function resend(User $user)
    {
        $user->sendEmailVerificationNotification();
        return new UserResource($user);
    }

    /**
     * Delete user
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(User $user)
    {
        $user->productFavorites()->delete();
        $user->serviceFavorites()->delete();
        $user->state()->delete();
        $user->bookings()->delete();
        $user->delete();

        return response()->json('', 204);
    }
}
