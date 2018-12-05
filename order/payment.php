<?php
    //Файл для оплаты заказа
    if ($_SERVER["REQUEST_METHOD"] = "POST") {
        define("shop", true);
        require_once "../include/connect.php";
        require_once "../functions/functions.php";

        $id_order = clear_string($_POST["WMI_PAYMENT_NO"]);
        $status_pay = strtolower(clear_string($_POST["WMI_ORDER_STATE"]));
        $order_type_pay = clear_string($_POST["WMI_PAYMENT_TYPE"]);
        $nomer_zakaza = clear_string($_POST["WMI_ORDER_ID"]);

        $update = mysqli_query($link,"UPDATE orders SET order_pay = '$status_pay', order_type_pay = '$order_type_pay',nomer_zakaza='$nomer_zakaza' WHERE order_id='$id_order'");

        echo 'WMI_RESULT=OK&WMI_DESCRIPTION=Заказ успешно оплачен!';
    }
?>