<?php
session_start();
require_once '../settings/core.php';
require_once '../settings/paystack_config.php';

// Get reference from URL
$reference = $_GET['reference'] ?? '';
$trxref = $_GET['trxref'] ?? $reference; // Paystack might use trxref

if (empty($reference) && empty($trxref)) {
    header('Location: shop.php');
    exit();
}

$reference = $reference ?: $trxref;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment - DistantLove</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .processing-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .spinner {
            width: 80px;
            height: 80px;
            border: 5px solid #ffdde1;
            border-top: 5px solid #d72660;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 2rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .title {
            font-family: 'Pacifico', cursive;
            font-size: 2rem;
            background: linear-gradient(135deg, #d72660 0%, #a8325e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
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
    <div class="processing-card">
        <div id="processing-view">
            <div class="spinner"></div>
            <h1 class="title">Processing Payment <span class="love-heart">❤️</span></h1>
            <p class="subtitle">Please wait while we confirm your payment...</p>
            <small class="text-muted">Reference: <?php echo htmlspecialchars($reference); ?></small>
        </div>

        <div id="success-view" style="display: none;">
            <i class="fas fa-check-circle" style="font-size: 5rem; color: #28a745; margin-bottom: 1rem;"></i>
            <h1 class="title">Payment Successful!</h1>
            <p class="subtitle" id="success-message"></p>
            <button onclick="window.location.href='payment_success.php?ref=' + encodeURIComponent('<?php echo htmlspecialchars($reference); ?>')"
                    class="btn btn-lg"
                    style="background: linear-gradient(135deg, #d72660 0%, #a8325e 100%); color: white; padding: 15px 40px; border-radius: 50px; border: none;">
                <i class="fas fa-heart me-2"></i>View Details
            </button>
        </div>

        <div id="error-view" style="display: none;">
            <i class="fas fa-times-circle" style="font-size: 5rem; color: #dc3545; margin-bottom: 1rem;"></i>
            <h1 class="title">Payment Failed</h1>
            <p class="subtitle" id="error-message"></p>
            <button onclick="window.location.href='shop.php'"
                    class="btn btn-lg"
                    style="background: linear-gradient(135deg, #d72660 0%, #a8325e 100%); color: white; padding: 15px 40px; border-radius: 50px; border: none;">
                <i class="fas fa-arrow-left me-2"></i>Back to Services
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const reference = '<?php echo htmlspecialchars($reference); ?>';

            // Verify payment
            $.ajax({
                url: '../actions/paystack_verify_payment.php',
                method: 'GET',
                data: { reference: reference },
                dataType: 'json',
                success: function(response) {
                    console.log('Verification response:', response);

                    if (response.status) {
                        // Show success view
                        $('#processing-view').hide();
                        $('#success-view').show();

                        let message = 'Your payment has been processed successfully!';
                        if (response.data.payment_type === 'counseling') {
                            message = 'Your counseling session has been booked successfully!';
                        } else if (response.data.payment_type === 'premium') {
                            message = 'Welcome to DistantLove Premium!';
                        }

                        $('#success-message').text(message);

                        // Redirect after 3 seconds
                        setTimeout(function() {
                            window.location.href = 'payment_success.php?ref=' + encodeURIComponent(reference);
                        }, 3000);
                    } else {
                        // Show error view
                        $('#processing-view').hide();
                        $('#error-view').show();
                        $('#error-message').text(response.message || 'Payment verification failed. Please contact support.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Verification error:', error);
                    $('#processing-view').hide();
                    $('#error-view').show();
                    $('#error-message').text('Unable to verify payment. Please contact support with reference: ' + reference);
                }
            });
        });
    </script>
</body>
</html>
