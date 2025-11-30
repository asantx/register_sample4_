<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DistantLove - Join Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/distantlove-theme.css">
    <style>
        body {
            background: var(--gradient-primary);
            min-height: 100vh;
            font-family: var(--font-primary);
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar-distantlove-main {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            z-index: 1030;
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(215, 38, 96, 0.1);
        }

        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand-main {
            font-family: var(--font-heading);
            font-size: 2rem;
            color: var(--primary-pink);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all var(--transition-normal);
        }

        .navbar-brand-main .love-heart {
            font-size: 1.8rem;
            animation: heartbeat 1.2s infinite;
        }

        .navbar-links {
            display: flex;
            gap: 15px;
        }

        .nav-btn {
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all var(--transition-normal);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            box-shadow: var(--shadow-sm);
        }

        .nav-btn-active {
            background: var(--gradient-rose);
            color: white;
            border: 2px solid transparent;
        }

        .nav-btn-secondary {
            background: white;
            color: var(--primary-pink);
            border: 2px solid var(--primary-pink);
        }

        .nav-btn-secondary:hover {
            background: var(--gradient-soft-pink);
        }

        /* Register Container */
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem 2rem;
        }

        .register-card {
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            display: grid;
            grid-template-columns: 350px 1fr;
            overflow: hidden;
            animation: scaleIn 0.6s ease-out;
        }

        .register-side-decoration {
            background: var(--gradient-rose);
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .decoration-content h3 {
            font-family: var(--font-heading);
            font-size: 2rem;
            color: white;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }

        .decoration-content p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1rem;
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }

        .decoration-hearts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
            position: relative;
            z-index: 2;
        }

        .decoration-hearts i {
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        .decoration-hearts i:nth-child(1) { animation-delay: 0s; }
        .decoration-hearts i:nth-child(2) { animation-delay: 0.4s; }
        .decoration-hearts i:nth-child(3) { animation-delay: 0.8s; }
        .decoration-hearts i:nth-child(4) { animation-delay: 1.2s; }
        .decoration-hearts i:nth-child(5) { animation-delay: 1.6s; }
        .decoration-hearts i:nth-child(6) { animation-delay: 2s; }

        .register-content {
            padding: 3rem 3rem;
            max-height: 90vh;
            overflow-y: auto;
        }

        .register-header {
            margin-bottom: 2rem;
        }

        .register-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-pink-dark);
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            color: var(--dark-gray);
            font-size: 1rem;
            margin: 0;
        }

        /* Modern Input Fields */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group-modern {
            margin-bottom: 1.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-gray);
            font-size: 1.1rem;
            transition: all var(--transition-normal);
            z-index: 2;
        }

        .input-modern {
            width: 100%;
            padding: 12px 12px 12px 32px;
            border: none;
            border-bottom: 2px solid var(--gray);
            background: transparent;
            font-size: 0.95rem;
            color: var(--darker-gray);
            transition: all var(--transition-normal);
            outline: none;
        }

        .input-modern:focus {
            border-bottom-color: var(--primary-pink);
        }

        .input-modern:focus ~ .input-icon {
            color: var(--primary-pink);
        }

        .input-modern:focus ~ .label-modern,
        .input-modern:not(:placeholder-shown) ~ .label-modern {
            transform: translateY(-22px);
            font-size: 0.8rem;
            color: var(--primary-pink);
        }

        .label-modern {
            position: absolute;
            left: 32px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-gray);
            font-size: 0.95rem;
            transition: all var(--transition-normal);
            pointer-events: none;
            font-weight: 500;
        }

        .input-border {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-rose);
            transition: width var(--transition-normal);
        }

        .input-modern:focus ~ .input-border {
            width: 100%;
        }

        .toggle-password {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--dark-gray);
            cursor: pointer;
            padding: 5px 10px;
            transition: all var(--transition-normal);
        }

        .toggle-password:hover {
            color: var(--primary-pink);
        }

        /* Modern Submit Button */
        .btn-submit-modern {
            width: 100%;
            padding: 15px 30px;
            background: var(--gradient-rose);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all var(--transition-normal);
            box-shadow: var(--shadow-md);
            margin-top: 1rem;
        }

        .btn-submit-modern:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-3px);
        }

        .btn-submit-modern:active {
            transform: translateY(-1px);
        }

        .btn-icon {
            transition: transform var(--transition-normal);
        }

        .btn-submit-modern:hover .btn-icon {
            transform: translateX(5px);
        }

        .register-footer {
            margin-top: 1.5rem;
            text-align: center;
        }

        .register-footer p {
            color: var(--dark-gray);
            margin: 0;
            font-size: 0.95rem;
        }

        .link-accent {
            color: var(--primary-pink);
            font-weight: 600;
            text-decoration: none;
            transition: all var(--transition-normal);
        }

        .link-accent:hover {
            color: var(--primary-pink-dark);
            text-decoration: underline;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-primary);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }

        .loading-overlay.active {
            display: flex;
            opacity: 1;
        }

        .loading-content {
            text-align: center;
        }

        .loading-heart {
            font-size: 5rem;
            color: var(--primary-pink);
            margin-bottom: 2rem;
            animation: pulse-glow 1.5s infinite;
        }

        .loading-text {
            font-size: 1.3rem;
            color: white;
            font-weight: 500;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 968px) {
            .register-card {
                grid-template-columns: 1fr;
            }

            .register-side-decoration {
                padding: 2rem;
            }

            .register-content {
                padding: 2rem 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .navbar-brand-main {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar-distantlove-main">
        <div class="navbar-content">
            <a href="../index.php" class="navbar-brand-main">
                <span class="love-heart">&#10084;&#65039;</span>
                <span>DistantLove</span>
            </a>
            <div class="navbar-links">
                <a href="login.php" class="nav-btn nav-btn-secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
                <a href="register.php" class="nav-btn nav-btn-active">
                    <i class="fas fa-user-plus"></i>
                    <span>Register</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Register Container -->
    <div class="register-container">
        <div class="register-card">
            <div class="register-side-decoration">
                <div class="decoration-content">
                    <h3>Join DistantLove</h3>
                    <p>Start your journey with us and discover products that speak the language of love across any distance.</p>
                </div>
                <div class="decoration-hearts">
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-heart"></i>
                </div>
            </div>

            <div class="register-content">
                <div class="register-header">
                    <h2 class="register-title">Create Account</h2>
                    <p class="register-subtitle">Fill in your details to get started</p>
                </div>

                <form method="POST" action="" id="register-form">
                    <div class="form-row">
                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" class="input-modern" id="first_name" name="customer_name" placeholder=" " required>
                                <label for="first_name" class="label-modern">Full Name</label>
                                <span class="input-border"></span>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" class="input-modern" id="email" name="customer_email" placeholder=" " required>
                                <label for="email" class="label-modern">Email Address</label>
                                <span class="input-border"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" class="input-modern" id="contact" name="customer_contact" placeholder=" " required>
                                <label for="contact" class="label-modern">Phone Number</label>
                                <span class="input-border"></span>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input type="text" class="input-modern" id="country" name="customer_country" placeholder=" " required>
                                <label for="country" class="label-modern">Country</label>
                                <span class="input-border"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <div class="input-wrapper">
                            <i class="fas fa-home input-icon"></i>
                            <input type="text" class="input-modern" id="city" name="customer_city" placeholder=" " required>
                            <label for="city" class="label-modern">City</label>
                            <span class="input-border"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" class="input-modern" id="password" name="customer_pass" placeholder=" " required>
                                <label for="password" class="label-modern">Password</label>
                                <span class="input-border"></span>
                                <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                                    <i class="fas fa-eye" id="toggleIcon1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" class="input-modern" id="confirm_password" placeholder=" " required>
                                <label for="confirm_password" class="label-modern">Confirm Password</label>
                                <span class="input-border"></span>
                                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                                    <i class="fas fa-eye" id="toggleIcon2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-modern">
                        <span class="btn-text">Create Account</span>
                        <i class="fas fa-arrow-right btn-icon"></i>
                    </button>
                </form>

                <div class="register-footer">
                    <p>Already have an account? <a href="login.php" class="link-accent">Sign in here</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-heart">
                <i class="fas fa-heart"></i>
            </div>
            <p class="loading-text">Creating your account...</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/register.js"></script>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Override the register form submission
        $(document).ready(function() {
            $('#register-form').on('submit', function(e) {
                e.preventDefault();

                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();

                // Validate passwords match
                if (password !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Passwords Don\'t Match',
                        text: 'Please make sure your passwords match.',
                        confirmButtonColor: '#d72660'
                    });
                    return;
                }

                // Validate password length
                if (password.length < 6) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Weak Password',
                        text: 'Password must be at least 6 characters long.',
                        confirmButtonColor: '#d72660'
                    });
                    return;
                }

                // Show loading state on button
                const submitBtn = $('.btn-submit-modern');
                const originalText = submitBtn.html();
                submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Creating account...');
                submitBtn.prop('disabled', true);

                $.ajax({
                    url: '../actions/register_customer_action.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Show loading overlay
                            const overlay = document.getElementById('loadingOverlay');
                            overlay.classList.add('active');

                            // Show success message then redirect
                            setTimeout(function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Welcome to DistantLove!',
                                    text: 'Your account has been created successfully.',
                                    confirmButtonColor: '#d72660',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    window.location.href = 'login.php';
                                });
                            }, 1000);
                        } else {
                            submitBtn.html(originalText);
                            submitBtn.prop('disabled', false);
                            Swal.fire({
                                icon: 'error',
                                title: 'Registration Failed',
                                text: response.message || 'An error occurred during registration.',
                                confirmButtonColor: '#d72660'
                            });
                        }
                    },
                    error: function() {
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred. Please try again.',
                            confirmButtonColor: '#d72660'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
