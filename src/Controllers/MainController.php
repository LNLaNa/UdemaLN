<?php

namespace Src\Controllers;

use MiladRahimi\PhpRouter\View\View;

class MainController
{
    public function indexPage(View $view)
    {
        return $view->make('index');
    }


}