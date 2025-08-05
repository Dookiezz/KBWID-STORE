<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth as Auth;


//Role Middleware
class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user();
        if ($user && $user->role !== $role) {
            return App::abort(Auth::check() ? 403 : 401,
            Auth::check() ? 'Forbidden' : 'Unauthorized');
        }
        return $next ($request);
    }
}