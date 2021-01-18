<?php

namespace Controllers;

use Core;

class ErrorController extends Core\Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
    }

    public function index()
    {
        $this->loadTemplate('error/404', []);
    }
}
