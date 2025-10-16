<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DistantLove - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
        }
        .love-header {
            font-family: 'Pacifico', cursive;
            color: #d72660;
            font-size: 2.5rem;
            margin-top: 40px;
            letter-spacing: 2px;
            text-shadow: 0 2px 8px #fff3f6;
        }
        .love-heart {
            color: #d72660;
            font-size: 2rem;
            animation: heartbeat 1.2s infinite;
        }
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            20% { transform: scale(1.2); }
            40% { transform: scale(0.95); }
            60% { transform: scale(1.1); }
            80% { transform: scale(0.98); }
        }
        .menu-tray-love {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.97);
            border: 2px solid #d72660;
            border-radius: 16px;
            padding: 10px 18px;
            box-shadow: 0 6px 24px rgba(215,38,96,0.10);
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
        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 16px rgba(215,38,96,0.10);
        }
        .card-header {
            background: #d72660;
            color: #fff;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }
        .btn-custom {
            background-color: #d72660;
            border-color: #d72660;
            color: #fff;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #a8325e;
            border-color: #a8325e;
        }
        .highlight {
            color: #d72660;
            transition: color 0.3s;
        }
        .highlight:hover {
            color: #a8325e;
        }
        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .form-label i {
            margin-left: 5px;
            color: #d72660;
        }
    </style>
</head>
<body>
    <div class="menu-tray-love">
        <span class="love-heart">&#10084;&#65039;</span>
        <a href="register.php" class="btn btn-outline-danger btn-sm">Register</a>
        <a href="login.php" class="btn btn-outline-danger btn-sm">Login</a>
    </div>
    <div class="container login-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-7 col-lg-6">
                <div class="love-header text-center">DistantLove Login</div>
                <div class="card animate__animated animate__zoomIn mt-3">
                    <div class="card-header text-center highlight">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" class="mt-4" id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Don't have an account? <a href="register.php" class="highlight">Register here</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/login.js"></script>
    <script src="../js/index.js"></script>
</body>
</html>