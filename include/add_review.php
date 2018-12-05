<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    define("shop", true);
    include("connect.php");
    include("../functions/functions.php");

    $id = clear_string($_POST['id']);
    $name = clear_string($_POST['name']);
    $good = clear_string($_POST['good']);
    $bad = clear_string($_POST['bad']);
    $comment = clear_string($_POST['comment']);
    mysqli_query($link,"INSERT INTO table_reviews(products_id,name,good_reviews,bad_reviews,comment,date)
                        VALUES(						
                            '".$id."',
                            '".$name."',
                            '".$good."',
                            '".$bad."',
                            '".$comment."',
                             NOW()							
                        )");
    echo 'yes';
}
?>