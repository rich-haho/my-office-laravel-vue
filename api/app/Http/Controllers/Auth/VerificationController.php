<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::where('id', $request->route('id'))->first();

        if ($user === null) {
            throw new AuthorizationException();
        }

        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            return Redirect::to(env('PLATFORM_URL') . '/email/error/' . Crypt::encrypt($request->route('id')));
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return Redirect::to(env('PLATFORM_URL') . '/email/error/' . Crypt::encrypt($request->route('id')));
        }

        if ($user->hasVerifiedEmail()) {
            return Redirect::to(env('PLATFORM_URL') . '/user/login');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            return Redirect::to(env('PLATFORM_URL') . '/email/verified');
        }

        throw new AuthorizationException;
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function resend(Request $request)
    {
        $user = User::find(Crypt::decrypt($request->get('id')));

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $user->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}
