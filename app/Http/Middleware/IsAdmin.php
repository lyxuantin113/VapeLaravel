<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        Log::info('Checking admin middleware', [
            'user_id' => $user?->id,
            'role' => $user?->role,
        ]);

        if ($user?->role !== 'admin') {
            Log::warning('Access denied: not admin', [
                'user_id' => $user?->id,
                'role' => $user?->role,
            ]);
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
