<?php
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/users.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = (string) ($_SESSION['login_error'] ?? '');
unset($_SESSION['login_error']);
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = '';
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');

    if (!hash_equals((string) csrf_token(), (string) ($_POST['csrf'] ?? ''))) {
        $error = 'Your session expired. Please try again.';
    } elseif ($name === '' || mb_strlen($name) > 100) {
        $error = 'Please enter your name (up to 100 characters).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must have at least 8 characters.';
    } elseif (strlen($password) > 72) {
        $error = 'Password must be 72 characters or fewer.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        [$ok, $result] = create_user($name, $email, $password);
        if ($ok) {
            $next = safe_next($_SESSION['next'] ?? '');
            login_user($result);
            unset($_SESSION['next']);
            header('Location: ' . ($next !== '' ? $next : 'index.php'));
            exit;
        }
        $error = (string) $result;
    }
}

$pageTitle = 'Create account | ITC Ratnadipa';
$bodyClass = 'login-body';
$publicPage = true;
$withSplash = false;
require_once __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="assets/css/login.css?v=1">
<main class="login login-modern">
    <section class="login-visual" aria-label="ITC Ratnadipa hotel">
        <div class="login-visual-copy">
            <?= icon('emblem', 'logo-mark') ?>
            <p class="login-brand">ITC RATNADIPA</p>
            <p class="login-brand-sub">A Luxury Collection Hotel, Colombo</p>
            <div class="login-welcome">
                <p class="login-eyebrow">WELCOME</p>
                <h2>Create<br>your account</h2>
                <p>Join to manage your bookings, reservations and enjoy a seamless experience at ITC Ratnadipa.</p>
            </div>
            <p class="login-quote">Where the ocean meets the city.</p>
        </div>
    </section>

    <section class="login-panel">
        <a class="login-home" href="login.php"><span aria-hidden="true">⌂</span> Back to Sign in</a>
        <div class="login-card">
            <p class="kicker">Register</p>
            <h1>Create account</h1>
            <p class="login-lead">Enter your details to create your ITC Ratnadipa account.</p>

            <?php if ($error !== ''): ?>
                <p class="login-error" role="alert"><?= e($error) ?></p>
            <?php endif; ?>

            <form class="login-form" action="register.php" method="post">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

                <label for="name">Your Name</label>
                <div class="login-input-wrap">
                    <span aria-hidden="true">♙</span>
                    <input id="name" name="name" type="text" autocomplete="name" placeholder="Enter your name" required maxlength="100" value="<?= e($name) ?>">
                </div>

                <label for="email">Email Address</label>
                <div class="login-input-wrap">
                    <span aria-hidden="true">✉</span>
                    <input id="email" name="email" type="email" autocomplete="username" placeholder="Enter your email address" required maxlength="150" value="<?= e($email) ?>">
                </div>

                <label for="password">Password</label>
                <div class="login-input-wrap">
                    <span aria-hidden="true">♙</span>
                    <input id="password" name="password" type="password" autocomplete="new-password" placeholder="At least 8 characters" required minlength="8" maxlength="72">
                </div>

                <label for="confirm_password">Confirm Password</label>
                <div class="login-input-wrap">
                    <span aria-hidden="true">♙</span>
                    <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" placeholder="Re-enter your password" required minlength="8" maxlength="72">
                </div>

                <button class="login-submit" type="submit">CREATE ACCOUNT <span aria-hidden="true">⟶</span></button>
            </form>

            <p class="login-note">Already have an account? <a href="login.php">Sign in</a></p>
        </div>
        <p class="login-foot">&copy; <?= date('Y') ?> ITC Ratnadipa. All Rights Reserved.</p>
    </section>
</main>
</body>
</html>
