<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        Log::info('Authentication check', [
            'session_id' => session()->getId(),
            'cookies' => $request->cookies->all(),
            'is_authenticated' => auth()->check(),
            'intended_url' => $request->fullUrl()
        ]);

        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
