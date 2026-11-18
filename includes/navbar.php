<?php
// includes/navbar.php — set $active to one of: home, rooms, dining, experiences, packages, gallery, contact
$active = $active ?? '';
$CMS = $CMS ?? (require __DIR__ . '/cms-content.php');
$links = [];
foreach (($CMS['header']['nav_links'] ?? []) as $i => $item) { $links['cms'.$i] = [$item['label'] ?? 'Link', $item['url'] ?? 'index.php']; }
?>
<header class="site-header">
    <nav class="navbar" aria-label="Main">
        <a href="index.php" class="logo" aria-label="ITC Ratnadipa, home">
            <?= icon('emblem', 'logo-mark') ?>
            <span class="logo-text">
                <span class="logo-name"><?= e($CMS['header']['brand']) ?></span>
                <span class="logo-sub"><?= e($CMS['header']['subtitle']) ?></span>
            </span>
        </a>

        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="nav-links">
            <?= icon('menu') ?><span class="sr-only">Menu</span>
        </button>

        <ul class="nav-links" id="nav-links">
            <?php foreach ($links as $key => [$label, $href]): ?>
                <li><a href="<?= e($href) ?>"<?= $active === $key ? ' class="is-active" aria-current="page"' : '' ?>><?= e($label) ?></a></li>
            <?php endforeach; ?>
        </ul>

        <a href="packages.php" class="offer-button"><?= icon('tag') ?> <?= e($CMS['header']['offer_label']) ?> <?= icon('arrow') ?></a>

        <?php if ($user = current_user()): ?>
            <div class="user-chip">
                <?php if ($user['picture'] !== ''): ?>
                    <img src="<?= e($user['picture']) ?>" alt="" width="34" height="34" referrerpolicy="no-referrer">
                <?php else: ?>
                    <span class="avatar-fallback"><?= e(mb_strtoupper(mb_substr($user['name'], 0, 1))) ?></span>
                <?php endif; ?>
                <span class="user-name"><?= e(explode(' ', trim($user['name']))[0]) ?></span>
                <a href="logout.php?token=<?= e(csrf_token()) ?>" class="signout">Sign out</a>
            </div>
        <?php endif; ?>
    </nav>
</header>
