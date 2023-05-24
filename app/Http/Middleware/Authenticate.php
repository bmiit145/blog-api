<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('home');
        // return $request->expectsJson() ? null : new RedirectResponse(route('home') , 307);
        // return response()->json(['error' => "Unauthorized access"]);
    }
}
