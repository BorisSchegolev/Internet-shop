<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    define("shop", true);
    include("connect.php");
    include("../functions/functions.php");

    $id = clear_string($_POST["id"]);

    $result = mysqli_query($link,"SELECT * FROM cart WHERE cart_id = '$id' AND cart_ip = '{$_SERVER['REMOTE_ADDR']}'");
    If (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $new_count = $row["cart_count"] + 1;
        $update = mysqli_query ($link,"UPDATE cart SET cart_count='$new_count' WHERE cart_id='$id' AND cart_ip = '{$_SERVER['REMOTE_ADDR']}'");
        echo $new_count;
    }
}
?>