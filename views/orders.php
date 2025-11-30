<?php
session_start();
require_once '../settings/core.php';
require_once '../controllers/order_controller.php';
requireLogin();

// Get user's counseling bookings
$user_id = $_SESSION['user_id'];
$bookings = get_user_counseling_bookings_ctr($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Journey - DistantLove</title>
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

        /* Page Header */
        .page-header {
            padding: 3rem 0 2rem;
            text-align: center;
        }

        .page-title {
            font-family: 'Pacifico', cursive;
            font-size: 3rem;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Tab Navigation */
        .journey-tabs {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .tab-pills {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .tab-pill {
            background: var(--gradient-soft-pink);
            color: var(--primary-pink);
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab-pill:hover, .tab-pill.active {
            background: var(--gradient-rose);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(215, 38, 96, 0.3);
        }

        /* Orders Container */
        .orders-container {
            padding: 2rem 0 4rem;
        }

        .order-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .order-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: var(--gradient-rose);
        }

        .order-card:hover {
            box-shadow: 0 15px 50px rgba(215, 38, 96, 0.15);
            transform: translateY(-5px);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-info h3 {
            color: var(--primary-pink);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .order-reference {
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 0.95rem;
        }

        .order-date {
            color: var(--dark-gray);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .status-badge {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-scheduled {
            background: linear-gradient(135deg, #ffc107 0%, #ff8800 100%);
        }

        .status-confirmed {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .status-completed {
            background: linear-gradient(135deg, #48dbfb 0%, #00d2ff 100%);
        }

        .status-cancelled {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        /* Session Details */
        .session-details {
            background: var(--gradient-soft-pink);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 2px solid rgba(215, 38, 96, 0.1);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--primary-pink-dark);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-value {
            color: var(--dark-gray);
            font-weight: 500;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.5rem;
            border-top: 2px solid #f0f0f0;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-total {
            font-size: 1.3rem;
            color: var(--primary-pink);
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .view-details-btn {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
        }

        .view-details-btn:hover {
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.4);
            transform: translateY(-3px);
        }

        .secondary-btn {
            background: white;
            color: var(--primary-pink);
            border: 2px solid var(--primary-pink);
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .secondary-btn:hover {
            background: var(--primary-pink);
            color: white;
            transform: translateY(-3px);
        }

        .no-orders {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .no-orders i {
            font-size: 5rem;
            color: var(--primary-pink-lighter);
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        .no-orders h3 {
            color: var(--primary-pink);
            margin-bottom: 15px;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .no-orders p {
            color: var(--dark-gray);
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .start-journey-btn {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .start-journey-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(215, 38, 96, 0.4);
            color: white;
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

        @media (max-width: 768px) {
            .page-title {
                font-size: 2.5rem;
            }

            .nav-links-modern {
                flex-direction: column;
                gap: 1rem;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .tab-pills {
                flex-direction: column;
            }

            .tab-pill {
                width: 100%;
                justify-content: center;
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
                    <a href="shop.php" class="nav-link-modern">
                        <i class="fas fa-home"></i> Home
                    </a>
                    <a href="counseling.php" class="nav-link-modern">
                        <i class="fas fa-comments"></i> Get Support
                    </a>
                    <a href="date_ideas.php" class="nav-link-modern">
                        <i class="fas fa-lightbulb"></i> Date Ideas
                    </a>
                    <a href="community.php" class="nav-link-modern">
                        <i class="fas fa-users"></i> Community
                    </a>
                    <a href="../login/logout.php" class="nav-link-modern">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">My Journey</h1>
            <p class="page-subtitle">Track your sessions, bookings, and relationship growth</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container orders-container">
        <!-- Tab Navigation -->
        <div class="journey-tabs">
            <div class="tab-pills">
                <button class="tab-pill active" onclick="filterBookings('all')">
                    <i class="fas fa-heart"></i> All My Bookings
                </button>
                <button class="tab-pill" onclick="filterBookings('scheduled')">
                    <i class="fas fa-clock"></i> Coming Up
                </button>
                <button class="tab-pill" onclick="filterBookings('completed')">
                    <i class="fas fa-check-circle"></i> Completed
                </button>
                <button class="tab-pill" onclick="filterBookings('cancelled')">
                    <i class="fas fa-times-circle"></i> Cancelled
                </button>
            </div>
        </div>

        <!-- Orders List -->
        <div id="orders-list">
            <?php if (empty($bookings)): ?>
                <!-- No Bookings Message -->
                <div class="no-orders" style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-heart-broken" style="font-size: 5rem; color: var(--primary-pink); margin-bottom: 1.5rem;"></i>
                    <h3 style="color: var(--primary-pink); font-size: 2rem; margin-bottom: 1rem;">Your Journey Awaits</h3>
                    <p style="color: var(--dark-gray); font-size: 1.1rem; margin-bottom: 2rem;">You haven't booked any sessions yet. Start your journey to a stronger relationship today!</p>
                    <a href="shop.php" class="btn btn-lg" style="background: var(--gradient-rose); color: white; padding: 15px 40px; border-radius: 50px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 10px;">
                        <i class="fas fa-heart"></i> Explore Our Services
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($bookings as $booking):
                    // Determine status badge class
                    $statusClass = 'status-' . strtolower($booking['status']);
                    $statusIcon = $booking['status'] === 'confirmed' ? 'fa-check-circle' :
                                 ($booking['status'] === 'completed' ? 'fa-heart' : 'fa-times-circle');

                    // Format date
                    $sessionDate = date('F j, Y', strtotime($booking['session_date']));
                    $bookedDate = date('M j, Y', strtotime($booking['order_date']));

                    // Check if session is upcoming or past
                    $isPast = strtotime($booking['session_date']) < time();
                    $dataStatus = $isPast ? 'completed' : $booking['status'];
                ?>
            <!-- Counseling Session Booking -->
            <div class="order-card" data-status="<?php echo htmlspecialchars($dataStatus); ?>">
                <div class="order-header">
                    <div class="order-info">
                        <h3><i class="fas fa-comments"></i> Counseling Session</h3>
                        <div class="order-reference">Booking #<?php echo htmlspecialchars($booking['order_reference']); ?></div>
                        <div class="order-date"><i class="fas fa-calendar-alt"></i> Booked on <?php echo $bookedDate; ?></div>
                    </div>
                    <span class="status-badge <?php echo $statusClass; ?>">
                        <i class="fas <?php echo $statusIcon; ?>"></i> <?php echo ucfirst($booking['status']); ?>
                    </span>
                </div>

                <div class="session-details">
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-user-md"></i> Counselor
                        </span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['counselor_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-calendar"></i> Session Date
                        </span>
                        <span class="detail-value"><?php echo $sessionDate; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Time
                        </span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['session_time']); ?> GMT</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-video"></i> Session Type
                        </span>
                        <span class="detail-value"><?php echo ucfirst(htmlspecialchars($booking['session_type'])); ?></span>
                    </div>
                    <?php if (!empty($booking['session_notes'])): ?>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-comment"></i> Notes
                        </span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['session_notes']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="order-footer">
                    <div class="order-total">GH₵ <?php echo number_format($booking['total_amount'], 2); ?></div>
                    <div class="action-buttons">
                        <?php if (!$isPast): ?>
                        <button class="view-details-btn">
                            <i class="fas fa-video"></i> Join Session
                        </button>
                        <?php else: ?>
                        <button class="secondary-btn" disabled>
                            <i class="fas fa-check"></i> Completed
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <!-- Default Sample Sessions (for demonstration) -->

            <!-- Upcoming Session 1 - Dr. Akosua Mensah -->
            <div class="order-card" data-status="confirmed">
                <div class="order-header">
                    <div class="order-info">
                        <h3><i class="fas fa-comments"></i> Counseling Session</h3>
                        <div class="order-reference">Booking #DL-2025-001234</div>
                        <div class="order-date"><i class="fas fa-calendar-alt"></i> Booked on Nov 25, 2025</div>
                    </div>
                    <span class="status-badge status-confirmed">
                        <i class="fas fa-check-circle"></i> Confirmed
                    </span>
                </div>

                <div class="session-details">
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-user-md"></i> Counselor
                        </span>
                        <span class="detail-value">Dr. Akosua Mensah</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-calendar"></i> Session Date
                        </span>
                        <span class="detail-value">December 5, 2025</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Time
                        </span>
                        <span class="detail-value">2:00 PM GMT</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-video"></i> Session Type
                        </span>
                        <span class="detail-value">Video</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-comment"></i> Notes
                        </span>
                        <span class="detail-value">Need help with communication and trust building in our long-distance relationship</span>
                    </div>
                </div>

                <div class="order-footer">
                    <div class="order-total">GH₵ 1,920.00</div>
                    <div class="action-buttons">
                        <button class="view-details-btn">
                            <i class="fas fa-video"></i> Join Session
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upcoming Session 2 - Kwame Asante -->
            <div class="order-card" data-status="confirmed">
                <div class="order-header">
                    <div class="order-info">
                        <h3><i class="fas fa-comments"></i> Counseling Session</h3>
                        <div class="order-reference">Booking #DL-2025-001567</div>
                        <div class="order-date"><i class="fas fa-calendar-alt"></i> Booked on Nov 28, 2025</div>
                    </div>
                    <span class="status-badge status-confirmed">
                        <i class="fas fa-check-circle"></i> Confirmed
                    </span>
                </div>

                <div class="session-details">
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-user-md"></i> Counselor
                        </span>
                        <span class="detail-value">Kwame Asante, LMFT</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-calendar"></i> Session Date
                        </span>
                        <span class="detail-value">December 10, 2025</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Time
                        </span>
                        <span class="detail-value">4:00 PM GMT</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-video"></i> Session Type
                        </span>
                        <span class="detail-value">Phone</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-comment"></i> Notes
                        </span>
                        <span class="detail-value">Dealing with time zone differences and planning our future together</span>
                    </div>
                </div>

                <div class="order-footer">
                    <div class="order-total">GH₵ 1,520.00</div>
                    <div class="action-buttons">
                        <button class="view-details-btn">
                            <i class="fas fa-video"></i> Join Session
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cancelled Session - Dr. Sarah Mitchell -->
            <div class="order-card" data-status="cancelled">
                <div class="order-header">
                    <div class="order-info">
                        <h3><i class="fas fa-comments"></i> Counseling Session</h3>
                        <div class="order-reference">Booking #DL-2025-000891</div>
                        <div class="order-date"><i class="fas fa-calendar-alt"></i> Booked on Nov 20, 2025</div>
                    </div>
                    <span class="status-badge status-cancelled">
                        <i class="fas fa-times-circle"></i> Cancelled
                    </span>
                </div>

                <div class="session-details">
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-user-md"></i> Counselor
                        </span>
                        <span class="detail-value">Dr. Sarah Mitchell</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-calendar"></i> Session Date
                        </span>
                        <span class="detail-value">November 28, 2025</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Time
                        </span>
                        <span class="detail-value">10:00 AM GMT</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-video"></i> Session Type
                        </span>
                        <span class="detail-value">Video</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-comment"></i> Notes
                        </span>
                        <span class="detail-value">Had to reschedule due to emergency - will book again soon</span>
                    </div>
                </div>

                <div class="order-footer">
                    <div class="order-total">GH₵ 1,760.00</div>
                    <div class="action-buttons">
                        <button class="secondary-btn">
                            <i class="fas fa-redo"></i> Book Again
                        </button>
                    </div>
                </div>
            </div>

            <!-- Completed Session 1 - Dr. Akosua Mensah -->
            <div class="order-card" data-status="completed">
                <div class="order-header">
                    <div class="order-info">
                        <h3><i class="fas fa-comments"></i> Counseling Session</h3>
                        <div class="order-reference">Booking #DL-2025-000456</div>
                        <div class="order-date"><i class="fas fa-calendar-alt"></i> Booked on Nov 10, 2025</div>
                    </div>
                    <span class="status-badge status-completed">
                        <i class="fas fa-heart"></i> Completed
                    </span>
                </div>

                <div class="session-details">
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-user-md"></i> Counselor
                        </span>
                        <span class="detail-value">Dr. Akosua Mensah</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-calendar"></i> Session Date
                        </span>
                        <span class="detail-value">November 18, 2025</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Time
                        </span>
                        <span class="detail-value">11:00 AM GMT</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-video"></i> Session Type
                        </span>
                        <span class="detail-value">Video</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-comment"></i> Notes
                        </span>
                        <span class="detail-value">First session - getting to know each other and setting relationship goals</span>
                    </div>
                </div>

                <div class="order-footer">
                    <div class="order-total">GH₵ 1,920.00</div>
                    <div class="action-buttons">
                        <button class="secondary-btn" disabled>
                            <i class="fas fa-check"></i> Completed
                        </button>
                    </div>
                </div>
            </div>

            <!-- Completed Session 2 - Kwame Asante -->
            <div class="order-card" data-status="completed">
                <div class="order-header">
                    <div class="order-info">
                        <h3><i class="fas fa-comments"></i> Counseling Session</h3>
                        <div class="order-reference">Booking #DL-2025-000123</div>
                        <div class="order-date"><i class="fas fa-calendar-alt"></i> Booked on Nov 5, 2025</div>
                    </div>
                    <span class="status-badge status-completed">
                        <i class="fas fa-heart"></i> Completed
                    </span>
                </div>

                <div class="session-details">
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-user-md"></i> Counselor
                        </span>
                        <span class="detail-value">Kwame Asante, LMFT</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-calendar"></i> Session Date
                        </span>
                        <span class="detail-value">November 12, 2025</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Time
                        </span>
                        <span class="detail-value">5:00 PM GMT</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-video"></i> Session Type
                        </span>
                        <span class="detail-value">Chat</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-comment"></i> Notes
                        </span>
                        <span class="detail-value">Working on conflict resolution strategies and improving our communication</span>
                    </div>
                </div>

                <div class="order-footer">
                    <div class="order-total">GH₵ 1,520.00</div>
                    <div class="action-buttons">
                        <button class="secondary-btn" disabled>
                            <i class="fas fa-check"></i> Completed
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function filterBookings(status) {
            const cards = document.querySelectorAll('.order-card');
            const pills = document.querySelectorAll('.tab-pill');

            // Update active pill
            pills.forEach(pill => pill.classList.remove('active'));
            event.target.classList.add('active');

            // Filter cards
            cards.forEach(card => {
                const cardStatus = card.dataset.status;

                if (status === 'all') {
                    card.style.display = 'block';
                } else if (status === 'scheduled' && (cardStatus === 'confirmed' || cardStatus === 'scheduled')) {
                    card.style.display = 'block';
                } else if (status === cardStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
