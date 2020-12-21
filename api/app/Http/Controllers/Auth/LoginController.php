<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param mixed $user
     * @return UserResource
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            throw new UnprocessableEntityHttpException('Merci de vérifier votre email');
        }
        return new UserResource($user);
    }

    /**
     * The user has logged out of the application.
     *
     * @param Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return response()->json([], 204);
    }
}
