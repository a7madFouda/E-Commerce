<?php

    include 'connect.php';

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

    // Include Navbar On All Pages Expect The On With $noNavbar Var

    if (!isset($noNavbar)) {

        include $tpl . "navbar.php";
        
    }
    