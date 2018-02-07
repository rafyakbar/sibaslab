<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            
            if($guard == 'web') {
                if(Auth::user()->isKalab() || Auth::user()->isKasublab())
                    return redirect()->route('kasublab.daftar.mahasiswa');
                else if(Auth::user()->isRoot())
                    return redirect()->route('root.dashboard');
                else if (Auth::user()->isAdmin())
                    return redirect()->route('admin.dashboard');
            }
            else if($guard == 'mhs') {
                return redirect()->route('mahasiswa.dashboard');
            }
        }

        return $next($request);
    }
}
