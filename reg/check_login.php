<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    define("shop", true);
    require_once "../include/connect.php";
    require_once "../functions/functions.php";

    $login = clear_string($_POST["login"]);

    $results = mysqli_query($link,"SELECT login FROM reg_user WHERE login = '$login'");

    if (mysqli_fetch_array($results) > 0) {
        echo "false";
    }else {
        echo "true";
    }
}