<?php
require_once __DIR__ . '/../settings/core.php';
requireAdmin('../login/login.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DistantLove Admin - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Reuse same styles as brand page for visual consistency */
        body { background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%); min-height:100vh; font-family:'Roboto',sans-serif; padding-top:60px; }
        .menu-tray{position:fixed;top:0;left:0;right:0;background:rgba(255,255,255,0.95);padding:12px 24px;display:flex;align-items:center;justify-content:flex-end;box-shadow:0 2px 12px rgba(215,38,96,0.08);backdrop-filter:blur(8px);z-index:1000}
        .menu-tray .user-name{color:#d72660;font-family:'Pacifico',cursive}
        .menu-tray .btn-outline-danger{border-color:#d72660;color:#d72660}
        .menu-tray .btn-outline-danger:hover{background-color:#d72660;color:white}
        .love-heart{color:#d72660;margin-right:8px;animation:pulse 1.5s infinite}
        @keyframes pulse{0%{transform:scale(1)}50%{transform:scale(1.1)}100%{transform:scale(1)}}
        .sidebar{background:#fff;border-radius:12px;box-shadow:0 6px 20px rgba(215, 38, 96, 0.07)}
        .sidebar a{color:#d72660;font-weight:600}
        .sidebar a.active{background:#fbe6ea;border-radius:8px}
        .love-header{font-family:'Pacifico',cursive;color:#d72660;font-size:2.2rem;margin-top:30px;letter-spacing:2px;text-shadow:0 2px 8px #fff3f6}
        .card-product{border:none;border-radius:18px;box-shadow:0 4px 16px rgba(215,38,96,0.10);background:#fff}
        .product-table th{background:#d72660;color:#fff;border-top:none}
        .product-table td{vertical-align:middle}
        .btn-add{background-color:#d72660;border-color:#d72660;color:#fff}
        .btn-add:hover{background-color:#a8325e;border-color:#a8325e}
        .btn-action{border-radius:50px;padding:0.3rem 0.9rem}
        .admin-user{color:#a8325e;font-weight:500}
        
        /* Additional styles for product management */
        .product-image-preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .image-upload-preview {
            width: 150px;
            height: 150px;
            border: 2px dashed #d72660;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }
        .image-upload-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .image-upload-preview .upload-icon {
            font-size: 2rem;
            color: #d72660;
        }
        .keywords-input {
            min-height: 100px;
        }
    </style>
</head>

<body>
    <div class="menu-tray">
        <span class="love-heart">&#10084;&#65039;</span>
        <!-- session / user info injected by JS -->
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="p-3 sidebar">
                    <h3 class="text-center" style="font-family: 'Pacifico', cursive; color:#d72660;">DistantLove Admin</h3>
                    <div class="text-center mb-2 admin-name">Logged in as: <span class="admin-user">Admin</span></div>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="category.php"> <i class="fa fa-list"></i> Categories</a>
                        <a class="nav-link" href="brand.php"> <i class="fa fa-tag"></i> Brand</a>
                        <a class="nav-link active" href="product.php"> <i class="fa fa-box"></i> Product</a>
                        <a class="nav-link" href="#"> <i class="fa fa-shopping-cart"></i> Orders</a>
                        <a class="nav-link" href="#"> <i class="fa fa-users"></i> Users</a>
                    </nav>
                </div>
            </div>
            <div class="col-md-9">
                <div class="love-header text-center">DistantLove Admin &mdash; Product Management</div>
                <div class="text-center mb-3 admin-info">Logged in as: <span class="admin-user">Admin</span></div>

                <div class="card card-product mb-4">
                    <div class="card-body">
                        <form id="add-product-form" class="row g-3">
                            <div class="col-md-6">
                                <div class="image-upload-preview mb-3" id="image-preview">
                                    <i class="fa fa-cloud-upload-alt upload-icon"></i>
                                </div>
                                <input type="file" id="product-image" name="image" class="form-control" accept="image/*" />
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-title" class="form-label">Product Title</label>
                                    <input type="text" id="product-title" name="title" class="form-control" required />
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product-category" class="form-label">Category</label>
                                            <select id="product-category" name="category_id" class="form-select" required>
                                                <option value="">Select Category</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product-brand" class="form-label">Brand</label>
                                            <select id="product-brand" name="brand_id" class="form-select" required>
                                                <option value="">Select Brand</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="product-price" class="form-label">Price</label>
                                    <input type="number" id="product-price" name="price" class="form-control" step="0.01" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="product-description" class="form-label">Description</label>
                                    <textarea id="product-description" name="description" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="product-keywords" class="form-label">Keywords (comma-separated)</label>
                                    <textarea id="product-keywords" name="keywords" class="form-control keywords-input" placeholder="Enter keywords separated by commas..."></textarea>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-add btn-lg px-5">
                                    <i class="fa fa-plus"></i> Add Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card card-product">
                    <div class="card-body p-0">
                        <table class="table table-bordered product-table mb-0" id="products-table">
                            <thead>
                                <tr>
                                    <th style="width:80px">Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th style="width:120px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <a href="../index.php" class="btn btn-outline-secondary mt-4">Back to Home</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/product.js"></script>
</body>

</html>