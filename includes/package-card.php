<?php // includes/package-card.php — expects $pkg ?>
<article class="package-card">
    <div class="card-media">
        <img src="assets/images/<?= e($pkg['image']) ?>" alt="<?= e($pkg['name']) ?>" loading="lazy">
    </div>
    <div class="package-body">
        <div class="package-head">
            <?= icon($pkg['icon'], 'i package-icon') ?>
            <div>
                <h3><?= e($pkg['name']) ?></h3>
                <p class="package-sub"><?= e($pkg['sub']) ?></p>
            </div>
        </div>
        <p class="package-price"><span>From</span> <strong><?= money($pkg['price']) ?></strong> <span>/ <?= e($pkg['unit']) ?></span></p>
        <a href="contact.php?package=<?= (int)$pkg['id'] ?>#enquiry" class="btn btn-outline">View Package</a>
    </div>
</article>
