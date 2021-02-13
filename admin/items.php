<?php

    session_start();

    $pageTitle = 'Items';

    if(isset($_SESSION['Username'])) {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage') {


            $stmt = $con->prepare("SELECT 
                                        items.*,
                                        categories.Name AS Category_Name,
                                        users.Username
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
                                    ORDER BY
                                        Item_ID DESC");
            $stmt->execute();
            $items = $stmt->fetchAll();
            
            ?>

            <!--Manage Member Page-->
            <h1 class='text-center'>Manage Items</h1>

            <div class='container'>
                <div class='table-responsive'>
                    <table class='main-table table text-center table-bordered'>
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>UserName</td>
                            <td>Control</td>
                        </tr>

                        <?php
                        
                            foreach($items as $item) {

                                echo '<tr>';
                                echo '<td>' . $item['Item_ID'] . '</td>';
                                echo '<td>' . $item['Name'] . '</td>';
                                echo '<td>' . $item['Description'] . '</td>';
                                echo '<td>' . $item['Price'] . '</td>';
                                echo '<td>' . $item['Add_Date'] . '</td>';
                                echo '<td>' . $item['Category_Name'] . '</td>';
                                echo '<td>' . $item['Username'] . '</td>';
                                echo 
                                "<td>
                                    <a href='items.php?do=Edit&itemid=". $item['Item_ID'] . "' class='btn btn-success'><i class='fas fa-edit' style='padding-right:3px'></i>Edit</a>
                                    <a href='items.php?do=Delete&itemid=". $item['Item_ID'] . "' class='btn btn-danger'><i class='fas fa-times'style='padding-right:3px'></i>Delete</a>";
                                    if($item['Approve'] == 0) {

                                        echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID'] . "' class='btn btn-info activate'><i class='fas fa-check'style='padding-right:3px'></i></a>";
    
                                        }
                                    echo "</td>";
                                echo '</tr>';
                            }

                        ?>
                        
                    </table>
                </div>
                <a href="items.php?do=Add" class='btn btn-primary'><i class="fas fa-plus" style='padding-right:3px'></i>New Item</a>

            </div>


<?php   } elseif($do == 'Add'){?>


            <!-- Add New Items -->

            <h1 class='text-center'>Add New Items</h1>

            <div class='container'>
                <form action='?do=Insert' method="POST">

                    <!-- Start Name Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Name</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  type="text" name='name' class='form-control form-control-lg' required="required" placeholder="Name Of Item"/>
                        </div>
                    </div>
                    <!-- End Name Field --> 

                    <!-- Start Description Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Description</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  type="text" name='description' class='form-control form-control-lg' required="required" placeholder="Descripe it"/>
                        </div>
                    </div>
                    <!-- End Description Field --> 

                    <!-- Start Price Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Price</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  type="text" name='price' class='form-control form-control-lg' required="required" placeholder="Cost of it"/>
                        </div>
                    </div>
                    <!-- End Price Field --> 

                    <!-- Start Country Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Country</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  type="text" name='country' class='form-control form-control-lg' required="required" placeholder="put the Country"/>
                        </div>
                    </div>
                    <!-- End Country Field --> 

                    <!-- Start Status Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Status</label>
                        <div class='col-sm-10 col-md-6'>
    
                            <select class='form-control' name='status'>
                                <option value='0'>...</option>
                                <option value='1'>New</option>
                                <option value='2'>Like New</option>
                                <option value='3'>Used</option>
                                <option value='4'>High Copy</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field --> 

                    <!-- Start Member Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Membber</label>
                        <div class='col-sm-10 col-md-6'>
    
                            <select class='form-control' name='member'>
                                <option value='0'>...</option>

                                <?php 
                                    $allMembers = getAllFrom("*", "users", "", "", "UserID");

                                    foreach($allMembers as $user){

                                        echo "<option value = '" .$user['UserID'] ."'> ".$user['Username'] ."</option>"; 
                                    }
                                
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Member Field --> 

                    <!-- Start Categories Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Category</label>
                        <div class='col-sm-10 col-md-6'>
    
                            <select class='form-control' name='category'>
                                <option value='0'>...</option>

                                <?php 
                                
                                    $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID");

                                    foreach($allCats as $cat){

                                        echo "<option value = '" .$cat['ID'] ."'>* ".$cat['Name'] ."</option>"; 

                                        $childCats = getAllFrom("*", "categories", "WHERE parent ={$cat['ID']} ", "", "ID");

                                        

                                        if($childCats){
                                            echo "<optgroup label='Child'>";
                                        
                                            foreach($childCats as $child){

                                                echo "<option value = '" .$child['ID'] ."'> --- ".$child['Name'] ."</option>"; 
                                                
                                            }
                                            echo "</optgroup>";
                                        }
                                    }
                                
                                ?>

                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field --> 

                    <!-- Start Tags Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Tags</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  type="text" name='tags' class='form-control form-control-lg' placeholder="seprate Tags With Comma ( , )"/>
                        </div>
                    </div>
                    <!-- End Tags Field --> 

                
                    <!-- BTN --> 
                    <div class='form-group row'>
                        <div class='col-sm-offset-2 col-sm-10'>
                            <input  type="submit" value="Add Item" class='btn btn-primary btn-lg' />
                        </div>
                    </div>
                    <!-- BTN-->
                </form>
            </div>


        <?php


        } elseif($do == 'Insert'){
            // Inser New Item

            

            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Item</h1>";
                echo "<div class='container'>";

                // Take Value From Input in form

                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $price      = $_POST['price'];
                $country    = $_POST['country'];
                $status     = $_POST['status'];
                $member     = $_POST['member'];
                $cat        = $_POST['category'];
                $tags       = $_POST['tags'];


                // Form Validate

                $formErrors = array();

                if(empty($name)) {

                    $formErrors[] = '<div class="alert alert-danger">Name Can\'t be <strong> Empty</strong> </div>';
                }

                if(empty($desc)) {

                    $formErrors[] = '<div class="alert alert-danger">Name Can\'t be <strong> Empty</strong> </div>';
                }

                if(empty($price)) {

                    $formErrors[] = '<div class="alert alert-danger">Name Can\'t be <strong> Empty</strong> </div>';
                }

                if(empty($country)) {

                    $formErrors[] = '<div class="alert alert-danger">Name Can\'t be <strong> Empty</strong> </div>';
                }

                if($status == 0) {

                    $formErrors[] = '<div class="alert alert-danger">You Must Select <strong> Status</strong> </div>';
                }

                if($member == 0) {

                    $formErrors[] = '<div class="alert alert-danger">You Must Select <strong> member</strong> </div>';
                }

                if($cat == 0) {

                    $formErrors[] = '<div class="alert alert-danger">You Must Select <strong> category</strong> </div>';
                }

                foreach($formErrors as $error) {


                    redirectHome($error, 'back');
                }

                
                // Check if there is no error

                if(empty($formErrors)) {

                        // Insert A New Member To Database

                        $stmt = $con->prepare("INSERT INTO
                                                    items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags)
                                                VALUES   (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");

                        $stmt->execute(array(

                            'zname' => $name,
                            'zdesc' => $desc,
                            'zprice' => $price,
                            'zcountry' => $country,
                            'zstatus' => $status,
                            'zcat' => $cat,
                            'zmember' => $member,
                            'ztags' => $tags
                        ));

                        

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Added </div>";

                        redirectHome($theMsg, 'back');

                        }
                
                
                    echo "</div>";

                } else {

                echo "<div class='container'>";

                $theMsg =  '<div class="alert alert-danger">Sorry You Cant Inser Info</div>';

                redirectHome($theMsg);

                echo "</div>";
                }

        


        } elseif($do == 'Edit') {

            //Check if The Get Request itemid is numeric and get The int num 
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;


            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
            $stmt->execute(array($itemid));
            $item = $stmt->fetch();
            $count = $stmt->rowCount();

            //If The Id Is Found In Database Show The Form
            if($stmt->rowCount() > 0) { ?>

                <!-- Add New Items -->

                <h1 class='text-center'>Edit Items</h1>

                <div class='container'>
                    <form action='?do=Update' method="POST">
                        <input type="hidden" name='itemid' value='<?php echo $itemid;?>'>

                        <!-- Start Name Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Name</label>
                            <div class='col-sm-10 col-md-6'>
                                <input  type="text" name='name' class='form-control form-control-lg' required="required" placeholder="Name Of Item" value="<?php echo $item['Name'] ?>"/>
                            </div>
                        </div>
                        <!-- End Name Field --> 

                        <!-- Start Description Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Description</label>
                            <div class='col-sm-10 col-md-6'>
                                <input  type="text" name='description' class='form-control form-control-lg' required="required" placeholder="Descripe it" value="<?php echo $item['Description'] ?>"/>
                            </div>
                        </div>
                        <!-- End Description Field --> 

                        <!-- Start Price Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Price</label>
                            <div class='col-sm-10 col-md-6'>
                                <input  type="text" name='price' class='form-control form-control-lg' required="required" placeholder="Cost of it" value="<?php echo $item['Price'] ?>"/>
                            </div>
                        </div>
                        <!-- End Price Field --> 

                        <!-- Start Country Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Country</label>
                            <div class='col-sm-10 col-md-6'>
                                <input  type="text" name='country' class='form-control form-control-lg' required="required" placeholder="put the Country" value="<?php echo $item['Country_Made'] ?>"/>
                            </div>
                        </div>
                        <!-- End Country Field --> 

                        <!-- Start Status Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Status</label>
                            <div class='col-sm-10 col-md-6'>

                                <select class='form-control' name='status'>
                                    <option value='1' <?php if($item['Status'] == 1) {echo 'selected';} ?>>New</option>
                                    <option value='2' <?php if($item['Status'] == 2) {echo 'selected';} ?>>Like New</option>
                                    <option value='3' <?php if($item['Status'] == 3) {echo 'selected';} ?>>Used</option>
                                    <option value='4' <?php if($item['Status'] == 4) {echo 'selected';} ?>>High Copy</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Status Field --> 

                        <!-- Start Member Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Membber</label>
                            <div class='col-sm-10 col-md-6'>

                                <select class='form-control' name='member'>

                                    <?php 
                                    
                                        $stmt = $con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users = $stmt->fetchAll();

                                        foreach($users as $user){

                                            echo "<option value = '" .$user['UserID'] ."'";
                                            if($item['Member_ID'] == $user['UserID']) {echo 'selected';}
                                            echo "> ".$user['Username'] ."</option>"; 
                                        }
                                    
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Member Field --> 

                        <!-- Start Categories Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Category</label>
                            <div class='col-sm-10 col-md-6'>

                                <select class='form-control' name='category'>

                                    <?php 
                                    
                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                        $stmt2->execute();
                                        $cats = $stmt2->fetchAll();

                                        foreach($cats as $cat){

                                            echo "<option value = '" .$cat['ID'] ."'";
                                            if($item['Cat_ID'] == $cat['ID']) {echo 'selected';}
                                            echo "> ".$cat['Name'] ."</option>"; 
                                        }
                                    
                                    ?>

                                </select>
                            </div>
                        </div>
                        <!-- End Categories Field --> 

                        <!-- Start Tags Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Tags</label>
                            <div class='col-sm-10 col-md-6'>
                                <input  type="text" name='tags' class='form-control form-control-lg' placeholder="seprate Tags With Comma ( , )" value="<?php echo $item['tags'] ?>"/>
                            </div>
                        </div>
                        <!-- End Tags Field --> 
                    
                        <!-- BTN --> 
                        <div class='form-group row'>
                            <div class='col-sm-offset-2 col-sm-10'>
                                <input  type="submit" value="Edit Item" class='btn btn-primary btn-lg' />
                            </div>
                        </div>
                        <!-- BTN-->
                    </form>

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
                                            item_id = ?");
                    $stmt->execute(array($itemid));
                    $rows = $stmt->fetchAll();

                    if(! empty($rows)){
            
                    ?>

                <!--Manage Member Page-->
                <h1 class='text-center'>Manage [<?php echo $item['Name'] ?>] Comments</h1>


                    <div class='table-responsive'>
                        <table class='main-table table text-center table-bordered'>
                            <tr>                                
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>

                            <?php
                            
                                foreach($rows as $row) {

                                echo '<tr>';
                                echo '<td>' . $row['comment'] . '</td>';
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
                    <?php }?>

                </div>


<?php   } else {
            
            echo "<div class='container'>";

                $theMsg =  "<div class='alert alert-danger'>There No Data For This ID</div>";

                redirectHome($theMsg);

            echo "</div>";

    }

        } elseif($do == 'Update') {


                // Update Info

            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Take Value From Input in form

                $id             = $_POST['itemid'];
                $name           = $_POST['name'];
                $desc           = $_POST['description'];
                $price          = $_POST['price'];
                $country        = $_POST['country'];
                $status         = $_POST['status'];
                $cat            = $_POST['category'];
                $member         = $_POST['member'];
                $tags           = $_POST['tags'];

                
                // Form Validate

                $formErrors = array();

                if(empty($name)) {

                    $formErrors[] = '<div class="alert alert-danger">Name Can\'t be <strong> Empty</strong> </div>';
                }

                if(empty($desc)) {

                    $formErrors[] = '<div class="alert alert-danger">Name Can\'t be <strong> Empty</strong> </div>';
                }

                if(empty($price)) {

                    $formErrors[] = '<div class="alert alert-danger">Name Can\'t be <strong> Empty</strong> </div>';
                }

                if(empty($country)) {

                    $formErrors[] = '<div class="alert alert-danger">Name Can\'t be <strong> Empty</strong> </div>';
                }

                if($status == 0) {

                    $formErrors[] = '<div class="alert alert-danger">You Must Select <strong> Status</strong> </div>';
                }

                if($member == 0) {

                    $formErrors[] = '<div class="alert alert-danger">You Must Select <strong> member</strong> </div>';
                }

                if($cat == 0) {

                    $formErrors[] = '<div class="alert alert-danger">You Must Select <strong> category</strong> </div>';
                }

                foreach($formErrors as $error) {

                    redirectHome($error, 'back');
                }


                
                // Check if there is no error

                if(empty($formErrors)) {

                    // Update Info in Database

                    $stmt = $con->prepare("UPDATE
                                                items 
                                            SET 
                                                Name = ?,
                                                Description = ? , 
                                                Price = ?, 
                                                Country_Made = ?, 
                                                Status = ?, 
                                                Cat_ID = ?, 
                                                Member_ID = ? ,
                                                tags = ?
                                            WHERE
                                                Item_ID = ?");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

                    $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>";

                    redirectHome($theMsg , 'back');

                }
                

                } else {

                    $theMsg =  '<div class="alert alert-danger">Sorry You Cant Update Info</div>';

                    redirectHome($theMsg);
                }

                echo "</div>";

        } elseif($do == 'Delete') {

                //Delete Page
                echo "<h1 class='text-center'>Delete Item</h1>";
                echo "<div class='container'>";

                //Check if The Get Request itemid is numeric and get The int num 

                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                // Check Item function

                $check = checkItem('Item_ID', 'items', $itemid);



                //If The Id Is Found In Database Show The Form

                if($check > 0) { 

                $stmt = $con->prepare('DELETE FROM items WHERE Item_ID = :zid');
                $stmt->bindParam(':zid', $itemid);
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

                //Activate Page

                echo "<h1 class='text-center'>Approve Items</h1>";
                echo "<div class='container'>";

                //Check if The Get Request itemid is numeric and get The int num 

                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                // Check Item function

                $check = checkItem('Item_ID', 'items', $itemid);



                //If The Id Is Found In Database Show The Form

                if($check > 0) { 

                    $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");

                    $stmt->execute(array($itemid));


                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Item Approved </div>";

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

        header('Location : index.php');

        exit();
    }
