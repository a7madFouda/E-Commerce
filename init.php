<?php

    // Error Reporting

    ini_set('display_errors', 'on');
    error_reporting(E_ALL);

    include 'admin/connect.php';

    $sessionUser = '';

    if(isset($_SESSION['user'])){

        $sessionUser = $_SESSION['user'];
    }

    // Routes

    $tpl = 'includes/templetes/';
    $css = 'layout/css/';
    $js = 'layout/js/';
    $lang = 'includes/languages/';
    $func = 'includes/functions/';

    // Includes The Important Files

    include $func . 'functions.php';
    include $lang .'english.php';
    include $tpl . "header.php";

    