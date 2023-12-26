<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiRateLimiter
{
    /**
     * The number of requests per minute allowed.
     *
     * @var int
     */
    protected $maxAttempts = 2000000; // Adjust this value as needed

    /**
     * The cache key prefix used to track requests.
     *
     * @var string
     */
    protected $cacheKeyPrefix = 'api_requests';

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $attempts = 1;
        if ($attempts >= $this->maxAttempts) {
            return response()->json([
                'message' => 'Too many requests. Please try again later. or contact to administrator.',
            ], 429);
        }
        return $next($request);
    }
}
