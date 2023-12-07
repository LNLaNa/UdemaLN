<?php

namespace Src\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;
use MiladRahimi\PhpRouter\View\View;
use ORM;

class CoursesController
{
    public function coursesListPage(View $view)
    {
        $categories = ORM::for_table('categories')->find_many();

        if(isset($_POST["categoriesFilter"]))
        {
            $categoriesFilter = $_POST["categoriesFilter"];
            if ($categoriesFilter == 'all'){
                $courses = ORM::for_table('courses')
                    ->table_alias('courses')
                    ->select('courses.*')
                    ->select('categories.name', 'categoryName')
                    ->join('categories', array('courses.category_id', '=', 'categories.id'), 'categories')
                    //            ->where_not_equal('status', 'Pending')
                    ->where('status', 'Started')
                    ->find_many();
            } else {
//                var_dump($categoriesFilter);
//                $('#checkboxAll').checked = false;
                $courses = ORM::for_table('courses')
                    ->table_alias('courses')
                    ->select('courses.*')
                    ->select('categories.name', 'categoryName')
                    ->join('categories', array('courses.category_id', '=', 'categories.id'), 'categories')
//            ->where_not_equal('status', 'Pending')
                    ->where('status', 'Started')
                    ->where('category_id',$categoriesFilter)
                    ->find_many();
            }
        }
        else
            $courses = ORM::for_table('courses')
                ->table_alias('courses')
                ->select('courses.*')
                ->select('categories.name', 'categoryName')
                ->join('categories', array('courses.category_id', '=', 'categories.id'), 'categories')
    //            ->where_not_equal('status', 'Pending')
                ->where('status', 'Started')
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

        $lessons = ORM::for_table('lessons')->where('course_id', $id)->findMany();
//        ->groupBy('category')

        $view->make('course-detail',[
            'course'=>$course,
            'reviews'=>$reviews,
            'lessons'=>$lessons,
        ]);
    }

}