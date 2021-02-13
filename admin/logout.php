<?php

    session_start(); // Start The Session

    session_unset(); // Unset The Session

    session_destroy(); // Destroy The session

    header('location: index.php');

    exit();