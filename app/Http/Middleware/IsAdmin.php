<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->level == 2) {
            return $next($request);
        }
        return redirect('/home'); // If user is not an admin.
    }
}
