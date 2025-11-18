<?php
session_start();
require_once '../settings/core.php';
require_once '../settings/db_class.php';

$db = new DB_Connection();
$db_conn = $db->db_conn();

// Get categories and brands for filters
$categories = [];
$sql = "SELECT cat_id, cat_name FROM categories ORDER BY cat_name";
$result = $db_conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$brands = [];
$sql = "SELECT brand_id, brand_name FROM brands ORDER BY brand_name";
$result = $db_conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $brands[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Shoppn</title>
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
        
        .shop-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
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
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .product-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        
        .add-to-cart-btn {
            width: 100%;
            background: linear-gradient(135deg, #d72660 0%, #a8325e 100%);
            color: white;
            border: none;
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: opacity 0.3s;
        }
        
        .add-to-cart-btn:hover {
            opacity: 0.9;
        }
        
        .no-products {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Shoppn</a>
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
                            <a class="nav-link" href="login/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login/login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login/register.php">
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
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['cat_id']; ?>"><?php echo htmlspecialchars($cat['cat_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="sidebar-section">
                    <h6><i class="fas fa-trademark"></i> Brands</h6>
                    <select id="brand" class="form-select">
                        <option value="">All Brands</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo $brand['brand_id']; ?>"><?php echo htmlspecialchars($brand['brand_name']); ?></option>
                        <?php endforeach; ?>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/shop.js"></script>
</body>
</html>
