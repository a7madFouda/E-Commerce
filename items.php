<?php
    
    session_start();

    $pageTitle="Show Items";

    include 'init.php';

    //Check if The Get Request itemid is numeric and get The int num 
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;


    // Select All Data Depend On This ID
    $stmt = $con->prepare("SELECT 
                                items.* ,
                                categories.Name AS category_name,
                                users.UserName
                            FROM 
                                items 
                            INNER JOIN
                                categories
                            ON
                                categories.ID = items.Cat_ID
                            INNER JOIN
                                users
                            ON
                                users.UserID = items.Member_ID

                            WHERE 
                                Item_ID = ?
                            AND 
                                Approve = 1");
    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();
    if($count > 0){

    
    $item = $stmt->fetch();



?>

    <h1 class='text-center'><?php echo $item['Name'];?></h1>

    <div class='container'>
        <div class='row'>
            <div class='col-md-3 text-center'>
                <img src = 'index.jpg' alt='none' class='img-thumbnail'>
            </div>
            <div class='col-md-9 item-info'>
                <h2><?php echo $item['Name']; ?></h2>
                <p><?php echo $item['Description']; ?></p>
                <ul class='list-unstyled'>
                    <li>
                        <i class='far fa-calendar fa-fw'></i>
                        <span>Added Date</span> : <?php echo $item['Add_Date']; ?>
                    </li>
                    <li>
                        <i class="far fa-money-bill-alt"></i>
                        <span> Price</span> : $<?php echo $item['Price']; ?>
                    </li>
                    <li>
                        <i class="far fa-flag"></i>
                        <span>Made In</span> <?php echo $item['Country_Made']; ?>
                    </li>
                    <li>
                        <i class="far fa-object-ungroup"></i>
                        <span>Category</span> : <a href='categories.php?pageid=<?php echo $item['Cat_ID']?>'> <?php echo $item['category_name']; ?></a>
                    </li>
                    <li>
                        <i class='far fa-user fa-fw'></i>    
                        <span>Added By</span> : <a href=''> <?php echo $item['UserName']; ?></a>
                    </li>
                    <li class='tags-items'>
                        <i class='far fa-user fa-fw'></i>    
                        <span>Tags</span> : 
                        <?php
                            $allTags = explode(',',$item['tags']);

                            foreach($allTags as $tag){
                                $tag = str_replace(' ', '', $tag);
                                $lowerTag = strtolower($tag);
                                if(!empty($tag)){
                                    echo "<a href='tags.php?name={$lowerTag}'>" . $tag . "</a> ";
                                }
                            }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <!-- Start Add Comment-->
        <?php if(isset($_SESSION['user'])){?>
        <div class='row'>
            <div class='col-md-9 offset-md-3'>
                <div class='add-comment'>
                    <h3>Add Your Comment</h3>
                    <form action='<?php echo $_SERVER["PHP_SELF"] . "?itemid=" . $item["Item_ID"] ?>' method="POST">
                        <textarea name='comment' required></textarea>
                        <input class='btn btn-primary' type='submit' value="Add Comment">
                    </form>
                    <?php 

                        if($_SERVER['REQUEST_METHOD'] == 'POST') {

                            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $itemid = $item['Item_ID'];
                            $userid = $_SESSION['uid'];

                            if(! empty($comment)){

                                $stmt = $con->prepare("INSERT INTO 
                                                            comments(comment, status, comment_date, item_id, user_id)
                                                        VALUE
                                                            (:zcomment, 0, now(), :zitemid, :zuserid)");

                                $stmt->execute(array(

                                    'zcomment'  => $comment,
                                    'zitemid'   => $itemid,
                                    'zuserid'   =>$userid
                                ));

                                if($stmt){

                                    echo "<div class='alert alert-success'> Comment Added </div>";
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php } else {
            
            echo "<a href='login.php'>Login</a> Or <a href='login.php'>Register</a> To Add Comment";
        }?>
        <!-- End Add Comment-->
        <hr>
<?php
                $stmt = $con->prepare("SELECT 
                                            comments.*, users.UserName AS Member
                                        FROM 
                                            comments
                                        INNER JOIN
                                            users
                                        ON
                                            users.UserID = comments.user_id
                                        WHERE 
                                            item_id = ?
                                        AND
                                            status = 1
                                        ORDER BY
                                            c_id DESC");
                $stmt->execute(array($item["Item_ID"]));
                $comments = $stmt->fetchAll();

                foreach($comments as $comment){?>
                    <div class='comment-box'>
                        <div class = 'row'>
                            <div class='col-md-2 text-center'>
                                <img src = 'index.jpg' alt='none' class='img-thumbnail rounded-circle mx-auto d-block'>
                                <?php echo $comment['Member'] ?> 
                            </div>
                            <div class='col-md-10'>
                                <p class='lead'><?php echo $comment['comment'] ?></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                <?php }
?>

    </div>


<?php

    }else {
        echo 'there is no such id or this item waiting approve';
    }

    include $tpl . "footer.php";
?>