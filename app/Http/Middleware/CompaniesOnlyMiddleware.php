<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompaniesOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if (($request->user()->type === 'company' || $request->user()->type === 'company-admin')
                && $request->user()->confirmed === 'approved') {
                return $next($request);
            } else {
                if ($request->user()->type === 'employee' && $request->user()->confirmed === 'approved') {
                    return redirect()->route('users.login');
                } else {
                    return redirect('/login');
                }
            }
        }
        return redirect()->route('company.login');
    }
}
