<?php
require_once __DIR__ . '/common.php';
startApplicationSession();
requireLogin();

$users = loadUsers();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = trim((string) ($_POST['username'] ?? ''));
    $newEmail = trim((string) ($_POST['email'] ?? ''));
    $currentUsername = $_SESSION['username'];

    if ($newUsername === '' || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a username and a valid email address.';
    } else {
        foreach ($users as $index => $user) {
            if (($user['username'] ?? '') === $currentUsername) {
                $users[$index]['username'] = $newUsername;
                $users[$index]['email'] = $newEmail;
                saveUsers($users);
                $_SESSION['username'] = $newUsername;
                $_SESSION['email'] = $newEmail;
                $message = 'Profile updated successfully.';
                break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="card">
        <h1>Edit Profile</h1>
        <?php if ($message): ?><p class="success"><?= h($message) ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
        <form method="POST">
            <label for="username">Username</label>
            <input id="username" name="username" value="<?= h((string) $_SESSION['username']) ?>" required>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="<?= h((string) ($_SESSION['email'] ?? '')) ?>" required>
            <button type="submit">Save profile</button>
        </form>
        <p><a href="dashboard.php">Back to dashboard</a></p>
    </main>
</body>
</html>
