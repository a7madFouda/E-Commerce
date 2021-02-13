<?php

    // Front End ===========================================================

    /*
    ** Get  All Function v1
    ** Function To Get  All Records From Any Database Tables
    */

    function getAllFrom($field, $tableName, $where = NULL, $and = NULL, $orderBy, $ordering = 'DESC'){

        global $con;


        $getAll = $con->prepare("SELECT $field FROM $tableName $where $and ORDER BY $orderBy $ordering");
        $getAll->execute();
        $all = $getAll->fetchAll();
        return $all;
    }


    
    // Check if user is not activated

    function checkUserStatus($user){

            global $con;

            $stmtx = $con->prepare("
                                    SELECT
                                        Username, RegStatus 
                                    From 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        RegStatus = 0 ");
        $stmtx->execute(array($user));
        $status = $stmtx->rowCount();
        return $status;
    }


    /*
        -title function that echo the page title in case the page
        -has the variable $pageTitle and echo default title for other page
    */

    function getTitle () {

        global $pageTitle;

        if(isset($pageTitle)) {

            echo $pageTitle;

        } else {

            echo 'Default';
        }
    }

    /*
    **Redirect function [This function accepted parameter]
    **$theMsg = echo the msg
    **$seconds = Seconds before Redirecting
    */
    
    function redirectHome($theMsg, $adrs = null,  $seconds = 3 ) {


        if($adrs === null) {

            $adrs = 'index.php';
            $link = 'Home Page';

        } else {

            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

                $adrs = $_SERVER['HTTP_REFERER'];
                $link = 'Previous Page';
                
            } else{

                $adrs = 'index.php';
                $link = 'Home Page';
            } 
            
        }
        echo $theMsg;

        echo "<div class='alert alert-info'>You Will Be Directed To $link After $seconds Seconds.</div>";

        header("refresh:$seconds;url=$adrs");

        exit();
    }

    /*
    ** Check item function in database [Accept parameter]
    **$select = the item to select [example : user , item , category]
    **$from = the table to select from  [example : users , items , categories]
    **$value = the value of select  [example : ahmed , box , electronics]
    */

    function checkItem($select, $from, $value) {

        global $con;

        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

        $statement->execute(array($value));

        $count = $statement->rowCount();

        return $count;
    }

    /*
    ** Count Number Of Function v1
    ** Function To Count The Items Function 
    */

    function countItems($item, $tbl) {

        global $con;

        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $tbl");

        $stmt2->execute();

        return $stmt2->fetchColumn();
    }

