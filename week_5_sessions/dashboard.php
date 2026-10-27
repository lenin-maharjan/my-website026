<?php
require_once __DIR__ . '/common.php';
startApplicationSession();
requireLogin();

$cookieUserInfo = json_decode((string) ($_COOKIE['user_info'] ?? ''), true);
$cookieUserInfo = is_array($cookieUserInfo) ? $cookieUserInfo : [];
$sessionUsername = (string) $_SESSION['username'];
$cookieUsername = is_string($cookieUserInfo['username'] ?? null) ? $cookieUserInfo['username'] : 'N/A';
$cookieIp = is_string($cookieUserInfo['ip'] ?? null) ? $cookieUserInfo['ip'] : 'N/A';
$browser = (string) ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtual Identity Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="card">
        <h1>Virtual Identity Dashboard</h1>
        <?php if (isset($_SESSION['timeout_message'])): ?>
            <p class="error"><?= h($_SESSION['timeout_message']) ?></p>
            <?php unset($_SESSION['timeout_message']); ?>
        <?php endif; ?>
        <p>Welcome, <strong><?= h($sessionUsername) ?></strong>!</p>

        <section class="identity">
            <h2>Server-side session</h2>
            <p>Username: <?= h($sessionUsername) ?></p>
            <p>Email: <?= h((string) ($_SESSION['email'] ?? 'N/A')) ?></p>
        </section>
        <section class="identity">
            <h2>Browser cookie (not trusted for authentication)</h2>
            <p>Username: <?= h($cookieUsername) ?></p>
            <p>IP address: <?= h($cookieIp) ?></p>
        </section>
        <section class="identity">
            <h2>Browser information</h2>
            <p><?= h($browser) ?></p>
        </section>
        <p class="hint">You will be logged out after 10 minutes without activity.</p>
        <p><a class="button" href="profile.php">Edit profile</a></p>
        <p><a class="button" href="change_password.php">Change password</a></p>
        <a class="button secondary" href="logout.php">Logout</a>
    </main>
</body>
</html>
