<?php

namespace Controllers;

use Core;

class HomeController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->controller_name = str_replace("Controller", "", basename(__FILE__, '.php'));
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('home/home', []);
    }
}
