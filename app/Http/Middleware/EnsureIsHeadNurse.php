<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsHeadNurse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->nurse || !$user->nurse->is_head_nurse) {
            abort(403, 'Access denied. Head nurse privileges required.');
        }

        return $next($request);
    }
}
