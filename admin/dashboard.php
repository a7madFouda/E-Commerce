<?php    

    session_start();

    if(isset($_SESSION['Username'])) {

        $pageTitle = 'dashboard';

        include 'init.php';

        /* Start Dashboard*/

        // Num Of Latest Users
        $numUsers = 4;

        // Function Of The Latest USers
        $latestUsers = getLatest("*", "users", "UserID", $numUsers);

        // Num Of Latest Items
        $numItems = 4;

        // Function Of The Latest USers
        $latestItems = getLatest("*", "items", "Item_ID", $numItems);

        // Num Of Comments
        $numComments = 4;


        ?>

        <div class='home-stats'>
            <div class='container text-center'>
                <h1>Dashboard</h1>
                <div class='row'>
                    <div class='col-md-3'>
                        <div class='stat st-members'>
                            Total Members
                            <span><a href='members.php'><?php echo countItems('UserID', 'users');?></a></span>
                        </div>
                    </div>

                    <div class='col-md-3'>
                        <div class='stat st-pending'>
                            Pending Members
                            <span><a href='members.php?do=Manage&page=pending'><?php echo checkItem('RegStatus', 'users', 0);?></a></span>
                        </div>
                    </div>

                    <div class='col-md-3'>
                        <div class='stat st-items'>
                            Total Items
                            <span><a href='items.php'><?php echo countItems('Item_ID', 'items');?></a></span>
                        </div>
                    </div>

                    <div class='col-md-3'>
                        <div class='stat st-comments'>
                            Total Comments
                            <span><a href='comments.php'><?php echo countItems('c_id', 'comments');?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class='latest'>
            <div class='container'>
                <div class='row'>
                    <div class='col-sm-6'>
                        <div class='card'>
                            
                            <div class='card-header'>
                                <i class='fa fa-users'></i> Latest <?php echo $numUsers ?> Register Users
                                <span class='toggle-info float-right'>
                                    <i class='fa fa-plus fa-lg'></i>
                                </span>
                            </div>
                            <div class='card-body'>
                                <ul class='list-unstyled latest-users'>
                                
                                    <?php

                                        if(! empty($latestUsers)){

                                            foreach($latestUsers as $user) {

                                                echo '<li>';
                                                    echo $user['Username'];
                                                    echo "<a href='members.php?do=Edit&userid=" .$user['UserID'] ."'>";
                                                        echo "<span class='btn btn-success float-right'>";
                                                            echo "<i class='fa fa-edit'></i> Edit";
                                                            if($user['RegStatus'] == 0) {

                                                                echo "<a href='members.php?do=Activate&userid=". $user['UserID'] . "' class='btn btn-info activate float-right'><i class='fas fa-check'style='padding-right:3px'></i></a>";
                            
                                                            }
                                                        echo"</span>";
                                                    echo "</a>";
                                                echo"</li>";
                                            }
                                        } else{

                                            echo "There's No Users To Show";
                                        }
                                    ?>

                                </ul>           
                            </div>
                        </div>
                    </div>

                    <div class='col-sm-6'>
                        <div class='card'>
                            <div class='card-header'>
                                <i class='fa fa-tag'></i> Latest <?php echo $numItems ?> Items Added
                                <span class='toggle-info float-right'>
                                    <i class='fa fa-plus fa-lg'></i>
                                </span>
                            </div>

                            <div class='card-body'>
                                <ul class='list-unstyled latest-users'>
                                    
                                    <?php
                                        if(! empty($latestItems)){

                                            foreach($latestItems as $item) {

                                                echo '<li>';
                                                    echo $item['Name'];
                                                    echo "<a href='items.php?do=Edit&itemid=" .$item['Item_ID'] ."'>";
                                                        echo "<span class='btn btn-success float-right'>";
                                                            echo "<i class='fa fa-edit'></i> Edit";
                                                            if($item['Approve'] == 0) {

                                                                echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID'] . "' class='btn btn-info activate float-right'><i class='fas fa-check'style='padding-right:3px'></i></a>";
                            
                                                            }
                                                        echo"</span>";
                                                    echo "</a>";
                                                echo"</li>";
                                            }
                                        }else{

                                            echo "There is No Items To Show";
                                        }
                                    ?>

                                </ul>           
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start Comment Row -->
                <div class='row'>

                    <div class='col-sm-6'>
                        <div class='card'>
                            <div class='card-header'>
                                <i class='fas fa-comments'></i> Latest <?php echo $numComments ?> Comment
                                <span class='toggle-info float-right'>
                                    <i class='fa fa-plus fa-lg'></i>
                                </span>
                            </div>

                            <div class='card-body'>
                            <?php

                                $stmt = $con->prepare("SELECT 
                                                        comments.*, users.UserName AS Member
                                                    FROM 
                                                        comments
                                                    INNER JOIN
                                                        users
                                                    ON
                                                        users.UserID = comments.user_id
                                                    ORDER BY 
                                                        c_id DESC
                                                    LIMIT
                                                        $numComments");
                                $stmt->execute();
                                $comments = $stmt->fetchAll();

                                if(! empty($comments)){

                                    foreach($comments as $comment){

                                        echo "<div class='comment-box'>";
                                            echo "<span class='member-n'>" . $comment['Member'] . "</span>";
                                            echo "<p class='member-c'>" . $comment['comment'] . "</p>";
                                        echo "</div>";
                                    }
                                } else {

                                    echo "There is No Comments To Show ";
                                }

                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php

        /* End Dashboard*/

        

        include $tpl . "footer.php";
    } else {

        echo 'You Are Not Authorized To SEe This Page';

        header('Location: index.php');

        exit();
    }

    ?>