<?php
    defined("shop") or die('Доступ запрещен!');
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'db_shop';

    $link = mysqli_connect($host, $user, $pass,$db) or die("Нет соединения с БД".mysqli_error($link));
