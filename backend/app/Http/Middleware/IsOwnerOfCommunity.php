<?php

namespace App\Http\Middleware;

use App\Models\Community;
use Closure;
use Illuminate\Http\Request;

class IsOwnerOfCommunity
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

        if($community->role && in_array($community->role->role, [Community::ROLE_ADMIN, Community::ROLE_AUTHOR], true)) {
            return $next($request);
        }
        return response()->json([
            'message' => 'У Вас нет прав'
        ], 403);
    }
}
