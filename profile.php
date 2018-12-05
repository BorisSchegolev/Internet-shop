<?php
define("shop", true);
session_start();

if ($_SESSION['auth'] == 'yes_auth') {
    require_once "include/connect.php";
    require_once "functions/functions.php";

    if ($_POST["save_submit"]) {
        $_POST["info_surname"] = clear_string($_POST["info_surname"]);
        $_POST["info_name"] = clear_string($_POST["info_name"]);
        $_POST["info_patronomic"] = clear_string($_POST["info_patronomic"]);
        $_POST["info_address"] = clear_string($_POST["info_address"]);
        $_POST["info_phone"] = clear_string($_POST["info_phone"]);
        $_POST["info_email"] = clear_string($_POST["info_email"]);

        $error = array();

        $pass = md5($_POST["info_pass"]);
        $pass = strrev($pass);
        $pass = "9nm2rv8q" . $pass . "2yo6z";

        if ($_SESSION['auth_pass'] != $pass) {
            $error[] = 'Неверный текущий пароль!';
        } else {

            if ($_POST["info_new_pass"] != "") {
                if (strlen($_POST["info_new_pass"]) < 7 || strlen($_POST["info_new_pass"]) > 15) {
                    $error[] = 'Укажите новый пароль от 7 до 15 символов!';
                } else {
                    $newpass = md5(clear_string($_POST["info_new_pass"]));
                    $newpass = strrev($newpass);
                    $newpass = "9nm2rv8q" . $newpass . "2yo6z";
                    $newpassquery = "pass='" . $newpass . "',";
                }
            }


            if (strlen($_POST["info_surname"]) < 3 || strlen($_POST["info_surname"]) > 15) {
                $error[] = 'Укажите Фамилию от 3 до 15 символов!';
            }


            if (strlen($_POST["info_name"]) < 3 || strlen($_POST["info_name"]) > 15) {
                $error[] = 'Укажите Имя от 3 до 15 символов!';
            }


            if (strlen($_POST["info_patronomic"]) < 3 || strlen($_POST["info_patronomic"]) > 25) {
                $error[] = 'Укажите Отчество от 3 до 25 символов!';
            }


            if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", trim($_POST["info_email"]))) {
                $error[] = 'Укажите корректный email!';
            }

            if (strlen($_POST["info_phone"]) == "") {
                $error[] = 'Укажите номер телефона!';
            }

            if (strlen($_POST["info_address"]) == "") {
                $error[] = 'Укажите адрес доставки!';
            }
        }

        if (count($error)) {
            $_SESSION['msg'] = "<p align='left' id='form-error'>" . implode('<br />', $error) . "</p>";
        } else {
            $_SESSION['msg'] = "<p align='left' id='form-success'>Данные успешно сохранены!</p>";

            $dataquery = $newpassquery . "surname='" . $_POST["info_surname"] . "',name='" . $_POST["info_name"] . "',patronomic='" . $_POST["info_patronomic"] . "',email='" . $_POST["info_email"] . "',phone='" . $_POST["info_phone"] . "',address='" . $_POST["info_address"] . "'";
            $update = mysqli_query($link, "UPDATE reg_user SET $dataquery WHERE login = '{$_SESSION['auth_login']}'");

            if ($newpass) {
                $_SESSION['auth_pass'] = $newpass;
            }


            $_SESSION['auth_surname'] = $_POST["info_surname"];
            $_SESSION['auth_name'] = $_POST["info_name"];
            $_SESSION['auth_patronomic'] = $_POST["info_patronomic"];
            $_SESSION['auth_address'] = $_POST["info_address"];
            $_SESSION['auth_phone'] = $_POST["info_phone"];
            $_SESSION['auth_email'] = $_POST["info_email"];
        }
    }

    ?>
    <!doctype html>
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
            <h2 class="title-h3">Изменение профиля</h2>
            <?php
                if ($_SESSION['msg']) {
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                }
            ?>
            <form id="form_reg">
                <ul class="info-profile">
                    <li>
                        <label for="info_pass">Текущий пороль</label>
                        <span class="star">*</span>
                        <input type="text" name="info_pass" id="info_pass">
                    </li>
                    <li>
                        <label for="info_new_pass">Новый пороль</label>
                        <span class="star">*</span>
                        <input type="text" name="info_new_pass" id="info_new_pass">
                    </li>
                    <li>
                        <label for="info_surname">Фамилия</label>
                        <span class="star">*</span>
                        <input type="text" name="info_surname" id="info_surname" value="<?php echo $_SESSION['auth_surname']?>">
                    </li>
                    <li>
                        <label for="info_name">Имя</label>
                        <span class="star">*</span>
                        <input type="text" name="info_name" id="info_name" value="<?php echo $_SESSION['auth_name']?>">
                    </li>
                    <li>
                        <label for="info_patronomic">Отчество</label>
                        <span class="star">*</span>
                        <input type="text" name="info_patronomic" id="info_patronomic" value="<?php echo $_SESSION['auth_patronomic']?>">
                    </li>
                    <li>
                        <label for="info_email">E-mail</label>
                        <span class="star">*</span>
                        <input type="text" name="info_email" id="info_email" value="<?php $_SESSION['auth_email']?>">
                    </li>
                    <li>
                        <label for="info_phone">Мобильный телефон</label>
                        <span class="star">*</span>
                        <input type="text" name="info_phone" id="info_phone" value="<?php $_SESSION['auth_phone']?>">
                    </li>
                    <li>
                        <label for="info_address">Адрес доставки</label>
                        <span class="star">*</span>
                        <textarea type="text" name="info_address" id="info_address"><?php $_SESSION['auth_address']?></textarea>
                    </li>
                    <p align="right"><input type="submit" name="save_submit" id="form_submit" value="Сохранить"></p>
                </ul>

            </form>
        </div>
        <?php
            require_once "include/block-random.php";
            require_once "include/footer.php";
        ?>
    </div>

    <script src="js/jcarousellite_1.0.1.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/TextChange.js"></script>
    <script src="js/main.js"></script>
    </body>
    </html>
<?php
}else {
    header("location: index.php");
}
?>