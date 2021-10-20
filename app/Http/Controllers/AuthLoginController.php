<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\ForgotPasswordMail;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class AuthLoginController extends Controller
{
	public function logout()
	{
		\auth()->logout();
		session()->flush();
		return redirect()->route('home');
	}

	/**
     * Employee User invited
     * @todo: When employee received an email with invitation token, redirects to this url 'company/employee/invite'
     *        and verify if token exists and not being used, and confirmed user
     * @param string $token | null
	*/
	public function invite($token = null)
	{
		if ($userToken = UserToken::where('token', $token)
			->where('status', 'pending')
			->first()
		) {
			$userToken->status = 'used';
			$userToken->save();

			$user = $userToken->user;
			$user->confirmed = 'approved';
			$user->save();

			\Auth::login($user);

			if (\Auth::check()) {
				return redirect()->route('users.signup');
			}
		}
		if($userToken = UserToken::where('token', $token)
            ->where('status', 'used')
            ->first()) {
		    $user = $userToken->user;
            \Auth::login($user);
            return redirect()->route('users.profile');
        }

		return redirect()->route('users.login')->withErrors('This invitation has expired or not valid.');

	}

	public function resetInvite()
	{
		$user = User::whereType("employee")->get()->last();
		$user->employee->first_name = '';
		$user->employee->last_name = '';
		$user->employee->description = '';
		$user->employee->location = '';
		$user->employee->save();
		$user->confirmed = 'approved';
		$user->save();

		\Auth::login($user);

		if (\Auth::check()) {
			return redirect()->route('users.onboarding');
		}
		return redirect()->route('users.login')->withErrors('This invitation has expired or not valid.');
	}
}
