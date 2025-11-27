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
    <title>Checkout - DistantLove</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/distantlove-theme.css">
    <style>
        body {
            background: var(--gradient-primary);
            min-height: 100vh;
        }

        .checkout-container {
            max-width: 1000px;
            margin: 50px auto;
            background: white;
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            animation: fadeInUp 0.6s ease-out;
        }

        .checkout-header {
            background: var(--gradient-rose);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .checkout-header h2 {
            margin: 0;
            font-family: var(--font-heading);
            font-size: 2.5rem;
            color: white;
        }

        .checkout-steps {
            display: flex;
            justify-content: space-between;
            padding: 2rem;
            background: var(--gradient-soft-pink);
            position: relative;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: 3px solid var(--primary-pink-lighter);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 700;
            color: var(--primary-pink);
            transition: all var(--transition-normal);
        }

        .step.active .step-circle {
            background: var(--gradient-rose);
            color: white;
            border-color: var(--primary-pink);
            box-shadow: var(--shadow-md);
            transform: scale(1.1);
        }

        .step.completed .step-circle {
            background: var(--success);
            color: white;
            border-color: var(--success);
        }

        .step-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--primary-pink-dark);
        }

        .step.active .step-label {
            color: var(--primary-pink);
            font-weight: 700;
        }

        .checkout-body {
            padding: 3rem;
        }

        .form-step {
            display: none;
            animation: fadeInUp 0.5s ease-out;
        }

        .form-step.active {
            display: block;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-pink-dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control-checkout {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--gray);
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: all var(--transition-normal);
        }

        .form-control-checkout:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(215, 38, 96, 0.15);
            outline: none;
        }

        .order-summary-card {
            background: var(--gradient-soft-pink);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(215, 38, 96, 0.1);
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-pink);
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid var(--primary-pink-lighter);
        }

        .payment-method {
            border: 2px solid var(--gray);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all var(--transition-normal);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .payment-method:hover {
            border-color: var(--primary-pink-lighter);
            background: var(--gradient-soft-pink);
        }

        .payment-method.selected {
            border-color: var(--primary-pink);
            background: var(--gradient-soft-pink);
            box-shadow: var(--shadow-md);
        }

        .payment-icon {
            font-size: 2rem;
            color: var(--primary-pink);
        }

        .payment-info h5 {
            margin: 0 0 5px 0;
            color: var(--primary-pink-dark);
            font-weight: 600;
        }

        .payment-info p {
            margin: 0;
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        .checkout-actions {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 2rem;
        }

        .btn-checkout {
            flex: 1;
            padding: 15px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: all var(--transition-normal);
        }

        .btn-checkout-primary {
            background: var(--gradient-rose);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-checkout-primary:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-3px);
        }

        .btn-checkout-secondary {
            background: white;
            color: var(--primary-pink);
            border: 2px solid var(--primary-pink);
        }

        .btn-checkout-secondary:hover {
            background: var(--gradient-soft-pink);
        }

        .address-card {
            border: 2px solid var(--gray);
            border-radius: var(--radius-md);
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all var(--transition-normal);
        }

        .address-card:hover {
            border-color: var(--primary-pink-lighter);
            box-shadow: var(--shadow-sm);
        }

        .address-card.selected {
            border-color: var(--primary-pink);
            background: var(--gradient-soft-pink);
            box-shadow: var(--shadow-md);
        }

        .success-animation {
            text-align: center;
            padding: 3rem 2rem;
        }

        .success-icon {
            font-size: 5rem;
            color: var(--success);
            animation: scaleIn 0.8s ease-out;
        }

        .success-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin: 1.5rem 0 1rem;
        }

        .success-message {
            color: var(--dark-gray);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .checkout-container {
                margin: 20px;
            }

            .checkout-body {
                padding: 2rem 1.5rem;
            }

            .checkout-steps {
                padding: 1rem;
            }

            .step-circle {
                width: 40px;
                height: 40px;
                font-size: 0.9rem;
            }

            .step-label {
                font-size: 0.75rem;
            }

            .checkout-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-distantlove">
        <div class="container-fluid">
            <a class="navbar-brand-distantlove" href="shop.php">
                <i class="fas fa-heart me-2"></i>DistantLove
            </a>
            <a href="cart.php" class="btn btn-soft-pink">
                <i class="fas fa-arrow-left me-2"></i>Back to Cart
            </a>
        </div>
    </nav>

    <div class="checkout-container">
        <!-- Header -->
        <div class="checkout-header">
            <h2><i class="fas fa-shopping-bag me-2"></i>Checkout</h2>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Complete your order in just a few steps</p>
        </div>

        <!-- Progress Steps -->
        <div class="checkout-steps">
            <div class="step active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Shipping Info</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Payment</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Review</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Confirmation</div>
            </div>
        </div>

        <!-- Checkout Body -->
        <div class="checkout-body">
            <!-- Step 1: Shipping Information -->
            <div class="form-step active" id="step-1">
                <h4 style="color: var(--primary-pink-dark); margin-bottom: 1.5rem;">
                    <i class="fas fa-shipping-fast me-2"></i>Shipping Information
                </h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control-checkout" id="fullname" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control-checkout" id="email" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control-checkout" id="phone" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Country *</label>
                            <input type="text" class="form-control-checkout" id="country" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Street Address *</label>
                    <input type="text" class="form-control-checkout" id="address" required>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-control-checkout" id="city" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">State/Province</label>
                            <input type="text" class="form-control-checkout" id="state">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Postal Code</label>
                            <input type="text" class="form-control-checkout" id="postal">
                        </div>
                    </div>
                </div>
                <div class="checkout-actions">
                    <button class="btn-checkout btn-checkout-primary" onclick="nextStep(2)">
                        Continue to Payment <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Payment Method -->
            <div class="form-step" id="step-2">
                <h4 style="color: var(--primary-pink-dark); margin-bottom: 1.5rem;">
                    <i class="fas fa-credit-card me-2"></i>Payment Method
                </h4>

                <div class="payment-method selected" data-payment="card">
                    <div class="payment-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="payment-info">
                        <h5>Credit / Debit Card</h5>
                        <p>Pay securely with your card</p>
                    </div>
                </div>

                <div class="payment-method" data-payment="paypal">
                    <div class="payment-icon">
                        <i class="fab fa-paypal"></i>
                    </div>
                    <div class="payment-info">
                        <h5>PayPal</h5>
                        <p>Fast and secure payment via PayPal</p>
                    </div>
                </div>

                <div class="payment-method" data-payment="bank">
                    <div class="payment-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="payment-info">
                        <h5>Bank Transfer</h5>
                        <p>Direct bank transfer</p>
                    </div>
                </div>

                <div class="checkout-actions">
                    <button class="btn-checkout btn-checkout-secondary" onclick="prevStep(1)">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </button>
                    <button class="btn-checkout btn-checkout-primary" onclick="nextStep(3)">
                        Continue to Review <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Order Review -->
            <div class="form-step" id="step-3">
                <h4 style="color: var(--primary-pink-dark); margin-bottom: 1.5rem;">
                    <i class="fas fa-clipboard-check me-2"></i>Review Your Order
                </h4>

                <div class="order-summary-card">
                    <h5 style="color: var(--primary-pink-dark); margin-bottom: 1rem;">Order Summary</h5>
                    <div id="order-items"></div>
                    <div class="summary-item">
                        <span>Subtotal:</span>
                        <span id="review-subtotal">₦0.00</span>
                    </div>
                    <div class="summary-item">
                        <span>Shipping:</span>
                        <span id="review-shipping">₦0.00</span>
                    </div>
                    <div class="summary-item">
                        <span>Total:</span>
                        <span id="review-total">₦0.00</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="order-summary-card">
                            <h5 style="color: var(--primary-pink-dark); margin-bottom: 1rem;">
                                <i class="fas fa-map-marker-alt me-2"></i>Shipping Address
                            </h5>
                            <p id="review-address" style="margin: 0; color: var(--dark-gray);"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="order-summary-card">
                            <h5 style="color: var(--primary-pink-dark); margin-bottom: 1rem;">
                                <i class="fas fa-credit-card me-2"></i>Payment Method
                            </h5>
                            <p id="review-payment" style="margin: 0; color: var(--dark-gray);"></p>
                        </div>
                    </div>
                </div>

                <div class="checkout-actions">
                    <button class="btn-checkout btn-checkout-secondary" onclick="prevStep(2)">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </button>
                    <button class="btn-checkout btn-checkout-primary" onclick="placeOrder()">
                        <i class="fas fa-lock me-2"></i>Place Order
                    </button>
                </div>
            </div>

            <!-- Step 4: Confirmation -->
            <div class="form-step" id="step-4">
                <div class="success-animation">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="success-title">Order Placed Successfully!</h3>
                    <p class="success-message">
                        Thank you for your order! We've sent a confirmation email to your inbox.
                    </p>
                    <div style="background: var(--gradient-soft-pink); border-radius: var(--radius-lg); padding: 1.5rem; margin: 2rem 0;">
                        <p style="margin: 0; color: var(--primary-pink-dark); font-weight: 600;">
                            Order Number: <span id="order-number" style="color: var(--primary-pink); font-size: 1.2rem;">#12345</span>
                        </p>
                    </div>
                    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                        <a href="orders.php" class="btn btn-distantlove">
                            <i class="fas fa-box me-2"></i>View Orders
                        </a>
                        <a href="shop.php" class="btn btn-outline-distantlove">
                            <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentStep = 1;
        let selectedPayment = 'card';

        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
                selectedPayment = this.dataset.payment;
            });
        });

        function nextStep(step) {
            // Validate current step
            if (currentStep === 1) {
                if (!validateShippingInfo()) {
                    return;
                }
            }

            // Hide current step
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('completed');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');

            // Show next step
            currentStep = step;
            document.getElementById(`step-${currentStep}`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');

            // Update review page if moving to step 3
            if (currentStep === 3) {
                updateReview();
            }

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function prevStep(step) {
            // Hide current step
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');

            // Show previous step
            currentStep = step;
            document.getElementById(`step-${currentStep}`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateShippingInfo() {
            const fullname = document.getElementById('fullname').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const country = document.getElementById('country').value.trim();
            const address = document.getElementById('address').value.trim();
            const city = document.getElementById('city').value.trim();

            if (!fullname || !email || !phone || !country || !address || !city) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Information',
                    text: 'Please fill in all required fields.',
                    confirmButtonColor: '#d72660'
                });
                return false;
            }

            return true;
        }

        function updateReview() {
            // Update shipping address
            const fullname = document.getElementById('fullname').value;
            const address = document.getElementById('address').value;
            const city = document.getElementById('city').value;
            const state = document.getElementById('state').value;
            const postal = document.getElementById('postal').value;
            const country = document.getElementById('country').value;

            let addressText = `${fullname}<br>${address}<br>${city}`;
            if (state) addressText += `, ${state}`;
            if (postal) addressText += ` ${postal}`;
            addressText += `<br>${country}`;

            document.getElementById('review-address').innerHTML = addressText;

            // Update payment method
            const paymentMethods = {
                'card': 'Credit / Debit Card',
                'paypal': 'PayPal',
                'bank': 'Bank Transfer'
            };
            document.getElementById('review-payment').textContent = paymentMethods[selectedPayment];

            // Load cart summary (placeholder - integrate with actual cart data)
            document.getElementById('review-subtotal').textContent = '₦10,000.00';
            document.getElementById('review-shipping').textContent = '₦1,500.00';
            document.getElementById('review-total').textContent = '₦11,500.00';
        }

        function placeOrder() {
            // Show loading
            Swal.fire({
                title: 'Processing Order...',
                html: 'Please wait while we process your order.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate order processing
            setTimeout(() => {
                Swal.close();

                // Mark step 3 as completed
                document.querySelector(`.step[data-step="3"]`).classList.add('completed');

                // Move to confirmation
                nextStep(4);

                // Generate random order number
                const orderNumber = '#' + Math.floor(100000 + Math.random() * 900000);
                document.getElementById('order-number').textContent = orderNumber;
            }, 2000);
        }
    </script>
</body>
</html>
