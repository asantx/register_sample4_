<?php
require_once '../settings/core.php';
requireAdmin('../login/login.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DistantLove Admin - Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
            padding-top: 60px; /* room for fixed menu tray */
        }

        .menu-tray {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.95);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            box-shadow: 0 2px 12px rgba(215,38,96,0.08);
            backdrop-filter: blur(8px);
            z-index: 1000;
        }

        .menu-tray .user-name {
            color: #d72660;
            font-family: 'Pacifico', cursive;
        }

        .menu-tray .btn-outline-danger {
            border-color: #d72660;
            color: #d72660;
        }

        .menu-tray .btn-outline-danger:hover {
            background-color: #d72660;
            color: white;
        }

        .love-heart {
            color: #d72660;
            margin-right: 8px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .love-header {
            font-family: 'Pacifico', cursive;
            color: #d72660;
            font-size: 2.2rem;
            margin-top: 30px;
            letter-spacing: 2px;
            text-shadow: 0 2px 8px #fff3f6;
        }

        .love-heart {
            color: #d72660;
            font-size: 1.7rem;
            animation: heartbeat 1.2s infinite;
        }

        @keyframes heartbeat {

            0%,
            100% {
                transform: scale(1);
            }

            20% {
                transform: scale(1.2);
            }

            40% {
                transform: scale(0.95);
            }

            60% {
                transform: scale(1.1);
            }

            80% {
                transform: scale(0.98);
            }
        }

        .menu-tray-love {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.97);
            border: 2px solid #d72660;
            border-radius: 16px;
            padding: 10px 18px;
            box-shadow: 0 6px 24px rgba(215, 38, 96, 0.10);
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        .menu-tray-love a {
            margin-left: 12px;
            color: #d72660;
            border-color: #d72660;
            font-weight: 500;
        }

        .menu-tray-love a:hover {
            background: #d72660;
            color: #fff;
        }

        .category-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(215, 38, 96, 0.10);
            background: #fff;
        }

        .category-table th {
            background: #d72660;
            color: #fff;
            border-top: none;
        }

        .category-table td {
            vertical-align: middle;
        }

        .btn-add {
            background-color: #d72660;
            border-color: #d72660;
            color: #fff;
        }

        .btn-add:hover {
            background-color: #a8325e;
            border-color: #a8325e;
        }

        .btn-action {
            border-radius: 50px;
            padding: 0.3rem 0.9rem;
        }

        .admin-user {
            color: #a8325e;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="menu-tray">
        <span class="love-heart">&#10084;&#65039;</span>
        <!-- session / user info will be injected here -->
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
                <div class="love-header text-center">DistantLove Admin &mdash; Category Management</div>
                <div class="text-center mb-3 admin-info">Logged in as: <span class="admin-user">Admin</span></div>
                <div class="card category-card mb-4">
                    <div class="card-body">
                        <form id="add-category-form" class="row g-2 align-items-center justify-content-center">
                            <div class="col-md-7 col-lg-8">
                                <input id="category-name" name="name" class="form-control form-control-lg" placeholder="Category name" required />
                            </div>
                            <div class="col-md-3 col-lg-2">
                                <button id="add-category-btn" class="btn btn-add btn-lg w-100" type="submit">
                                    <i class="fa fa-plus"></i> Add Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card category-card">
                    <div class="card-body p-0">
                        <table class="table table-bordered category-table mb-0" id="categories-table">
                            <thead>
                                <tr>
                                    <th style="width:60px">ID</th>
                                    <th>Name</th>
                                    <th style="width:120px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="../index.php" class="btn btn-outline-secondary mt-4">Back to Home</a>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/category.js"></script>
</body>

</html>