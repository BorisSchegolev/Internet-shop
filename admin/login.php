<?php
    session_start();
    define('shop', true);
    include("include/connect.php");
    include("include/functions.php");


    If ($_POST["submit_enter"]) {
        $login = clear_string($_POST["input_login"]);
        $pass  = clear_string($_POST["input_pass"]);

        if ($login && $pass) {
            $pass   = md5($pass);
            $pass   = strrev($pass);
            $pass   = strtolower("mb03foo51".$pass."qj2jjdp9");

            $result = mysqli_query($link,"SELECT * FROM reg_admin WHERE login = '$login' AND pass = '$pass'");

            If (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);

                $_SESSION['auth_admin'] = 'yes_auth';
                $_SESSION['auth_admin_login'] = $row["login"];
                // Должность
                $_SESSION['admin_role'] = $row["role"];
                // Привилегии
                // Заказы
                $_SESSION['accept_orders'] = $row["accept_orders"];
                $_SESSION['delete_orders'] = $row["delete_orders"];
                $_SESSION['view_orders'] = $row["view_orders"];
                // Товары
                $_SESSION['delete_tovar'] = $row["delete_tovar"];
                $_SESSION['add_tovar'] = $row["add_tovar"];
                $_SESSION['edit_tovar'] = $row["edit_tovar"];
                // Отзывы
                $_SESSION['accept_reviews'] = $row["accept_reviews"];
                $_SESSION['delete_reviews'] = $row["delete_reviews"];
                // Клиенты
                $_SESSION['view_clients'] = $row["view_clients"];
                $_SESSION['delete_clients'] = $row["delete_clients"];
                // Новости
                $_SESSION['add_news'] = $row["add_news"];
                $_SESSION['delete_news'] = $row["delete_news"];
                // Категории
                $_SESSION['add_category'] = $row["add_category"];
                $_SESSION['delete_category'] = $row["delete_category"];
                // Администраторы
                $_SESSION['view_admin'] = $row["view_admin"];

                header("Location: index.php");
            }else {
                $msgerror = "Неверный Логин и(или) Пароль.";
            }

        }else {
            $msgerror = "Заполните все поля!";
        }
    }
?>
<!Doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Панель управления - Вход</title>
        <link rel="stylesheet" href="css/style-login.css">
        <link rel="stylesheet" href="css/reset.css">
    </head>
    <body>
        <div id="block-pass-login" >
            <?php
                if ($msgerror) {
                    echo '<p id="msgerror" >'.$msgerror.'</p>';
                }
            ?>
            <form method="post" >
                <ul id="pass-login">
                    <li><label for="input_login">Логин</label><input type="text" id="input_login" name="input_login" /></li>
                    <li><label for="input_pass">Пароль</label><input type="password" id="input_pass" name="input_pass" /></li>
                </ul>
                <div align="right">
                    <input type="submit" name="submit_enter" id="submit_enter" value="Вход"/>
                </div>
            </form>

        </div>
    </body>
</html>