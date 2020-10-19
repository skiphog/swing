<?php

namespace App\Middleware;

use System\Http\Request;
use System\Http\Response;
use System\Middleware\MiddlewareInterface;

/**
 * Class ProfilerMiddleware
 *
 * @package App\Middleware
 */
class NoCacheMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param callable $next
     *
     * @return Response|mixed
     */
    public function handle(Request $request, callable $next)
    {
        /** @var Response $response */
        $response = $next($request);

        return $response->withHeaders([
            'Cache-Control' => 'no-store;max-age=0',
        ]);
    }
}
