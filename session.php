<?php

/**
 * Session Management Functions
 * AutoParts Hub E-Commerce System
 */


/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}


/**
 * Require user login
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}


/**
 * Require admin access
 */
function requireAdmin()
{
    requireLogin();

    if ($_SESSION['role'] !== 'admin') {
        header("Location: " . BASE_URL);
        exit;
    }
}


function requireCustomer()
{
    requireLogin();

    if ($_SESSION['role'] !== 'customer') {

        header("Location: " . BASE_URL);

        exit;
    }
}


/**
 * Get current user ID
 */
function getUserId()
{
    return $_SESSION['user_id'] ?? null;
}


/**
 * Get current user name
 */
function getUserName()
{
    return $_SESSION['full_name'] ?? "Guest";
}


/**
 * Logout user
 */
function logoutUser()
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
}