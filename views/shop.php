<?php
session_start();
require_once '../settings/core.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DistantLove - Your Long Distance Relationship Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/distantlove-theme.css">
    <style>
        * {
            font-family: 'Inter', 'Roboto', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Modern Navigation */
        .navbar-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(215, 38, 96, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand-modern {
            font-family: 'Pacifico', cursive;
            font-size: 1.8rem;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 400;
        }

        .navbar-brand-modern .love-heart {
            color: #d72660;
            -webkit-text-fill-color: #d72660;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        .nav-links-modern {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link-modern {
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link-modern::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-rose);
            transition: width 0.3s ease;
        }

        .nav-link-modern:hover::after {
            width: 100%;
        }

        .nav-link-modern:hover {
            color: var(--primary-pink);
        }

        /* Hero Section */
        .hero-section {
            padding: 4rem 0;
            text-align: center;
        }

        .hero-title {
            font-family: 'Pacifico', cursive;
            font-size: 3.5rem;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: white;
            margin-bottom: 2rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Services Grid */
        .services-container {
            padding: 3rem 0;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-soft-pink);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .service-card:hover::before {
            opacity: 0.15;
        }

        .service-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 60px rgba(215, 38, 96, 0.2);
        }

        .service-icon {
            width: 100px;
            height: 100px;
            background: var(--gradient-soft-pink);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 3rem;
            color: var(--primary-pink);
            position: relative;
            z-index: 1;
            transition: all 0.4s ease;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
            background: var(--gradient-rose);
            color: white;
        }

        .service-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .service-description {
            color: var(--dark-gray);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .service-btn {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
            position: relative;
            z-index: 1;
            text-decoration: none;
            display: inline-block;
        }

        .service-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.4);
            color: white;
        }

        /* Premium Banner */
        .premium-banner {
            background: linear-gradient(135deg, #d72660 0%, #a8325e 100%);
            padding: 4rem 2rem;
            margin: 4rem 0;
            border-radius: 30px;
            box-shadow: 0 15px 50px rgba(215, 38, 96, 0.3);
            position: relative;
            overflow: hidden;
        }

        .premium-banner::before {
            content: '💕';
            position: absolute;
            font-size: 15rem;
            opacity: 0.1;
            top: -50px;
            right: -50px;
            animation: float 6s ease-in-out infinite;
        }

        .premium-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .premium-title {
            font-family: 'Pacifico', cursive;
            font-size: 2.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .premium-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
        }

        .premium-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
            text-align: left;
        }

        .premium-feature {
            display: flex;
            align-items: start;
            gap: 1rem;
        }

        .premium-feature i {
            color: white;
            font-size: 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .premium-feature-content h4 {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .premium-feature-content p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin: 0;
        }

        .premium-btn {
            background: white;
            color: var(--primary-pink);
            border: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }

        .premium-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(0, 0, 0, 0.3);
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .service-card {
                padding: 2rem 1.5rem;
            }

            .premium-title {
                font-size: 2rem;
            }

            .nav-links-modern {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Modern Navigation -->
    <nav class="navbar-modern">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a href="shop.php" class="navbar-brand-modern">
                    <span class="love-heart">❤️</span> DistantLove
                </a>
                <div class="nav-links-modern">
                    <?php if (isUserLoggedIn()): ?>
                        <a href="shop.php" class="nav-link-modern">
                            <i class="fas fa-heart"></i> Home
                        </a>
                        <a href="orders.php" class="nav-link-modern">
                            <i class="fas fa-box"></i> My Bookings
                        </a>
                        <a href="../login/logout.php" class="nav-link-modern">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    <?php else: ?>
                        <a href="../login/login.php" class="nav-link-modern">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="../login/register.php" class="nav-link-modern">
                            <i class="fas fa-user-plus"></i> Join Us
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Welcome to DistantLove</h1>
            <p class="hero-subtitle">Your Complete Hub for Thriving Long Distance Relationships</p>
        </div>
    </div>

    <!-- Services Container -->
    <div class="container services-container">
        <div class="row g-4">
            <!-- Counseling Sessions -->
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="service-title">Counseling Sessions</h3>
                    <p class="service-description">
                        Connect with experienced relationship counselors who specialize in long-distance relationships. Book one-on-one sessions tailored to your unique needs.
                    </p>
                    <a href="counseling.php" class="service-btn">
                        <i class="fas fa-calendar-check"></i> Book a Session
                    </a>
                </div>
            </div>

            <!-- Date Ideas -->
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="service-title">Date Ideas</h3>
                    <p class="service-description">
                        Discover creative and fun date ideas perfect for couples separated by distance. Keep the spark alive with virtual dates, surprises, and shared experiences.
                    </p>
                    <a href="date_ideas.php" class="service-btn">
                        <i class="fas fa-heart"></i> Explore Ideas
                    </a>
                </div>
            </div>

            <!-- Community -->
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="service-title">Community</h3>
                    <p class="service-description">
                        Join a supportive community of couples in long-distance relationships. Share experiences, tips, and learn from others who understand your journey.
                    </p>
                    <a href="community.php" class="service-btn">
                        <i class="fas fa-user-friends"></i> Join Community
                    </a>
                </div>
            </div>
        </div>

        <!-- Premium Subscription Banner -->
        <div class="premium-banner">
            <div class="premium-content">
                <h2 class="premium-title">Upgrade to DistantLove Premium</h2>
                <p class="premium-subtitle">Unlock exclusive features and take your relationship to the next level</p>

                <div class="premium-features">
                    <div class="premium-feature">
                        <i class="fas fa-star"></i>
                        <div class="premium-feature-content">
                            <h4>Unlimited Community Access</h4>
                            <p>Full access to all community posts, stories, and exclusive discussions</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-envelope"></i>
                        <div class="premium-feature-content">
                            <h4>Weekly Expert Advice</h4>
                            <p>Personalized relationship tips from counselors delivered to your inbox</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-percent"></i>
                        <div class="premium-feature-content">
                            <h4>20% Off Sessions</h4>
                            <p>Exclusive discount on all counseling sessions and consultations</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-gift"></i>
                        <div class="premium-feature-content">
                            <h4>Premium Date Ideas</h4>
                            <p>Access to exclusive, creative date ideas and relationship challenges</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-calendar-alt"></i>
                        <div class="premium-feature-content">
                            <h4>Priority Booking</h4>
                            <p>Get first access to counselor schedules and premium time slots</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-headset"></i>
                        <div class="premium-feature-content">
                            <h4>24/7 Support</h4>
                            <p>Round-the-clock priority support for any questions or concerns</p>
                        </div>
                    </div>
                </div>

                <button class="premium-btn" onclick="showSubscriptionModal()">
                    <i class="fas fa-crown"></i> Subscribe Now - $19.99/month
                </button>
            </div>
        </div>
    </div>

    <!-- Subscription Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header" style="background: var(--gradient-rose); color: white; border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title"><i class="fas fa-crown"></i> Subscribe to Premium</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <div class="text-center mb-4">
                        <h3 style="color: var(--primary-pink); font-weight: 700;">$19.99/month</h3>
                        <p style="color: var(--dark-gray);">Cancel anytime, no commitment</p>
                    </div>

                    <form id="subscriptionForm">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--dark-gray); font-weight: 600;">Card Number</label>
                            <input type="text" class="form-control" placeholder="1234 5678 9012 3456" style="border-radius: 10px; padding: 12px;">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: var(--dark-gray); font-weight: 600;">Expiry Date</label>
                                <input type="text" class="form-control" placeholder="MM/YY" style="border-radius: 10px; padding: 12px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: var(--dark-gray); font-weight: 600;">CVV</label>
                                <input type="text" class="form-control" placeholder="123" style="border-radius: 10px; padding: 12px;">
                            </div>
                        </div>
                        <button type="submit" class="service-btn w-100" style="padding: 15px;">
                            <i class="fas fa-lock"></i> Complete Subscription
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showSubscriptionModal() {
            <?php if (isUserLoggedIn()): ?>
                const modal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
                modal.show();
            <?php else: ?>
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please login or register to subscribe to premium',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Register',
                    confirmButtonColor: '#d72660',
                    cancelButtonColor: '#a8325e'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../login/login.php';
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = '../login/register.php';
                    }
                });
            <?php endif; ?>
        }

        // Handle subscription form
        document.getElementById('subscriptionForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process your subscription',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate payment processing
            setTimeout(() => {
                Swal.fire({
                    title: 'Welcome to Premium!',
                    text: 'Your subscription has been activated successfully',
                    icon: 'success',
                    confirmButtonColor: '#d72660'
                }).then(() => {
                    location.reload();
                });
            }, 2000);
        });
    </script>
</body>

</html>
