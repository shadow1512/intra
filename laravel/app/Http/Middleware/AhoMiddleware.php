<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AhoMiddleware
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
        if (!(Auth::user()->role_id   ==  6 ||  Auth::user()->role_id   ==  1))
        {
            abort(403);
            //return redirect('/moderate');
        }

        return $next($request);
    }
}
