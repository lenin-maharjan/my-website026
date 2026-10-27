<?php
require_once __DIR__ . '/common.php';
startApplicationSession();
requireLogin();

$users = loadUsers();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = (string) ($_POST['current_password'] ?? '');
    $newPassword = (string) ($_POST['new_password'] ?? '');
    $userIndex = null;

    foreach ($users as $index => $user) {
        if (($user['username'] ?? '') === $_SESSION['username']) {
            $userIndex = $index;
            break;
        }
    }

    if ($userIndex === null || !password_verify($currentPassword, $users[$userIndex]['password_hash'])) {
        $error = 'The current password is incorrect.';
    } elseif (strlen($newPassword) < 8) {
        $error = 'The new password must contain at least 8 characters.';
    } else {
        $users[$userIndex]['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        saveUsers($users);
        $message = 'Password changed successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="card">
        <h1>Change Password</h1>
        <?php if ($message): ?><p class="success"><?= h($message) ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
        <form method="POST">
            <label for="current_password">Current password</label>
            <input id="current_password" name="current_password" type="password" required>
            <label for="new_password">New password</label>
            <input id="new_password" name="new_password" type="password" minlength="8" required>
            <button type="submit">Change password</button>
        </form>
        <p><a href="dashboard.php">Back to dashboard</a></p>
    </main>
</body>
</html>
