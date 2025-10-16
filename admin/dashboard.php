<?php
require_once __DIR__ . '/../settings/core.php';
requireAdmin('../login/login.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DistantLove Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
        }

        .sidebar {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(215, 38, 96, 0.07);
        }

        .sidebar a {
            color: #d72660;
            font-weight: 600;
        }

        .sidebar a.active {
            background: #fbe6ea;
            border-radius: 8px;
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            align-items: center;
        }

        .admin-name {
            color: #a8325e;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="menu-tray-love menu-tray">
        <span class="love-heart">&#10084;&#65039;</span>
        <!-- dynamic menu inserted by js/index.js -->
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="p-3 sidebar">
                    <h3 class="text-center" style="font-family: 'Pacifico', cursive; color:#d72660;">DistantLove Admin</h3>
                    <div class="text-center mb-2 admin-name">Logged in as: <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></div>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="category.php"> <i class="fa fa-list"></i> Categories</a>
                        <a class="nav-link" href="#"> <i class="fa fa-tag"></i> Brand</a>
                        <a class="nav-link" href="#"> <i class="fa fa-box"></i> Product</a>
                        <a class="nav-link" href="#"> <i class="fa fa-shopping-cart"></i> Orders</a>
                        <a class="nav-link" href="#"> <i class="fa fa-users"></i> Users</a>
                    </nav>
                    <div class="mt-3 text-center">
                        <!-- Legacy logout button removed to centralize logout in menu tray -->
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card" style="border-radius:14px;">
                    <div class="card-body">
                        <h4>Dashboard</h4>
                        <p>Welcome to the admin dashboard. Use the sidebar to navigate management pages.</p>
                        <div id="dashboard-widgets" class="row">
                            <div class="col-md-4 mb-3">
                                <div class="p-3" style="background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.03)">
                                    <h5>Categories</h5>
                                    <p class="text-muted">Manage categories</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3" style="background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.03)">
                                    <h5>Products</h5>
                                    <p class="text-muted">Manage products and inventory</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3" style="background:#fff;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.03)">
                                    <h5>Orders</h5>
                                    <p class="text-muted">View recent orders</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/reference.js"></script>
</body>

</html>