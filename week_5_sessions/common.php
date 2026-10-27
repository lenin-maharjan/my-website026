<?php
const SESSION_TIMEOUT_SECONDS = 600;
const MAX_LOGIN_ATTEMPTS = 3;
const LOGIN_BLOCK_SECONDS = 300;
const USERS_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'users.json';

function startApplicationSession(): void
{
    session_start();

    if (isset($_SESSION['username'], $_SESSION['last_activity'])
        && time() - $_SESSION['last_activity'] > SESSION_TIMEOUT_SECONDS) {
        $_SESSION = [];
        session_destroy();
        session_start();
        $_SESSION['timeout_message'] = 'Your session expired after 10 minutes of inactivity.';
    }

    if (isset($_SESSION['username'])) {
        $_SESSION['last_activity'] = time();
    }
}

function loadUsers(): array
{
    if (!is_file(USERS_FILE)) {
        return [];
    }

    $users = json_decode((string) file_get_contents(USERS_FILE), true);
    return is_array($users) ? $users : [];
}

function saveUsers(array $users): void
{
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    if (file_put_contents(USERS_FILE, $json, LOCK_EX) === false) {
        throw new RuntimeException('Unable to save user data.');
    }
}

function redirectTo(string $location): never
{
    header("Location: $location");
    exit();
}

function requireLogin(): void
{
    if (!isset($_SESSION['username'])) {
        redirectTo('index.html');
    }
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function cookieOptions(int $expires): array
{
    return [
        'expires' => $expires,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}
