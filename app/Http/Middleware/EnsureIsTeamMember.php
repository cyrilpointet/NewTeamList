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
        $teams = $user->teams()->where('team_id', $request->route('id'))->get();
        if (0 === count($teams)) {
            return response([
                'message' => ['User is not a team member']
            ], 403);
        }
        return $next($request);
    }
}
