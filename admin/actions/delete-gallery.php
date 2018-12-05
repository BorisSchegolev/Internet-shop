<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    define('shop', true);
    include("../include/connect.php");

    $path = $_SERVER['DOCUMENT_ROOT']."/uploads_images/".$_POST["title"];

    if (file_exists($path)) {
        unlink($path);
        $delete = mysqli_query($link,"DELETE FROM uploads_images WHERE id = '{$_POST["id"]}'");
        echo "delete";
    } else {
        echo "delete";
        $delete = mysqli_query($link,"DELETE FROM uploads_images WHERE id = '{$_POST["id"]}'");
    }
}
?>