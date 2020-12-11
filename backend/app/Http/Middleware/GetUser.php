<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class GetUser
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
        $userId = $request->route('userId');
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        if (auth()->user()->id === $user->id) {
            return response()->json([
                'message' => 'The same user'
            ], 404);
        }
        $request->merge(['user' => $user]);
        return $next($request);
    }
}
