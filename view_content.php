<?php
    define('shop', true);
    include("include/connect.php");
    include("functions/functions.php");
    session_start();
    include("include/auth_cookie.php");

    $id = clear_string($_GET["id"]);
    $action = clear_string($_GET["action"]);

    switch ($action) {
	    case 'clear':
            $clear = mysqli_query($link,"DELETE FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'");
            break;

        case 'delete':
            $delete = mysqli_query($link,"DELETE FROM cart WHERE cart_id = '$id' AND cart_ip = '{$_SERVER['REMOTE_ADDR']}'");
            break;
    }

    if (isset($_POST["submitdata"])){
        //проверяем авторизирован ли пользователь
        if ( $_SESSION['auth'] == 'yes_auth' ){
            //Если да добавляем всю информацию о клиенте
        mysqli_query($link,"INSERT INTO orders(order_datetime,order_dostavka,order_fio,order_address,order_phone,order_note,order_email)
                            VALUES(	
                                 NOW(),
                                '".$_POST["order_delivery"]."',					
                                '".$_SESSION['auth_surname'].' '.$_SESSION['auth_name'].' '.$_SESSION['auth_patronymic']."',
                                '".$_SESSION['auth_address']."',
                                '".$_SESSION['auth_phone']."',
                                '".$_POST['order_note']."',
                                '".$_SESSION['auth_email']."'                              
                                )");
//Если нет вставляем данные , которые ввел
        }else{
            $_SESSION["order_delivery"] = $_POST["order_delivery"];
            $_SESSION["order_fio"] = $_POST["order_fio"];
            $_SESSION["order_email"] = $_POST["order_email"];
            $_SESSION["order_phone"] = $_POST["order_phone"];
            $_SESSION["order_address"] = $_POST["order_address"];
            $_SESSION["order_note"] = $_POST["order_note"];

            mysqli_query($link,"INSERT INTO orders(order_datetime,order_dostavka,order_fio,order_address,order_phone,order_note,order_email)
                                VALUES(	
                                     NOW(),
                                    '".clear_string($_POST["order_delivery"])."',					
                                    '".clear_string($_POST["order_fio"])."',
                                    '".clear_string($_POST["order_address"])."',
                                    '".clear_string($_POST["order_phone"])."',
                                    '".clear_string($_POST["order_note"])."',
                                    '".clear_string($_POST["order_email"])."'                   
                                    )");
        }

        //Определяем под каким id вставился заказ
        $_SESSION["order_id"] = mysqli_insert_id();
        //Смотрим ,какие товары выбрал клиент
        $result = mysqli_query($link,"SELECT * FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'");
    If (mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);

        do{

            mysqli_query($link,"INSERT INTO buy_products(buy_id_order,buy_id_product,buy_count_product)
						VALUES(	
                            '".$_SESSION["order_id"]."',					
							'".$row["cart_id_product"]."',
                            '".$row["cart_count"]."'                   
						    )");



        } while ($row = mysqli_fetch_array($result));
    }
    header("Location: cart.php?action=completion");
}


    $result = mysqli_query($link,"SELECT * FROM cart,products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND products.products_id = cart.cart_id_product");
    If (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        do {
            $int = $int + ($row["price"] * $row["cart_count"]);
        }
        while ($row = mysqli_fetch_array($result));


        $itogpricecart = $int;
    }
?>
<!Doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <meta name="Description" content="<? echo $resquery["seo_description"]; ?>"/>
        <meta name="keywords" content="<? echo $resquery["seo_words"]; ?>" />
        <title>Интернет-Магазин Цифровой Техники</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="trackbar/trackbar.css">
        <link rel="stylesheet" href="fancybox/jquery.fancybox.css">
        <script src="js/jquery-1.8.2.min.js"></script>
        <script src="trackbar/jquery.trackbar.js"></script>
        <script src="fancybox/jquery.fancybox.js"></script>


        <script type="text/javascript">
            $(document).ready(function(){
                $(".image-modal").fancybox();
                $(".send-review").fancybox();
                $("ul.tabs").jTabs({content: ".tabs_content", animate: true, effect:"fade"});
            });
        </script>

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
                    $result1 = mysqli_query($link,"SELECT * FROM products WHERE products_id='$id' AND visible='1'");
                If (mysqli_num_rows($result1) > 0) {
                    $row1 = mysqli_fetch_array($result1);
                    do {
                        if  (strlen($row1["img"]) > 0 && file_exists("./uploads_images/".$row1["img"])){
                            $img_path = './uploads_images/'.$row1["img"];
                            $max_width = 300;
                            $max_height = 300;
                            list($width, $height) = getimagesize($img_path);
                            $ratioh = $max_height/$height;
                            $ratiow = $max_width/$width;
                            $ratio = min($ratioh, $ratiow);

                            $width = intval($ratio*$width);
                            $height = intval($ratio*$height);
                        }else {
                            $img_path = "img/no-image.png";
                            $width = 110;
                            $height = 200;
                        }

                        // Количество отзывов
                        $query_reviews = mysqli_query($link,"SELECT * FROM table_reviews WHERE products_id = '$id' AND moderat='1'");
                        $count_reviews = mysqli_num_rows($query_reviews);

                        echo  '
        
                        <div id="block-breadcrumbs-and-rating">
                            <p id="nav-breadcrumbs2"><a href="view_cat.php?type=mobile">Мобильные телефоны</a> \ <span>'.$row1["brand"].'</span></p>
                            <div id="block-like">
                                <p id="likegood" tid="'.$id.'" >Нравится</p><p id="likegoodcount" >'.$row1["yes_like"].'</p>
                            </div>
                        </div>
                        
                        <div id="block-content-info">               
                            <img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" />
                            <div id="block-mini-description">
                                <p id="content-title">'.$row1["title"].'</p>
                                <ul class="reviews-and-counts-content">
                                    <li><img src="img/eye-icon.png" /><p>'.$row1["count"].'</p></li>
                                    <li><img src="img/comment.png" /><p>'.$count_reviews.'</p></li>
                                </ul>
                                <p id="style-price" >'.group_numerals($row1["price"]).' грн</p>
                                <a class="add-cart" id="add-cart-view" tid="'.$row1["products_id"].'" ></a>
                                <p id="content-text">'.$row1["mini_description"].'</p>
                            </div>
                        </div>
                        ';
                    }
                    while ($row1 = mysqli_fetch_array($result1));

                    //Выводим маленькие картинки fancybox
                    $result = mysqli_query($link,"SELECT * FROM uploads_images WHERE products_id='$id'");
                    If (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_array($result);
                        echo '<div id="block-img-slide">
                                <ul>';
                        do {
                            $img_path = './uploads_images/'.$row["img"];
                            $max_width = 70;
                            $max_height = 70;
                            list($width, $height) = getimagesize($img_path);
                            $ratioh = $max_height/$height;
                            $ratiow = $max_width/$width;
                            $ratio = min($ratioh, $ratiow);

                            $width = intval($ratio*$width);
                            $height = intval($ratio*$height);

                            echo '
                                <li>
                                <a class="image-modal" rel="group" href="#image'.$row["id"].'"><img src="'.$img_path.'" width="'.$width.'" height="'.$height.'" /></a>
                                </li>
                                <a style="display:none;" class="image-modal"  id="image'.$row["id"].'" ><img  src="./uploads_images/'.$row["img"].'" /></a>
';
                        }
                        while ($row = mysqli_fetch_array($result));
                        echo '
                                </ul>
                            </div>    
                        ';
                    }

                    $result = mysqli_query($link,"SELECT * FROM products WHERE products_id='$id' AND visible='1'");
                    $row = mysqli_fetch_array($result);

                    echo '
                        <ul class="tabs">
                            <li><a class="active" href="#" >Описание</a></li>
                            <li><a href="#" >Характеристики</a></li>
                            <li><a href="#" >Отзывы</a></li>   
                        </ul>

                        <div class="tabs_content">
                            <div>'.$row["description"].'</div>
                            <div>'.$row["features"].'</div>
                        <div>
                        <p id="link-send-review" ><a class="send-review" href="#send-review" >Написать отзыв</a></p>
                    ';

                    $query_reviews = mysqli_query($link,"SELECT * FROM table_reviews WHERE products_id='$id' AND moderat='1' ORDER BY reviews_id DESC");

                    If (mysqli_num_rows($query_reviews) > 0) {
                        $row_reviews = mysqli_fetch_array($query_reviews);
                        do {
                            echo '
                        <div class="block-reviews" >
                            <p class="author-date" ><strong>'.$row_reviews["name"].'</strong>,  '.$row_reviews["date"].'</p>
                            <img src="img/plus-reviews.png" />
                            <p class="textrev" >'.$row_reviews["good_reviews"].'</p>
                            <img src="img/minus-reviews.png"/>
                            <p class="textrev" >'.$row_reviews["bad_reviews"].'</p>
                        
                            <p class="text-comment">'.$row_reviews["comment"].'</p>
                        </div>
                    ';
                        }
                        while ($row_reviews = mysqli_fetch_array($query_reviews));
                    } else {
                        echo '<p class="title-no-info" >Отзывов нет</p>';
                    }
                    echo '
                </div>
            </div>


            <form id="send-review" >
                <p align="right" id="title-review">Публикация отзыва производится после предварительной модерации.</p>
                <ul>
                    <li>
                        <p align="right"><label id="label-name" >Имя<span>*</span></label><input maxlength="15" type="text"  id="name_review" /></p>
                    </li>
                    <li>
                        <p align="right"><label id="label-good" >Достоинства<span>*</span></label><textarea id="good_review" ></textarea></p>
                    </li>    
                    <li>
                        <p align="right"><label id="label-bad" >Недостатки<span>*</span></label><textarea id="bad_review" ></textarea></p>
                    </li>     
                    <li>
                        <p align="right"><label id="label-comment" >Комментарий</label><textarea id="comment_review" ></textarea></p>
                    </li>     
                </ul>
                <p id="reload-img"><img src="img/103.gif"></p> <p id="button-send-review" iid="'.$id.'" ></p>
            </form>
            ';
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
        <script src="js/jTabs.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>