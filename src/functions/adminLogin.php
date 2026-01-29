<?php
require_once __DIR__  . '/../config/config.php';
function adminLogin(string $username, string $password): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if ($username === '' || $password === '') {
        return false;
    }

    if (strlen($username) < 3 || strlen($password) < 6) {
        return false;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return false;
    }

    if (!defined('ADMIN_USERNAME') || !defined('ADMIN_PASSWORD_HASH')) {
        return false;
    }

    if (!isset($_SESSION['admin_attempts'])) {
        $_SESSION['admin_attempts'] = 0;
    }

    if ($_SESSION['admin_attempts'] >= ADMIN_MAX_ATTEMPTS) {
        return false;
    }

    if (
        $username === ADMIN_USERNAME &&
        password_verify($password, ADMIN_PASSWORD_HASH)
    ) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = ADMIN_USERNAME;
        $_SESSION['admin_attempts'] = 0;
        return true;
    }

    $_SESSION['admin_attempts']++;
    return false;
}