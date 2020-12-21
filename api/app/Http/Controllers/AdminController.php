<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAdminRequest;
use App\Http\Requests\SaveAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetAdminRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetAdminRequest $request)
    {
        $filter = $request->get('filters');

        $admins = Admin::where('name', 'like', '%'.$filter.'%')
          ->orwhere('email', 'like', '%'.$filter.'%')
          ->orderBy($request->get('orderProp', 'id'), $request->get('order', 'asc'))
          ->paginate($request->get('perPage'));

        return AdminResource::collection($admins);
    }

    /**
     * @param Admin $admin
     * @return AdminResource
     */
    public function get(Admin $admin)
    {
        return new AdminResource($admin);
    }

    /**
     * Save admin
     * @param SaveAdminRequest $request
     * @param Admin|null $admin
     * @return AdminResource
     */
    public function save(SaveAdminRequest $request, Admin $admin = null)
    {
        $superAdmin = $request->admin;
        $admin = $admin ?? new Admin();
        $admin->fill($request->all());
        $admin->password = Hash::make($request->password);
        $role = Role::where('id', $request->role_id)->where('guard_name', 'admin')->get()->first();
        $checkPartner = $admin->roles()->where('name', 'Partenaire')->count();
        if ($role->name === 'Partenaire' && $checkPartner !== 1) {
            throw new UnprocessableEntityHttpException(
                'Vous ne pouvez pas créer un administrateur partenaire ici'
            );
        }
        if (count($admin->roles)) {
            $admin->removeRole($admin->roles->first());
        }
        if (!$superAdmin) {
            $admin->assignRole($request->role_id);
            $admin->admin = 0;
        } else {
            $admin->admin = 1;
        }
        $admin->save();
        return new AdminResource($admin);
    }

    /**
     * Delete admin
     * @param Admin $admin
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Admin $admin)
    {
        $admin->delete();

        return response()->json('', 204);
    }
}
