<?php

require_once __DIR__ . '/config.php';

// If already logged in, redirect to admin page
if (isset($_COOKIE[ADMIN_COOKIE_NAME]) && $_COOKIE[ADMIN_COOKIE_NAME] === ADMIN_COOKIE_VALUE) {
    header('Location: admin.php');
    exit;
}

// Handle POST login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if ($password === ADMIN_PASSWORD) {
        // Set cookie, valid for configured lifetime
        setcookie(ADMIN_COOKIE_NAME, ADMIN_COOKIE_VALUE, time() + ADMIN_COOKIE_LIFETIME, '/');
        header('Location: admin.php');
        exit;
    } else {
        header('Location: login.php?fail=1');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <div id="notification" class="notification hidden"></div>
        <div class="card">
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter password" required autocomplete="current-password">
                </div>
                <button type="submit">Login</button>
                <div class="login-link">
                    <a href="index.html">Back to Upload</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const fail = urlParams.get('fail');
        const notification = document.getElementById('notification');

        if (fail === '1') {
            notification.textContent = 'Invalid password. Please try again.';
            notification.classList.remove('hidden');
            notification.classList.add('fail');
        }

        // Clear query string so notification doesn't persist on reload
        history.replaceState({}, '', window.location.pathname);
    </script>
</body>
</html>