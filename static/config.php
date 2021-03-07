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
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <meta name='description' content='PlanoD'>
        <meta name='author' content='Lucão'>
        <style>
            " . file_get_contents(BASE_PATH . "assets/css/TimesNewRoman.css") . "

            table {
                border-collapse: collapse;
                width: 100%;
                font-size: 15px;
                font-family: \"TimesNewRoman\" !important;
            }

            thead 
            {
                display: table-header-group
            }

            tfoot 
            {
                display: table-row-group
            }

            tr
            {
                page-break-inside: avoid
            }

            th, td {
                white-space: nowrap;
                margin: 0px;
                padding: 5px;
            }

            h1, h2, h3, h4, h5, p {
                margin-top: 5px;
                margin-left: 0px;
                margin-bottom: 5px;
                margin-right: 0px;
            }

            .keep-together {
                page-break-inside: avoid;
            }
            
            .break-before {
                page-break-before: always;
            }
            
            .break-after {
                page-break-after: always;
            }

            .text-center {
                text-align:center
            }

            .text-left {
                text-align:left
            }

            .text-right {
                text-align:right
            }

            .alignLeft {
                float: left;
                width:33.33333%;
                text-align:center;
            }

            .alignCenter {
                float: left;
                width:33.33333%;
                text-align:center;
            }

            .alignRight {
                float: left;
                width:33.33333%;
                text-align:center;
            }​
        </style>
    </head>
    <body>");
define('PRINT_END', "
    </body>
</html>");
