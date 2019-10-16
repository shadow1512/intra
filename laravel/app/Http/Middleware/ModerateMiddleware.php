<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ModerateMiddleware
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
        if (!Auth::check()  ||  !in_array(Auth::user()->role_id, array(1,3,4,5,6)))
        {
            abort(403);
            //return redirect('/');
        }

        return $next($request);
    }
}
