<?php
    define("shop", true);
    require_once "include/connect.php";
    require_once  "functions/functions.php";
    session_start();
    require_once "include/auth_cookie.php";


    $cat = clear_string($_GET["cat"]);
    $type = clear_string($_GET["type"]);

?>
<!Doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Регистрация</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="trackbar/trackbar.css">
    </head>
    <body>
        <div class="container">
            <?php
                require_once "include/header.php";
            ?>
            <div class="block-right">
                <?php
                    require_once "include/category.php";
                    require_once "include/block-parameter.php";
                    require_once "include/block-news.php";
                ?>
            </div>
            <div class="block-content">
                <h2 class="h2-title">Регистрация</h2>
                <form action="reg/handler_reg.php" id="form_reg">

                    <p class="reg_message"></p>
                    <div class="block-form-registration">
                        <ul class="form-registration">
                            <li>
                                <label for="login">Логин</label>
                                <span class="star">*</span>
                                <input type="text" name="login" id="login">
                            </li>
                            <li>
                                <label for="password">Пароль</label>
                                <span class="star">*</span>
                                <input type="text" name="password" id="password">
                                <span class="genpass">Сгенерировать</span>
                            </li>
                            <li>
                                <label for="surname">Фамилия</label>
                                <span class="star">*</span>
                                <input type="text" name="surname" id="surname">
                            </li>
                            <li>
                                <label for="name">Имя</label>
                                <span class="star">*</span>
                                <input type="text" name="name" id="name">
                            </li>
                            <li>
                                <label for="patronomic">Отчество</label>
                                <span class="star">*</span>
                                <input type="text" name="patronomic" id="patronomic">
                            </li>
                            <li>
                                <label for="email">E-mail</label>
                                <span class="star">*</span>
                                <input type="text" name="email" id="email">
                            </li>
                            <li>
                                <label for="phone">Мобильный телефон</label>
                                <span class="star">*</span>
                                <input type="text" name="phone" id="phone">
                            </li>
                            <li>
                                <label for="address">Адрес доставки</label>
                                <span class="star">*</span>
                                <input type="text" name="address" id="address">
                            </li>
                            <li>
                                <div class="captcha">
                                    <img src="reg/reg_captcha.php" alt="">
                                    <input type="text" name="captcha" id="captcha">
                                    <p class="reloadcaptcha">Обновить</p>
                                </div>
                            </li>
                            <p align="right"><input type="submit" name="submit" id="form_submit" value="Регистрация"></p>
                        </ul>
                    </div>
                </form>
            </div>
            <?php
                require_once "include/block-random.php";
                require_once "include/footer.php"
            ?>
        </div>
        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="trackbar/jquery.trackbar.js"></script>
        <script src="js/jcarousellite_1.0.1.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script src="js/control_form.js"></script>
        <script src="js/jquery.form.js"></script>
        <script src="js/jquery.validate.js"></script>
        <script src="js/TextChange.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>