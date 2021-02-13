<?php

    session_start();
    $pageTitle="Login";
    
    if(isset($_SESSION['user'])) {

        header('Location: index.php'); // Redirect To HomePage
    }

    include 'init.php'; 

    //Check if USer Coming From HTTP Post Request

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['login'])){

            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPass = sha1($pass);
    
            // Check If The USer Exist In DAtaBase
    
            $stmt = $con->prepare("
                                    SELECT
                                        UserID, Username, Password 
                                    From 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        Password = ? ");
            $stmt->execute(array($user, $hashedPass));
            $get = $stmt->fetch();
            $count = $stmt->rowCount();
    
            // If count > 0 this mean the database contain record about this username
    
            if($count > 0) {
    
                $_SESSION['user'] = $user; // Register Session Name
                $_SESSION['uid'] = $get['UserID']; // Register User ID in Session Session 
                header('Location: index.php'); // Redirect To Dashboard Page
                exit();
            }
        }else {

            $formErrors = array();

            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            if(isset($username)){

                $filterdUser = filter_var($username,FILTER_SANITIZE_STRING);

                if(strlen($filterdUser) < 4){

                    $formErrors[] = 'User Name Cant be Less Than 4 Char';
                }
            }

            if(isset($_POST['password']) && isset($_POST['password2'])){

                if(empty($_POST['password'])){

                    $formErrors[] = 'Sorry You Must Type Password';
                }

                $pass1 = sha1($_POST['password']);
                $pass2 = sha1($_POST['password2']);

                if($pass1 !== $pass2){

                    $formErrors[] = 'Sorry Password is not match';
                }
            }

            if(isset($email)){

                $filterdEmail = filter_var($email,FILTER_SANITIZE_EMAIL);

                if(filter_var($email, FILTER_VALIDATE_EMAIL) != true){

                    $formErrors[] = 'This Email IS InValid';
                }
            }

            // Check if there is no error

            if(empty($formErrors)) {


                //Check if user is exist

                $check = checkItem('Username', 'users', $username);

                if($check == 1) {

                    $formErrors[] = 'This User is Exist';

                } else {

                    // Insert A New Member To Database

                    $stmt = $con->prepare('INSERT INTO
                                            users(Username, Password, Email,RegStatus, Date)
                                        VALUES   (:zuser, :zpass, :zmail, 0, now())');

                    $stmt->execute(array(

                    'zuser' => $username,
                    'zpass' => sha1($_POST['password']),
                    'zmail' => $email,
                    ));

                    $succesMsg = "Congrats You have Registered";

                }
            }
        }

        

    }
?>

    <div class='container login-page'>
        <h1 class='text-center'>
            <span class='selected' data-class='login'>Login</span> | <span data-class='signup'>Signup</span>
        </h1>

        <!-- Start Login Form -->
        <form class='login' action='<?php echo $_SERVER["PHP_SELF"]?>' method="POST">
            <div class='input-container'>
                <input class='form-control' type="text" name='username' autocomplete="off" placeholder="Type Your UserName"/>
            </div>

            <div class='input-container'>
                <input class='form-control' type="password" name='password' autocomplete="new-password" placeholder="**********"/>
            </div>
            <input class='btn btn-primary btn-block' name='login' type="submit" value="Login"/>
        </form>

        <!-- Start Signup Form -->
        <form class='signup' action='<?php echo $_SERVER["PHP_SELF"]?>' method="POST">
            <input pattern=".{4,8}" title="UserName Must Be Between 4 & 8 Chars" class='form-control' type="text" name='username' autocomplete="off" placeholder="Type Your UserName" required/>
            <input minlength="4" class='form-control' type="password" name='password' autocomplete="new-password" placeholder="Enter a Complex Password" required/>
            <input minlength="4" class='form-control' type="password" name='password2' autocomplete="new-password" placeholder="Enter Password Again" required/>
            <input class='form-control' type="email" name='email' placeholder="Type a Valid Email" required/>
            <input class='btn btn-success btn-block' name='signup' type="submit" value="SignUp"/>

        </form>
    </div>

    <!-- Start Errors Div -->

    <div class='the-errors text-center'>
        <?php 

            if(!empty($formErrors)){

                foreach($formErrors as $error){

                    echo '<div class="msg">'. $error . '</div>';
                }
            }

            if(isset($succesMsg)){

                echo "<div class='msg success'>" . $succesMsg . "</div>";
            }
        ?>
    </div>

<?php include $tpl . 'footer.php' ?>