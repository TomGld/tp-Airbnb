<?php

namespace App\Middleware;

use Closure;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

use Symplefony\IMiddleware;

use App\Controller\AuthController;

class AnnouncerMiddleware implements IMiddleware
{
    public function handle(ServerRequestInterface $request, Closure $next): mixed
    {
        if (AuthController::isAnnouncer()) {
            return $next($request);
        }

        return new RedirectResponse('/');
    }
}
