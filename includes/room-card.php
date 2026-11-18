<?php
// includes/room-card.php — expects $room and $stay
$query = stay_query($stay);
?>
<article class="room-card">
    <div class="card-media">
        <img src="assets/<?= strpos($room['image'], 'uploads/') === 0 ? e($room['image']) : 'images/' . e($room['image']) ?>" alt="<?= e($room['name']) ?> room" loading="lazy">
        <span class="pill"><?= e($room['tag']) ?></span>
    </div>
    <div class="card-body">
        <h3><?= e($room['name']) ?></h3>
        <p class="price"><span>From</span> <strong><?= money($room['price']) ?></strong> <span>/ night</span></p>
        <ul class="facts">
            <li><?= icon('pin') ?> <?= e($room['view']) ?></li>
            <li><?= icon('sun') ?> <?= $room['balcony'] ? 'With Balcony' : 'No Balcony' ?></li>
            <li><?= icon('user') ?> <?= e($room['guests_text']) ?></li>
        </ul>
        <ul class="amenities">
            <?php foreach ($room['amenities'] as $key): ?>
                <li><?= icon($key) ?><span><?= e($AMENITY_LABELS[$key]) ?></span></li>
            <?php endforeach; ?>
        </ul>
        <a href="room-details.php?id=<?= (int)$room['id'] ?>&amp;<?= e($query) ?>" class="btn btn-outline">View Room <?= icon('arrow') ?></a>
    </div>
</article>
