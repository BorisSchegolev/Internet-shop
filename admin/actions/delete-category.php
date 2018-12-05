<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
    define('shop', true);
    include("../include/connect.php");

              $delete = mysqli_query($link,"DELETE FROM category WHERE id = '{$_POST["id"]}'");
              echo "delete";

    }
?>