<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($lots_categories as $category) : ?>
            <li class="nav__item">
                <a href="all_lots.php?category_id=<?= $category['id']; ?>"><?= filter_user_data($category['category_name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
