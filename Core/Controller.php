<?php

namespace Core;

class Controller extends Acl
{
  public $controller_name;

  public function __construct()
  {
    parent::__construct();
  }

  public function loadViewInTemplate($viewName, $viewData)
  {
    extract($viewData);
    include 'views/' . $viewName . '.php';
  }

  public function loadTemplate($viewName, $viewData)
  {
    include 'views/template.php';
  }

  public function loadTemplateLogin($viewName, $viewData)
  {
    include 'views/login/login.php';
  }

  public function asJson($array)
  {
    echo json_encode($array, JSON_PRETTY_PRINT);
  }
}
