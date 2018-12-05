<?php
    defined("shop") or die('Доступ запрещен!');
?>

<div class="block-category">
    <p class="header-title">Категории товаров</p>
    <ul>
        <li><a id="index1"><img src="img/mobile.png" class="mobile-img" alt="">Мобильные телефоны</a>
            <ul class="category-section">
                <li>
                    <a href="view_cat.php?type=mobile"><strong>Все модели</strong></a>
                </li>
                <?php
                    $query = "SELECT * FROM category WHERE type='mobile'";
                    $result = mysqli_query($link, $query);

                    if (mysqli_num_rows($result) > 0 ) {
                        $row = mysqli_fetch_array($result);
                        do {
                            echo '
                                <li><a href="view_cat.php?cat='.strtolower($row["brand"]).'&type='.$row["type"].'">'.$row["brand"].'</a></li>
                            ';
                        }
                        while ($row = mysqli_fetch_array($result));
                    }
                ?>
            </ul>
        </li>
        <li><a id="index2"><img src="img/notebook.png" class="mobile-img" alt="">Ноутбуки</a>
            <ul class="category-section">
                <li>
                    <a href="view_cat.php?type=notebook"><strong>Все модели</strong></a>
                </li>
                <?php
                    $query = "SELECT * FROM category WHERE type='notebook'";
                    $result = mysqli_query($link, $query);

                    if (mysqli_num_rows($result) > 0 ) {
                        $row = mysqli_fetch_array($result);
                        do {
                            echo '
                            <li><a href="view_cat.php?cat='.strtolower($row["brand"]).'&type='.$row["type"].'">'.$row["brand"].'</a></li>';
                        }
                        while ($row = mysqli_fetch_array($result));
                    }
                ?>
            </ul>
        </li>
        <li><a id="index3"><img src="img/tablet.png" class="mobile-img" alt="">Планшеты</a>
            <ul class="category-section">
                <li>
                    <a href="view_cat.php?type=notepad"><strong>Все модели</strong></a>
                </li>
                <?php
                $query = "SELECT * FROM category WHERE type='notepad'";
                $result = mysqli_query($link, $query);

                if (mysqli_num_rows($result) > 0 ) {
                    $row = mysqli_fetch_array($result);
                    do {
                        echo '
                            <li><a href="view_cat.php?cat='.strtolower($row["brand"]).'&type='.$row["type"].'">'.$row["brand"].'</a></li>';
                    }
                    while ($row = mysqli_fetch_array($result));
                }
                ?>
            </ul>
        </li>
    </ul>
</div>