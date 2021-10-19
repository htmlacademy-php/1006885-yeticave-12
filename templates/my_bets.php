<main>
    <?= $nav ?>

    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach($bets as $bet) : ?>
            <tr class="rates__item">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= filter_user_data($bet['img_url']) ?>" width="54" height="40" alt="<?= filter_user_data($bet['lot_name']); ?>">
                    </div>
                    <h3 class="rates__title"><a href="lot.php?lot_id=<?= $bet['lot_id']; ?>"><?= filter_user_data($bet['lot_name']); ?></a></h3>
                </td>
                <td class="rates__category">
                    <?= $bet['category_name'] ?>
                </td>
                <td class="rates__timer">
                    <?php if ($bet['date_exp'] < $now) : ?>
                        <div class="timer timer--end">Торги окончены</div>
                    <?php else : ?>
                        <div class="timer <?= get_time_interval($bet['date_exp'])['hh'] < 1 ? 'timer--finishing' : '' ?>">
                        <?php printf('%02d', get_time_interval($bet['date_exp'])['hh']); ?>
                        :
                        <?php printf('%02d', get_time_interval($bet['date_exp'])['mm']); ?>
                        :
                        <?php printf('%02d', get_time_interval($bet['date_exp'])['ss']); ?>
                    </div>
                    <?php endif; ?>
                </td>
                <td class="rates__price">
                    <?= $bet['rate_price'] ?> р
                </td>
                <td class="rates__time">
                    <?= calculate_time_interval($bet['date_add']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
<!--Строка выигравшей ставки-->
<!--
            <tr class="rates__item rates__item--win">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="../img/rate3.jpg" width="54" height="40" alt="Крепления">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.html">Крепления Union Contact Pro 2015 года размер L/XL</a></h3>
                        <p>Телефон +7 900 667-84-48, Скайп: Vlas92. Звонить с 14 до 20</p>
                    </div>
                </td>
                <td class="rates__category">
                    Крепления
                </td>
                <td class="rates__timer">
                    <div class="timer timer--win">Ставка выиграла</div>
                </td>
                <td class="rates__price">
                    10 999 р
                </td>
                <td class="rates__time">
                    Час назад
                </td>
            </tr>
-->
        </table>
    </section>
</main>
