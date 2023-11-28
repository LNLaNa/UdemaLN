<?php

use Laminas\Diactoros\Response\RedirectResponse;
use MiladRahimi\PhpRouter\Router;
use Src\Controllers\AdminController;
use Src\Controllers\AuthController;
use Src\Controllers\CoursesController;
use Src\Controllers\MainController;
use Src\Controllers\TeacherController;
use Src\Middleware\AdminMiddleware;
use Src\Middleware\AuthMiddleware;

require_once "vendor/autoload.php";

session_start();

ORM::configure('mysql:host=database;dbname=docker');
ORM::configure('username', 'docker');
ORM::configure('password', 'docker');

$router = Router::create();

$router->setupView('view');

$router->get("/", [MainController::class,'indexPage']);

$router->get("/login", [AuthController::class, 'loginPage']);
$router->post("/login", [AuthController::class, 'login']);


$router->get("/register", [AuthController::class, 'registerPage']);
$router->post("/register", [AuthController::class, 'register']);


$router->group(
    ['middleware' => [AuthMiddleware::class]],
    function (Router $router) {
        $router->get('/profile', [TeacherController::class, 'profilePage']);
        $router->post('/update_profile', [TeacherController::class, 'updateProfile']);
        $router->get('/reviews', [TeacherController::class, 'reviewsPage']);
        $router->get('/bookmarks', [TeacherController::class, 'bookmarksPage']);

        $router->get('/add-listing', [CoursesController::class, 'addListingPage']);
        $router->post('/add-listing', [CoursesController::class, 'createContent']);

        $router->get('/courses', [CoursesController::class, 'coursesPage']);
        $router->post('/courses', [CoursesController::class, 'createCourse']);

        $router->get('/add-contents/{id_course}', [CoursesController::class, 'addContentsPage']);

        $router->get('/clear-sessions', function () {
            unset($_SESSION['user_id']);
            return new RedirectResponse('/');
        });

        $router->group(
            ['middleware' => [AdminMiddleware::class]],
            function (Router $router) {
                $router->get('/admin', [AdminController::class, 'adminPage']);
                $router->get('/change/approve/{id}', [AdminController::class, 'changeApproveStatus']);
                $router->get('/change/cancel/{id}', [AdminController::class, 'changeCancelStatus']);
            }
        );
    }
);



$router->dispatch();