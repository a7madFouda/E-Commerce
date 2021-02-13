<?php

    /*============================================================
      == Manage Member Page
      == Add | Edit | Delete Member From Here
      ============================================================*/ 

      session_start();

      if(isset($_SESSION['Username'])) {

        $pageTitle = 'Member';

        include 'init.php';

        $do = isset($_GET['do'])?$_GET['do']:'Manage';

        // Start Manage Page
        if($do == 'Manage') {

          $query = '';

          if(isset($_GET['page']) && $_GET['page'] == 'pending'){

            $query = "AND RegStatus = 0";
          }

            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
            $stmt->execute();
            $rows = $stmt->fetchAll();
          
          ?>

            <!--Manage Member Page-->
            <h1 class='text-center'>Manage Members</h1>

            <div class='container'>
                <div class='table-responsive'>
                    <table class='main-table table text-center table-bordered'>
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>UserName</td>
                            <td>Email</td>
                            <td>FullName</td>
                            <td>Registered Date</td>
                            <td>Control</td>
                        </tr>

                        <?php
                        
                            foreach($rows as $row) {

                              echo '<tr>';
                              echo '<td>' . $row['UserID'] . '</td>';
                              echo '<td>';

                                if(! empty($row['avatar'])){
                                  echo '<img src = "uploads/avatars/' . $row['avatar'] . '" alt= "avatar" />';
                                } else {
                                  echo '<img src = "uploads/avatars/user.png" alt= "avatar" />';
                                }

                              echo '</td>';
                              echo '<td>' . $row['Username'] . '</td>';
                              echo '<td>' . $row['Email'] . '</td>';
                              echo '<td>' . $row['FullName'] . '</td>';
                              echo '<td>' . $row['Date'] . '</td>';
                              echo 
                              "<td>
                                  <a href='members.php?do=Edit&userid=". $row['UserID'] . "' class='btn btn-success'><i class='fas fa-edit' style='padding-right:3px'></i>Edit</a>
                                  <a href='members.php?do=Delete&userid=". $row['UserID'] . "' class='btn btn-danger'><i class='fas fa-times'style='padding-right:3px'></i>Delete</a>";

                                  if($row['RegStatus'] == 0) {

                                    echo "<a href='members.php?do=Activate&userid=". $row['UserID'] . "' class='btn btn-info activate'><i class='fas fa-check'style='padding-right:3px'></i></a>";

                                  }
                              echo "</td>";
                              echo '</tr>';
                            }

                        ?>
                        
                    </table>
                </div>
                <a href="members.php?do=Add" class='btn btn-primary'><i class="fas fa-plus" style='padding-right:3px'></i>New Memeber</a>

            </div>


  <?php } elseif($do == 'Add'){ ?>

          <!-- Add Page Member -->

          <h1 class='text-center'>Add New Member</h1>

          <div class='container'>
              <form action='?do=Insert' method="POST" enctype="multipart/form-data">

                    <!-- Start USerName Field --> 
                  <div class='form-group row'>
                      <label class='col-sm-2 col-form-label col-form-label-lg'>UserName</label>
                      <div class='col-sm-10 col-md-6'>
                          <input  type="text" name='username' class='form-control form-control-lg' autocomplete="off" required="required" placeholder="USerName To Login Into Shop"/>
                      </div>
                  </div>
                    <!-- End USerName Field --> 

                    <!-- Start Password Field --> 
                  <div class='form-group row'>
                      <label class='col-sm-2 col-form-label col-form-label-lg'>Password</label>
                      <div class='col-sm-10 col-md-6'>
                          <input  type="password" name='newpassword' class='password form-control form-control-lg' autocomplete="new-password" required="required" placeholder="Password Must Be Complex"/>
                          <i class="fas fa-eye show-pw"></i> 
                      </div>
                  </div>
                    <!-- End Password Field -->

                    <!-- Start Email Field --> 
                  <div class='form-group row'>
                      <label class='col-sm-2 col-form-label col-form-label-lg'>Email</label>
                      <div class='col-sm-10 col-md-6'>
                          <input  type="email" name='email' class='form-control form-control-lg' required="required" placeholder="Email Must Be Valid"/>
                      </div>
                  </div>
                    <!-- End Email Field -->

                    <!-- Start Fullname Field --> 
                  <div class='form-group row'>
                      <label class='col-sm-2 col-form-label col-form-label-lg'>Fullname</label>
                      <div class='col-sm-10 col-md-6'>
                          <input  type="text" name='full' class='form-control form-control-lg' required="required" placeholder="Full Name For Your Profile"/>
                      </div>
                  </div>
                    <!-- End Fullname Field -->

                    <!-- Start Avatar Field --> 
                  <div class='form-group row'>
                      <label class='col-sm-2 col-form-label col-form-label-lg'>User Avatar</label>
                      <div class='col-sm-10 col-md-6'>
                          <input  type="file" name='avatar' class='form-control form-control-lg' required="required"/>
                      </div>
                  </div>
                    <!-- End Avatar Field -->

                    <!-- BTN --> 
                  <div class='form-group row'>
                      <div class='col-sm-offset-2 col-sm-10'>
                          <input  type="submit" value="Add Member" class='btn btn-primary btn-lg' />
                      </div>
                  </div>
                    <!-- BTN-->
              </form>
          </div>

  <?php 
  
        } elseif($do == 'Insert'){

              // Inser New Member

              

              if($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Member</h1>";
                echo "<div class='container'>";

                // Upload Variable

                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTmp = $_FILES['avatar']['tmp_name'];
                $avatarType = $_FILES['avatar']['type'];

                // List Of file Typed
                $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

                // Get Avatar Extension
                $splitName = explode('.', $avatarName);

                $Extension = end($splitName);

                $avatarExtension = strtolower($Extension);

                

                // Take Value From Input in form

                $user   = $_POST['username'];
                $pass   = $_POST['newpassword'];
                $email  = $_POST['email'];
                $name   = $_POST['full'];

                $hashPass = sha1($_POST['newpassword']);

                // Form Validate

                $formErrors = array();

                if(strlen($user) < 4) {

                  $formErrors[] = '<div class="alert alert-danger">UserName Can\'t be Less Than <strong>4 Char</strong> </div>';
                }

                if(strlen($user) > 20) {

                  $formErrors[] = '<div class="alert alert-danger">UserName Can\'t be More Than <strong>20 Char</strong></div>';
                }

                if(empty($user)) {

                  $formErrors[] = '<div class="alert alert-danger">UserName Can\'t Be Empty</div>';

                }

                if(empty($name)) {

                  $formErrors[] = '<div class="alert alert-danger">Full Name Can\'t Be Empty</div>';

                }

                if(empty($email)) {

                  $formErrors[] = '<div class="alert alert-danger">Email Can\'t Be Empty</div>';

                }

                if(! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)){

                  $formErrors[] = '<div class="alert alert-danger">This Extension Is Not Allowed</div>';

                }

                if(empty($avatarName)){

                  $formErrors[] = '<div class="alert alert-danger">Avatar is Required</div>';

                }

                if($avatarSize > 4194304){

                  $formErrors[] = '<div class="alert alert-danger">Avatar Can\'t be larger than 4MB</div>';

                }

                foreach($formErrors as $error) {

                  echo $error . '<br/>';
                }

                
                // Check if there is no error

                if(empty($formErrors)) {

                  $avatar = rand(0, 1000000000) . "_" . $avatarName;

                  move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

                    //Check if user is exist

                    $check = checkItem('Username', 'users', $user);

                    if($check == 1) {

                        $theMsg =  '<div class="alert alert-danger">This User Is Already Exist</div>';

                        redirectHome($theMsg , 'back');
                    } else {

                        // Insert A New Member To Database

                        $stmt = $con->prepare('INSERT INTO
                                                  users(Username, Password, Email, FullName,RegStatus, Date, avatar)
                                              VALUES   (:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar)');

                        $stmt->execute(array(

                          'zuser' => $user,
                          'zpass' => $hashPass,
                          'zmail' => $email,
                          'zname' => $name,
                          'zavatar' => $avatar
                        ));

                        

                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Added </div>";

                        redirectHome($theMsg, 'back');

                      }
                }
                
                  echo "</div>";

              } else {

                echo "<div class='container'>";

                $theMsg =  '<div class="alert alert-danger">Sorry You Cant Inser Info</div>';

                redirectHome($theMsg);

                echo "</div>";
              }

        
        
        } elseif($do == 'Edit') { //Edit Page 
        

              //Check if The Get Request userid is numeric and get The int num 
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;


            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            //If The Id Is Found In Database Show The Form
            if($stmt->rowCount() > 0) { ?>

                <h1 class='text-center'>Member</h1>

                <div class='container'>
                    <form action='?do=Update' method="POST">

                        <input type="hidden" name='userid' value='<?php echo $userid ?>' />
                          <!-- Start USerName Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>UserName</label>
                            <div class='col-sm-10 col-md-6'>
                                <input  type="text" name='username' class='form-control form-control-lg' value="<?php echo $row['Username'] ?>" autocomplete="off" required="required"/>
                            </div>
                        </div>
                          <!-- End USerName Field --> 

                          <!-- Start Password Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Password</label>
                            <div class='col-sm-10 col-md-6'>
                                <input  type="hidden" name='oldpassword' value="<?php echo $row['Password']?>"/>
                                <input  type="password" name='newpassword' class='form-control form-control-lg' autocomplete="new-password" placeholder="Leave IT BLank If You Don't Want To Change"/>
                            </div>
                        </div>
                          <!-- End Password Field -->

                          <!-- Start Email Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Email</label>
                            <div class='col-sm-10 col-md-6'>
                                <input  type="email" name='email' class='form-control form-control-lg' value="<?php echo $row['Email']?>" required="required"/>
                            </div>
                        </div>
                          <!-- End Email Field -->

                          <!-- Start Fullname Field --> 
                        <div class='form-group row'>
                            <label class='col-sm-2 col-form-label col-form-label-lg'>Fullname</label>
                            <div class='col-sm-10 col-md-6'>
                                <input  type="text" name='full' class='form-control form-control-lg' value="<?php echo $row['FullName'] ?>" required="required" />
                            </div>
                        </div>
                          <!-- End Fullname Field -->

                          <!-- Start USerName Field --> 
                        <div class='form-group row'>
                            <div class='col-sm-offset-2 col-sm-10'>
                                <input  type="submit" value="Save" class='btn btn-primary btn-lg' />
                            </div>
                        </div>
                          <!-- End USerName Field -->
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

        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

          // Take Value From Input in form

          $id     = $_POST['userid'];
          $user   = $_POST['username'];
          $email  = $_POST['email'];
          $name   = $_POST['full'];

          // Password Set

          //Condition ? true : false
          $pass= empty($_POST['newpassword'])?$_POST['oldpassword']:sha1($_POST['newpassword']);

          // Form Validate

          $formErrors = array();

          if(strlen($user) < 4) {

            $formErrors[] = '<div class="alert alert-danger">UserName Can\'t be Less Than <strong>4 Char</strong> </div>';
          }

          if(strlen($user) > 20) {

            $formErrors[] = '<div class="alert alert-danger">UserName Can\'t be More Than <strong>20 Char</strong></div>';
          }

          if(empty($user)) {

            $formErrors[] = '<div class="alert alert-danger">UserName Can\'t Be Empty</div>';

          }

          if(empty($name)) {

            $formErrors[] = '<div class="alert alert-danger">Full Name Can\'t Be Empty</div>';

          }

          if(empty($email)) {

            $formErrors[] = '<div class="alert alert-danger">Email Can\'t Be Empty</div>';

          }

          foreach($formErrors as $error) {

            echo $error . '<br/>';
          }

          
          // Check if there is no error

          if(empty($formErrors)) {

              $stmt2 = $con->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ?");
              $stmt2->execute(array($user,$id));
              $count = $stmt2->rowCount();

              if($count == 1) {

                $theMsg =  '<div class="alert alert-danger">Sorry This User Is Exist</div>';
                redirectHome($theMsg , 'back');

              } else {

                    // Update Info in Database

                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ? , FullName = ?, Password = ?  WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));

                    $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>";

                    redirectHome($theMsg , 'back');

              }


              
          }
          

        } else {

          $theMsg =  '<div class="alert alert-danger">Sorry You Cant Update Info</div>';

          redirectHome($theMsg);
        }

        echo "</div>";

      } elseif ($do == 'Delete'){

        //Delete Page
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

            //Check if The Get Request userid is numeric and get The int num 

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            // Check Item function

            $check = checkItem('UserID', 'users', $userid);



            //If The Id Is Found In Database Show The Form

            if($check > 0) { 

              $stmt = $con->prepare('DELETE FROM users WHERE UserID = :zuser');
              $stmt->bindParam(':zuser', $userid);
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


      } elseif($do == 'Activate') {

        //Activate Page

            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";

        //Check if The Get Request userid is numeric and get The int num 

            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Check Item function

            $check = checkItem('UserID', 'users', $userid);



            //If The Id Is Found In Database Show The Form

            if($check > 0) { 

              $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

              $stmt->execute(array($userid));


              $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " User Activated </div>";

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