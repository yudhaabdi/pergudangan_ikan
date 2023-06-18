<?php

namespace App\Http\Middleware;

use Closure;
use Auth;


class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$names)
    {
        // dd(Auth::user());
        // dd($names);
        if (in_array(Auth::user()->role,$names)) {
            return $next($request);
          }
         return redirect('/login');
    }
}
