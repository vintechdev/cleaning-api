<?php

namespace App\Http\Middleware;

use Closure;

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
        $isRole = $request->user()->hasRole($role);

        if (!$isRole) {
            abort(401, 'This action is unauthorized.');
        }

        if ($role == 'admin') {
            $request->request->add(['isAdmin' => true]);
        }

        return $next($request);
    }
}