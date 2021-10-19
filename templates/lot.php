<main>
    <?= $nav ?>

    <section class="lot-item container">
        <h2><?= filter_user_data($lot['lot_name']) ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
              <div class="lot-item__image">
                    <img src="<?= filter_user_data($lot['img_url']) ?>" width="730" height="548" alt="">
              </div>
              <p class="lot-item__category">Категория: <span><?= filter_user_data($lot['category_name']) ?></span></p>
              <p class="lot-item__description"><?= filter_user_data($lot['lot_desc']) ?></p>
            </div>
            <?php if (isset($_SESSION['user'])) : ?>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer <?= get_time_interval($lot['date_exp'])['hh'] < 1 ? "timer--finishing" : "" ?>">
                        <?php printf('%02d', get_time_interval($lot['date_exp'])['hh']); ?>
                        :
                        <?php printf('%02d', get_time_interval($lot['date_exp'])['mm']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                      <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= filter_user_data($lot['lot_price']) ?></span>
                      </div>
                      <div class="lot-item__min-cost">
                        Мин. ставка <span><?= filter_user_data($lot['lot_price'] + $lot['bet_step']) ?></span>
                      </div>
                    </div>
                    <form class="lot-item__form" action="lot.php" method="post" autocomplete="off">
                      <p class="lot-item__form-item form__item <?= isset($errors['cost']) ? "form__item--invalid" : ""; ?>">
                        <label for="cost">Ваша ставка</label>
                        <input type="hidden" name="lot_id" value="<?= $lot['id'] ?>">
                        <input type="hidden" name="lot_min_bet" value="<?= $lot['lot_price'] + $lot['bet_step'] ?>">
                        <input id="cost" type="text" name="cost" placeholder="12 000" value="<?= get_post_value('cost'); ?>">
                        <span class="form__error"><?= $errors['cost'] ?></span>
                      </p>
                      <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <div class="history">
                    <h3>История ставок (<span><?= $bets_count ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bets as $bet) : ?>
                        <tr class="history__item">
                            <td class="history__name"><?= $bet['username'] ?></td>
                            <td class="history__price"><?= $bet['bet_price'] ?> р</td>
                            <td class="history__time"><?= calculate_time_interval($bet['date_add']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>
