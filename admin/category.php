<?php
session_start();
if ($_SESSION['auth_admin'] == "yes_auth") {
    define('shop', true);

    if (isset($_GET["logout"])) {
        unset($_SESSION['auth_admin']);
        header("Location: login.php");
    }

    $_SESSION['urlpage'] = "<a href='index.php' >Главная</a> \ <a href='category.php' >Категории</a>";

    include("include/connect.php");
    include("include/functions.php");
    if ($_POST["submit_cat"]) {
        if ($_SESSION['add_category'] == '1') {

            $error = array();

            if (!$_POST["cat_type"])  $error[] = "Укажите тип товара!";
            if (!$_POST["cat_brand"]) $error[] = "Укажите название категории!";
            //Вставляем в базу дданных новый бренд
            if (count($error)) {
                $_SESSION['message'] = "<p id='form-error'>".implode('<br />',$error)."</p>";
            }else {
                $cat_type = clear_string($_POST["cat_type"]);
                $cat_brand = clear_string($_POST["cat_brand"]);

                mysqli_query($link,"INSERT INTO category(type,brand)
						VALUES(						
                            '".$cat_type."',
                            '".$cat_brand."'                              
						)");

                $_SESSION['message'] = "<p id='form-success'>Категория успешно добавлена!</p>";
            }
        }else {
            $msgerror = 'У вас нет прав на добавление категорий!';
        }
    }
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <title>Панель Управления - Категории</title>
    </head>
    <body>
    <div class="container">
        <?php
            include("include/header.php");
        ?>
        <div class="content">
            <div class="block-parameters">
                <p class="title-page" >Категории</p>
            </div>
            <?php
                if (isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';

                if(isset($_SESSION['message'])) {
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                }
            ?>
            <form method="post">
                <ul id="cat_products">
                    <li>
                        <label>Категории</label>
                        <div>
                            <?php
                                if ($_SESSION['delete_category'] == '1') {
                                    echo '<a class="delete-cat">Удалить</a>';
                                }
                            ?>
                        </div>
                        <select name="cat_type" id="cat_type" size="10">

                            <?php
                            $result = mysqli_query($link,"SELECT * FROM category ORDER BY type DESC");

                            If (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_array($result);
                                do {
                                echo '
                                    <option value="'.$row["id"].'" >'.$row["type"].' - '.$row["brand"].'</option>
                                ';
                                }
                                while ($row = mysqli_fetch_array($result));
                            }
                            ?>
                        </select>
                    </li>
                    <li>
                        <label>Тип товара</label>
                        <input type="text" name="cat_type" />
                    </li>
                    <li>
                        <label>Бренд</label>
                        <input type="text" name="cat_brand" />
                    </li>
                </ul>
                <p align="right"><input type="submit" name="submit_cat" id="submit_form" /></p>
            </form>
        </div>
    </div>
    </body>
</html>
<?php
    }else {
        header("Location: login.php");
    }
?>