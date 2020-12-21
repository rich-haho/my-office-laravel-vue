<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Http\Requests\SaveUserStateRequest;
use App\Models\Site;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UserController extends Controller
{

    /**
     * Save user state
     * @param SaveUserStateRequest $request
     * @return UserResource
     */
    public function saveState(SaveUserStateRequest $request)
    {
        // @todo change this verification for a custom rule in the form request
        $site = Site::find($request->get('site_id'));
        if ($site->client_id !== Auth::user()->client_id && Auth::user()->superuser === false) {
            throw new UnprocessableEntityHttpException('You are not allowed to access this');
        }

        State::updateOrCreate([
            'user_id'   => Auth::id(),
        ], [
            'site_id'       => $request->get('site_id'),
            'locale_id'     => $request->get('locale_id'),
        ]);

        return new UserResource($request->user());
    }

    /**
     * Save user
     * @param SaveUserRequest $request
     * @return UserResource
     */
    public function save(SaveUserRequest $request)
    {
        $user = Auth::user();
        $user->update([
            'name' => $request->get('name')
        ]);
        if ($request->get('updatepassword')) {
            $user->update(['password' => Hash::make($request->get('new_password'))]);
        }
        return new UserResource($request->user());
    }
}
