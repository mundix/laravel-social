<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            if (Auth::guard($guard)->check()) {

                if ($request->user()->type === 'employee' && $request->user()->confirmed === 'approved') {

                    return redirect(RouteServiceProvider::EMPLOYEE);

                }elseif ($request->user()->type === 'company' && $request->user()->confirmed === 'approved') {

                    return redirect(RouteServiceProvider::COMPANY);

                }elseif ( ($request->user()->type === 'admin' || $request->user()->type === 'super' ) && $request->user()->confirmed === 'approved') {

                    return redirect(RouteServiceProvider::ADMIN);

                }
                return redirect(RouteServiceProvider::HOME);
            }

        }

        return $next($request);
    }
}
