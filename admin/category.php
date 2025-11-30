<?php
require_once '../settings/core.php';
requireAdmin('../login/login.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DistantLove Admin - Categories</title>
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

        /* Add Category Card */
        .add-category-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .form-control-modern {
            border-radius: 12px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control-modern:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(215, 38, 96, 0.15);
        }

        .btn-add-modern {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
        }

        .btn-add-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.4);
            color: white;
        }

        /* Category Table Card */
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

        /* Action Buttons */
        .btn-edit-modern {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-edit-modern:hover {
            background: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }

        .btn-delete-modern {
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

        .btn-delete-modern:hover {
            background: #da190b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
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

        /* Loading State */
        .loading-row {
            text-align: center;
            padding: 3rem;
            color: var(--dark-gray);
            font-style: italic;
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

            .add-category-card {
                padding: 1.5rem;
            }

            .table-modern thead th,
            .table-modern tbody td {
                padding: 1rem;
                font-size: 0.9rem;
            }

            .btn-edit-modern,
            .btn-delete-modern {
                padding: 6px 12px;
                font-size: 0.85rem;
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
                        <a class="nav-item-modern active" href="category.php">
                            <i class="fa fa-heart"></i>
                            <span>Date Ideas</span>
                        </a>
                        <a class="nav-item-modern" href="#">
                            <i class="fa fa-comments"></i>
                            <span>Counseling Sessions</span>
                        </a>
                        <a class="nav-item-modern" href="#">
                            <i class="fa fa-users-heart"></i>
                            <span>Community Posts</span>
                        </a>
                        <a class="nav-item-modern" href="#">
                            <i class="fa fa-shopping-cart"></i>
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
                    <h1 class="page-title">Date Ideas Management</h1>
                    <p class="page-subtitle">Create amazing date experiences for long-distance couples ❤️</p>
                </div>

                <!-- Date Idea Categories Overview -->
                <div class="add-category-card mb-4">
                    <h4 style="color: var(--primary-pink); margin-bottom: 1.5rem;">
                        <i class="fa fa-heart me-2"></i>Date Idea Categories
                    </h4>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3" style="background: var(--gradient-soft-pink); border-radius: 12px; text-align: center;">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎬</div>
                                <h6 style="color: var(--primary-pink); font-weight: 600;">Free Ideas</h6>
                                <p class="mb-0 text-muted small">Budget-friendly dates</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3" style="background: var(--gradient-soft-pink); border-radius: 12px; text-align: center;">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📹</div>
                                <h6 style="color: var(--primary-pink); font-weight: 600;">Video Dates</h6>
                                <p class="mb-0 text-muted small">Virtual connection</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3" style="background: var(--gradient-soft-pink); border-radius: 12px; text-align: center;">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎨</div>
                                <h6 style="color: var(--primary-pink); font-weight: 600;">Creative</h6>
                                <p class="mb-0 text-muted small">Artistic activities</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-3" style="background: var(--gradient-soft-pink); border-radius: 12px; text-align: center;">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎁</div>
                                <h6 style="color: var(--primary-pink); font-weight: 600;">Surprises</h6>
                                <p class="mb-0 text-muted small">Special moments</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Date Ideas -->
                <div class="table-card">
                    <div class="p-4" style="background: var(--gradient-rose); color: white;">
                        <h4 class="mb-0"><i class="fa fa-heart me-2"></i>Active Date Ideas</h4>
                    </div>
                    <table class="table table-modern" id="date-ideas-table">
                        <thead style="background: white; color: var(--primary-pink);">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Date Idea Title</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Cost</th>
                                <th style="width: 200px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Free Ideas -->
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-film me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Virtual Movie Night</strong>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: #e3f2fd; color: #1976d2;">Free</span></td>
                                <td><span class="badge" style="background: #f3e5f5; color: #7b1fa2;">Video</span></td>
                                <td><strong style="color: #4caf50;">Free</strong></td>
                                <td>
                                    <button class="btn-edit-modern me-2"><i class="fa fa-edit me-1"></i>Edit</button>
                                    <button class="btn-delete-modern"><i class="fa fa-trash me-1"></i>Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-gamepad me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Online Gaming Session</strong>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: #e3f2fd; color: #1976d2;">Free</span></td>
                                <td><span class="badge" style="background: #fff3e0; color: #e65100;">Creative</span></td>
                                <td><strong style="color: #4caf50;">Free</strong></td>
                                <td>
                                    <button class="btn-edit-modern me-2"><i class="fa fa-edit me-1"></i>Edit</button>
                                    <button class="btn-delete-modern"><i class="fa fa-trash me-1"></i>Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-utensils me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Cook Together</strong>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: #e3f2fd; color: #1976d2;">Free</span></td>
                                <td><span class="badge" style="background: #f3e5f5; color: #7b1fa2;">Video</span></td>
                                <td><strong style="color: #4caf50;">Free</strong></td>
                                <td>
                                    <button class="btn-edit-modern me-2"><i class="fa fa-edit me-1"></i>Edit</button>
                                    <button class="btn-delete-modern"><i class="fa fa-trash me-1"></i>Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-envelope-open-text me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Love Letter Exchange</strong>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: #fff3e0; color: #e65100;">Creative</span></td>
                                <td><span class="badge" style="background: #fce4ec; color: #c2185b;">Surprise</span></td>
                                <td><strong style="color: #ff9800;">GH₵ 80-160</strong></td>
                                <td>
                                    <button class="btn-edit-modern me-2"><i class="fa fa-edit me-1"></i>Edit</button>
                                    <button class="btn-delete-modern"><i class="fa fa-trash me-1"></i>Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-palette me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Virtual Art Class</strong>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: #fff3e0; color: #e65100;">Creative</span></td>
                                <td><span class="badge" style="background: #f3e5f5; color: #7b1fa2;">Video</span></td>
                                <td><strong style="color: #ff9800;">GH₵ 160-480</strong></td>
                                <td>
                                    <button class="btn-edit-modern me-2"><i class="fa fa-edit me-1"></i>Edit</button>
                                    <button class="btn-delete-modern"><i class="fa fa-trash me-1"></i>Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-gift me-2" style="color: var(--primary-pink);"></i>
                                        <strong>Care Package Surprise</strong>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: #fce4ec; color: #c2185b;">Surprise</span></td>
                                <td><span class="badge" style="background: #fce4ec; color: #c2185b;">Surprise</span></td>
                                <td><strong style="color: #ff9800;">GH₵ 320-800</strong></td>
                                <td>
                                    <button class="btn-edit-modern me-2"><i class="fa fa-edit me-1"></i>Edit</button>
                                    <button class="btn-delete-modern"><i class="fa fa-trash me-1"></i>Delete</button>
                                </td>
                            </tr>
                            <!-- Premium Ideas -->
                            <tr style="background: #fff9e6;">
                                <td>7</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-crown me-2" style="color: #ffa500;"></i>
                                        <strong>Virtual Escape Room</strong>
                                        <span class="badge ms-2" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: white;"><i class="fa fa-crown me-1"></i>Premium</span>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: #fff3e0; color: #e65100;">Creative</span></td>
                                <td><span class="badge" style="background: #f3e5f5; color: #7b1fa2;">Video</span></td>
                                <td><strong style="color: #ff9800;">GH₵ 480-960</strong></td>
                                <td>
                                    <button class="btn-edit-modern me-2"><i class="fa fa-edit me-1"></i>Edit</button>
                                    <button class="btn-delete-modern"><i class="fa fa-trash me-1"></i>Delete</button>
                                </td>
                            </tr>
                            <tr style="background: #fff9e6;">
                                <td>8</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-crown me-2" style="color: #ffa500;"></i>
                                        <strong>Personalized Scavenger Hunt</strong>
                                        <span class="badge ms-2" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: white;"><i class="fa fa-crown me-1"></i>Premium</span>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: #fce4ec; color: #c2185b;">Surprise</span></td>
                                <td><span class="badge" style="background: #fff3e0; color: #e65100;">Creative</span></td>
                                <td><strong style="color: #ff9800;">GH₵ 240-640</strong></td>
                                <td>
                                    <button class="btn-edit-modern me-2"><i class="fa fa-edit me-1"></i>Edit</button>
                                    <button class="btn-delete-modern"><i class="fa fa-trash me-1"></i>Delete</button>
                                </td>
                            </tr>
                            <tr style="background: #fff9e6;">
                                <td>9</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-crown me-2" style="color: #ffa500;"></i>
                                        <strong>Virtual Theater Experience</strong>
                                        <span class="badge ms-2" style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: white;"><i class="fa fa-crown me-1"></i>Premium</span>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: #f3e5f5; color: #7b1fa2;">Video</span></td>
                                <td><span class="badge" style="background: #f3e5f5; color: #7b1fa2;">Video</span></td>
                                <td><strong style="color: #ff9800;">GH₵ 320-1,600</strong></td>
                                <td>
                                    <button class="btn-edit-modern me-2"><i class="fa fa-edit me-1"></i>Edit</button>
                                    <button class="btn-delete-modern"><i class="fa fa-trash me-1"></i>Delete</button>
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
    <script src="../js/category.js"></script>
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