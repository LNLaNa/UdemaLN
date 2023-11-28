<?php

namespace Src\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use ORM;

class TeacherController
{
    public function reviewsPage(View $view)
    {
        return $view->make('users.reviews');
    }

    public function bookmarksPage(View $view)
    {
        return $view->make('users.bookmarks');
    }

    public function profilePage(View $view)
    {
        $user = ORM::for_table('users')->findOne($_SESSION['user_id']);
        return $view->make('users.teacher-profile', ['user' => $user]);
    }

    public function updateProfile(ServerRequest $request)
    {
        $body = $request->getParsedBody();

//        $random = bin2hex(random_bytes(10));
//        $img = explode('.', $request->getUploadedFiles()['img_profile']->getClientFilename());
//        $img_name = $random.time().'.'.$img[1];
//        $request->getUploadedFiles()['img_profile']->moveTo('/var/www/html/img/profile_img/'.$img_name);
//        var_dump($img_name);

        $user = ORM::for_table('users')->findOne($_SESSION['user_id']);

        $user->set('name', $body['name']);
        $user->set('last_name', $body['last_name']);
        $user->set('phone', $body['phone']);
        $user->set('email', $body['email']);
//        $user->set('img', $img_name);

        $user->save();

        return new RedirectResponse('/profile');
    }
}