<?php
// includes/users.php — file-based user store (no database needed).
// Users live in config/users.json.php. The file starts with a PHP line that stops
// execution, so even if someone requests it in a browser they only see a blank page.

const USERS_FILE  = __DIR__ . '/../config/users.json.php';
const USERS_GUARD = "<?php http_response_code(403); exit; ?>\n";

function users_decode(string $raw): array {
    $pos = strpos($raw, "?>\n");
    if ($pos === false) return [];
    $data = json_decode(substr($raw, $pos + 3), true);
    return is_array($data) ? array_values($data) : [];
}

// The single account from the old config/admin-account.php, if it still exists.
function users_legacy(): array {
    $file = __DIR__ . '/../config/admin-account.php';
    if (!is_file($file)) return [];
    $acc = require $file;
    if (!is_array($acc) || empty($acc['email']) || empty($acc['password'])) return [];
    return [[
        'id'       => 1,
        'name'     => (string)($acc['name'] ?? $acc['email']),
        'email'    => strtolower((string)$acc['email']),
        'password' => (string)$acc['password'],
        'created'  => date('c'),
    ]];
}

function users_all(): array {
    $raw = is_file(USERS_FILE) ? (string)@file_get_contents(USERS_FILE) : '';
    $users = users_decode($raw);
    return $users ?: users_legacy();
}

function find_user_by_email(string $email): ?array {
    $email = strtolower(trim($email));
    foreach (users_all() as $u) {
        if (($u['email'] ?? '') === $email) return $u;
    }
    return null;
}

// Creates a user. Returns [true, $user] or [false, 'error message'].
function create_user(string $name, string $email, string $password): array {
    $fh = @fopen(USERS_FILE, 'c+');
    if (!$fh) {
        return [false, 'Could not save your account. Check the config folder permissions.'];
    }
    flock($fh, LOCK_EX);

    $users = users_decode((string)stream_get_contents($fh));
    if (!$users) $users = users_legacy();   // keep the old account when the store is first created

    $email = strtolower(trim($email));
    foreach ($users as $u) {
        if (($u['email'] ?? '') === $email) {
            flock($fh, LOCK_UN);
            fclose($fh);
            return [false, 'An account with this email already exists. Please sign in.'];
        }
    }

    $nextId = 1;
    foreach ($users as $u) $nextId = max($nextId, (int)($u['id'] ?? 0) + 1);

    $user = [
        'id'       => $nextId,
        'name'     => $name,
        'email'    => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'created'  => date('c'),
    ];
    $users[] = $user;

    ftruncate($fh, 0);
    rewind($fh);
    $ok = fwrite($fh, USERS_GUARD . json_encode($users)) !== false;
    fflush($fh);
    flock($fh, LOCK_UN);
    fclose($fh);

    return $ok ? [true, $user] : [false, 'Could not save your account. Please try again.'];
}

// Puts a user into the session (used after login and after registering).
function login_user(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'sub'     => 'user-' . (int)$user['id'],
        'id'      => (int)$user['id'],
        'name'    => (string)($user['name'] ?? $user['email']),
        'email'   => (string)$user['email'],
        'picture' => '',
    ];
    unset($_SESSION['login_error'], $_SESSION['login_email']);
}
