<?php
    defined("shop") or die('Доступ запрещен!');

function clear_string($cl_str) {
    global $link;
    $cl_str = strip_tags($cl_str);
    $cl_str = mysqli_real_escape_string($link, $cl_str);
    $cl_str = trim($cl_str);

    return $cl_str;


}

