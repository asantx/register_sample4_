<?php
// core.php - session and authorization helpers

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in.
 * Works with $_SESSION['user'] (array), or individual session keys like 'user_id', 'user_role', 'user_name'.
 */
function isUserLoggedIn()
{
    if (isset($_SESSION['user']) && !empty($_SESSION['user'])) return true;
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) return true;
    if (isset($_SESSION['user_role']) && !empty($_SESSION['user_role'])) return true;
    if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) return true;
    return false;
}

/**
 * Check if current logged in user is admin.
 * Works with $_SESSION['user']['role'], $_SESSION['role'], or $_SESSION['user_role'].
 * Only users with role value 2 (numeric or string) are admins.
 */
function isAdmin()
{
    if (!isUserLoggedIn()) return false;
    // If session stores user array
    if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
        $role = $_SESSION['user']['role'];
    } elseif (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
    } elseif (isset($_SESSION['user_role'])) {
        $role = $_SESSION['user_role'];
    } else {
        return false;
    }

    // Only accept numeric 2 or string '2' as admin
    if ($role === 2 || $role === '2') {
        return true;
    }
    return false;
}

/**
 * Helper: require login, else redirect to login page
 */
function requireLogin($redirect = '/login/login.php')
{
    if (!isUserLoggedIn()) {
        header("Location: $redirect");
        exit();
    }
}

/**
 * Helper: require admin, else redirect to login/home
 */
function requireAdmin($redirect = '/login/login.php')
{
    if (!isAdmin()) {
        header("Location: $redirect");
        exit();
    }
}

function getUserName()
{
    if (isset($_SESSION['user']) && isset($_SESSION['user']['name'])) {
        return $_SESSION['user']['name'];
    } elseif (isset($_SESSION['user_name'])) {
        return $_SESSION['user_name'];
    } elseif (isset($_SESSION['name'])) {
        return $_SESSION['name'];
    }
    return null;
}
