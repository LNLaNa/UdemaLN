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


    public function reviewCreate(ServerRequest $request, $id_course)
    {
        $body = $request->getParsedBody();

        date_default_timezone_set('Asia/Krasnoyarsk');
        $date = date('Y-m-d');

        if (isset($_SESSION['user_id'])){
            $reviews = ORM::for_table('reviews')->create();
            $reviews->review = $body['review'];
            $reviews->user_id = $_SESSION['user_id'];
            $reviews->course_id = $id_course;
            $reviews->date = $date;

            $reviews->save();

            return new  RedirectResponse('/courses-detail/'.$id_course);
        }
        else return new  RedirectResponse('/login');

    }

    public function whishList()
    {
        $course_id =  $_GET['course_id'];
        $user_id =  $_SESSION['user_id'];


        $isLike = ORM::for_table('whishList')->where('course_id', $course_id)->where('user_id', $user_id)->findOne();

        if ($isLike) {
            $isLike->delete();
        } else {
            $like = ORM::for_table('whishList')->create();
            $like->course_id = $course_id;
            $like->user_id = $user_id;
            $like->save();

        }
        $referer = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'/';
        return new RedirectResponse($referer);
//        return new RedirectResponse('/course-detail/'.$course_id);
    }
}