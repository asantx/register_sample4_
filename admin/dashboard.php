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
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
        }

        .sidebar {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(215, 38, 96, 0.07);
        }

        .sidebar a {
            color: #d72660;
            font-weight: 600;
        }

        .sidebar a.active {
            background: #fbe6ea;
            border-radius: 8px;
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            align-items: center;
        }

        .admin-name {
            color: #a8325e;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="menu-tray-love menu-tray">
        <span class="love-heart">&#10084;&#65039;</span>
        <!-- dynamic menu inserted by js/index.js -->
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="p-3 sidebar">
                    <h3 class="text-center" style="font-family: 'Pacifico', cursive; color:#d72660;">DistantLove Admin</h3>
                    <div class="text-center mb-2 admin-name">Logged in as: <span class="admin-user">Admin</span></div>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="category.php"> <i class="fa fa-list"></i> Categories</a>
                        <a class="nav-link" href="#"> <i class="fa fa-tag"></i> Brand</a>
                        <a class="nav-link" href="#"> <i class="fa fa-box"></i> Product</a>
                        <a class="nav-link" href="#"> <i class="fa fa-shopping-cart"></i> Orders</a>
                        <a class="nav-link" href="#"> <i class="fa fa-users"></i> Users</a>
                    </nav>
                    <div class="mt-3 text-center">
                        <!-- Legacy logout button removed to centralize logout in menu tray -->
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card" style="border-radius:14px; background: linear-gradient(135deg, #fff5f7 0%, #fff 100%); border: none; box-shadow: 0 8px 24px rgba(215, 38, 96, 0.08);">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3 style="color: #d72660; font-family: 'Pacifico', cursive;">Welcome to DistantLove Admin</h3>
                            <p class="text-muted">Manage your online store with love and care ❤️</p>
                        </div>
                        <div id="dashboard-widgets" class="row">
                            <div class="col-md-4 mb-3">
                                <div class="p-4" style="background:#fff; border-radius:12px; box-shadow:0 6px 18px rgba(215, 38, 96, 0.06); transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa fa-list" style="color: #d72660; font-size: 24px; margin-right: 12px;"></i>
                                        <h5 class="mb-0" style="color: #d72660;">Categories</h5>
                                    </div>
                                    <p class="text-muted mb-0">Organize your products with love</p>
                                    <a href="category.php" class="stretched-link"></a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-4" style="background:#fff; border-radius:12px; box-shadow:0 6px 18px rgba(215, 38, 96, 0.06); transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa fa-box" style="color: #d72660; font-size: 24px; margin-right: 12px;"></i>
                                        <h5 class="mb-0" style="color: #d72660;">Products</h5>
                                    </div>
                                    <p class="text-muted mb-0">Curate your special offerings</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-4" style="background:#fff; border-radius:12px; box-shadow:0 6px 18px rgba(215, 38, 96, 0.06); transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa fa-shopping-cart" style="color: #d72660; font-size: 24px; margin-right: 12px;"></i>
                                        <h5 class="mb-0" style="color: #d72660;">Orders</h5>
                                    </div>
                                    <p class="text-muted mb-0">Manage customer happiness</p>
                                </div>
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
                // Update the admin name in the sidebar
                if (data.user_name) {
                    $('.admin-user').text(escapeHtml(data.user_name));
                }

                // Update menu tray
                const tray = $('.menu-tray');
                if (tray.length) {
                    tray.html(`
                        <span class="me-2">Welcome, <strong class="user-name">${escapeHtml(data.user_name || 'Admin')}</strong>!</span>
                        <a href="#" id="logout-btn" class="btn btn-sm btn-outline-danger ms-2">Logout</a>
                    `);
                }
            }

            function fetchSessionInfo() {
                $.getJSON('../actions/get_session_info.php')
                    .done(function(res) {
                        if (!res.logged_in || res.user_role !== '2') {
                            // If not logged in or not admin, redirect to login
                            window.location.href = '../login/login.php';
                            return;
                        }
                        updateAdminUI(res);
                    })
                    .fail(function() {
                        // On failure, redirect to login
                        window.location.href = '../login/login.php';
                    });
            }

            // Handle logout
            $(document).on('click', '#logout-btn', function(e) {
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