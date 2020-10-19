<?php

namespace App\Middleware;

use System\Http\Request;
use System\Middleware\MiddlewareInterface;

/**
 * Class AuthMiddleware
 *
 * @package App\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @param Request  $request
     * @param callable $next
     *
     * @return \System\Http\Response|mixed
     */
    public function handle(Request $request, callable $next)
    {
        if (auth()->isGuest()) {
            if ($request->ajax()) {
                return json(['message' => 'Доступ разрешен только зарегистрированным пользователям'], 403);
            }

            return redirect('/auth/login');
        }

        return $next($request);
    }
}
