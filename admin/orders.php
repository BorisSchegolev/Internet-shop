<?php
    session_start();
    if ($_SESSION['auth_admin'] == "yes_auth") {
        define('shop', true);

        if (isset($_GET["logout"])) {
            unset($_SESSION['auth_admin']);
            header("Location: login.php");
        }

        $_SESSION['urlpage'] = "<a href='index.php' >Главная</a> \ <a href='orders.php' >Заказы</a>";

        include("include/connect.php");
        include("include/functions.php");

        $sort = $_GET["sort"];
        switch ($sort) {
            case 'all-orders':
                $sort = "order_id DESC";
                $sort_name = 'От А до Я';
                break;

            case 'confirmed':
                $sort = "order_confirmed = 'yes' DESC";
                $sort_name = 'Обработаные';
                break;

            case 'no-confirmed':
                $sort = "order_confirmed = 'no' DESC";
                $sort_name = 'Не обработаные';
                break;

            default:
                $sort = "order_id DESC";
                $sort_name = 'От А до Я';
                break;
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
        <link href="jquery_confirm/jquery_confirm.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="jquery_confirm/jquery_confirm.js"></script>

        <title>Панель Управления - Заказы</title>
    </head>
    <body>
    <div class="container">
        <?php
            include("include/header.php");
            //Подсчитываем сколько всего заказов
            $all_count = mysqli_query($link,"SELECT * FROM orders");
            $all_count_result = mysqli_num_rows($all_count);
            //Подсчитываем все обработанные заказы
            $buy_count = mysqli_query($link,"SELECT * FROM orders WHERE order_confirmed = 'yes'");
            $buy_count_result = mysqli_num_rows($buy_count);
            //Подсчитываем не одобренные заказы
            $no_buy_count = mysqli_query($link,"SELECT * FROM orders WHERE order_confirmed = 'no'");
            $no_buy_count_result = mysqli_num_rows($no_buy_count);

        ?>
        <div class="content">
            <div class="block-parameters">
                <ul class="options-list">
                    <li>Сортировать</li>
                    <li><a class="select-links" href="#"><? echo $sort_name; ?></a>
                        <ul class="list-links-sort">
                            <li><a href="orders.php?sort=all-orders">От А до Я</a></li>
                            <li><a href="orders.php?sort=confirmed">Обработаные</a></li>
                            <li><a href="orders.php?sort=no-confirmed">Не обработаные</a></li>

                        </ul>
                    </li>
                </ul>
            </div>
            <div class="block-info">
                <ul id="review-info-count">
                    <li>Всего заказов - <strong><? echo $all_count_result; ?></strong></li>
                    <li>Обработаных - <strong><? echo $buy_count_result; ?></strong></li>
                    <li>Не обработаных - <strong><? echo $no_buy_count_result; ?></strong></li>
                </ul>
            </div>
            <?php
            //Выводим все отзывы
            $result = mysqli_query($link,"SELECT * FROM orders ORDER BY $sort");

            If (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                do {
                    if ($row["order_confirmed"] == 'yes') {
                        $status = '<span class="green">Обработан</span>';
                    } else {
                        $status = '<span class="red">Не обработан</span>';
                    }

                    echo '
                         <div class="block-order">
                              <p class="order-datetime" >'.$row["order_datetime"].'</p>
                              <p class="order-number" >Заказ № '.$row["order_id"].' - '.$status.'</p>
                              <p class="order-link" ><a class="green" href="view_order.php?id='.$row["order_id"].'" >Подробнее</a></p>
                         </div>
                         ';
                } while ($row = mysqli_fetch_array($result));
            }
    ?>
             </div>
        </div>
    </body>
</html>
<?php
    }else {
        header("Location: login.php");
    }
?>
