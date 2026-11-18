<?php
require_once __DIR__ . "/includes/data.php";
$pageTitle = 'Rooms & Suites | ITC Ratnadipa';
$active = 'rooms';
$stay = stay_from_request($_GET ?? []);

// Filters (Guide section 25 — server-side; swap for SQL later)
$viewFilter    = trim($_GET['view'] ?? '');
$balconyFilter = trim($_GET['balcony'] ?? '');
$maxPrice      = (int)($_GET['max_price'] ?? 0);

$nights = count_nights($stay['check_in'], $stay['check_out']);
$dateError = $nights < 1 ? 'Check-out must be after check-in.' : '';
$guests = $stay['adults'] + $stay['children'];

$results = array_filter($ROOMS, function ($r) use ($viewFilter, $balconyFilter, $maxPrice, $guests) {
    if ($viewFilter !== '' && stripos($r['view'], $viewFilter) === false) return false;
    if ($balconyFilter === 'yes' && !$r['balcony']) return false;
    if ($maxPrice > 0 && $r['price'] > $maxPrice) return false;
    return $r['max_guests'] >= $guests;
});

require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<main>
    <section class="page-hero">
        <p class="kicker">Rooms &amp; Suites</p>
        <h1>Find your room</h1>
    </section>

    <div class="search-wrap search-wrap-flat">
        <?php include 'includes/search-form.php'; ?>
    </div>

    <section class="section">
        <?php if ($dateError): ?>
            <p class="notice notice-error" role="alert"><?= e($dateError) ?></p>
        <?php else: ?>
            <p class="stay-summary"><?= (int)$nights ?> night<?= $nights > 1 ? 's' : '' ?> &middot;
                <?= e(date('D j M', strtotime($stay['check_in']))) ?> to <?= e(date('D j M Y', strtotime($stay['check_out']))) ?> &middot;
                <?= (int)$stay['adults'] ?> adult<?= $stay['adults'] > 1 ? 's' : '' ?><?= $stay['children'] ? ', ' . (int)$stay['children'] . ' child' . ($stay['children'] > 1 ? 'ren' : '') : '' ?></p>
        <?php endif; ?>

        <form class="filters" method="GET" action="rooms.php">
            <?php foreach (['check_in','check_out','adults','children','rooms'] as $k): ?>
                <input type="hidden" name="<?= e($k) ?>" value="<?= e($stay[$k]) ?>">
            <?php endforeach; ?>
            <label>View
                <select name="view">
                    <option value="">Any view</option>
                    <option value="Ocean" <?= $viewFilter === 'Ocean' ? 'selected' : '' ?>>Ocean</option>
                    <option value="City" <?= $viewFilter === 'City' ? 'selected' : '' ?>>City / Beira Lake</option>
                </select>
            </label>
            <label>Balcony
                <select name="balcony">
                    <option value="">Either</option>
                    <option value="yes" <?= $balconyFilter === 'yes' ? 'selected' : '' ?>>With balcony</option>
                </select>
            </label>
            <label>Max price / night
                <select name="max_price">
                    <option value="0">Any price</option>
                    <?php foreach ([40000, 55000, 80000] as $p): ?>
                        <option value="<?= $p ?>" <?= $maxPrice === $p ? 'selected' : '' ?>>Up to <?= money($p) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit" class="btn btn-outline">Apply filters</button>
            <a href="rooms.php?<?= e(stay_query($stay)) ?>" class="filters-reset">Clear</a>
        </form>

        <?php if (!$dateError && $results): ?>
            <div class="room-grid">
                <?php foreach ($results as $room): ?>
                    <?php include 'includes/room-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php elseif (!$dateError): ?>
            <p class="notice">No rooms match these filters for <?= (int)$guests ?> guest<?= $guests > 1 ? 's' : '' ?>. Try removing a filter or reducing the number of guests.</p>
        <?php endif; ?>
    </section>
</main>
<?php require_once 'includes/footer.php'; ?>
