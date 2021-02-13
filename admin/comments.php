<?php

        /*============================================================
        == Manage Comment Page
        ==  Edit | Delete | Approve comment From Here
        ============================================================*/ 

        session_start();

        if(isset($_SESSION['Username'])) {

            $pageTitle = 'Member';

            include 'init.php';

            $do = isset($_GET['do'])?$_GET['do']:'Manage';

            // Start Manage Page
            if($do == 'Manage') {


                $stmt = $con->prepare("SELECT 
                                            comments.*, items.Name AS Item_Name, users.UserName AS Member
                                        FROM 
                                            comments
                                        INNER JOIN
                                            items
                                        ON
                                            items.Item_ID = comments.item_id
                                        INNER JOIN
                                            users
                                        ON
                                            users.UserID = comments.user_id");
                $stmt->execute();
                $rows = $stmt->fetchAll();
            
            ?>

                <!--Manage Member Page-->
                <h1 class='text-center'>Manage Comments</h1>

                <div class='container'>
                    <div class='table-responsive'>
                        <table class='main-table table text-center table-bordered'>
                            <tr>
                                <td>ID</td>
                                <td>Comment</td>
                                <td>Item Name</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>

                            <?php
                            
                                foreach($rows as $row) {

                                echo '<tr>';
                                echo '<td>' . $row['c_id'] . '</td>';
                                echo '<td>' . $row['comment'] . '</td>';
                                echo '<td>' . $row['Item_Name'] . '</td>';
                                echo '<td>' . $row['Member'] . '</td>';
                                echo '<td>' . $row['comment_date'] . '</td>';
                                echo 
                                "<td>
                                    <a href='comments.php?do=Edit&comid=". $row['c_id'] . "' class='btn btn-success'><i class='fas fa-edit' style='padding-right:3px'></i>Edit</a>
                                    <a href='comments.php?do=Delete&comid=". $row['c_id'] . "' class='btn btn-danger'><i class='fas fa-times'style='padding-right:3px'></i>Delete</a>";

                                    if($row['status'] == 0) {

                                        echo "<a href='comments.php?do=Approve&comid=". $row['c_id'] . "' class='btn btn-info activate'><i class='fas fa-check'style='padding-right:3px'></i></a>";

                                    }
                                echo "</td>";
                                echo '</tr>';
                                }

                            ?>
                            
                        </table>
                    </div>
                    
                </div>


    <?php }  elseif($do == 'Edit') { //Edit Page 
            

                //Check if The Get Request comid is numeric and get The int num 
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;


                // Select All Data Depend On This ID
                $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
                $stmt->execute(array($comid));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                //If The Id Is Found In Database Show The Form
                if($stmt->rowCount() > 0) { ?>

                    <h1 class='text-center'>Comments</h1>

                    <div class='container'>
                        <form action='?do=Update' method="POST">

                            <input type="hidden" name='comid' value='<?php echo $comid ?>' />
                            <!-- Start Comments Field --> 
                            <div class='form-group row'>
                                <label class='col-sm-2 col-form-label col-form-label-lg'>Comment</label>
                                <div class='col-sm-10 col-md-6'>
                                    <textarea class='form-control' name='comment'><?php echo $row['comment'] ?></textarea>
                                </div>
                            </div>
                            <!-- End Comments Field --> 

                            
                            <!-- Start Submit Field --> 
                            <div class='form-group row'>
                                <div class='col-sm-offset-2 col-sm-10'>
                                    <input  type="submit" value="Save" class='btn btn-primary btn-lg' />
                                </div>
                            </div>
                            <!-- End Submit Field -->
                        </form>
                    </div>

                
            
        <?php } else {
                
                echo "<div class='container'>";

                    $theMsg =  "<div class='alert alert-danger'>There No Data For This ID</div>";

                    redirectHome($theMsg);

                echo "</div>";

            }
        } elseif($do == 'Update') {

            // Update Info

            echo "<h1 class='text-center'>Update Comment</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Take Value From Input in form

                $id     = $_POST['comid'];
                $comment   = $_POST['comment'];
                

                // Update Info in Database

                $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                $stmt->execute(array($comment, $id));

                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>";

                redirectHome($theMsg , 'back');

            
            

            } else {

                $theMsg =  '<div class="alert alert-danger">Sorry You Cant Update Info</div>';

                redirectHome($theMsg);
            }

            echo "</div>";

        } elseif ($do == 'Delete'){

            //Delete Page
            echo "<h1 class='text-center'>Delete Comment</h1>";
            echo "<div class='container'>";

                //Check if The Get Request userid is numeric and get The int num 

                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

                // Check Item function

                $check = checkItem('c_id', 'comments', $comid);



                //If The Id Is Found In Database Show The Form

                if($check > 0) { 

                $stmt = $con->prepare('DELETE FROM comments WHERE c_id = :zid');
                $stmt->bindParam(':zid', $comid);
                $stmt->execute();


                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted </div>";

                redirectHome($theMsg, 'back');



            echo '</div>';

            } else {

                echo "<div class='container'>";

                    $theMsg = '<div class="alert alert-danger">This Is Not Exist</div>';

                    redirectHome($theMsg);

                echo "</div>";

            }


        } elseif($do == 'Approve') {

            //Approve Page

                echo "<h1 class='text-center'>Approve Comment</h1>";
                echo "<div class='container'>";

            //Check if The Get Request userid is numeric and get The int num 

                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

            // Check Item function

                $check = checkItem('c_id', 'comments', $comid);



                //If The Id Is Found In Database Show The Form

                if($check > 0) { 

                $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");

                $stmt->execute(array($comid));


                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Comment Approved </div>";

                redirectHome($theMsg, 'back');



            echo '</div>';

            } else {

                echo "<div class='container'>";

                    $theMsg = '<div class="alert alert-danger">This Is Not Exist</div>';

                    redirectHome($theMsg);

                echo "</div>";

            }

        }
        
            include $tpl . 'footer.php';

        } else {

            echo "You Are Not Authorized To Visit This Page ";
            header('location: index.php');
        }