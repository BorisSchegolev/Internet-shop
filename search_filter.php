<?php
    define("shop", true);
    require_once "include/connect.php";
    require_once  "functions/functions.php";
    session_start();
    require_once "include/auth_cookie.php";

    $cat = clear_string($_GET["cat"]);
    $type = clear_string($_GET["type"]);


?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Поиск по параметрам</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="trackbar/trackbar.css">

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
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
                    if ($_GET["brand"]) {
                        $checkbrand = implode(',', $_GET["brand"] );
                    }
                    $start_price = (int)$_GET["start_price"];
                    $end_price = (int)$_GET["end_price"];

                    if (!empty($checkbrand) OR !empty($end_price)) {
                        if (!empty($checkbrand)) $query_brand = " AND brand_id IN($checkbrand)";
                        if (!empty($end_price)) $query_price = "AND price BETWEEN $start_price AND $end_price";//В этом диапозоне будут выводится товары
                    }

                    $result = mysqli_query($link, "SELECT * FROM products WHERE visible='1' $query_brand $query_price ORDER BY products_id DESC");

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);

                    echo '
                        <div class="block-sorting">
                            <p class="breadcrumbs">
                                <a href="index.php">Главная страница</a>\ <span>Все товары</span>
                            </p>
                            <ul class="option-list">
                                <li>Вид: </li>
                                <li><img class="style-grid" src="img/icon-grid.png" alt=""></li>
                                <li><img class="style-list" src="img/icon-list.png" alt=""></li>
                                <li><a href="#" class="select-sort">'.$sort_name.'</a>
                                    <ul class="sorting-list">
                                        <li><a href="view_cat.php?'.$catlink.'type='.$type.' & sort=price-asc">От дешевых к дорогим</a></li>
                                        <li><a href="view_cat.php?'.$catlink.'type='.$type.' &sort=price-desc">От дорогих к дешевым</a></li>
                                        <li><a href="view_cat.php?'.$catlink.'type='.$type.' &sort=popular">Популярные</a></li>
                                        <li><a href="view_cat.php?'.$catlink.'type='.$type.' &sort=news">Новинки</a></li>
                                        <li><a href="view_cat.php?'.$catlink.'type='.$type.' &sort=brand">От А до Я</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        
                        <ul class="block-tovar-grid">
                    ';

                do {
                    if ($row["img"] != "" && file_exists("./uploads_images/".$row["img"])) {
                        $img_path = './uploads_images/'.$row["img"];
                        $max_width = 200;
                        $max_height = 200;
                        list($width, $height) = getimagesize($img_path);
                        $ratioh = $max_height/$height;
                        $ratiow = $max_width/$width;
                        $ratio = min($ratioh, $ratiow);
                        $width = intval($ratio * $width);
                        $height = intval($ratio * $height);
                    }else {
                        $img_path = "img/no-image.png";
                        $width = 110;
                        $height = 200;
                    }

                    // Количество отзывов
                    $query_reviews = mysqli_query($link,"SELECT * FROM table_reviews WHERE products_id = '{$row["products_id"]}' AND moderat='1'");
                    $count_reviews = mysqli_num_rows($query_reviews);
                    echo '
  
                        <li>
                            <div class="block-img-grid" >
                                <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" />
                            </div>
                            <p class="style-title-grid" ><a href="" >'.$row["title"].'</a></p>
                            <ul class="reviews-and-counts-grid">
                                <li><img src="img/eye-icon.png" /><p>'.$row["count"].'</p></li>
                                <li><img src="img/comment.png" /><p>'.$count_reviews.'</p></li>
                            </ul>
                            <a class="add-cart-style-grid" ></a>
                            <p class="style-price-grid" ><strong>'.$row["price"].'</strong> руб.</p>
                            <div class="mini-features" >
                                '.$row["mini_features"].'
                            </div>
                        </li>
                        
                    ';
                }
                while ($row = mysqli_fetch_array($result));
                //Отсюда забираем скобку
                ?>
                        </ul>

                <ul class="block-tovar-list">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM products where visible = '1' $query_brand $query_price ORDER BY products_id DESC");

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                        do {
                            if ($row["img"] != "" && file_exists("./uploads_images/".$row["img"])) {
                                $img_path = './uploads_images/'.$row["img"];
                                $max_width = 150;
                                $max_height = 150;
                                list($width, $height) = getimagesize($img_path);
                                $ratioh = $max_height/$height;
                                $ratiow = $max_width/$width;
                                $ratio = min($ratioh, $ratiow);
                                $width = intval($ratio * $width);
                                $height = intval($ratio * $height);
                            }else {
                                $img_path = "img/no-image.png";
                                $width = 80;
                                $height = 70;
                            }

                            // Количество отзывов
                            $query_reviews = mysqli_query($link,"SELECT * FROM table_reviews WHERE products_id = '{$row["products_id"]}' AND moderat='1'");
                            $count_reviews = mysqli_num_rows($query_reviews);
                            echo '
                                <li>
                                    <div class="block-img-list">
                                        <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'"> 
                                    </div>
                                    
                                    <ul class="reviews-and-counts-list">
                                        <li><img src="img/eye-icon.png" alt=""><p>'.$row["count"].'</p></li>
                                        <li><img src="img/comment.png" alt=""><p>'.$count_reviews.'</p></li>
                                    </ul>
                                    <p class="style-title-list"><a href="view_content.php?id='.$row["products_id"].'">'.$row["title"].'</a></p>
                                    <a href="" class="add-cart-style-list"></a>
                                    <p class="style-price-list"><strong>'.group_numerals($row["price"]).'</strong> грн.</p>
                                    <div class="style-text-list">'
                                        .$row["mini_description"].'
                                    </div>
                                </li>
                            ';
                        }
                        while ($row = mysqli_fetch_array($result));
                    }

                    }else {//А сюда вставляем
                        echo '<h3>Категория не доступна или не создана!</h3>';
                    }
                ?>
                </ul>
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
