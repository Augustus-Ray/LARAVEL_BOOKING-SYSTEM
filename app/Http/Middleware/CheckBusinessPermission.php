<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBusinessPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission = null): Response
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return redirect()->route('admin.login');
        }

        // Super admin has all permissions
        if (Auth::guard('super_admin')->check()) {
            return $next($request);
        }

        // Check if admin has specific permission
        if ($permission && !$admin->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
