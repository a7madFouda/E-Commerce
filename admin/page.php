 <?php

    /*
        Categories => [manage , add, edit , insert, update, delete, stats]
    */

    $do = '';

    if(isset($_GET['do'])) {

        $do = $_GET['do'];

    } else {

        $do = 'Manage';
        
    }

    // if this page is main page

    if($do == 'Manage') {

        echo 'Welcome You Are In manage category page';
        echo '<a href="page.php?do=Add"> Add New Category </a>';

    }elseif ($do == 'Add') {

        echo 'Welcome You Are in Add Category Page';

    } elseif ($do == 'Insert') {

        echo 'Welcome To insert Category Page';

    } else {

        echo 'Error There\'s no page with this name';
    }