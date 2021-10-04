<main>
    <?= $nav ?>

    <form class="form container <?= $errors ? "form--invalid" : ""; ?>" action="sign_up.php" method="post" autocomplete="off"> <!-- form
    --invalid -->
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item <?= isset($errors['email']) ? "form__item--invalid" : ""; ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" value="<?= get_post_value('email'); ?>" placeholder="Введите e-mail">
            <span class="form__error"><?= $errors['email'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['password']) ? "form__item--invalid" : ""; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль">
            <span class="form__error"><?= $errors['password'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['name']) ? "form__item--invalid" : ""; ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" value="<?= get_post_value('name'); ?>" placeholder="Введите имя">
            <span class="form__error"><?= $errors['name'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['message_sign_up']) ? "form__item--invalid" : ""; ?>">
            <label for="message_sign_up">Контактные данные <sup>*</sup></label>
            <textarea id="message_sign_up" name="message_sign_up" placeholder="Напишите как с вами связаться"><?= get_post_value('message_sign_up'); ?></textarea>
            <span class="form__error"><?= $errors['message_sign_up'] ?></span>
        </div>
        <span class="form__error form__error--bottom"><?= $errors['form'] ?></span>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
</main>
