<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/icons.php';
$CMS = $CMS ?? (require __DIR__ . '/cms-content.php'); // no-op if the header already loaded it
?>
<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <a href="index.php" class="logo logo-stack">
                <?= icon('emblem', 'logo-mark') ?>
                <span class="logo-name"><?= e($CMS['header']['brand']) ?></span>
                <span class="logo-sub"><?= e($CMS['header']['subtitle']) ?></span>
            </a>
        </div>

        <div class="footer-col">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="rooms.php">Rooms &amp; Suites</a></li>
                <li><a href="index.php#highlights">Dining</a></li>
                <li><a href="index.php#highlights">Experiences</a></li>
                <li><a href="packages.php">Packages</a></li>
                <li><a href="index.php#rooms">Gallery</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Contact &amp; Support</h3>
            <ul class="contact-list">
                <li><?= icon('phone') ?><a href="tel:<?= e(preg_replace('/[^0-9+]/','',$CMS['footer']['phone'])) ?>"><?= e($CMS['footer']['phone']) ?></a></li>
                <li><?= icon('mail') ?><a href="mailto:<?= e($CMS['footer']['email']) ?>"><?= e($CMS['footer']['email']) ?></a></li>
                <li><?= icon('pin') ?><a href="https://www.google.com/maps/search/?api=1&query=ITC+Ratnadipa+2+Galle+Face+Terrace+Colombo" target="_blank" rel="noopener"><?= e($CMS['footer']['address']) ?></a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Follow Us</h3>
            <div class="social">
                <?php foreach ($SOCIALS as [$ico, $label, $url]): ?>
                    <a href="<?= e($url) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e($label) ?>" title="<?= e($label) ?>"><?= icon($ico) ?></a>
                <?php endforeach; ?>
            </div>

            <div class="share-wrap">
                <button type="button" class="share-link" id="share-btn" aria-haspopup="true" aria-expanded="false" aria-controls="share-menu">
                    <span class="social-dot"><?= icon('share') ?></span> Share Your Experience
                </button>
                <div class="share-menu" id="share-menu" hidden>
                    <a href="#" data-share="whatsapp" target="_blank" rel="noopener noreferrer"><?= icon('chat') ?> WhatsApp</a>
                    <a href="#" data-share="facebook" target="_blank" rel="noopener noreferrer"><?= icon('facebook') ?> Facebook</a>
                    <button type="button" data-share="instagram"><?= icon('instagram') ?> <span>Instagram</span></button>
                    <button type="button" data-share="copy"><?= icon('link') ?> <span>Copy link</span></button>
                </div>
            </div>
        </div>

        <div class="footer-tagline">
            <?= icon('spa', 'lotus') ?>
            <p><?= e($CMS['footer']['tagline']) ?></p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <?= e($CMS['footer']['copyright']) ?></p>
        <p><a href="#">Terms &amp; Condition</a><span class="sep"></span><a href="contact.php">Contact &amp; Support</a></p>
    </div>
</footer>
<script src="assets/js/script.js?v=<?= (int)@filemtime(dirname(__DIR__) . '/assets/js/script.js') ?>" defer></script>
</body>
</html>
