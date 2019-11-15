<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if($request->user()->hasFlag(User::FLAG_CHANGE_PASSWORD)) {
            return redirect('/password/change');
        }

        if (!$request->user()->hasRole($role) && !$request->user()->hasRole(User::ROLE_SUPER_ADMIN)) {
            abort(404);
        }

        return $next($request);
    }
}
