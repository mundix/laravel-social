<?php

namespace App\Http\Controllers\Auth;

use App\Managers\UserManager;
use App\Services\InviteService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use \Socialite;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Okta authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return \Socialite::driver('okta')->redirect();
    }

    /**
     * Obtain the user information from Okta.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request)
    {
        try {
            $socialUser = \Socialite::driver('okta')->user();
        } catch (\Throwable $e) {
            return redirect()->route('home')->withErrors('This account was not invited.');
        }

        $localUser = User::where('email', $socialUser->email)->first();

        if ($localUser) {
            $user = (new UserManager)->processOktaUser($localUser, $socialUser);
            try {
                Auth::login($user);
            } catch (\Throwable $e) {
                return redirect('/login-okta');
            }
        } else {
            $company = (new InviteService)->getCompanyInvitedByEmail($socialUser->email);
                if ($company) {
                    try {
                        Auth::login($company->user);
                    } catch (\Throwable $e) {
                        return redirect('/login-okta');
                    }
                }
        }

        return redirect()->route('home');
    }
}
