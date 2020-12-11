<?php


namespace App\Http\Middleware;

//use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = Auth::guard($guard);

        if (Auth::guard($guard)->guest()) {
            if ($request->getRequestUri() == '/admin/login') {
                return $next($request);
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        }

        if (!$auth->user()->isAdmin()) {
            return response('Access denied.', 401);
        }

        return $next($request);
    }
}
