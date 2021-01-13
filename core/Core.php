<?php

namespace Core;

class Core
{
  public function run()
  {
    $url = $_SERVER['QUERY_STRING'];

    $params = [];
    if (!empty($url) && $url != '/') {
      $url = explode('/', $url);
      $currentController = 'Controllers\\' . ucfirst($url[0]) . 'Controller';
      array_shift($url);

      if (isset($url[0])) {
        $currentAction = $url[0];
        array_shift($url);
      } else {
        $currentAction = 'index';
      }

      if (count($url) > 0) {
        $params = $url;
      }
    } else {
      $currentController = 'Controllers\HomeController';
      $currentAction = 'index';
    }

    if (class_exists($currentController)) {
      $c = new $currentController;
    } else {
      $c = new \Controllers\ErrorController;
    }

    if (method_exists($c, $currentAction)) {
      call_user_func_array([$c, $currentAction], $params);
    } else {
      $c = new \Controllers\ErrorController;
      $c->index();
      //throw new \Exception("Method $currentAction not found in controller", 500);
    }
  }
}
