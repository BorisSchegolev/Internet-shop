<?php
    defined("shop") or die("Доступ запрещен!");
?>
<div class="header">
    <div class="block-header-top">
        <ul class="header-top-menu">
            <li><strong>Ваш город - <span>Москва</span></strong></li>
            <li><a href="about.php">О нас</a></li>
            <li><a href="magazine.php">Магазины</a></li>
            <li><a href="feedback.php">Контакты</a></li>
        </ul>

        <?php
            if($_SESSION['auth'] == 'yes_auth') {
                echo '<p id="auth-user-info" align="right"><img src="img/user.png" alt="">Здравствуйте, '.$_SESSION["auth_name"].'!</p>';
            }else {
                echo '
                   <!-- <li><a href="#" class="top-auth">Вход</a><a href="registration.php">Регистрация</a></li>-->
                ';
            }

        ?>
        <ul class="auth">
            <li><a href="#" class="top-auth">Вход</a>
                <div class="block-top-auth">
                    <form action="" method="post">
                        <h3>Вход</h3>
                        <p class="message-auth">Не верный логин и(или) пороль</p>
                        <ul class="input-email-pass">
                            <li><input type="text" class="auth-login" placeholder="Логин или E-mail"></li>
                            <li><input type="password" class="auth-pass" placeholder="Пороль"><span id="button-pass-show-hide" class="pass-show"></span>
                            </li>
                            <ul id="list-auth">
                                <li><input type="checkbox" name="remember" id="remember"><label for="remember">Запомнить меня</label></li>
                                <li><a href="#" class="remindpass">Забыли пороль?</a></li>
                                <p id="button-auth"><a href="#">Вход</a></p>
                                <p class="auth-loading"><img class="auth-loading-img" src="img/103.gif" alt=""></p>
                            </ul>
                        </ul>
                    </form>
                    <div id="block-remind">
                        <h3>Восстановление<br />пароля</h3>
                        <p id="message-remind" class="message-remind-success"></p>
                        <center><input type="text" id="remind-email" placeholder="Ваш E-mail"></center>
                        <p align="right" id="button-remind"><a>Готово</a></p>
                        <p align="right" class="auth-loading"><img src="img/103.gif" alt=""></p>
                        <p id="prev-auth">Назад</p>
                    </div>
                </div>
            </li>
            <li><a href="registration.php">Регистрация</a></li>
        </ul>
    </div>
    <hr style="height: 3px; width: 100%; background: silver;opacity: .3">

    <div id="block-user" >
        <ul class="profile-list">
            <li><img src="img/info.png" /><a href="profile.php">Профиль</a></li>
            <li><img src="img/exit.png" /><a class="logout" >Выход</a></li>
        </ul>
    </div>

    <img class="logo" src="img/internet-magazin-2.png" alt="">
    <div class="personal-info">
        <p align="right">Звонок бесплатный</p>
        <h3 align="right">8 (800) 100-12-34</h3>
        <img src="img/phone.png" alt="">
        <p align="right">Режим работы:</p>
        <p align="right">Будние дни: с 9:00 до 18:00</p>
        <p align="right">Суббота, Воскресенье - выходные</p>
        <img src="img/clock.png" alt="">
    </div>
    <div class="block-search">
        <form action="search.php?q=" method="get">
            <span><img src="img/search.png" alt=""></span>
            <input type="text" id="input-search" name="q" placeholder="Поиск среди более 100 000 товаров" value="<?php echo $search?>">
            <input type="submit" id="button-search" value="Поиск">
        </form>
        <ul id="result-search">

        </ul>
    </div>
    <div class="top-menu">
        <ul>
            <li><img src="img/home.png" alt=""><a href="index.php">Главная</a></li>
            <li><img src="img/new.png" alt=""><a href="view_aystopper.php?go=news">Новинки</a></li>
            <li><img src="img/bestprice.png" alt=""><a href="view_aystopper.php?go=leaders">Лидеры продаж</a></li>
            <li><img src="img/sale.png" alt=""><a href="view_aystopper.php?go=sale">Распродажа</a></li>
        </ul>
        <p align="right" class="block-basket"><img src="img/basket.png" alt=""><a href="cart.php?action=oneclick">Корзина пуста</a></p>
    </div>
    <hr style="background: silver; margin-top: -3px; height: 2px">

</div>
