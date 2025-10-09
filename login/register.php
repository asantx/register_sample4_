

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        /* your same CSS styles here */
    </style>
</head>

<body>
    <div class="container register-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
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
                                        <input class="form-check-input" type="radio" name="role" id="customer" value="1" checked>
                                        <label class="form-check-label" for="customer">Customer</label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="owner" value="2">
                                        <label class="form-check-label" for="owner">Restaurant Owner</label>
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
    <script src="../js/register.js"></script>
</body>
</html>
