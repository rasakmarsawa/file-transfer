<?php

require_once __DIR__ . '/config.php';

// Clear the login cookie
setcookie(ADMIN_COOKIE_NAME, '', time() - 3600, '/');

header('Location: index.html');
exit;