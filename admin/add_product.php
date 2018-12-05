<?php
    session_start();
    if ($_SESSION['auth_admin'] == "yes_auth") {
        define('shop', true);

        if (isset($_GET["logout"])) {
            unset($_SESSION['auth_admin']);
            header("Location: login.php");
        }

        $_SESSION['urlpage'] = "<a href='index.php' >Главная</a> \ <a href='tovar.php' >Товары</a> \ <a>Добавление товара</a>";

        include("include/connect.php");
        include("include/functions.php");

        if ($_POST["submit_add"]) {
            if ($_SESSION['add_tovar'] == '1') {

                $error = array();

                // Проверка полей

                if (!$_POST["form_title"]) {
                    $error[] = "Укажите название товара";
                }

                if (!$_POST["form_price"]) {
                    $error[] = "Укажите цену";
                }

                if (!$_POST["form_category"]) {
                    $error[] = "Укажите категорию";
                }else {
                    $result = mysqli_query($link,"SELECT * FROM category WHERE id='{$_POST["form_category"]}'");
                    $row = mysqli_fetch_array($result);
                    $selectbrand = $row["brand"];
                }

                // Проверка чекбоксов
                if ($_POST["chk_visible"]) {
                    $chk_visible = "1";
                }else { $chk_visible = "0"; }

                if ($_POST["chk_new"]) {
                    $chk_new = "1";
                }else { $chk_new = "0"; }

                if ($_POST["chk_leader"]) {
                    $chk_leader= "1";
                }else { $chk_leader = "0"; }

                if ($_POST["chk_sale"]) {
                    $chk_sale = "1";
                }else { $chk_sale = "0"; }


                if (count($error)) {
                    $_SESSION['message'] = "<p id='form-error'>".implode('<br />',$error)."</p>";
                }else {

                    mysqli_query($link,"INSERT INTO products(title,price,brand,seo_words,seo_description,mini_description,description,mini_features,features,new,leader,sale,visible,type_product,brand_id)
                            VALUES(						
                                '".$_POST["form_title"]."',
                                '".$_POST["form_price"]."',
                                '".$selectbrand."',
                                '".$_POST["form_seo_words"]."',
                                '".$_POST["form_seo_description"]."',
                                '".$_POST["txt1"]."',
                                '".$_POST["txt2"]."',
                                '".$_POST["txt3"]."',
                                '".$_POST["txt4"]."',
                                '".$chk_new."',
                                '".$chk_leader."',
                                '".$chk_sale."',
                                '".$chk_visible."',
                                '".$_POST["form_type"]."',
                                '".$_POST["form_category"]."'                               
                            )");

                    $_SESSION['message'] = "<p id='form-success'>Товар успешно добавлен!</p>";
                    $id = mysqli_insert_id($link);

                    if (empty($_POST["upload_image"])) {
                        include("actions/upload-image.php");
                        unset($_POST["upload_image"]);
                    }

                    if (empty($_POST["galleryimg"])) {
                        include("actions/upload-gallery.php");
                        unset($_POST["galleryimg"]);
                    }
                }
            }else {
                $msgerror = 'У вас нет прав на добавление товаров!';
            }
        }

    ?>
<!DOCTYPE html>
    <html  lang="ru">

    <head>
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="css/reset.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
        <title>Панель Управления</title>
    </head>
    <body>
        <div class="container">
            <?php
                include("include/header.php");
            ?>
            <div class="content">
                <div class="block-parameters">
                    <p class="title-page" >Добавление товара</p>
                </div>
                <?php
                    if (isset($msgerror))
                        echo '<p id="form-error" align="center">'.$msgerror.'</p>';

                    if(isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }

                    if(isset($_SESSION['answer'])) {
                        echo $_SESSION['answer'];
                        unset($_SESSION['answer']);
                    }
                ?>

                <form enctype="multipart/form-data" method="post">
                    <ul id="edit-tovar">
                        <li>
                            <label for="form_title">Название товара</label>
                            <input type="text" name="form_title" id="form_title"/>
                        </li>

                        <li>
                            <label for="form_price">Цена</label>
                            <input type="text" name="form_price" id="form_price"/>
                        </li>

                        <li>
                            <label for="form_seo_words">Ключевые слова</label>
                            <input type="text" name="form_seo_words" id="form_seo_words" />
                        </li>

                        <li>
                            <label for="form_seo_description">Краткое описание</label>
                            <textarea name="form_seo_description" id="form_seo_description"></textarea>
                        </li>
                        <li>
                            <label>Тип товара</label>
                            <select name="form_type" id="type" size="1" >
                                <option value="mobile" >Мобильные телефоны</option>
                                <option value="notebook" >Ноутбуки</option>
                                <option value="notepad" >Планшеты</option>
                            </select>
                        </li>

                        <li>
                            <label>Категория</label>
                            <select name="form_category" size="10" >

                                <?php
                                $category = mysqli_query($link,"SELECT * FROM category");

                                If (mysqli_num_rows($category) > 0) {
                                    $result_category = mysqli_fetch_array($category);
                                    do {
                                        echo '
                                            <option value="'.$result_category["id"].'" >'.$result_category["brand"].'</option>
                                        ';
                                    }
                                    while ($result_category = mysqli_fetch_array($category));
                                }
                                ?>

                            </select>
                        </li>
                    </ul>
                    <label class="stylelabel" >Основная картинка</label>
                    <div id="baseimg-upload">
                        <input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
                        <input type="file" name="upload_image" />
                    </div>

                    <h3 class="h3click" >Краткое описание товара</h3>
                    <div class="div-editor1" >
                        <textarea id="editor1" name="txt1" cols="100" rows="20"></textarea>
                        <script type="text/javascript">
                            var ckeditor1 = CKEDITOR.replace( "editor1" );
                            AjexFileManager.init({
                                returnTo: "ckeditor",
                                editor: ckeditor1
                            });
                        </script>
                    </div>

                    <h3 class="h3click" >Описание товара</h3>
                    <div class="div-editor2" >
                        <textarea id="editor2" name="txt2" cols="100" rows="20"></textarea>
                        <script type="text/javascript">
                            var ckeditor1 = CKEDITOR.replace( "editor2" );
                            AjexFileManager.init({
                                returnTo: "ckeditor",
                                editor: ckeditor1
                            });
                        </script>
                    </div>

                    <h3 class="h3click" >Краткие характеристики</h3>
                    <div class="div-editor3" >
                        <textarea id="editor3" name="txt3" cols="100" rows="20"></textarea>
                        <script type="text/javascript">
                            var ckeditor1 = CKEDITOR.replace( "editor3" );
                            AjexFileManager.init({
                                returnTo: "ckeditor",
                                editor: ckeditor1
                            });
                        </script>
                    </div>

                    <h3 class="h3click" >Характеристики</h3>
                    <div class="div-editor4" >
                        <textarea id="editor4" name="txt4" cols="100" rows="20"></textarea>
                        <script type="text/javascript">
                            var ckeditor1 = CKEDITOR.replace( "editor4" );
                            AjexFileManager.init({
                                returnTo: "ckeditor",
                                editor: ckeditor1
                            });
                        </script>
                    </div>

                    <label class="stylelabel">Галлерея картинок</label>

                    <div id="objects" >
                        <div id="addimage1" class="addimage">
                            <input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
                            <input type="file" name="galleryimg[]" />
                        </div>
                    </div>

                    <p id="add-input" >Добавить</p>

                    <h3 class="h3title" >Настройки товара</h3>
                    <ul id="chkbox">
                        <li><input type="checkbox" name="chk_visible" id="chk_visible" /><label for="chk_visible" >Показывать товар</label></li>
                        <li><input type="checkbox" name="chk_new" id="chk_new"  /><label for="chk_new" >Новый товар</label></li>
                        <li><input type="checkbox" name="chk_leader" id="chk_leader"  /><label for="chk_leader" >Популярный товар</label></li>
                        <li><input type="checkbox" name="chk_sale" id="chk_sale"  /><label for="chk_sale" >Товар со скидкой</label></li>
                    </ul>
                    <p align="right" ><input type="submit" id="submit_form" name="submit_add" value="Добавить товар"/></p>
                </form>
            </div>
        </div>
    </body>
</html>
<?php
    }else {
         header("Location: login.php");
    }
?>