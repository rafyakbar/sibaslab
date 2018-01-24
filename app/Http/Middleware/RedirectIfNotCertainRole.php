<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotCertainRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $hakakses)
    {
        if(($hakakses == 'MHS' && Auth::guard('mhs')->check())) {
            return abort(403);
        }
        else {
            if(!Auth::check() ||
            (Auth::user()->isAdmin() && $hakakses != Role::ADMIN) ||
            (Auth::user()->isKalab() && $hakakses != Role::KALAB) ||
            (Auth::user()->isKasublab() && $hakakses != Role::KASUBLAB)) {
                return abort(403);
            }
        }

        return $next($request);
    }
    
}
