<?php

if($_SERVER["REQUEST_METHOD"] == "POST") {
    define("shop", true);
	include('connect.php');
    include('../functions/functions.php');
    
    $login = clear_string($_POST["login"]);
    
    $pass   = md5(clear_string($_POST["pass"]));
    $pass   = strrev($pass);
    $pass   = strtolower("9nm2rv8q".$pass."2yo6z");
    

    
    if ($_POST["rememberme"] == "yes") {
        setcookie('rememberme',$login.'+'.$pass,time()+3600*24*31, "/");
    }

    $result = mysqli_query($link,"SELECT * FROM reg_user WHERE (login = '$login' OR email = '$login') AND pass = '$pass'");
    If (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        session_start();
        $_SESSION['auth'] = 'yes_auth';
        $_SESSION['auth_pass'] = $row["pass"];
        $_SESSION['auth_login'] = $row["login"];
        $_SESSION['auth_surname'] = $row["surname"];
        $_SESSION['auth_name'] = $row["name"];
        $_SESSION['auth_patronomic'] = $row["patronomic"];
        $_SESSION['auth_address'] = $row["address"];
        $_SESSION['auth_phone'] = $row["phone"];
        $_SESSION['auth_email'] = $row["email"];
        echo 'yes_auth';
    }else {
        echo 'no_auth';
    }
}
?>