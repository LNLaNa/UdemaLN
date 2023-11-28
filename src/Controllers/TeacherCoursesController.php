<?php

namespace Src\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use ORM;
use Src\RequestStatus;

class TeacherCoursesController
{
    public function addListingPage(View $view)
    {
        $categories = ORM::for_table('categories')->find_many();
        return $view->make('users.add-listing', ['categories' => $categories]);
    }

    public function createCourse(ServerRequest $request)
    {
        $body = $request->getParsedBody();

        $course = ORM::for_table('courses')->create();

        $course->name = $body['name'];
        $course->description = $body['description'];
        $course->price = $body['price'];
        $course->status = RequestStatus::PENDING->value;
        $course->date_start = $body['date_start'];
        $course->date_end = $body['date_end'];
        $course->category_id = $body['category'];
        $course->user_id = $_SESSION['user_id'];

        $course->save();

        return new RedirectResponse('/courses');
    }

    public function coursesPage(View $view)
    {
        $courses = ORM::for_table('courses')
            ->table_alias('courses')
            ->select('courses.*')
            ->select('users.name', 'nameUser')
            ->select('users.last_name', 'lastNameUser')
            ->select('categories.name', 'categoryName')
            ->join('users', array('courses.user_id', '=', 'users.id'), 'users')
            ->join('categories', array('courses.category_id', '=', 'categories.id'), 'categories')
            ->where('user_id', $_SESSION['user_id'])
            ->find_many();
        return $view->make('users.courses', ['courses' => $courses]);
    }

    public function addContentsPage(View $view,$id_course)
    {
        $course = ORM::for_table('courses')->findOne($id_course);
        return $view->make('users.add-content',['course' => $course]);
    }
}