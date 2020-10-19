<?php

namespace App\Exceptions;

use Throwable;
use System\Routing\RouteException;
use System\Http\Exceptions\NotFoundException;
use System\Middleware\Handlers\WebExceptionsMiddleware as ExceptionsMiddleware;

class WebExceptionsMiddleware extends ExceptionsMiddleware
{
    /**
     * @param Throwable $e
     *
     * @return mixed
     */
    protected function generateError(Throwable $e)
    {
        if ($e instanceof NotFoundException) {
            //return view('404', compact('e'))->withCode(404);
        }

        if ($e instanceof RouteException) {
            //return view('404', compact('e'))->withCode(404);
        }

        return parent::generateError($e);
    }
}
