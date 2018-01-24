<?php

namespace App\Http\Middleware;

use Closure;

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
        if(!$request->user()->isKalab() || !$request->user()->isKasublab())
            return abortt(403);

        return $next($request);
    }
    
}
