<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    define("shop", true);
    include("connect.php");
    include("../functions/functions.php");

    $search = iconv("UTF-8", "cp1251",strtolower(clear_string($_POST['text'])));

    $result = mysqli_query($link,"SELECT * FROM products WHERE title LIKE '%$search%' AND visible = '1'");

    If (mysqli_num_rows($result) > 0) {
        $result = mysqli_query($link,"SELECT * FROM products WHERE title LIKE '%$search%'  AND visible = '1' LIMIT 5");
        $row = mysqli_fetch_array($result);
        do {
            echo '<li><a href="search.php?q='.$row["title"].'">'.$row["title"].'</a></li>';
        }
        while ($row = mysqli_fetch_array($result));
    }
}
?>