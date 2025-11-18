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
    <title>Cart - Shoppn</title>
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
        
        .cart-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-top: 30px;
            margin-bottom: 30px;
        }
        
        .cart-items-area {
            padding: 25px;
        }
        
        .cart-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            flex-shrink: 0;
            overflow: hidden;
        }
        
        .cart-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .cart-item-price {
            color: var(--primary);
            font-weight: 700;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 10px 0;
        }
        
        .quantity-control button {
            background: #d72660;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
        }
        
        .quantity-control input {
            width: 50px;
            padding: 5px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-align: center;
        }
        
        .remove-btn {
            background: var(--danger);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
        }
        
        .cart-summary {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            position: sticky;
            top: 100px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1rem;
        }
        
        .summary-row.total {
            border-top: 2px solid #dee2e6;
            padding-top: 15px;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary);
        }
        
        .checkout-btn {
            width: 100%;
            background: linear-gradient(135deg, #d72660 0%, #a8325e 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: opacity 0.3s;
        }
        
        .checkout-btn:hover {
            opacity: 0.9;
        }
        
        .checkout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #d72660 0%, #a8325e 100%);
            color: white;
        }
        
        .form-control {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 10px 12px;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(215, 38, 96, 0.25);
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
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">
                            <i class="fas fa-store"></i> Shop
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row">
            <!-- Cart Items -->
            <div class="col-md-8">
                <div class="cart-container">
                    <div class="cart-items-area">
                        <h4 style="color: var(--primary); margin-bottom: 20px;"><i class="fas fa-shopping-cart"></i> Your Cart</h4>
                        <div id="cart-items"></div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-md-4">
                <div class="cart-summary">
                    <h5 style="color: var(--primary); margin-bottom: 20px;">Order Summary</h5>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">₦0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (0%):</span>
                        <span id="tax">₦0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="total">₦0.00</span>
                    </div>
                    <button class="checkout-btn" id="checkout-btn" onclick="proceedToCheckout()">
                        <i class="fas fa-credit-card"></i> Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-credit-card"></i> Checkout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="checkout-form">
                        <div class="mb-3">
                            <label for="customer-name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="customer-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="shipping-address" class="form-label">Shipping Address</label>
                            <textarea class="form-control" id="shipping-address" rows="3" required></textarea>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <strong>Total Amount:</strong>
                            <div style="font-size: 1.5rem; color: var(--primary); font-weight: 700;" id="checkout-total">₦0.00</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="confirmPayment()" style="background: linear-gradient(135deg, #d72660 0%, #a8325e 100%); border: none;">
                        <i class="fas fa-lock"></i> Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/checkout.js"></script>
</body>
</html>
