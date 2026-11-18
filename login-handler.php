<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/users.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

if (!hash_equals((string) csrf_token(), (string) ($_POST['csrf'] ?? ''))) {
    $_SESSION['login_error'] = 'Your session expired. Please try again.';
    header('Location: login.php');
    exit;
}

$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$password = (string) ($_POST['password'] ?? '');

$_SESSION['login_email'] = $email;

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    $_SESSION['login_error'] = 'Enter a valid email address and password.';
    header('Location: login.php');
    exit;
}

if (!users_all()) {
    $_SESSION['login_error'] = 'No accounts yet. Please create one first.';
    header('Location: register.php');
    exit;
}

$user = find_user_by_email($email);

if (!$user || !password_verify($password, (string) $user['password'])) {
    $_SESSION['login_error'] = 'Email or password is incorrect.';
    header('Location: login.php');
    exit;
}

$next = safe_next($_SESSION['next'] ?? '');
login_user($user);
unset($_SESSION['next']);

header('Location: ' . ($next !== '' ? $next : 'index.php'));
exit;
