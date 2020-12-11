<?php

namespace App\Http\Middleware;

use App\Models\Community;
use App\Models\CommunityMember;
use Closure;
use Illuminate\Http\Request;

class GetCommunityMember
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

        if (!$community->role || !in_array($community->role->role, [Community::ROLE_AUTHOR, Community::ROLE_ADMIN], true)) {
            return response()->json([
                'message' => 'Нет прав',
            ], 403);
        }

        $member = CommunityMember::where('community_id', $community->id)
            ->where('user_id', $request->userId)
            ->first();
        if (!$member) {
            return response()->json([
                'message' => 'Участник не найден',
            ], 422);
        }

        return $next($request);
    }
}
