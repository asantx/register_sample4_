<?php
session_start();
require_once '../settings/core.php';
requireLogin();

$reference = $_GET['ref'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - DistantLove</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .success-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: scaleIn 0.5s ease-out;
        }

        .success-icon i {
            font-size: 3rem;
            color: white;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .page-title {
            font-family: 'Pacifico', cursive;
            font-size: 2.5rem;
            background: linear-gradient(135deg, #d72660 0%, #a8325e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .success-message {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .details-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            color: #333;
            font-weight: 500;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #d72660 0%, #a8325e 100%);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.4);
        }

        .btn-secondary-custom {
            background: white;
            color: #d72660;
            padding: 15px 40px;
            border-radius: 50px;
            border: 2px solid #d72660;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: #d72660;
            color: white;
            transform: translateY(-2px);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .love-heart {
            color: #d72660;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>

            <h1 class="page-title">Payment Successful! <span class="love-heart">❤️</span></h1>
            <p class="success-message">Thank you for your payment. Your transaction has been completed successfully.</p>

            <div class="details-card" id="payment-details">
                <h5 style="color: #d72660; margin-bottom: 1.5rem; font-weight: 700;">
                    <i class="fas fa-file-invoice me-2"></i>Payment Details
                </h5>
                <div class="detail-row">
                    <span class="detail-label">Transaction Reference:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($reference); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Date:</span>
                    <span class="detail-value"><?php echo date('F j, Y - g:i A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #28a745; font-weight: 700;">
                        <i class="fas fa-check-circle me-1"></i>Successful
                    </span>
                </div>
            </div>

            <div class="action-buttons">
                <button onclick="window.location.href='orders.php'" class="btn-primary-custom">
                    <i class="fas fa-box me-2"></i>View My Bookings
                </button>
                <button onclick="window.location.href='shop.php'" class="btn-secondary-custom">
                    <i class="fas fa-home me-2"></i>Back to Home
                </button>
            </div>

            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #dee2e6;">
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    A confirmation email has been sent to your registered email address.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
