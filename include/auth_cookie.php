<?php
	defined('shop') or die('Доступ запрещен!');
    if ($_SESSION['auth'] != 'yes_auth' && $_COOKIE["rememberme"]) {
    
        $str = $_COOKIE["rememberme"];

        // Вся длина строки
        $all_len = strlen($str);
        // Длина строки
        $login_len = strpos($str,'+');
        //Обрезаем строку до плюса и получаем логин
        $login = clear_string(substr($str,0,$login_len));

        // Получаем пороль
        $pass = clear_string(substr($str,$login_len+1,$all_len));


         $result = mysqli_query($link,"SELECT * FROM reg_user WHERE (login = '$login' or email = '$login') AND pass = '$pass'");
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
        }
    }
?>