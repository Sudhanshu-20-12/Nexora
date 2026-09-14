<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Step 1: Check karo user login hai ya nahi
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized. Please login.'], 401);
        }

        // Step 2: Check karo user ka role, allowed roles mein hai ya nahi
        if (!in_array($request->user()->role, $roles)) {
            return response()->json(['message' => 'Forbidden. You do not have access to this resource.'], 403);
        }

        // Step 3: Sab sahi hai, aage jaane do
        return $next($request);
    }
}