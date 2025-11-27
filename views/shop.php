<?php
session_start();
require_once '../settings/core.php';
// Backend/data loading is performed by controllers/actions and fetched
// client-side via AJAX (keep view clean and MVC-compliant).
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - DistantLove</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/distantlove-theme.css">
    <style>
        :root {
            --primary: #d72660;
            --secondary: #a8325e;
            --success: #48dbfb;
            --danger: #ff6b6b;
        }

        * {
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-family: 'Pacifico', cursive;
            font-size: 1.8rem;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
        }

        .cart-badge {
            background: var(--danger);
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .shop-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .sidebar {
            background: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #dee2e6;
        }

        .sidebar-section {
            margin-bottom: 25px;
        }

        .sidebar-section h6 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 12px;
        }

        .sidebar-section input,
        .sidebar-section select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .sidebar-section label {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .sidebar-section input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
            cursor: pointer;
        }

        .products-area {
            padding: 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition-normal);
            cursor: pointer;
            box-shadow: var(--shadow-md);
            position: relative;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-soft-pink);
            opacity: 0;
            transition: opacity var(--transition-normal);
            z-index: 0;
            pointer-events: none;
        }

        .product-card:hover::before {
            opacity: 0.1;
        }

        .product-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-hover);
        }

        .product-image {
            width: 100%;
            height: 220px;
            background: var(--gradient-soft-pink);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-pink);
            font-size: 3.5rem;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--gradient-rose);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
            z-index: 2;
        }

        .product-info {
            padding: 18px;
            position: relative;
            z-index: 1;
        }

        .product-category {
            color: var(--primary-pink-dark);
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .product-title {
            font-weight: 600;
            color: var(--darker-gray);
            margin-bottom: 10px;
            font-size: 1rem;
            min-height: 48px;
            line-height: 1.4;
        }

        .product-description {
            color: var(--dark-gray);
            font-size: 0.85rem;
            margin-bottom: 10px;
            line-height: 1.5;
            max-height: 40px;
            overflow: hidden;
        }

        .product-price {
            color: var(--primary-pink);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 12px;
            text-shadow: 0 1px 3px rgba(215, 38, 96, 0.1);
        }

        .product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 0.85rem;
        }

        .product-rating .stars {
            color: var(--accent-gold);
            margin-right: 5px;
        }

        .add-to-cart-btn {
            width: 100%;
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 12px;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all var(--transition-normal);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .add-to-cart-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .add-to-cart-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .add-to-cart-btn:active {
            transform: translateY(0);
        }

        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: var(--dark-gray);
        }

        .no-products i {
            font-size: 4rem;
            color: var(--primary-pink-lighter);
            margin-bottom: 20px;
        }

        .quick-view-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transform: scale(0);
            transition: all var(--transition-normal);
            z-index: 2;
            box-shadow: var(--shadow-md);
        }

        .quick-view-btn i {
            color: var(--primary-pink);
            font-size: 1.1rem;
        }

        .product-card:hover .quick-view-btn {
            opacity: 1;
            transform: scale(1);
        }

        .quick-view-btn:hover {
            background: var(--primary-pink);
        }

        .quick-view-btn:hover i {
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">DistantLove</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isUserLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i class="fas fa-shopping-cart"></i> Cart <span class="cart-badge" id="cart-count">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders.php">
                                <i class="fas fa-box"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../login/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../login/login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../login/register.php">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i class="fas fa-shopping-cart"></i> Cart <span class="cart-badge" id="cart-count">0</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Shop Container -->
    <div class="container shop-container">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <div class="sidebar-section">
                    <h6><i class="fas fa-search"></i> Search</h6>
                    <input type="text" id="search" placeholder="Search products..." class="form-control">
                </div>

                <div class="sidebar-section">
                    <h6><i class="fas fa-tag"></i> Categories</h6>
                    <select id="category" class="form-select">
                        <option value="">All Categories</option>
                    </select>
                </div>

                <div class="sidebar-section">
                    <h6><i class="fas fa-trademark"></i> Brands</h6>
                    <select id="brand" class="form-select">
                        <option value="">All Brands</option>
                    </select>
                </div>
            </div>

            <!-- Products Area -->
            <div class="col-md-9 products-area">
                <div class="product-grid" id="product-grid">
                    <div class="text-center w-100" style="padding: 40px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Product Modal -->
    <?php include 'product_modal.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/shop.js"></script>
</body>

</html>