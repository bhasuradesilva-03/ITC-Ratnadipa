<?php
// includes/auth.php — sessions, Google ID-token verification and page protection.
require_once __DIR__ . '/../config/google.php';

if (session_status() === PHP_SESSION_NONE) {
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function google_configured(): bool {
    return GOOGLE_CLIENT_ID !== '' && strpos(GOOGLE_CLIENT_ID, 'PASTE_YOUR') === false;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function is_logged_in(): bool {
    return !empty($_SESSION['user']['sub']);
}

function current_user(): ?array {
    return is_logged_in() ? $_SESSION['user'] : null;
}

// Only allow redirects to a page inside this site, e.g. "rooms.php?view=Ocean".
function safe_next($value): string {
    $value = is_string($value) ? $value : '';
    return preg_match('/^[A-Za-z0-9_\-]+\.php(\?[A-Za-z0-9_\-=&%.+]*)?$/', $value) ? $value : '';
}

function require_login(): void {
    if (is_logged_in()) return;
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $path  = basename((string)parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));
        $query = (string)parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY);
        $next  = safe_next($path . ($query !== '' ? '?' . $query : ''));
        if ($next !== '' && $path !== 'login.php') {
            $_SESSION['next'] = $next;
        }
    }
    header('Location: login.php');
    exit;
}

// Checks a Google ID token with Google and returns its verified details.
// Throws RuntimeException with a message that is safe to show to the visitor.
function verify_google_token(string $idToken): array {
    $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken);
    $json = false;
    $status = 0;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => true]);
        $json = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    } else {
        $ctx = stream_context_create(['http' => ['timeout' => 10, 'ignore_errors' => true]]);
        $json = @file_get_contents($url, false, $ctx);
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
            $status = (int)$m[1];
        }
    }

    if ($json === false || $status === 0) {
        throw new RuntimeException('We could not reach Google to confirm your sign-in. Please try again.');
    }
    $data = json_decode($json, true);
    if ($status !== 200 || !is_array($data)) {
        throw new RuntimeException('Google did not accept that sign-in. Please try again.');
    }

    $issuerOk = in_array($data['iss'] ?? '', ['accounts.google.com', 'https://accounts.google.com'], true);
    if (!$issuerOk || ($data['aud'] ?? '') !== GOOGLE_CLIENT_ID || (int)($data['exp'] ?? 0) < time()) {
        throw new RuntimeException('That sign-in could not be verified. Please try again.');
    }
    if (($data['email_verified'] ?? '') !== 'true' && ($data['email_verified'] ?? false) !== true) {
        throw new RuntimeException('Your Google email address is not verified.');
    }

    $email = strtolower((string)($data['email'] ?? ''));
    $allowed = GOOGLE_ALLOWED_DOMAINS;
    if ($allowed) {
        $domain = substr(strrchr($email, '@') ?: '', 1);
        if (!in_array($domain, array_map('strtolower', $allowed), true)) {
            throw new RuntimeException('This Google account is not allowed to sign in.');
        }
    }

    return [
        'sub'     => (string)$data['sub'],
        'email'   => $email,
        'name'    => (string)($data['name'] ?? $email),
        'picture' => (string)($data['picture'] ?? ''),
    ];
}
