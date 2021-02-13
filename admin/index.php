<?php
    session_start();

    $noNavbar = '';
    $pageTitle = 'Login';


    if(isset($_SESSION['Username'])) {

        header('Location:dashboard.php'); //Redirect To Dashboard page
    }

    include 'init.php';

    //Check if USer Coming From HTTP Post Request

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPass = sha1($password);

        // Check If The USer Exist In DAtaBase

        $stmt = $con->prepare("
                                SELECT
                                    UserID, Username, Password 
                                From 
                                    users 
                                WHERE 
                                    Username = ? 
                                AND 
                                    Password = ? 
                                AND 
                                    GroupID = 1
                                Limit 1");
        $stmt->execute(array($username, $hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // If count > 0 this mean the database contain record about this username

        if($count > 0) {

            $_SESSION['Username'] = $username; // Register Session Name
            $_SESSION['ID'] = $row['UserID'];  // Register User ID 
            header('Location: dashboard.php'); // Redirect To Dashboard Page
            exit();
        }

    }
?>


    <form class='login' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h4 class='text-center'>Admin Login</h4>
        <input class='form-control' type='text' name='user' placeholder='Username' autocomplete="off" />
        <input class='form-control' type='password' name='pass' placeholder="password" autocomplete="new-password" />
        <input class='btn btn-primary btn-block' type='submit' value='login' />
    </form>

<?php

    include $tpl . "footer.php";
?>