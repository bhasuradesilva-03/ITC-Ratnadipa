<?php
require_once 'includes/data.php';

$roomId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$roomId || !isset($ROOMS[$roomId])) {
    http_response_code(404);
    exit('Room not found.');
}
$room = $ROOMS[$roomId];
$stay = stay_from_request($_GET ?? []);
$nights = max(1, count_nights($stay['check_in'], $stay['check_out']));

$pageTitle = $room['name'] . ' | ITC Ratnadipa';
$active = 'rooms';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<main class="section room-detail">
    <p class="breadcrumb"><a href="rooms.php?<?= e(stay_query($stay)) ?>">Rooms &amp; Suites</a> / <?= e($room['name']) ?></p>

    <div class="detail-grid">
        <div class="detail-media">
            <img src="assets/images/<?= e($room['image']) ?>" alt="<?= e($room['name']) ?> room">
            <span class="pill"><?= e($room['tag']) ?></span>
        </div>

        <div class="detail-info">
            <h1><?= e($room['name']) ?></h1>
            <p class="lead"><?= e($room['description']) ?></p>
            <p class="price"><span>From</span> <strong><?= money($room['price']) ?></strong> <span>/ night</span></p>

            <ul class="facts">
                <li><?= icon('pin') ?> <?= e($room['view']) ?></li>
                <li><?= icon('sun') ?> <?= $room['balcony'] ? 'With Balcony' : 'No Balcony' ?></li>
                <li><?= icon('user') ?> <?= e($room['guests_text']) ?></li>
            </ul>

            <h2 class="mini-title">In this room</h2>
            <ul class="amenities amenities-large">
                <?php foreach ($room['amenities'] as $key): ?>
                    <li><?= icon($key) ?><span><?= e($AMENITY_LABELS[$key]) ?></span></li>
                <?php endforeach; ?>
            </ul>

            <div class="detail-cta">
                <p><?= (int)$nights ?> night<?= $nights > 1 ? 's' : '' ?> would be <strong><?= money($room['price'] * $nights) ?></strong></p>
                <a class="btn btn-gold" href="booking.php?room_id=<?= (int)$room['id'] ?>&amp;<?= e(stay_query($stay)) ?>">Book This Room <?= icon('arrow') ?></a>
            </div>
        </div>
    </div>
</main>
<?php require_once 'includes/footer.php'; ?>
