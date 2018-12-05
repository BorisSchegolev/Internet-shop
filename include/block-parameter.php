<?
    defined("shop") or die('Доступ запрещен!');
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#blocktrackbar').trackbar({
            onMove : function () {
                document.getElementById("start-price").value = this.leftValue;
                document.getElementById("end-price").value = this.rightValue;
            },
            width : 160,
            leftLimit : 1000,
            leftValue: <?
                if ((int)$_GET["start_price"] >= 1000 AND (int)$_GET["start_price"] <= 50000){
                    echo (int)$_GET["start_price"];
                }else {
                    echo "1000";
                }
            ?>,
            rightLimit: 50000,
            rightValue: <?
                if ((int)$_GET["end_price"] >= 1000 AND (int)$_GET["end_price"] <= 50000){
                    echo (int)$_GET["end_price"];
                }else {
                    echo "1000";
                }
                ?>,
            roundUp : 1000
        });
    });
</script>
<div class="block-parameter">
    <p class="header-title">Поиск по параметрам</p>
    <p class="title-filter">Стоимость</p>
    <form action="search_filter.php" method="get">
        <div class="block-input-price">
            <ul>
                <li><p>от</p></li>
                <li><input type="text" id="start-price" name="start_price" value="1000"></li>
                <li><p>до</p></li>
                <li><input type="text" id="end-price" name="end_price" value="30000"></li>
                <li><p>руб</p></li>
            </ul>
        </div>
        <div id="blocktrackbar"></div>
        <p class="title-filter">Производители</p>
        <ul class="checkbox-brand">
            <?php
                $result = mysqli_query($link,"SELECT * FROM category WHERE type='mobile'");
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    do{

                        $checked_brand = "";
                        if ($_GET["brand"]) {
                            if (in_array($row["id"],$_GET["brand"])){
                                $checked_brand = "checked";
                            }
                        }
                        echo '
                            <li><input '.$checked_brand.' type="checkbox" name="brand[]" value="'.$row["id"].'" id="checkbrand'.$row["id"].'"><label for="checkbrand'.$row["id"].'">'.$row["brand"].'</label></li>
                        ';
                    }
                    while($row = mysqli_fetch_array($result));
                }
            ?>


        </ul>
        <center><input type="submit" name="submit" id="button-param-search" value="Найти"></center>
    </form>
</div>