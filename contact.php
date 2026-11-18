<?php
$pageTitle = 'Contact | ITC Ratnadipa';
$active = 'contact';
$sent = false; $error = '';
$selected = (int)($_GET['package'] ?? 0);
require_once 'includes/data.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $n = trim($_POST['name'] ?? ''); $m = trim($_POST['email'] ?? ''); $msg = trim($_POST['message'] ?? '');
    if ($n === '' || !filter_var($m, FILTER_VALIDATE_EMAIL) || $msg === '') {
        $error = 'Enter your name, a valid email and a message.';
    } else {
        $sent = true; // Later: send mail or store the enquiry.
    }
}
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<main>
    <section class="page-hero">
        <p class="kicker">Contact</p>
        <h1>How can we help?</h1>
    </section>

    <section class="section contact-layout" id="enquiry">
        <div class="contact-info">
            <ul class="contact-list contact-list-dark">
                <li><?= icon('phone') ?><a href="tel:+94112100100">+94 11 2 100 100</a></li>
                <li><?= icon('mail') ?><a href="mailto:reservations@itcratnadipa.com">reservations@itcratnadipa.com</a></li>
                <li><?= icon('pin') ?><a href="https://www.google.com/maps/search/?api=1&query=ITC+Ratnadipa+2+Galle+Face+Terrace+Colombo" target="_blank" rel="noopener">ITC Ratnadipa, 2 Galle Face Terrace,<br>Colombo 00300, Sri Lanka</a></li>
            </ul>
        </div>

        <?php if ($sent): ?>
            <div class="notice notice-ok" role="status">Thanks, <?= e($n) ?>. Our reservations team will reply to <?= e($m) ?> soon.</div>
        <?php else: ?>
            <form method="POST" class="booking-form" novalidate>
                <?php if ($error): ?><p class="notice notice-error" role="alert"><?= e($error) ?></p><?php endif; ?>
                <div class="form-row two">
                    <label>Name <input type="text" name="name" required></label>
                    <label>Email <input type="email" name="email" required></label>
                </div>
                <div class="form-row">
                    <label>Interested in
                        <select name="topic">
                            <option>A room booking</option>
                            <?php foreach ($PACKAGES as $p): ?>
                                <option <?= $selected === $p['id'] ? 'selected' : '' ?>><?= e($p['name']) ?> package</option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <div class="form-row">
                    <label>Message <textarea name="message" rows="5" required></textarea></label>
                </div>
                <button type="submit" class="btn btn-gold btn-lg">Send message</button>
            </form>
        <?php endif; ?>
    </section>
</main>
<?php require_once 'includes/footer.php'; ?>
