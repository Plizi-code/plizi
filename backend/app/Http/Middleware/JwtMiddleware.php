<?php

namespace App\Http\Middleware;

use Closure;
use \Tymon\JWTAuth\Exceptions\TokenExpiredException;
use \Tymon\JWTAuth\Exceptions\TokenInvalidException;
use \JWTAuth;

class JwtMiddleware
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
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['message' => 'Token is Invalid'], 401);
            } elseif ($e instanceof TokenExpiredException){
                return response()->json(['message' => 'Token is Expired'], 401);
            } else {
                return response()->json(['message' => $e->getMessage()], 401);
            }
        }
        return $next($request);
    }
}
