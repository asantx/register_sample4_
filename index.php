<!DOCTYPE html><html><body><?php session_start(); ?></body></html>

<div style="position:fixed; top:10px; right:10px;">
<?php
if (isset($_SESSION['user_id']) || isset($_SESSION['user'])) {
    $isAdmin = false;
    if (isset($_SESSION['role']) && ($_SESSION['role'] === 1 || $_SESSION['role'] === '1' || strtolower((string)$_SESSION['role']) === 'admin')) $isAdmin = true;
    if (isset($_SESSION['user']['role']) && ($_SESSION['user']['role'] === 1 || $_SESSION['user']['role'] === '1' || strtolower((string)$_SESSION['user']['role']) === 'admin')) $isAdmin = true;

    echo '<a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a> ';
    if ($isAdmin) echo '<a href="admin/category.php" class="btn btn-sm btn-outline-primary">Category</a>';
} else {
    echo '<a href="login/register.php" class="btn btn-sm btn-outline-primary">Register</a> ';
    echo '<a href="login/login.php" class="btn btn-sm btn-outline-secondary">Login</a>';
}
?>
</div>
