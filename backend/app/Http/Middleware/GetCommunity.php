<?php

namespace App\Http\Middleware;

use App\Models\Community;
use Closure;
use Illuminate\Http\Request;

class GetCommunity
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
        $groupId = $request->route('groupId');
        $community = Community::find($groupId);
        if (!$community) {
            return response()->json([
                'message' => 'Сообщество не найдено'
            ], 422);
        }
        $request->merge(['community' => $community]);
        return $next($request);
    }
}
