<main>
    <?= $nav ?>

    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= filter_user_data($lot_search) ?></span>»</h2>
            <?php if(!$lots) : ?>
            <p>Ничего не найдено по вашему запросу</p>
            <?php else : ?>
            <ul class="lots__list">
            <?php foreach($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= filter_user_data($lot['img_url']) ?>" width="350" height="260" alt="<?= filter_user_data($lot['lot_name']) ?>">
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

        <?php if ($pages_total > 1): ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <?php if($current_page > 1) : ?>
                    <a href="search.php?page=<?= $current_page - 1 ?>&search=<?= filter_user_data($lot_search) ?>">Назад</a>
                    <?php else : ?>
                    <a>Назад</a>
                    <?php endif; ?>
                </li>
                <?php foreach ($pages as $page): ?>
                    <li class="pagination-item <?= ($page == $current_page) ? "pagination-item-active" : "" ?>">
                        <a href="search.php?page=<?= $page ?>&search=<?= filter_user_data($lot_search) ?>"><?= $page ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next">
                <?php if($current_page < $pages_total) : ?>
                <a href="search.php?page=<?= $current_page + 1 ?>&search=<?= filter_user_data($lot_search) ?>">Вперед</a>
                <?php else : ?>
                    <a>Вперед</a>
                <?php endif; ?>
                </li>
            </ul>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
