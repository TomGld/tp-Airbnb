<?php

namespace App\Middleware;

use Closure;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

use Symplefony\IMiddleware;

use App\Controller\AuthController;

class Customer_VisitorMiddleware implements IMiddleware
{

    // Vérifie si l'utilisateur est un visiteur et un Customer
    public function handle(ServerRequestInterface $request, Closure $next): mixed
    {
        if (!AuthController::isAuth() || AuthController::isCustomer()) {
            return $next($request);
        }

        return new RedirectResponse('/');
    }

}
