<main>
    <?= $nav ?>

    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach($bets as $bet) : ?>
            <tr class="rates__item <?= $bet['winner_id'] == $user_id && $bet['id'] == $bet['winner_bet_id'] ? 'rates__item--win' : '' ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= filter_user_data($bet['img_url']) ?>" width="54" height="40" alt="<?= filter_user_data($bet['lot_name']); ?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.php?lot_id=<?= $bet['lot_id']; ?>"><?= filter_user_data($bet['lot_name']); ?></a></h3>
                        <?php if ($bet['winner_id'] == $user_id && $bet['id'] == $bet['winner_bet_id']) : ?>
                        <p><?= $bet['contacts'] ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $bet['category_name'] ?>
                </td>
                <td class="rates__timer">
                    <?php if ($bet['winner_id'] == $user_id && $bet['id'] == $bet['winner_bet_id']) : ?>
                    <div class="timer timer--win">Ставка выиграла</div>
                    <?php elseif ($bet['date_exp'] < $now) : ?>
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
                    <?= $bet['bet_price'] ?> р
                </td>
                <td class="rates__time">
                    <?= calculate_time_interval($bet['date_add']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>
