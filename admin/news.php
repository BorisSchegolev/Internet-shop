<?php
    session_start();
    if ($_SESSION['auth_admin'] == "yes_auth") {
        define('shop', true);

        if (isset($_GET["logout"])) {
            unset($_SESSION['auth_admin']);
            header("Location: login.php");
        }

        $_SESSION['urlpage'] = "<a href='index.php' >Главная</a> \ <a href='news.php' >Новости</a>";

        include("include/connect.php");
        include("include/functions.php");

        if ($_POST["submit_news"]) {
            if ($_SESSION['add_news'] == '1') {
                if ($_POST["news_title"] == "" || $_POST["news_text"] == "") {
                    $message = "<p id='form-error' >Заполните все поля!</p>";
                } else {
                    mysqli_query($link,"INSERT INTO news(title,text,date)
                            VALUES(	
                                '".$_POST["news_title"]."',
                                '".$_POST["news_text"]."',					
                                NOW()                                                                     
                                )");
                    $message = "<p id='form-success' >Новость добавлена!</p>";
                }
            }else {
                $msgerror = 'У вас нет прав на добавление новостей!';
            }
        }

        $id = clear_string($_GET["id"]);
        $action = $_GET["action"];
        if (isset($action)) {
            switch ($action) {
                case 'delete':
                    if ($_SESSION['delete_news'] == '1') {
                        $delete = mysqli_query($link,"DELETE FROM news WHERE id = '$id'");
                    }else {
                        $msgerror = 'У вас нет прав на удаление новостей!';
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
        <link href="fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="jquery_confirm/jquery_confirm.js"></script>
        <script type="text/javascript" src="fancybox/jquery.fancybox.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".news").fancybox();
            });
        </script>

        <title>Панель Управления - Новости</title>
    </head>
    <body>
    <div class="container">
        <?php
            include("include/header.php");
            $all_count = mysqli_query($link,"SELECT * FROM news");
            $result_count = mysqli_num_rows($all_count);
        ?>
        <div class="content">
            <div class="block-parameters">
                <p id="count-client" >Всего новостей - <strong><?php echo $result_count; ?></strong></p>
                <p align="right" id="add-style"><a class="news" href="#news" >Добавить новость</a></p>
            </div>
            <?php
                if (isset($msgerror)) echo '<p id="form-error" align="center">'.$msgerror.'</p>';

                if ($message != "") echo $message;

                $result = mysqli_query($link,"SELECT * FROM news ORDER BY id DESC");

                If (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    do {
                        echo '
                            <div class="block-news">                     
                                <h3>'.$row["title"].'</h3>
                                <span>'.$row["date"].'</span>
                                <p>'.$row["text"].'</p>                          
                                <p class="links-actions" align="right" ><a class="delete" rel="news.php?id='.$row["id"].'&action=delete" >Удалить</a></p>                     
                            </div>                      
                        ';
                    } while ($row = mysqli_fetch_array($result));
                }
                        ?>
                <div id="news">
                    <form method="post">
                        <div id="block-input">
                            <label>Заголовок <input type="text" name="news_title" /></label>
                            <label>Описание <textarea name="news_text" ></textarea></label>
                        </div>
                        <p align="right">
                            <input type="submit" name="submit_news" id="submit_news" value="Добавить" />
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
    }else {
        header("Location: login.php");
    }
?>