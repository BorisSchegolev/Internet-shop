<?php
   session_start();
    if ($_SESSION['auth_admin'] == "yes_auth") {
        define('shop', true);

        if (isset($_GET["logout"])) {
            unset($_SESSION['auth_admin']);
            header("Location: login.php");
        }

        $_SESSION['urlpage'] = "<a href='index.php' >Главная</a>";

        include("include/connect.php");

?>
<!Doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Панель Управлени</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container" ">
            <?php
                include("include/header.php");

                // Общее количество заказов
                $query1 = mysqli_query($link,"SELECT * FROM orders");
                $result1 = mysqli_num_rows($query1);
                // Общее количество товаров
                $query2 = mysqli_query($link,"SELECT * FROM products");
                $result2 = mysqli_num_rows($query2);
                // Общее количество отзывов
                $query3 = mysqli_query($link,"SELECT * FROM table_reviews");
                $result3 = mysqli_num_rows($query3);
                // Общее количество клиентов
                $query4 = mysqli_query($link,"SELECT * FROM reg_user");
                $result4 = mysqli_num_rows($query4);
            ?>
            <div class="content">
                <div class="block-parameters">
                    <p class="title-page" >Общая статистика</p>
                </div>

                <ul class="general-statistics">
                    <li><p>Всего заказов - <span><?php echo $result1; ?></span></p></li>
                    <li><p>Товаров - <span><?php echo $result2; ?></span></p></li>
                    <li><p>Отзывы - <span><?php echo $result3; ?></span></p></li>
                    <li><p>Клиенты - <span><?php echo $result4; ?></span></p></li>
                </ul>

                <h3 class="title-statistics">Статистика продаж</h3>

                <table align="center" CELLPADDING="10" WIDTH="100%">
                    <TR>
                        <TH>Дата</TH>
                        <TH>Товар</TH>
                        <TH>Цена</TH>
                        <TH>Статус</TH>
                    </TR>
                <?php
                   $result = mysqli_query($link,"SELECT * FROM orders,buy_products WHERE orders.order_pay='accepted' AND orders.order_id=buy_products.buy_id_order");

                    If (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                        do {
                            $result2 = mysqli_query($link,"SELECT * FROM products WHERE products_id='{$row["buy_id_product"]}'");
                            If (mysqli_num_rows($result2) > 0) {
                                $row2 = mysqli_fetch_array($result2);
                            }

                        $statuspay = "";
                        if ($row["order_pay"] == "accepted") $statuspay = "Оплачено";

                        echo '
                             <TR>
                                <TD  align="CENTER" >'.$row["order_datetime"].'</TD>
                                <TD  align="CENTER" >'.$row2["title"].'</TD>
                                <TD  align="CENTER" >'.$row2["price"].'</TD>
                                <TD  align="CENTER" >'.$statuspay.'</TD>
                            </TR>
                            ';

                    }
                    while ($row = mysqli_fetch_array($result));
                }
                ?>

                </table>
            </div>
        </div>
    </body>
</html>
<?php
    }else {
        header("Location: login.php");
    }
?>