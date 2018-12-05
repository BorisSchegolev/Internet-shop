<?php
//Загрузка файла на сервер
    defined('shop') or die('Доступ запрещён!');
    $error_img = array();

    if($_FILES['upload_image']['error'] > 0) {
        //в зависимости от номера ошибки выводим соответствующее сообщение
        switch ($_FILES['upload_image']['error']) {
            case 1: $error_img[] =  'Размер файла превышает допустимое значение UPLOAD_MAX_FILE_SIZE'; break;
            case 2: $error_img[] =  'Размер файла превышает допустимое значение MAX_FILE_SIZE'; break;
            case 3: $error_img[] =  'Не удалось загрузить часть файла'; break;
            case 4: $error_img[] =  'Файл не был загружен'; break;
            case 6: $error_img[] =  'Отсутствует временная папка.'; break;
            case 7: $error_img[] =  'Не удалось записать файл на диск.'; break;
            case 8: $error_img[] =  'PHP-расширение остановило загрузку файла.'; break;
        }

    }else {
    //проверяем расширения
        if($_FILES['upload_image']['type'] == 'img/jpeg' || $_FILES['upload_image']['type'] == 'img/jpg' || $_FILES['upload_image']['type'] == 'img/png') {

            $imgext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES['upload_image']['name']));

            //папка для загрузки
            $uploaddir = '../uploads_images/';
            //новое сгенерированное имя файла
            $newfilename = $_POST["form_type"].'-'.$id.rand(10,100).'.'.$imgext;
            //путь к файлу (папка.файл)
            $uploadfile = $uploaddir.$newfilename;

            //загружаем файл move_uploaded_file
            if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $uploadfile)) {
                $update = mysqli_query($link,"UPDATE products SET img='$newfilename' WHERE products_id = '$id'");
            }
            else {
                $error_img[] =  "Ошибка загрузки файла.";
            }
        }else {
            $error_img[] =  'Допустимые расширения: jpeg, jpg, png';
        }
    }
?>