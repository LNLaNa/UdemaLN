<?php

namespace Src\Middleware;

use Closure;
use Laminas\Diactoros\Response\RedirectResponse;
use ORM;
use Psr\Http\Message\ServerRequestInterface;

class AdminMiddleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        $user = ORM::for_table('users')->findOne($_SESSION['user_id']);
        if ($user['is_admin'] === 1) {
            return $next($request);
        }
        return new RedirectResponse('/login');
    }
}