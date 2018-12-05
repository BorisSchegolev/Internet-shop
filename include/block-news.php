<?php
defined("shop") or die('Доступ запрещен!');
?>
<div class="block-news">
    <center><img id="icon-prev" src="img/prev.png" alt=""></center>
    <div class="newsticker">
        <ul>
            <?php
                $results = mysqli_query($link, "SELECT * FROM news ORDER BY id DESC");

                foreach ($results as $result) {
                    echo '
                    <li>
                        <span>'.$result["date"].'</span>
                        <a href="">'.$result["title"].'</a>
                        <p>'.$result["text"].'</p>
                    </li>
                    ';
                }
            ?>

        </ul>
    </div>
    <center><img id="icon-next" src="img/next.png" alt=""></center>
</div>