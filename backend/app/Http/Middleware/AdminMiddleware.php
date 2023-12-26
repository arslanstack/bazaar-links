<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth('admin')->check()) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
