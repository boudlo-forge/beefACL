<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckFlags
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
        if($request->user()->hasFlag(User::FLAG_CHANGE_PASSWORD)) {
            return redirect('/password/change')->with('notification', "Your password must be updated before you can proceed.");
        }

        return $next($request);
    }
}
