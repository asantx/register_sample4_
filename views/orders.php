<?php
session_start();
require_once '../settings/core.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - DistantLove</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-family: 'Pacifico', cursive;
            font-size: 1.8rem;
            color: white !important;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.8) !important;
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
        
        .orders-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-top: 30px;
            margin-bottom: 30px;
        }
        
        .orders-area {
            padding: 25px;
        }
        
        .order-card {
            background: #f8f9fa;
            border-left: 4px solid #d72660;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .order-reference {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        .order-date {
            color: #666;
            font-size: 0.9rem;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
        }
        
        .status-pending {
            background: #ffc107;
        }
        
        .status-confirmed {
            background: #28a745;
        }
        
        .status-shipped {
            background: #17a2b8;
        }
        
        .status-delivered {
            background: #6c757d;
        }
        
        .status-cancelled {
            background: #dc3545;
        }
        
        .order-items {
            margin: 15px 0;
            padding: 15px 0;
            border-top: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
        }
        
        .order-item {
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
            align-items: center;
        }
        
        .order-item:last-child {
            margin-bottom: 0;
        }
        
        .order-item-image {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
            overflow: hidden;
        }
        
        .order-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .order-item-details {
            flex: 1;
        }
        
        .order-item-name {
            font-weight: 600;
            color: #333;
        }
        
        .order-item-meta {
            font-size: 0.85rem;
            color: #666;
        }
        
        .order-item-price {
            font-weight: 700;
            color: var(--primary);
        }
        
        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }
        
        .order-total {
            font-size: 1.1rem;
            color: var(--primary);
        }
        
        .no-orders {
            text-align: center;
            padding: 60px 20px;
            color: #999;
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
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">
                            <i class="fas fa-store"></i> Shop
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="orders-container">
            <div class="orders-area">
                <h4 style="color: var(--primary); margin-bottom: 20px;"><i class="fas fa-box"></i> My Orders</h4>
                <div id="orders-list"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/orders.js"></script>
</body>
</html>
