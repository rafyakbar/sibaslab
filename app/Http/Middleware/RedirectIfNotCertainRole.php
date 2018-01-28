<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Support\Role;

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
            return redirect()->route('mahasiswa.login');
        }
        else {
            if(!Auth::check() ||
            (Auth::user()->isAdmin() && $hakakses != Role::ADMIN) ||
            (Auth::user()->isRoot() && $hakakses != Role::ROOT) ||
            (Auth::user()->isKalab() && $hakakses != Role::KALAB) ||
            (Auth::user()->isKasublab() && $hakakses != Role::KASUBLAB)) {
                return redirect()->route('user.login');
            }
        }

        return $next($request);
    }
    
}
