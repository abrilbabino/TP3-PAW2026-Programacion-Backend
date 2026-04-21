<nav>
    <ul>
        <?php foreach ($menu as $item) : ?>
            <li><a href="<?= $item["href"] ?>"><?= $item["name"] ?></li>
        <?php endforeach; ?>
    </ul>
</nav>