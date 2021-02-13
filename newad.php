<?php
    
    session_start();

    $pageTitle="Create New Item";

    include 'init.php';

    if(isset($_SESSION['user'])){


        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $formErrors = array();

            $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $cat        = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

            if(strlen($name) < 4) {

                $formErrors[] = 'Title Must Be At Least 4 Char';
            }

            if(strlen($desc) < 10) {

                $formErrors[] = 'Description Must Be At Least 10 Char';
            }

            if(strlen($country) < 2) {

                $formErrors[] = 'Country Must Be At Least 2 Char';
            }

            if(empty($price)) {

                $formErrors[] = 'Price Field Can\'t be Empty';
            }

            if(empty($status)) {

                $formErrors[] = 'Status box Can\'t be Empty';
            }

            if(empty($cat)) {

                $formErrors[] = 'Category Box Can\'t be Empty';
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
                    'zmember' => $_SESSION['uid'],
                    'ztags' => $tags,
                ));

                

                if($stmt){

                    $succesMsg = 'item added';
                }

            }

        }


?>

    <h1 class='text-center'><?php echo $pageTitle;?></h1>

    <div class='create-ad block'>
        <div class='container'>
            <div class='card '>
                <div class='card-header bg-primary text-white'><?php echo $pageTitle;?></div>
                <div class='card-body border border-primary'>
                    <div class='row'>
                        <div class='col-md-8'>
                            <form class='main-form' action='<?php echo $_SERVER["PHP_SELF"]?>' method="POST">

                                <!-- Start Name Field --> 
                                <div class='form-group row'>
                                    <label class='col-sm-3 col-form-label col-form-label-lg'>Name</label>
                                    <div class='col-sm-10 col-md-8'>
                                        <input  type="text" name='name' class='form-control form-control-lg live-name' placeholder="Name Of Item" required/>
                                    </div>
                                </div>
                                <!-- End Name Field --> 

                                <!-- Start Description Field --> 
                                <div class='form-group row'>
                                    <label class='col-sm-3 col-form-label col-form-label-lg'>Description</label>
                                    <div class='col-sm-10 col-md-8'>
                                        <input  type="text" name='description' class='form-control form-control-lg live-desc' placeholder="Descripe it" required/>
                                    </div>
                                </div>
                                <!-- End Description Field --> 

                                <!-- Start Price Field --> 
                                <div class='form-group row'>
                                    <label class='col-sm-3 col-form-label col-form-label-lg'>Price</label>
                                    <div class='col-sm-10 col-md-8'>
                                        <input  type="text" name='price' class='form-control form-control-lg live-price' placeholder="Cost of it" required/>
                                    </div>
                                </div>
                                <!-- End Price Field --> 

                                <!-- Start Country Field --> 
                                <div class='form-group row'>
                                    <label class='col-sm-3 col-form-label col-form-label-lg'>Country</label>
                                    <div class='col-sm-10 col-md-8'>
                                        <input  type="text" name='country' class='form-control form-control-lg' placeholder="put the Country" required/>
                                    </div>
                                </div>
                                <!-- End Country Field --> 

                                <!-- Start Status Field --> 
                                <div class='form-group row'>
                                    <label class='col-sm-3 col-form-label col-form-label-lg'>Status</label>
                                    <div class='col-sm-10 col-md-8'>

                                        <select class='form-control' name='status' required>
                                            <option value=''>...</option>
                                            <option value='1'>New</option>
                                            <option value='2'>Like New</option>
                                            <option value='3'>Used</option>
                                            <option value='4'>High Copy</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Status Field --> 

                                <!-- Start Categories Field --> 
                                <div class='form-group row'>
                                    <label class='col-sm-3 col-form-label col-form-label-lg'>Category</label>
                                    <div class='col-sm-10 col-md-8'>

                                        <select class='form-control' name='category' required>
                                            <option value=''>...</option>

                                            <?php 
                                                
                                                $cats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID" , "ASC");

                                                foreach($cats as $cat){

                                                    echo "<option value = '" .$cat['ID'] ."'> * ".$cat['Name'] ."</option>"; 

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
                                    <label class='col-sm-3 col-form-label col-form-label-lg'>Tags</label>
                                    <div class='col-sm-10 col-md-8'>
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
                        <div class='col-md-4'>
                            <div class='card item-box live-preview'>
                                <span class='price-tag'>$0</span>
                                <img src = 'index.jpg' alt='none'>
                                <div class='card-body'>
                                    <h3 class='card-title'>Title</h3>
                                    <p class='card-text'>None</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Start Looping Errors -->

                    <?php

                        global $formErrors;
                        
                        if(is_array($formErrors)){

                            foreach($formErrors as $error){
                                echo "<div class='alert alert-danger'>" . $error . "</div>";
                            }
                        }

                        if(isset($succesMsg)) {

                            echo "<div class='alert alert-success'>" . $succesMsg . "</div>";
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
