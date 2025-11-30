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
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary-pink: #d72660;
            --secondary-pink: #a8325e;
            --gradient-rose: linear-gradient(135deg, #d72660 0%, #a8325e 100%);
            --gradient-soft-pink: linear-gradient(135deg, #ffdde1 0%, #fbe6ea 100%);
            --dark-gray: #333;
            --light-gray: #f8f9fa;
        }

        * {
            font-family: 'Inter', 'Roboto', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            padding-top: 80px;
        }

        /* Modern Navigation */
        .navbar-modern {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(215, 38, 96, 0.1);
            padding: 1rem 0;
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

        .nav-user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-name {
            color: var(--primary-pink);
            font-weight: 600;
        }

        .btn-logout-modern {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
        }

        .btn-logout-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(215, 38, 96, 0.4);
            color: white;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
        }

        /* Sidebar */
        .sidebar-modern {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            position: sticky;
            top: 100px;
        }

        .sidebar-title {
            font-family: 'Pacifico', cursive;
            font-size: 1.8rem;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 2rem;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item-modern {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: var(--dark-gray);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-item-modern:hover {
            background: var(--gradient-soft-pink);
            color: var(--primary-pink);
            transform: translateX(5px);
        }

        .nav-item-modern.active {
            background: var(--gradient-rose);
            color: white;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
        }

        .nav-item-modern i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        /* Dashboard Content */
        .dashboard-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 3rem;
        }

        .welcome-title {
            font-family: 'Pacifico', cursive;
            font-size: 2.5rem;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .welcome-subtitle {
            color: var(--dark-gray);
            font-size: 1.1rem;
        }

        /* Widget Cards */
        .widget-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(215, 38, 96, 0.08);
            transition: all 0.4s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .widget-card::before {
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

        .widget-card:hover::before {
            opacity: 0.15;
        }

        .widget-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(215, 38, 96, 0.2);
        }

        .widget-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-soft-pink);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: var(--primary-pink);
            position: relative;
            z-index: 1;
            transition: all 0.4s ease;
        }

        .widget-card:hover .widget-icon {
            transform: scale(1.1) rotate(5deg);
            background: var(--gradient-rose);
            color: white;
        }

        .widget-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .widget-description {
            color: var(--dark-gray);
            font-size: 0.95rem;
            line-height: 1.6;
            position: relative;
            z-index: 1;
            text-align: center;
        }

        /* Stats Cards */
        .stats-row {
            margin-top: 2rem;
        }

        .stat-card {
            background: var(--gradient-rose);
            border-radius: 16px;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 6px 20px rgba(215, 38, 96, 0.25);
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }

            .sidebar-modern {
                position: relative;
                top: 0;
                margin-bottom: 2rem;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .widget-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Modern Navigation -->
    <nav class="navbar-modern">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a href="index.php" class="navbar-brand-modern">
                    <span class="love-heart">❤️</span> DistantLove Admin
                </a>
                <div class="nav-user-info">
                    <span class="love-heart">❤️</span>
                    <span class="me-2">Welcome back, <strong class="user-name admin-user">Admin</strong></span>
                    <button id="logout-btn" class="btn-logout-modern">
                        <i class="fa fa-sign-out-alt me-1"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="sidebar-modern">
                    <h3 class="sidebar-title">Admin Panel</h3>
                    <nav class="nav-menu">
                        <a class="nav-item-modern active" href="index.php">
                            <i class="fa fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a class="nav-item-modern" href="category.php">
                            <i class="fa fa-list"></i>
                            <span>Categories</span>
                        </a>
                        <a class="nav-item-modern" href="brand.php">
                            <i class="fa fa-tag"></i>
                            <span>Brands</span>
                        </a>
                        <a class="nav-item-modern" href="product.php">
                            <i class="fa fa-box"></i>
                            <span>Products</span>
                        </a>
                        <a class="nav-item-modern" href="#">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Orders</span>
                        </a>
                        <a class="nav-item-modern" href="#">
                            <i class="fa fa-users"></i>
                            <span>Users</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="dashboard-card">
                    <!-- Welcome Section -->
                    <div class="welcome-section">
                        <h1 class="welcome-title">Welcome to DistantLove Admin</h1>
                        <p class="welcome-subtitle">Manage your online store with love and care ❤️</p>
                    </div>

                    <!-- Widget Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <a href="category.php" class="widget-card">
                                <div class="widget-icon">
                                    <i class="fa fa-list"></i>
                                </div>
                                <h3 class="widget-title">Categories</h3>
                                <p class="widget-description">Organize your products with love</p>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="product.php" class="widget-card">
                                <div class="widget-icon">
                                    <i class="fa fa-box"></i>
                                </div>
                                <h3 class="widget-title">Products</h3>
                                <p class="widget-description">Curate your special offerings</p>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="widget-card">
                                <div class="widget-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <h3 class="widget-title">Orders</h3>
                                <p class="widget-description">Manage customer happiness</p>
                            </a>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="row g-4 stats-row">
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="stat-number">1,247</div>
                                <div class="stat-label">Total Users</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fa fa-box"></i>
                                </div>
                                <div class="stat-number">342</div>
                                <div class="stat-label">Products</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <div class="stat-number">89</div>
                                <div class="stat-label">Pending Orders</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fa fa-chart-line"></i>
                                </div>
                                <div class="stat-number">GH₵ 45K</div>
                                <div class="stat-label">Monthly Revenue</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            function escapeHtml(s) {
                return $('<div>').text(s).html();
            }

            function updateAdminUI(data) {
                if (data.user_name) {
                    $('.admin-user').text(escapeHtml(data.user_name));
                }
            }

            function fetchSessionInfo() {
                $.getJSON('../actions/get_session_info.php')
                    .done(function(res) {
                        if (res.logged_in) {
                            updateAdminUI(res);
                        }
                    })
                    .fail(function(err) {
                        console.error('Failed to fetch session info:', err);
                    });
            }

            // Handle logout
            $('#logout-btn').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Logout?',
                    text: 'Are you sure you want to logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d72660',
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('../login/logout.php')
                            .always(function() {
                                window.location.href = '../login/login.php';
                            });
                    }
                });
            });

            // Initialize session check
            fetchSessionInfo();
        });
    </script>
</body>

</html>