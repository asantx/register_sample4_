<?php
require_once __DIR__ . '/../settings/core.php';
if (!isUserLoggedIn()) {
    header('Location: ../login/login.php');
    exit();
}
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Categories - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h2>Category Management</h2>
    <p>Logged in as: <?php echo isset($_SESSION['name'])?htmlspecialchars($_SESSION['name']):'Admin'; ?></p>

    <div class="card mb-4">
        <div class="card-body">
            <form id="add-category-form" class="row g-2">
                <div class="col-md-8">
                    <input id="category-name" name="name" class="form-control" placeholder="Category name" required />
                </div>
                <div class="col-md-4">
                    <button id="add-category-btn" class="btn btn-success">Add Category</button>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered" id="categories-table">
        <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
        <tbody><tr><td colspan="3">Loading...</td></tr></tbody>
    </table>

    <a href="../index.php" class="btn btn-secondary mt-3">Back</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/category.js"></script>
</body>
</html>
