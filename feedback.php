<?php
    define("shop", true);
    require_once "include/connect.php";
    require_once "functions/functions.php";
    session_start();
    require_once "include/auth_cookie.php";

    if ($_POST["send_message"]) {
        $error = array();

        if (!$_POST["feed_name"]) $error[] = "Укажите своё имя";

        if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", trim($_POST["feed_email"]))) {
            $error[] = "Укажите корректный E-mail";
        }

        if (!$_POST["feed_subject"]) $error[] = "Укажите тему письма!";
        if (!$_POST["feed_text"]) $error[] = "Укажите текст сообщения!";

        if (strtolower($_POST["reg_captcha"]) != $_SESSION['img_captcha']) {
            $error[] = "Неверный код с картинки!";
        }

        if (count($error)) {
            $_SESSION['message'] = "<p id='form-error'>" . implode('<br />', $error) . "</p>";
        } else {
            send_mail($_POST["feed_email"],
                'admin@shop.ru',
                $_POST["feed_subject"],
                'От: ' . $_POST["feed_name"] . '<br/>' . $_POST["feed_text"]);

            $_SESSION['message'] = "<p id='form-success'>Ваше сообщение успешно отправлено!</p>";

        }
    }
?>
<!Doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Интернет-Магазин Цифровой Техники</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="trackbar/trackbar.css">

        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="trackbar/jquery.trackbar.js"></script>

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
                <?php
                    if(isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                ?>
                <form method="post">
                    <div id="block-feedback">
                        <ul id="feedback">
                            <li><label>Ваше Имя</label><input type="text" name="feed_name"  /></li>
                            <li><label>Ваш E-mail</label><input type="text" name="feed_email"  /></li>
                            <li><label>Тема</label><input type="text" name="feed_subject"  /></li>
                            <li><label>Текст сообщения</label><textarea name="feed_text" ></textarea></li>
                            <li>
                                <label for="reg_captcha">Защитный код</label>
                                <div class="captcha">
                                    <img src="reg/reg_captcha.php" />
                                    <input type="text" name="reg_captcha" id="captcha" />
                                    <p id="reloadcaptcha">Обновить</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <p align="right"><input type="submit" name="send_message" id="form_submit" /></p>
                </form>

            </div>
            <?php
                require_once "include/block-random.php";
                require_once "include/footer.php"
            ?>
        </div>

        <script src="js/jcarousellite_1.0.1.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script src="js/TextChange.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>