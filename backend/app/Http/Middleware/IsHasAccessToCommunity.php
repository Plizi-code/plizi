<?php

namespace App\Http\Middleware;

use App\Models\Community;
use Closure;
use Illuminate\Http\Request;

class IsHasAccessToCommunity
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var Community $community */
        $community = $request->community;

        if ($community->privacy === Community::PRIVACY_OPEN) {
            return $next($request);
        }

        if($community->role
            && in_array($community->role->role, [Community::ROLE_USER, Community::ROLE_ADMIN, Community::ROLE_AUTHOR], true)) {
            return $next($request);
        }
        return response()->json([
            'message' => 'У Вас нет прав'
        ], 403);
    }
}
