<?php
require_once __DIR__ . '/../settings/core.php';
requireAdmin('../login/login.php');

// In a production environment, this data would come from the database
// For now, we'll use sample data from orders.php counseling bookings
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DistantLove Admin - Counseling Sessions</title>
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

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-family: 'Pacifico', cursive;
            font-size: 2.5rem;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: white;
            font-size: 1.1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Sessions Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .table-modern {
            margin: 0;
        }

        .table-modern thead {
            background: var(--gradient-rose);
            color: white;
        }

        .table-modern thead th {
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border: none;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .table-modern tbody td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background: var(--gradient-soft-pink);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-confirmed {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-completed {
            background: #e3f2fd;
            color: #1565c0;
        }

        .status-cancelled {
            background: #ffebee;
            color: #c62828;
        }

        /* Action Buttons */
        .btn-view-modern {
            background: #2196f3;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-view-modern:hover {
            background: #1976d2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
            color: white;
        }

        .btn-cancel-modern {
            background: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-cancel-modern:hover {
            background: #da190b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card-small {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 6px 20px rgba(215, 38, 96, 0.08);
            text-align: center;
        }

        .stat-card-small i {
            font-size: 2rem;
            color: var(--primary-pink);
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 0.25rem;
        }

        .stat-label-small {
            font-size: 0.9rem;
            color: var(--dark-gray);
        }

        /* Back Button */
        .btn-back-modern {
            background: white;
            color: var(--primary-pink);
            border: 2px solid var(--primary-pink);
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back-modern:hover {
            background: var(--gradient-rose);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
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

            .page-title {
                font-size: 2rem;
            }

            .table-modern thead th,
            .table-modern tbody td {
                padding: 1rem;
                font-size: 0.9rem;
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
                        <a class="nav-item-modern" href="index.php">
                            <i class="fa fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a class="nav-item-modern" href="category.php">
                            <i class="fa fa-heart"></i>
                            <span>Date Ideas</span>
                        </a>
                        <a class="nav-item-modern" href="brand.php">
                            <i class="fa fa-user-md"></i>
                            <span>Counselors</span>
                        </a>
                        <a class="nav-item-modern active" href="product.php">
                            <i class="fa fa-comments"></i>
                            <span>Sessions</span>
                        </a>
                        <a class="nav-item-modern" href="#">
                            <i class="fa fa-calendar-check"></i>
                            <span>Bookings</span>
                        </a>
                        <a class="nav-item-modern" href="#">
                            <i class="fa fa-users"></i>
                            <span>Users</span>
                        </a>
                        <a class="nav-item-modern" href="#">
                            <i class="fa fa-crown"></i>
                            <span>Premium Members</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Counseling Sessions</h1>
                    <p class="page-subtitle">Manage and monitor all counseling bookings ❤️</p>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card-small">
                        <i class="fa fa-calendar-check"></i>
                        <div class="stat-value">47</div>
                        <div class="stat-label-small">Upcoming Sessions</div>
                    </div>
                    <div class="stat-card-small">
                        <i class="fa fa-check-circle"></i>
                        <div class="stat-value">128</div>
                        <div class="stat-label-small">Completed This Month</div>
                    </div>
                    <div class="stat-card-small">
                        <i class="fa fa-user-md"></i>
                        <div class="stat-value">3</div>
                        <div class="stat-label-small">Active Counselors</div>
                    </div>
                </div>

                <!-- Sessions Table -->
                <div class="table-card">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th style="width: 100px;">Booking ID</th>
                                <th>Counselor</th>
                                <th>Client</th>
                                <th>Date & Time</th>
                                <th>Type</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Upcoming Session -->
                            <tr>
                                <td><strong>#DL-001234</strong></td>
                                <td>
                                    <div>
                                        <i class="fa fa-user-circle me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Dr. Akosua Mensah</strong>
                                    </div>
                                </td>
                                <td>John & Sarah</td>
                                <td>
                                    <div><i class="fa fa-calendar me-1"></i>Dec 5, 2025</div>
                                    <div class="text-muted small"><i class="fa fa-clock me-1"></i>2:00 PM GMT</div>
                                </td>
                                <td><span class="badge bg-info">Video</span></td>
                                <td><strong>GH₵ 1,920</strong></td>
                                <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                <td>
                                    <button class="btn-view-modern me-1"><i class="fa fa-eye me-1"></i>View</button>
                                    <button class="btn-cancel-modern"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>#DL-001567</strong></td>
                                <td>
                                    <div>
                                        <i class="fa fa-user-circle me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Kwame Asante</strong>
                                    </div>
                                </td>
                                <td>Emily & Michael</td>
                                <td>
                                    <div><i class="fa fa-calendar me-1"></i>Dec 10, 2025</div>
                                    <div class="text-muted small"><i class="fa fa-clock me-1"></i>4:00 PM GMT</div>
                                </td>
                                <td><span class="badge bg-success">Phone</span></td>
                                <td><strong>GH₵ 1,520</strong></td>
                                <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                <td>
                                    <button class="btn-view-modern me-1"><i class="fa fa-eye me-1"></i>View</button>
                                    <button class="btn-cancel-modern"><i class="fa fa-times"></i></button>
                                </td>
                            </tr>

                            <!-- Completed Session -->
                            <tr>
                                <td><strong>#DL-000456</strong></td>
                                <td>
                                    <div>
                                        <i class="fa fa-user-circle me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Dr. Akosua Mensah</strong>
                                    </div>
                                </td>
                                <td>David & Lisa</td>
                                <td>
                                    <div><i class="fa fa-calendar me-1"></i>Nov 18, 2025</div>
                                    <div class="text-muted small"><i class="fa fa-clock me-1"></i>11:00 AM GMT</div>
                                </td>
                                <td><span class="badge bg-info">Video</span></td>
                                <td><strong>GH₵ 1,920</strong></td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td>
                                    <button class="btn-view-modern"><i class="fa fa-eye me-1"></i>View Notes</button>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>#DL-000123</strong></td>
                                <td>
                                    <div>
                                        <i class="fa fa-user-circle me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Kwame Asante</strong>
                                    </div>
                                </td>
                                <td>James & Maria</td>
                                <td>
                                    <div><i class="fa fa-calendar me-1"></i>Nov 12, 2025</div>
                                    <div class="text-muted small"><i class="fa fa-clock me-1"></i>5:00 PM GMT</div>
                                </td>
                                <td><span class="badge bg-warning text-dark">Chat</span></td>
                                <td><strong>GH₵ 1,520</strong></td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td>
                                    <button class="btn-view-modern"><i class="fa fa-eye me-1"></i>View Notes</button>
                                </td>
                            </tr>

                            <!-- Cancelled Session -->
                            <tr style="opacity: 0.7;">
                                <td><strong>#DL-000891</strong></td>
                                <td>
                                    <div>
                                        <i class="fa fa-user-circle me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Dr. Sarah Mitchell</strong>
                                    </div>
                                </td>
                                <td>Alex & Sophie</td>
                                <td>
                                    <div><i class="fa fa-calendar me-1"></i>Nov 28, 2025</div>
                                    <div class="text-muted small"><i class="fa fa-clock me-1"></i>10:00 AM GMT</div>
                                </td>
                                <td><span class="badge bg-info">Video</span></td>
                                <td><strong>GH₵ 1,760</strong></td>
                                <td><span class="status-badge status-cancelled">Cancelled</span></td>
                                <td>
                                    <button class="btn-view-modern"><i class="fa fa-eye me-1"></i>Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Back Button -->
                <div class="mt-4">
                    <a href="index.php" class="btn-back-modern">
                        <i class="fa fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
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

            // Handle view details
            $('.btn-view-modern').on('click', function() {
                const row = $(this).closest('tr');
                const bookingId = row.find('td:first strong').text();
                const counselor = row.find('td:nth-child(2) strong').text();

                Swal.fire({
                    title: 'Session Details',
                    html: `
                        <div style="text-align: left; padding: 1rem;">
                            <p><strong>Booking ID:</strong> ${bookingId}</p>
                            <p><strong>Counselor:</strong> ${counselor}</p>
                            <p>Full session details would be displayed here in production.</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#d72660'
                });
            });

            // Handle cancel session
            $('.btn-cancel-modern').on('click', function() {
                const row = $(this).closest('tr');
                const bookingId = row.find('td:first strong').text();

                Swal.fire({
                    title: 'Cancel Session?',
                    text: `Are you sure you want to cancel booking ${bookingId}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f44336',
                    confirmButtonText: 'Yes, Cancel Session',
                    cancelButtonText: 'No, Keep It'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Cancelled!',
                            text: 'The session has been cancelled.',
                            icon: 'success',
                            confirmButtonColor: '#d72660'
                        });
                        // In production, this would make an API call to update the database
                    }
                });
            });

            // Initialize session check
            fetchSessionInfo();
        });
    </script>
</body>
</html>
