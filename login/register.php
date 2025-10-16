

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DistantLove - Register</title>
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
    <div class="container register-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-7 col-lg-6">
                <div class="love-header text-center">DistantLove Registration</div>
                <div class="card animate__animated animate__zoomIn mt-3">
                    <div class="card-header text-center highlight">
                        <h4>Register</h4>
                    </div>
                    <div class="card-body">
                        <!-- ✅ Post form directly to same PHP file -->
                        <form method="POST" action="" class="mt-4" id="register-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <i class="fa fa-user"></i></label>
                                <input type="text" class="form-control" id="name" name="name" required maxlength="100">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control" id="email" name="email" required maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country <i class="fa fa-flag"></i></label>
                                <input type="text" class="form-control" id="country" name="country" required maxlength="30">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City <i class="fa fa-city"></i></label>
                                <input type="text" class="form-control" id="city" name="city" required maxlength="30">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number <i class="fa fa-phone"></i></label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" required maxlength="15">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Register As</label>
                                <div class="d-flex justify-content-start">
                                    <div class="form-check me-3 custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="smitten" value="1" checked>
                                        <label class="form-check-label" for="customer">Smitten</label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="cupid" value="2">
                                        <label class="form-check-label" for="cupid">Cupid</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Register</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Already have an account? <a href="login.php" class="highlight">Login here</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/app_root.js"></script>
    <script src="../js/register.js"></script>
</body>
</html>
