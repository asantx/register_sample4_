<?php
// core.php - session and authorization helpers

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in.
 * Works with both $_SESSION['user'] (array) or individual session keys like 'user_id'
 */
function isUserLoggedIn() {
    if (isset($_SESSION['user']) && !empty($_SESSION['user'])) return true;
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) return true;
    return false;
}

/**
 * Check if current logged in user is admin.
 * Works with $_SESSION['user']['role'] or $_SESSION['role'].
 * We consider role value 1 (administrator) or string 'admin' as admin.
 */
function isAdmin() {
    if (!isUserLoggedIn()) return false;
    // If session stores user array
    if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
        $role = $_SESSION['user']['role'];
    } elseif (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
    } else {
        return false;
    }

    // Accept numeric 1 or 'admin'
    if ($role === 1 || $role === '1' || strtolower((string)$role) === 'admin') {
        return true;
    }
    return false;
}

/**
 * Helper: require login, else redirect to login page
 */
function requireLogin($redirect = '/login/login.php') {
    if (!isUserLoggedIn()) {
        header("Location: $redirect");
        exit();
    }
}

/**
 * Helper: require admin, else redirect to login/home
 */
function requireAdmin($redirect = '/login/login.php') {
    if (!isAdmin()) {
        header("Location: $redirect");
        exit();
    }
}
?>
