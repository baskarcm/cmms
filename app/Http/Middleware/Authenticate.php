<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if ($request->is('api/*')) {
             return route('unauthorized');
        }
        if (! $request->expectsJson()) {
            return route('login');
        }
        
        
    }
    
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json(
            [
                'status' => '401',
                'message' => 'UnAuthenticated',
            ], 401));
    }

}
