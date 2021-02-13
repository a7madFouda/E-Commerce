<?php
    
    session_start();

    $pageTitle="Profile";

    include 'init.php';

    if(isset($_SESSION['user'])){

    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");

    $getUser->execute(array($sessionUser));

    $info = $getUser->fetch();

?>

    <h1 class='text-center'>My Profile</h1>

    <div class='information block'>
        <div class='container'>
            <div class='card '>
                <div class='card-header bg-primary text-white'>My Information</div>
                <div class='card-body border border-primary'>
                    <ul class='list-unstyled'>
                        <li>
                            <i class='fa fa-unlock-alt fa-fw'></i>
                            <span>Name </span> : <?php echo $info['Username'];?> 
                        </li>
                        <li>
                            <i class='far fa-envelope fa-fw'></i>
                            <span>Email </span> : <?php echo $info['Email'];?> 
                        </li>
                        <li>
                            <i class='fa fa-user fa-fw'></i>
                            <span>FullName </span> : <?php echo $info['FullName'];?> 
                        </li>
                        <li>
                            <i class='far fa-calendar fa-fw'></i>
                            <span>Register Date </span> : <?php echo $info['Date'];?> 
                        </li>
                        <li>
                            <i class='fa fa-tag fa-fw'></i>
                            <span>Fav Category </span> : 
                        </li>
                    </ul>
                    <a href='#' class='btn btn-outline-primary'>Edit Information</a>
                </div>
            </div>
        </div>
    </div>

    <div id="my-items" class='my-ads block'>
        <div class='container'>
            <div class='card '>
                <div class='card-header bg-primary text-white'>My Items</div>
                <div class='card-body border border-primary'>
                    
                        <?php
                            $myItems = getAllFrom("*", "items", "WHERE Member_ID = {$info['UserID']}", "", "Item_ID");
                            if(!empty($myItems)){
                                echo "<div class='row'>";
                                    foreach($myItems as $item){
                                        echo "<div class='col-sm-6 col-md-3'>";
                                            echo "<div class='card item-box'>";
                                                if($item['Approve'] == 0){ echo "<span class='approve-status'>Not Approved</span>";}
                                                echo "<span class='price-tag'>". $item['Price'] ."</span>";
                                                echo "<img src = 'index.jpg' alt='none'>";
                                                echo "<div class='card-body'>";
                                                    echo "<h3 class='card-title'><a href='items.php?itemid=". $item['Item_ID'] ."'>" . $item['Name'] ."</a></h3>";
                                                    echo "<p class='card-text'>". $item['Description'] ."</p>";
                                                    echo "<div class='date'>". $item['Add_Date'] ."</div>";
                                                echo "</div>";
                                            echo "</div>";
                                        echo "</div>";
                                    }
                                echo "</div>";
                            } else {

                                echo "Sorry There is No Ads To Show, Create <a href='newad.php'>New Ad</a>";
                            }

                        ?>
                </div>
            </div>
        </div>
    </div>

    <div class='my-comments block'>
        <div class='container'>
            <div class='card '>
                <div class='card-header bg-primary text-white'>Latest Comments</div>
                <div class='card-body border border-primary'>
                    <?php

                        $myComments = getAllFrom("comment", "comments", "WHERE user_id = {$info['UserID']}", "", "c_id");
                        

                        if(! empty($myComments)){

                            foreach($myComments as $comment){
                                echo "<p> " . $comment['comment']. "</p>";
                            }
                        } else {

                            echo 'There is No Comments';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php

    }else {
        header('Location: login.php');
        exit();
    }

    include $tpl . "footer.php";
?>