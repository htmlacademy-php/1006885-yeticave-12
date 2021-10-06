<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($lots_categories as $category) : ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= filter_user_data($category['category_name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
