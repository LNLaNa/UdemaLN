<?php

namespace Src\Controllers;

use MiladRahimi\PhpRouter\View\View;
use ORM;

class CoursesController
{
    public function coursesListPage(View $view)
    {
        $categories = ORM::for_table('categories')->find_many();
        $courses = ORM::for_table('courses')
            ->table_alias('courses')
            ->select('courses.*')
            ->select('categories.name', 'categoryName')
            ->join('categories', array('courses.category_id', '=', 'categories.id'), 'categories')
            ->where_not_equal('status', 'Pending')
            ->find_many();

        return $view->make('courses-list-sidebar',[
            'courses'=>$courses,
            'categories'=>$categories,
        ]);
    }

    public function courseDetailPage(View $view, $id)
    {
        $reviews = ORM::for_table('reviews')->where('course_id', $id)
            ->table_alias('reviews')
            ->select('reviews.*')
            ->select('users.name', 'userName')
            ->join('users', array('reviews.user_id', '=', 'users.id'), 'users')
            ->findMany();

        $course = ORM::for_table('courses')
            ->table_alias('courses')
            ->select('courses.*')
            ->select('users.name', 'userName')
            ->select('users.last_name', 'userLastName')
            ->join('users', array('courses.user_id', '=', 'users.id'), 'users')
            ->findOne($id);


        $view->make('course-detail',[
            'course'=>$course,
            'reviews'=>$reviews,
//            'newDate'=>$newDate,
        ]);
    }
}