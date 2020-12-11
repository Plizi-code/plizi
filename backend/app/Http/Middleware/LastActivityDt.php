<?php

namespace App\Http\Middleware;

use Auth;
use Carbon\Carbon;
use DB;
use Closure;

class LastActivityDt
{
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return $next($request);
        }
        if (Carbon::create(auth()->user()->last_activity_dt)->diffInMinutes(now()) <= config('app.user_activity_margin')) {
            DB::table("users")
                ->where("id", auth()->user()->id)
                ->update(["last_activity_dt" => time()]);
        }
        return $next($request);
    }
}
