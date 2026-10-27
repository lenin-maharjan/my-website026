<?php
require_once __DIR__ . '/common.php';
startApplicationSession();

$_SESSION = [];
session_destroy();

foreach (['user_info', 'remember_username'] as $cookieName) {
    setcookie($cookieName, '', cookieOptions(time() - 3600));
}

redirectTo('index.html');
