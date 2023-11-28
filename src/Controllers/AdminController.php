<?php

namespace Src\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use ORM;
use Src\RequestStatus;

class AdminController
{
    public function adminPage(View $view)
    {
        $courses = ORM::for_table('courses')
            ->table_alias('courses')
            ->select('courses.*')
            ->select('users.name', 'nameUser')
            ->select('users.last_name', 'lastNameUser')
            ->select('categories.name', 'categoryName')
            ->join('users', array('courses.user_id', '=', 'users.id'), 'users')
            ->join('categories', array('courses.category_id', '=', 'categories.id'), 'categories')
            ->find_many();
        return $view->make('admin.courses', ['courses' => $courses]);
    }

    public function changeApproveStatus($id)
    {
        $courses = ORM::for_table('courses')->findOne($id);
        $courses->set('status', RequestStatus::STARTED->value);
        $courses->save();
        return new RedirectResponse('/admin');
    }

    public function changeCancelStatus($id)
    {
        $courses = ORM::for_table('courses')->findOne($id);
        $courses->set('status', RequestStatus::CANCELLED->value);
        $courses->save();
        return new RedirectResponse('/admin');
    }
}