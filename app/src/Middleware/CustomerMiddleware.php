<?php

namespace App\Middleware;

use App\Controller\AuthController;
use Closure;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;
use Symplefony\IMiddleware;

class CustomerMiddleware implements IMiddleware
{
    public function handle( ServerRequestInterface $request, Closure $next ): mixed
    {
        if( AuthController::isCustomer() ) {
            return $next( $request );
        }

        return new RedirectResponse( '/' );
    }
}