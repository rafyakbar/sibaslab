<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotKalabOrKasublab
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check())
            return redirect()->route('user.login');

        if(!$request->user()->isKalab() && !$request->user()->isKasublab())
            return redirect()->route('user.login');

        return $next($request);
    }
    
}
