<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo getTitle () ?></title>
        <link rel='stylesheet' href='<?php echo $css; ?>bootstrap.min.css'>
        <link rel='stylesheet' href='<?php echo $css; ?>all.min.css'>
        <link rel='stylesheet' href='<?php echo $css; ?>front.css'>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Shop</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="app-nav">
                
                <ul class="navbar-nav mr-auto">
                    <li class='nav-item'><a class="nav-link" href="index.php">HomePage</a></li>
                    <?php
                        $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID" , "ASC");
                        foreach($allCats as $cat){
                            echo "<li class='nav-item'><a class='nav-link' href='categories.php?pageid=" . $cat['ID'] ."'>" .$cat['Name'] ."</a></li>";
                        }
                    
                    ?>
                </ul>
            

            <?php 

            if(isset($_SESSION['user'])){?>

                <ul class='navbar-nav ml-auto'>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION['user']  ?> <i class="fas fa-user-circle"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="profile.php">Profile</a>
                        <a class="dropdown-item" href="newad.php">New Item</a>
                        <a class="dropdown-item" href="profile.php#my-items">My Items</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Log out</a>
                        </div>
                    </li>
                </ul>
            
            <?php

            } else{
        ?>
        <ul class="navbar-nav ml-auto">
                    <li class='nav-item'><a class="nav-link custom-login" href="login.php" style="color:#50b0ea">Login/Signup</a></li>
        </ul>
        <?php }?>
        </div>
        </nav>
    </body>
