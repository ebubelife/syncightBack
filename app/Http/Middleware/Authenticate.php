<?php

namespace App\Http\Middleware;

protected function redirectTo($request)
{
    if (! $request->expectsJson()) {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }
}