<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsTeamMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $user = $request->user();
        foreach ($user->teams as $team) {
            if ($team->id === intval($request->route('id'))) {
                return $next($request);
                exit;
            }
        }
        return $next($request);
    }
}
