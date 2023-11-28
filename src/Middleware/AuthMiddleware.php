<?php

namespace Src\Middleware;

use Closure;
use Laminas\Diactoros\Response\RedirectResponse;
use ORM;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        $user = ORM::for_table('users')->findOne($_SESSION['user_id']);
        if (isset($_SESSION['user_id'])) {
            return $next($request);
        }
        return new RedirectResponse('/login');
    }
}