<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmployeeAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if(!auth()->check()) {
            return redirect()->route( 'users.login');
        }

        if(!((int)$user->on_boarding_complete)) {
            return redirect()->route('users.onboarding');
        }

        return $next($request);
    }
}
