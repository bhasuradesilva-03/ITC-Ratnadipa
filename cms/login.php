
<?php
require_once __DIR__ . '/../includes/data.php';
require_once __DIR__ . '/../includes/cms-admin.php';

if (empty(cms_admins())) {
    header('Location: ../cms-admin-setup.php');
    exit;
}

if (!empty($_SESSION['cms_admin']['id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals(csrf_token(), $_POST['csrf'] ?? '')) {
        $error = 'Invalid request.';
    } else {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        foreach (cms_admins() as $a) {
            if (
                strtolower($a['email'] ?? '') === $email &&
                password_verify($password, $a['password'] ?? '')
            ) {
                session_regenerate_id(true);

                $_SESSION['cms_admin'] = [
                    'id' => $a['id'],
                    'name' => $a['name'],
                    'email' => $a['email']
                ];

                header('Location: index.php');
                exit;
            }
        }

        $error = 'Email or password is incorrect.';
    }
}

$registered = isset($_GET['registered']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CMS Admin Login</title>
    <link rel="stylesheet" href="../assets/css/cms.css">
</head>
<body>

<main class="auth-card">

    <h1>ITC Ratnadipa CMS</h1>
    <p>Administrator sign in</p>

    <?php if ($registered): ?>
        <p class="success">
            Account created successfully! Please sign in.
        </p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error">
            <?= e($error) ?>
        </p>
    <?php endif; ?>

    <form method="post">

        <input
            type="hidden"
            name="csrf"
            value="<?= e(csrf_token()) ?>"
        >

        <label>
            Email
            <input
                type="email"
                name="email"
                required
            >
        </label>

        <label>
            Password
            <input
                type="password"
                name="password"
                required
            >
        </label>

        <button type="submit">Sign in</button>

    </form>

    <p>
        Don't have an account?
        <a href="register.php">Create Account</a>
    </p>

    <p>
        <a href="../index.php">Back to website</a>
    </p>

</main>

</body>
</html>
