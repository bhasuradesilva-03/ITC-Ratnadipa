<?php
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Sign in | ITC Ratnadipa';
$bodyClass = 'login-body';
$publicPage = true;
$withSplash = false;
require_once __DIR__ . '/includes/header.php';
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<link rel="stylesheet" href="assets/css/login.css?v=1">
<main class="login login-modern">
    <section class="login-visual" aria-label="ITC Ratnadipa hotel">
        <div class="login-visual-copy">
            <?= icon('emblem', 'logo-mark') ?>
            <p class="login-brand">ITC RATNADIPA</p>
            <p class="login-brand-sub">A Luxury Collection Hotel, Colombo</p>
            <div class="login-welcome">
                <p class="login-eyebrow">WELCOME BACK</p>
                <h2>Sign in to<br>your account</h2>
                <p>Access your bookings, manage your reservations and enjoy a seamless experience at ITC Ratnadipa.</p>
            </div>
            <p class="login-quote">Where the ocean meets the city.</p>
        </div>
    </section>

    <section class="login-panel">
        <a class="login-home" href="index.php"><span aria-hidden="true">⌂</span> Back to Home</a>
        <div class="login-card">
            <p class="kicker">Login</p>
            <h1>Welcome back</h1>
            <p class="login-lead">Please enter your email and password to sign in to your ITC Ratnadipa account.</p>

            <?php if ($error !== ''): ?>
                <p class="login-error" role="alert"><?= e($error) ?></p>
            <?php endif; ?>

            <form class="login-form" action="login-handler.php" method="post">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <label for="email">Email Address</label>
                <div class="login-input-wrap">
                    <span aria-hidden="true">✉</span>
                    <input id="email" name="email" type="email" autocomplete="username" placeholder="Enter your email address" required maxlength="150" value="<?= e($_SESSION['login_email'] ?? '') ?>">
                </div>
                <?php unset($_SESSION['login_email']); ?>

                <label for="password">Password</label>
                <div class="login-input-wrap">
                    <span aria-hidden="true">♙</span>
                    <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required>
                    <button class="password-toggle" type="button" id="toggle-password" aria-label="Show password">◎</button>
                </div>

                <div class="login-options">
                    <label class="remember-option"><input type="checkbox" name="remember" value="1"> <span>Remember me</span></label>
                    <span class="forgot-note">Forgot password?</span>
                </div>
                <button class="login-submit" type="submit">SIGN IN <span aria-hidden="true">⟶</span></button>
            </form>

            <p class="login-note">New here? <a href="register.php">Create an account</a></p>
            <p class="login-note">Your account details are handled securely. Never share your password with anyone.</p>
        </div>
        <p class="login-foot">&copy; <?= date('Y') ?> ITC Ratnadipa. All Rights Reserved.</p>
    </section>
</main>
<script>
(() => {
  const input = document.getElementById('password');
  const button = document.getElementById('toggle-password');
  if (!input || !button) return;
  button.addEventListener('click', () => {
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    button.textContent = show ? '◉' : '◎';
  });
})();
</script>
</body>
</html>
