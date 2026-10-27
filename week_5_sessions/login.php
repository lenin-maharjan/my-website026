<?php
require_once __DIR__ . '/common.php';
startApplicationSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('index.html');
}

if (isset($_SESSION['blocked_until']) && time() < $_SESSION['blocked_until']) {
    $remaining = ceil(($_SESSION['blocked_until'] - time()) / 60);
    $error = "Too many failed attempts. Try again in $remaining minute(s).";
} else {
    unset($_SESSION['blocked_until']);
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $captcha = (string) ($_POST['captcha'] ?? '');
    $users = loadUsers();
    $user = null;

    foreach ($users as $candidate) {
        if (($candidate['username'] ?? '') === $username) {
            $user = $candidate;
            break;
        }
    }

    $valid = $captcha === '7'
        && is_array($user)
        && password_verify($password, (string) ($user['password_hash'] ?? ''));

    if (!$valid) {
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $_SESSION['blocked_until'] = time() + LOGIN_BLOCK_SECONDS;
            $error = 'Too many failed attempts. Login is blocked for 5 minutes.';
        } else {
            $remaining = MAX_LOGIN_ATTEMPTS - $_SESSION['login_attempts'];
            $error = "Invalid login details or CAPTCHA. $remaining attempt(s) remaining.";
        }
    } else {
        unset($_SESSION['login_attempts'], $_SESSION['blocked_until']);
        session_regenerate_id(true);
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['last_activity'] = time();

        if (isset($_POST['remember_me'])) {
            setcookie('remember_username', $user['username'], [
                'expires' => time() + 2592000,
                'path' => '/',
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => false,
                'samesite' => 'Lax',
            ]);
        } else {
            setcookie('remember_username', '', cookieOptions(time() - 3600));
        }

        $userInfo = json_encode([
            'username' => $user['username'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        ], JSON_THROW_ON_ERROR);
        setcookie('user_info', $userInfo, cookieOptions(time() + 3600));
        redirectTo('dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login failed</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="card">
        <h1>Login failed</h1>
        <p class="error"><?= h($error) ?></p>
        <p><a href="index.html">Try again</a></p>
    </main>
</body>
</html>
