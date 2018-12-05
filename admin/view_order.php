<?php
    session_start();
    if ($_SESSION['auth_admin'] == "yes_auth") {
        define('shop', true);

        if (isset($_GET["logout"])) {
            unset($_SESSION['auth_admin']);
            header("Location: login.php");
        }

        $_SESSION['urlpage'] = "<a href='index.php' >Главная</a> \ <a href='view_order.php' >Просмотр заказов</a>";

        include("include/connect.php");
        include("include/functions.php");

        $id = clear_string($_GET["id"]);
        $action = $_GET["action"];

        if (isset($action)) {
            switch ($action) {
                case 'accept':
                    if ($_SESSION['accept_orders'] == '1') {
                        $update = mysqli_query($link,"UPDATE orders SET order_confirmed='yes' WHERE order_id = '$id'");
                    }else {
                        $msgerror = 'У вас нет прав на подтверждение заказов!';
                    }
                    break;

                case 'delete':
                    if ($_SESSION['delete_orders'] == '1') {
                        $delete = mysqli_query($link,"DELETE FROM orders WHERE order_id = '$id'");
                        header("Location: orders.php");
                    }else {
                        $msgerror = 'У вас нет прав на удаление заказов!';
                    }
                    break;
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
        <link href="jquery_confirm/jquery_confirm.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="jquery_confirm/jquery_confirm.js"></script>

        <title>Панель Управления - Просмотр заказов</title>
    </head>
    <body>
    <div class="container">
        <?php
            include("include/header.php");
        ?>
        <div class="content">
            <div class="block-parameters">
                <p class="title-page" >Просмотр заказа</p>
            </div>
            <?php
                if (isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';
                //Если равно 1 ,то администратору можно делать просмотр
                if ($_SESSION['view_orders'] == '1') {
                    $result = mysqli_query($link,"SELECT * FROM orders WHERE order_id = '$id'");

                    If (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                        do {
                            if ($row["order_confirmed"] == 'yes') {
                                $status = '<span class="green">Обработан</span>';
                            } else {
                                $status = '<span class="red">Не обработан</span>';
                            }
                            echo '
                                <p class="view-order-link" ><a class="green" href="view_order.php?id='.$row["order_id"].'&action=accept" >Подтвердить заказ</a> | <a class="delete" rel="view_order.php?id='.$row["order_id"].'&action=delete" >Удалить заказ</a></p>
                                <p class="order-datetime" >'.$row["order_datetime"].'</p>
                                <p class="order-number" >Заказ № '.$row["order_id"].' - '.$status.'</p>
    
                                <TABLE align="center" CELLPADDING="10" WIDTH="100%">
                                    <TR>
                                        <TH>№</TH>
                                        <TH>Наименование товара</TH>
                                        <TH>Цена</TH>
                                        <TH>Количество</TH>
                                    </TR>
                                ';
                            $query_product = mysqli_query($link,"SELECT * FROM buy_products,products WHERE buy_products.buy_id_order = '$id' AND products.products_id = buy_products.buy_id_product");

                            $result_query = mysqli_fetch_array($query_product);
                            do {
                                $price = $price + ($result_query["price"] * $result_query["buy_count_product"]);
                                $index_count =  $index_count + 1;
                                echo '
                                     <TR>
                                        <TD  align="CENTER" >'.$index_count.'</TD>
                                        <TD  align="CENTER" >'.$result_query["title"].'</TD>
                                        <TD  align="CENTER" >'.$result_query["price"].' руб</TD>
                                        <TD  align="CENTER" >'.$result_query["buy_count_product"].'</TD>
                                    </TR>
                                
                                ';
                            } while ($result_query = mysqli_fetch_array($query_product));


                            if ($row["order_pay"] == "accepted") {
                                $statpay = '<span class="green">Оплачено</span>';
                            }else {
                                $statpay = '<span class="red">Не оплачено</span>';
                            }

                            echo '
                            </TABLE>
                            <ul id="info-order">
                                <li>Общая цена - <span>'.$price.'</span> руб</li>
                                <li>Способ доставки - <span>'.$row["order_dostavka"].'</span></li>
                                <li>Статус оплаты - '.$statpay.'</li>
                                <li>Тип оплаты - <span>'.$row["order_type_pay"].'</span></li>
                                <li>Дата оплаты - <span>'.$row["order_datetime"].'</span></li>
                            </ul>
                            <TABLE align="center" CELLPADDING="10" WIDTH="100%">
                                <TR>
                                    <TH>ФИО</TH>
                                    <TH>Адрес</TH>
                                    <TH>Контакты</TH>
                                    <TH>Примечание</TH>
                                </TR>
                                <TR>
                                    <TD  align="CENTER" >'.$row["order_fio"].'</TD>
                                    <TD  align="CENTER" >'.$row["order_address"].'</TD>
                                    <TD  align="CENTER" >'.$row["order_phone"].'</br>'.$row["order_email"].'</TD>
                                    <TD  align="CENTER" >'.$row["order_note"].'</TD>
                                </TR>
                            </TABLE>
    
                                ';

                            } while ($row = mysqli_fetch_array($result));
                        }
                    }else {
                        echo '<p id="form-error" align="center">У вас нет прав на просмотр данного раздела!</p>';
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
