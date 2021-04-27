<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach($lots_categories as $category) : ?>
            <li class="promo__item promo__item--<?php print($category['code']) ?>">
                <a class="promo__link" href="pages/all-lots.html"><?php filter_xss(print($category['category_name'])); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <?php foreach($lots as $lot) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= filter_xss($lot['img_url']) ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?php filter_xss(print($lot['category_name'])); ?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?php filter_xss(print($lot['lot_name'])); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?php filter_xss(print(format_price($lot['lot_price']))); ?></span>
                        </div>
                        <div class="lot__timer timer <?php if (get_time_interval($lot['date_exp'])['hh'] < 1) : ?>timer--finishing<?php endif; ?>">
                            <?php printf('%02d', get_time_interval($lot['date_exp'])['hh']); ?>
                            :
                            <?php printf('%02d', get_time_interval($lot['date_exp'])['mm']); ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
