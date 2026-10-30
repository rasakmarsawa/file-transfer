<?php

require_once __DIR__ . '/config.php';

// Refresh cookie expiry if valid
if (isset($_COOKIE[ADMIN_COOKIE_NAME]) && $_COOKIE[ADMIN_COOKIE_NAME] === ADMIN_COOKIE_VALUE) {
    setcookie(ADMIN_COOKIE_NAME, ADMIN_COOKIE_VALUE, time() + ADMIN_COOKIE_LIFETIME, '/');
}

// If not logged in, redirect to login
if (!isset($_COOKIE[ADMIN_COOKIE_NAME]) || $_COOKIE[ADMIN_COOKIE_NAME] !== ADMIN_COOKIE_VALUE) {
    header('Location: login.php');
    exit;
}