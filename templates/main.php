<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach($lots_categories as $category) : ?>
            <li class="promo__item promo__item--<?= $category['code'] ?>">
                <a class="promo__link" href="all_lots.php?category_id=<?= $category['id']; ?>"><?= filter_user_data($category['category_name']); ?></a>
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
                    <img src="<?= filter_user_data($lot['img_url']) ?>" width="350" height="260" alt="<?= filter_user_data($lot['lot_name']); ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= filter_user_data($lot['category_name']); ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?= $lot['id']; ?>"><?= filter_user_data($lot['lot_name']); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= filter_user_data(format_price($lot['lot_price'])); ?></span>
                        </div>
                        <div class="lot__timer timer <?= get_time_interval($lot['date_exp'])['hh'] < 1 ? "timer--finishing" : ""?>">
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
