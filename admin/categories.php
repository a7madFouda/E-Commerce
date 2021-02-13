<?php

    session_start();

    $pageTitle = 'Categories';

    if(isset($_SESSION['Username'])) {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == 'Manage') {

            $sort = 'ASC';

            $sort_array = array('ASC', 'DESC');

            if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){

                $sort = $_GET['sort'];
            }
            $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");
            $stmt2->execute();
            $cats = $stmt2->fetchAll();  ?>

            <h1 class='text-center'>Manage Category</h1>
            <div class='container categories'>
                <div class='card'>
                    <div class='card-header'>
                        <i class="fa fa-edit"></i> Manage Category
                        <div class='option float-right'>
                            <i class="fas fa-sort"></i> Ordering: [
                            <a class="<?php if($sort == 'ASC') {echo 'active';}?>" href='?sort=ASC'>ASC</a> |
                            <a class="<?php if($sort == 'DESC') {echo 'active';}?>" href='?sort=DESC'>DESC</a> ] 
                            <i class="fas fa-eye"></i> View: [
                            <span data-view='full' class='active'>Full</span> |
                            <span data-view='classic'>Classic</span> ]
                        </div>
                    </div>
                    <div class='card-body'>
                        <?php
                            foreach($cats as $cat) {

                                echo "<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a href='categories.php?do=Edit&catid=" .$cat['ID']. "' class='btn btn-sm btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                        echo "<a href='categories.php?do=Delete&catid=" .$cat['ID']. "' class='btn btn-sm btn-danger'><i class='fa fa-times'></i> Delete</a>";
                                    echo "</div>";
                                    echo '<h3>'. $cat['Name'] . '</h3>';
                                    echo "<div class='full-view'>";
                                        echo '<p>'; if($cat['Description'] == ''){ echo 'No Description for it';} else {echo $cat['Description'];}   echo '</p>';
                                        if($cat['Visibility'] == 1) {echo '<span class="visiblity"><i class="fas fa-eye-slash"></i> Hidden </span>' ;} 
                                        if($cat['Allow_Comment'] == 1) {echo '<span class="commenting"><i class="fa fa-times"></i> Commenting Disabled</span>';} 
                                        if($cat['Allow_Ads'] == 1) {echo '<span class="advertises"><i class="fa fa-times"></i> Ads Disabled</span>';} 
                                    echo "</div>";

                                    // Get Child Category

                                    $childCats = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}", "", "ID" , "ASC");

                                    if(! empty($childCats)){
                                        
                                        echo "<h4 class='child-head'>Child Category</h4>";
                                        echo "<ul class='list-unstyled child-cats'>";
                                        foreach($childCats as $c){
                                            echo "
                                            <li class='child-link'> 
                                                <a href='categories.php?do=Edit&catid=" .$c['ID']. "' >" . $c['Name'] ."</a>
                                                <a href='categories.php?do=Delete&catid=" .$c['ID']. "' class='show-delete'>Delete</a>
                                            </li>";
                                        }
                                        echo "</ul>";
                                    }
                                    
                                echo "</div>";
                                

                                
                                echo "<hr>";

                            }
                        ?>
                    </div>
                </div>

                <a href='categories.php?do=Add' class='add-category btn btn-primary'><i class='fa fa-plus' style='margin-right:5px;'></i>Add New Category</a>
            </div>

<?php
        } elseif($do == 'Add'){?>


            <!-- Add New Category -->

            <h1 class='text-center'>Add New Category</h1>

            <div class='container'>
                <form action='?do=Insert' method="POST">

                    <!-- Start Name Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Name</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  type="text" name='name' class='form-control form-control-lg' autocomplete="off" required="required" placeholder="Name Of Category"/>
                        </div>
                    </div>
                    <!-- End Name Field --> 

                    <!-- Start Description Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Description</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  type="text" name='description' class='form-control form-control-lg' placeholder="Descripe It .."/> 
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Ordering Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Ordering</label>
                        <div class='col-sm-10 col-md-6'>
                            <input  type="text" name='ordering' class='form-control form-control-lg'placeholder="Number to arrange category"/>
                        </div>
                    </div>
                    <!-- End Ordering Field -->
                    
                    <!-- Start Category Type Field --> 

                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Parent ?</label>
                        <div class='col-sm-10 col-md-6'>
                            <select class='form-control' name='parent'>
                                <option value="0"> None </option>
                                <?php

                                    $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "ASC");

                                    foreach($allCats as $cat){

                                        echo "<option value='" .$cat['ID'] . "'>" .$cat['Name'] . "</option>";
                                    }

                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- End Category Type Field -->


                    <!-- Start Visibility Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Visibile</label>
                        <div class='col-sm-10 col-md-6'>
                            <div>
                                <input id='vis-yes' type='radio' name='visibility' value='0' checked />
                                <label for='vis-yes'>Yes</label>
                            </div>

                            <div>
                                <input id='vis-no' type='radio' name='visibility' value='1' />
                                <label for='vis-no'>No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility Field -->

                    <!-- Start Comment Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Allow Commenting</label>
                        <div class='col-sm-10 col-md-6'>
                            <div>
                                <input id='com-yes' type='radio' name='commenting' value='0' checked />
                                <label for='com-yes'>Yes</label>
                            </div>

                            <div>
                                <input id='com-no' type='radio' name='commenting' value='1' />
                                <label for='com-no'>No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Comment Field -->

                    <!-- Start Ads Field --> 
                    <div class='form-group row'>
                        <label class='col-sm-2 col-form-label col-form-label-lg'>Allow Ads</label>
                        <div class='col-sm-10 col-md-6'>
                            <div>
                                <input id='ads-yes' type='radio' name='ads' value='0' checked />
                                <label for='ads-yes'>Yes</label>
                            </div>

                            <div>
                                <input id='ads-no' type='radio' name='ads' value='1' />
                                <label for='ads-no'>No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Field -->

                    <!-- BTN --> 
                    <div class='form-group row'>
                        <div class='col-sm-offset-2 col-sm-10'>
                            <input  type="submit" value="Add Category" class='btn btn-primary btn-lg' />
                        </div>
                    </div>
                    <!-- BTN-->
                </form>
            </div>


        <?php
        } elseif($do == 'Insert'){

            // Inser New Member

            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Category</h1>";
                echo "<div class='container'>";

                // Take Value From Input in form

                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $parent     = $_POST['parent'];
                $order      = $_POST['ordering'];
                $visible    = $_POST['visibility'];
                $comment    = $_POST['commenting'];
                $ads        = $_POST['ads'];


                // Form Validate


                    //Check if Category is exist

                    if(!empty($name)){

                        $check = checkItem('Name', 'categories', $name);

                        if($check == 1) {
    
                            $theMsg =  '<div class="alert alert-danger">This Category Is Already Exist</div>';
    
                            redirectHome($theMsg , 'back');
                        } else {
    
                            // Insert A New Member To Database
    
                            $stmt = $con->prepare("INSERT INTO
                                                        categories(Name, Description, parent, Ordering, Visibility, Allow_Comment, Allow_Ads)
                                                    VALUES   (:zname, :zdesc, :zparent, :zorder, :zvis, :zcom, :zads)");
    
                            $stmt->execute(array(
    
                                'zname'     => $name,
                                'zdesc'     => $desc,
                                'zparent'   => $parent,
                                'zorder'    => $order,
                                'zvis'      => $visible,
                                'zcom'      => $comment,
                                'zads'      => $ads
                            ));
    
                            
    
                            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Added </div>";
    
                            redirectHome($theMsg, 'back');
    
                            }
                    } else{

                        $theMsg =  "<div class='alert alert-danger'>Name Can't Be Empty</div>";

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

                //Check if The Get Request catid is numeric and get The int num 

                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;


                // Select All Data Depend On This ID
                $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");

                $stmt->execute(array($catid));

                $row = $stmt->fetch();

                $count = $stmt->rowCount();
    
                //If The Id Is Found In Database Show The Form
                if($count > 0) { ?>
    
                    <h1 class='text-center'>Edit Category</h1>

                    <div class='container'>
                        <form action='?do=Update' method="POST">

                            <input type="hidden" name='catid' value='<?php echo $catid ?>' />

                            <!-- Start Name Field --> 
                            <div class='form-group row'>
                                <label class='col-sm-2 col-form-label col-form-label-lg'>Name</label>
                                <div class='col-sm-10 col-md-6'>
                                    <input  type="text" name='name' class='form-control form-control-lg' required="required" placeholder="Name Of Category" value="<?php echo $row['Name']; ?>"/>
                                </div>
                            </div>
                            <!-- End Name Field --> 

                            <!-- Start Description Field --> 
                            <div class='form-group row'>
                                <label class='col-sm-2 col-form-label col-form-label-lg'>Description</label>
                                <div class='col-sm-10 col-md-6'>
                                    <input  type="text" name='description' class='form-control form-control-lg' placeholder="Descripe It .." value="<?php echo $row['Description']; ?>"/> 
                                </div>
                            </div>
                            <!-- End Description Field -->

                            <!-- Start Ordering Field --> 
                            <div class='form-group row'>
                                <label class='col-sm-2 col-form-label col-form-label-lg'>Ordering</label>
                                <div class='col-sm-10 col-md-6'>
                                    <input  type="text" name='ordering' class='form-control form-control-lg'placeholder="Number to arrange category" value="<?php echo $row['Ordering']; ?>"/>
                                </div>
                            </div>
                            <!-- End Ordering Field -->

                            <!-- Start Category Type Field --> 

                            <div class='form-group row'>
                                <label class='col-sm-2 col-form-label col-form-label-lg'>Parent ?</label>
                                <div class='col-sm-10 col-md-6'>
                                    <select class='form-control' name='parent'>
                                        <option value="0"> None </option>
                                        <?php

                                            $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "ASC");

                                            foreach($allCats as $c){

                                                echo "<option value='" .$c['ID'] . "'";
                                                if($row['parent'] == $c['ID']){
                                                    echo "selected";
                                                }
                                                echo ">" .$c['Name'] . "</option>";
                                            }

                                        ?>
                                    </select>
                                </div>
                            </div>

                    <!-- End Category Type Field -->

                            <!-- Start Visibility Field --> 
                            <div class='form-group row'>
                                <label class='col-sm-2 col-form-label col-form-label-lg'>Visibile</label>
                                <div class='col-sm-10 col-md-6'>
                                    <div>
                                        <input id='vis-yes' type='radio' name='visibility' value='0' <?php if($row['Visibility'] == '0'){ echo 'checked';} ?> />
                                        <label for='vis-yes'>Yes</label>
                                    </div>

                                    <div>
                                        <input id='vis-no' type='radio' name='visibility' value='1' <?php if($row['Visibility'] == '1'){ echo 'checked';} ?> />
                                        <label for='vis-no'>No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Visibility Field -->

                            <!-- Start Comment Field --> 
                            <div class='form-group row'>
                                <label class='col-sm-2 col-form-label col-form-label-lg'>Allow Commenting</label>
                                <div class='col-sm-10 col-md-6'>
                                    <div>
                                        <input id='com-yes' type='radio' name='commenting' value='0' <?php if($row['Allow_Comment'] == '0'){ echo 'checked';} ?> />
                                        <label for='com-yes'>Yes</label>
                                    </div>

                                    <div>
                                        <input id='com-no' type='radio' name='commenting' value='1' <?php if($row['Allow_Comment'] == '1'){ echo 'checked';} ?>/>
                                        <label for='com-no'>No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Comment Field -->

                            <!-- Start Ads Field --> 
                            <div class='form-group row'>
                                <label class='col-sm-2 col-form-label col-form-label-lg'>Allow Ads</label>
                                <div class='col-sm-10 col-md-6'>
                                    <div>
                                        <input id='ads-yes' type='radio' name='ads' value='0' <?php if($row['Allow_Ads'] == '0'){ echo 'checked';} ?> />
                                        <label for='ads-yes'>Yes</label>
                                    </div>

                                    <div>
                                        <input id='ads-no' type='radio' name='ads' value='1' <?php if($row['Allow_Ads'] == '1'){ echo 'checked';} ?> />
                                        <label for='ads-no'>No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- End Ads Field -->

                            <!-- BTN --> 
                            <div class='form-group row'>
                                <div class='col-sm-offset-2 col-sm-10'>
                                    <input  type="submit" value="Edit Category" class='btn btn-primary btn-lg' />
                                </div>
                            </div>
                            <!-- BTN-->
                        </form>
                    </div>

                
<?php   } else {
                
                echo "<div class='container'>";
    
                    $theMsg =  "<div class='alert alert-danger'>There No Data For This ID</div>";
    
                    redirectHome($theMsg);
    
                echo "</div>";
        }


        } elseif($do == 'Update') {

            // Update Info

            echo "<h1 class='text-center'>Update Categories</h1>";
            echo "<div class='container'>";

            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Take Value From Input in form

                $id         = $_POST['catid'];
                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $order      = $_POST['ordering'];
                $parent     = $_POST['parent'];
                $visible    = $_POST['visibility'];
                $comment    = $_POST['commenting'];
                $ads        = $_POST['ads'];


                // Update Info in Database

                $stmt = $con->prepare("UPDATE 
                                            categories
                                        SET 
                                            Name = ?,
                                            Description = ? , 
                                            Ordering = ?, 
                                            parent = ?, 
                                            Visibility = ?, 
                                            Allow_Comment = ?, 
                                            Allow_Ads= ?  
                                        WHERE 
                                            ID = ?");
                $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));

                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>";

                redirectHome($theMsg , 'back');

                
            

            } else {

                $theMsg =  '<div class="alert alert-danger">Sorry You Cant Update Info</div>';

                redirectHome($theMsg);
            }

            echo "</div>";

        } elseif($do == 'Delete') {


                //Delete Page
                echo "<h1 class='text-center'>Delete Category</h1>";
                echo "<div class='container'>";

                //Check if The Get Request userid is numeric and get The int num 

                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

                // Check Item function

                $check = checkItem('ID', 'categories', $catid);



                //If The Id Is Found In Database Show The Form

                if($check > 0) { 

                $stmt = $con->prepare('DELETE FROM categories WHERE ID = :zid');
                $stmt->bindParam(':zid', $catid);
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
        }

        include $tpl . 'footer.php';

    } else {

        header('Location : index.php');

        exit();
    }
