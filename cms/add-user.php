
<?php
require_once __DIR__ . '/../includes/data.php';
require_once __DIR__ . '/../includes/cms-admin.php';

cms_require_admin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals(csrf_token(), $_POST['csrf'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {

        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (
            $name === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($password) < 10
        ) {
            $error = 'Enter a valid name, email and password with at least 10 characters.';
        } else {

            $admins = cms_admins();
            $exists = false;

            foreach ($admins as $admin) {
                if (strtolower($admin['email'] ?? '') === $email) {
                    $exists = true;
                    break;
                }
            }

            if ($exists) {
                $error = 'This email already has a CMS account.';
            } else {

                $ids = array_column($admins, 'id');
                $newId = $ids ? max($ids) + 1 : 1;

                $admins[] = [
                    'id' => $newId,
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    )
                ];

                if (cms_admin_save($admins)) {
                    $success = 'New CMS user created successfully!';
                } else {
                    $error = 'Could not save user. Check config folder permissions.';
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add CMS User</title>
<link rel="stylesheet" href="../assets/css/cms.css">
</head>
<body>

<main class="auth-card">

<h1>ITC Ratnadipa CMS</h1>
<p>Create another CMS administrator account.</p>

<?php if ($error): ?>
<p class="error"><?= e($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
<p><?= e($success) ?></p>
<?php endif; ?>

<form method="post">

<input type="hidden" name="csrf"
value="<?= e(csrf_token()) ?>">

<label>
User Name
<input type="text" name="name" required>
</label>

<label>
Email
<input type="email" name="email" required>
</label>

<label>
Password (minimum 10 characters)
<input type="password" name="password"
minlength="10" required>
</label>

<button type="submit">Create CMS User</button>

</form>

<p><a href="index.php">Back to CMS Dashboard</a></p>
<p><a href="logout.php">Logout</a></p>

</main>
</body>
</html>