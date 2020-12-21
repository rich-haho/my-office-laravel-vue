<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Locale;
use App\Models\Site;
use App\Models\State;
use App\Models\User;
use App\Models\EmailDomain;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'client_id' => $data['client_id'],
                'password'  => Hash::make($data['password']),
            ]);

            $site = Site::where('client_id', $user->client_id)->first();
            if ($site === null) {
                throw new UnprocessableEntityHttpException('Erreur inatendue - 01');
            }

            $locale = Locale::first();
            if ($locale === null) {
                throw new UnprocessableEntityHttpException('Erreur inatendue - 02');
            }

            State::create([
                'user_id'       => $user->getKey(),
                'site_id'       => $site->getKey(),
                'locale_id'     => $locale->getKey(),
            ]);

            return $user;
        });
    }


    /**
     * The user has been registered.
     *
     * @param Request $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        return new UserResource($user);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $client = EmailDomain::getClient($request->get('email'));
        if ($client === null) {
            return $this->registerFailed();
        }

        event(new Registered($user = $this->create($request->all() + ['client_id' => $client->id])));

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * @return JsonResponse
     */
    protected function registerFailed()
    {
        return response()->json('auths.register_domain_fail', 403);
    }
}
