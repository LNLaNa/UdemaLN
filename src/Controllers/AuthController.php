<?php

namespace Src\Controllers;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use ORM;

class AuthController
{
    public function loginPage(View $view)
    {
        return $view->make('auth.login');
    }

    public function registerPage(View $view)
    {
        return $view->make('auth.register');
    }



    public function login(ServerRequest $request)
    {
        $email = $request->getParsedBody()['email'];
        $password = md5($request->getParsedBody()['password']);

        $user = ORM::for_table('users')->where('email', $email)->findOne();

        if ($user && $user['password'] == $password) {
            $_SESSION['user_id'] = $user['id'];
            if ($user['is_admin'] == 1) {
                return new RedirectResponse('/admin');
            } else
                return new RedirectResponse('/profile');
        }
        return new EmptyResponse();
    }

    public function register(ServerRequest $request)
    {
        $body = $request->getParsedBody();

        $users = ORM::for_table('users')->create();

        $users->name = $body['name'];
        $users->last_name = $body['last_name'];
        $users->email = $body['email'];
        $users->password = md5($body['password']);

        $users->save();

        $_SESSION['user_id'] = $users['id'];

        return new RedirectResponse('/profile');
    }
}