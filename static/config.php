<?php

const DB_OS = 'windows';
const DB_ENGINE = 'mysql';
const DB_HOST = 'localhost';
const DB_NAME = 'planod';
const DB_USER = 'root';
const DB_PASSWORD = 'root';
const SHOW_ERRORS = false;
const SESSION_NAME = 'planod';

define('PRINT_START', "<!DOCTYPE html>
    <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <meta name='description' content='PlanoD'>
    <meta name='author' content='Lucão'>
    <style>
        " . file_get_contents(BASE_PATH . 'assets/vendor/fontawesome-free/css/all.min.css') . "
        " . file_get_contents(BASE_PATH . 'assets/vendor/bootstrap/css/bootstrap.min.css') . "
        " . file_get_contents(BASE_PATH . 'assets/css/ruang-admin.min.css') . "
    </style>
    </head>
    <body id='page-top'>
        <div id='wrapper'>
            <div id='content-wrapper' class='d-flex flex-column'>
                <div id='content'>
                    <div class='container-fluid' id='container-wrapper'>
                        <div class='row'>
                            <div class='col-lg-6'>
                                <div class='card mb-4'>
                                    <div class='table-responsive'>");
define('PRINT_END', "
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>");
