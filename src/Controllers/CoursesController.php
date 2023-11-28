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
            ->find_many();

        return $view->make('courses-list',[
            'courses'=>$courses,
            'categories'=>$categories,
        ]);
    }

    public function courseDetailPage(View $view, $id)
    {
        $course = ORM::for_table('courses')->findOne($id);
        $view->make('course-detail',[
            'course'=>$course
        ]);
    }
}