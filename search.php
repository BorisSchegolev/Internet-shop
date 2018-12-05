<?php
    define("shop", true);
    require_once "include/connect.php";
    require_once  "functions/functions.php";
    session_start();
    require_once "include/auth_cookie.php";

    $search = clear_string($_GET["q"]);

    /*Сортировка товара*/
    $sorting = $_GET['sort'];

    switch ($sorting) {
        case 'price-asc';
            $sorting = 'price ASC';
            $sort_name = "От дешевых к дорогим";
            break;

        case 'price-desc';
            $sorting = 'price DESC';
            $sort_name = "От дорогих к дешевым";
            break;

        case 'popular';
            $sorting = 'count Desc';
            $sort_name = "Популярные";
            break;

        case 'news';
            $sorting = 'datetime DESC';
            $sort_name = "Новинки";
            break;

        case 'brand';
            $sorting = 'brand';
            $sort_name = "От А до Я";
            break;

        default:
            $sorting = 'products_id DESC';
            $sort_name = "Нет сортировки";
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Поиск - <?php echo $search?></title>
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
                <?php
                    if (strlen($search) >= 3 && strlen($search) < 150) {

                    //Формирование страниц
                    $num = 4; //Здесь указываем сколько хотим выводить товаров
                    $page = (int)$_GET['page'];

                    $count = mysqli_query($link, "SELECT COUNT(*) FROM products WHERE title LIKE '%$search%' AND visible = '1'");
                    $temp = mysqli_fetch_array($count);

                    if ($temp[0] > 0) {
                        $tempcount = $temp[0];


                        //Находим общее число страниц
                        $total = (($tempcount - 1) / $num) + 1;
                        $total = intval($total); //Округляем значение

                        $page = intval($page);

                        if (empty($page) or $page < 0) $page = 1;
                        if ($page > $total) $page = $total;

                        //Вычисляем начиная с какого номера следует выводить товары
                        $start = $page * $num - $num;
                        $query_start_num = " LIMIT $start, $num";
                    }

                    if ($temp[0] > 0){
                        echo '
                        <div class="block-sorting">
                            <p class="breadcrumbs">
                                <a href="index.php">Главная страница</a>\ <span>Поиск</span>
                            </p>
                            <ul class="option-list">
                                <li>Вид:</li>
                                <li><img class="style-grid" src="img/icon-grid.png" alt=""></li>
                                <li><img class="style-list" src="img/icon-list.png" alt=""></li>
                                <li>Сортировать:</li>
                                <li><a href="#" class="select-sort">'.$sort_name.'</a>
                                    <ul class="sorting-list">
                                        <li><a href="index.php?sort=price-asc">От дешевых к дорогим</a></li>
                                        <li><a href="index.php?sort=price-desc">От дорогих к дешевым</a></li>
                                        <li><a href="index.php?sort=popular">Популярные</a></li>
                                        <li><a href="index.php?sort=news">Новинки</a></li>
                                        <li><a href="index.php?sort=brand">От А до Я</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <ul class="block-tovar-grid">
                        ';


                    $result = mysqli_query($link, "SELECT * FROM products where title LIKE '%$search%' AND visible = '1' ORDER BY $sorting  $query_start_num");

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                        do {
                            if ($row["img"] != "" && file_exists("./uploads_images/" . $row["img"])) {
                                $img_path = './uploads_images/' . $row["img"];
                                $max_width = 200;
                                $max_height = 200;
                                list($width, $height) = getimagesize($img_path);
                                $ratioh = $max_height / $height;
                                $ratiow = $max_width / $width;
                                $ratio = min($ratioh, $ratiow);
                                $width = intval($ratio * $width);
                                $height = intval($ratio * $height);
                            } else {
                                $img_path = "img/no-image.png";
                                $width = 110;
                                $height = 200;
                            }

                            // Количество отзывов
                            $query_reviews = mysqli_query($link,"SELECT * FROM table_reviews WHERE products_id = '{$row["products_id"]}' AND moderat='1'");
                            $count_reviews = mysqli_num_rows($query_reviews);
                            echo '
                                <li>
                                    <div class="block-img-grid">
                                        <img src="' . $img_path . '" width="' . $width . '" height="' . $height . '"> 
                                    </div>
                                    <p class="style-title-grid"><a href="view_content.php?id='.$row["products_id"].'">' . $row["title"] . '</a></p>
                                    <ul class="reviews-and-counts-grid">
                                        <li><img src="img/eye-icon.png" alt=""><p>'.$row["count"].'</p></li>
                                        <li><img src="img/comment.png" alt=""><p>'.$count_reviews.'</p></li>
                                    </ul>
                                    <a href="" class="add-cart-style-grid"></a>
                                    <p class="style-price-grid"><strong>'.group_numerals($row["price"]).'</strong> грн.</p>
                                    <div class="mini-features">'.$row["mini_features"].'</div>
                                </li>
                            ';
                        } while ($row = mysqli_fetch_array($result));
                    }
                    ?>
                </ul>

                <ul class="block-tovar-list">
                    <?php
                    $result = mysqli_query($link, "SELECT * FROM products where title LIKE '%$search%' AND visible = '1' ORDER BY $sorting $query_start_num");

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                        do {
                            if ($row["img"] != "" && file_exists("./uploads_images/" . $row["img"])) {
                                $img_path = './uploads_images/' . $row["img"];
                                $max_width = 150;
                                $max_height = 150;
                                list($width, $height) = getimagesize($img_path);
                                $ratioh = $max_height / $height;
                                $ratiow = $max_width / $width;
                                $ratio = min($ratioh, $ratiow);
                                $width = intval($ratio * $width);
                                $height = intval($ratio * $height);
                            } else {
                                $img_path = "img/no-image2.png";
                                $width = 80;
                                $height = 70;
                            }

                            // Количество отзывов
                            $query_reviews = mysqli_query($link,"SELECT * FROM table_reviews WHERE products_id = '{$row["products_id"]}' AND moderat='1'");
                            $count_reviews = mysqli_num_rows($query_reviews);
                            echo '
                            <li>
                                <div class="block-img-list">
                                    <img src="' . $img_path . '" width="' . $width . '" height="' . $height . '"> 
                                </div>
                                
                                <ul class="reviews-and-counts-list">
                                    <li><img src="img/eye-icon.png" alt=""><p>'.$row["count"].'</p></li>
                                    <li><img src="img/comment.png" alt=""><p>'.$count_reviews.'</p></li>
                                </ul>
                                <p class="style-title-list"><a href="view_content.php?id='.$row["products_id"].'">'.$row["title"].'</a></p>
                                <a href="" class="add-cart-style-list" tid="'.$row["products_id"].'"></a>
                                <p class="style-price-list"><strong>'.group_numerals($row["price"]).'</strong> грн.</p>
                                <div class="style-text-list">'.$row["mini_description"].'</div>
                            </li>
                                    ';
                        } while ($row = mysqli_fetch_array($result));
                    }
                    echo '</ul>';

                    if ($page != 1) {
                        $pstr_prev = '<li><a class="pstr-prev" href="search.php?q=' . $search . '&' . catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page - 1) . '">&lt;</a></li>';
                    }
                    if ($page != $total) $pstr_next = '<li><a class="pstr-next" href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page + 1) . '">&gt;</a></li>';


                    // Формируем ссылки со страницами
                    if ($page - 5 > 0) $page5left = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page - 5) . '">' . ($page - 5) . '</a></li>';
                    if ($page - 4 > 0) $page4left = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page - 4) . '">' . ($page - 4) . '</a></li>';
                    if ($page - 3 > 0) $page3left = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page - 3) . '">' . ($page - 3) . '</a></li>';
                    if ($page - 2 > 0) $page2left = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page - 2) . '">' . ($page - 2) . '</a></li>';
                    if ($page - 1 > 0) $page1left = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page - 1) . '">' . ($page - 1) . '</a></li>';

                    if ($page + 5 <= $total) $page5right = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page + 5) . '">' . ($page + 5) . '</a></li>';
                    if ($page + 4 <= $total) $page4right = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page + 4) . '">' . ($page + 4) . '</a></li>';
                    if ($page + 3 <= $total) $page3right = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page + 3) . '">' . ($page + 3) . '</a></li>';
                    if ($page + 2 <= $total) $page2right = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page + 2) . '">' . ($page + 2) . '</a></li>';
                    if ($page + 1 <= $total) $page1right = '<li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . ($page + 1) . '">' . ($page + 1) . '</a></li>';


                    if ($page + 5 < $total) {
                        $strtotal = '<li><p class="nav-point">...</p></li><li><a href="search.php?q=' . $search . '&' . $catlink . '&type=' . $type . '&sort=' . $_GET["sort"] . '&page=' . $total . '">' . $total . '</a></li>';
                    } else {
                        $strtotal = "";
                    }

                    if ($total > 1) {
                        echo '
                            <div class="pstrnav">
                                <ul>
                            ';
                        echo $pstr_prev . $page5left . $page4left . $page3left . $page2left . $page1left . "<li><a class='pstr-active' href='''search.php?q=" . $search . "&'.$catlink.'cat='.$cat.'&type='.$type.'page=" . $page . "'''>" . $page . "</a></li>" . $page1right . $page2right . $page3right . $page4right . $page5right . $strtotal . $pstr_next;
                        echo '
                                </ul>
                            </div>
                            ';
                    }
                    }else {
                        echo "<p class='search-text'>Ничего не найдено!</p>";
                    }
                }else {
                        echo "<p class='search-text'>Поисковое значение должно быть от 3 до 150 символов!</p>";
                    }
                ?>
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
