<?php

namespace Core;

class Acl
{
  public function __construct()
  {
  }

  public function isLoggedIn()
  {
    if (empty($_SESSION[SESSION_NAME])) {
      die(header('Location: ' . BASE_URL . "login"));
    }
  }

  public function isLoggedOut()
  {
    if (!empty($_SESSION[SESSION_NAME])) {
      die(header('Location: ' . BASE_URL . "home"));
    }
  }

  public function renewSession()
  {
    if (isset($_SESSION[SESSION_NAME]))
      $_SESSION[SESSION_NAME] = $_SESSION[SESSION_NAME];
  }
}
