<?php
ini_set('session.gc_maxlifetime', 60 * 60 * 24);
session_set_cookie_params(60 * 60 * 24);
session_start();

date_default_timezone_set("America/Bahia");

$base_path = rtrim(__DIR__, '/\\') . DIRECTORY_SEPARATOR;
define('BASE_PATH', $base_path);
define('BASE_URL', "http://" . $_SERVER['HTTP_HOST'] . "/" . @end(explode("\\", dirname(__FILE__))) . "/");

/**
 * Composer
 */
require 'vendor/autoload.php';

/**
 * Erro and Exception Handling
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$core = new Core\Core();
$core->run();
