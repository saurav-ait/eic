<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * @param  string|array  $roles
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;
        $roles = explode('|', $roles);

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized');
        }

        $response = $next($request);

        // Prevent caching
        return $response->header('Cache-Control','no-cache,no-store,max-age=0,must-revalidate')
                        ->header('Pragma','no-cache')
                        ->header('Expires','0');
    }
}