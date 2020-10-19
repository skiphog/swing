<?php

namespace App\Middleware;

use System\Http\Request;
use System\Middleware\MiddlewareInterface;

class GuestMiddleware implements MiddlewareInterface
{
    /**
     * @param Request  $request
     * @param callable $next
     *
     * @return \System\Http\Response|mixed
     */
    public function handle(Request $request, callable $next)
    {
        $auth = auth();

        if ($auth->isUser()) {
            if ($request->ajax()) {
                return json(['message' => 'Доступ разрешен только гостям'], 403);
            }

            return redirect('/user/' . $auth->id);
        }

        return $next($request);
    }
}
