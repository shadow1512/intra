<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoomsMiddleware
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
        if (!(Auth::user()->role_id   ==  5 ||  Auth::user()->role_id   ==  1))
        {
            abort(403);
            //return redirect('/moderate');
        }

        return $next($request);
    }
}
