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

        /* Account Type Selection */
        .account-type-selector {
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .account-type-btn {
            flex: 1;
            padding: 15px 25px;
            border: 2px solid var(--gray);
            background: white;
            border-radius: 15px;
            cursor: pointer;
            transition: all var(--transition-normal);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .account-type-btn.active {
            border-color: var(--primary-pink);
            background: var(--gradient-soft-pink);
            color: var(--primary-pink);
        }

        .account-type-btn:hover {
            border-color: var(--primary-pink);
            transform: translateY(-2px);
        }

        .account-type-btn i {
            font-size: 1.3rem;
        }

        /* Partner Fields */
        .partner-fields {
            display: none;
            padding: 1.5rem;
            background: var(--gradient-soft-pink);
            border-radius: 15px;
            margin-bottom: 1.5rem;
        }

        .partner-fields.active {
            display: block;
            animation: fadeInUp 0.4s ease-out;
        }

        .partner-fields-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-pink);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Autocomplete Dropdown */
        .autocomplete-wrapper {
            position: relative;
        }

        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid var(--primary-pink);
            border-top: none;
            border-radius: 0 0 10px 10px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .autocomplete-dropdown.active {
            display: block;
        }

        .autocomplete-item {
            padding: 12px 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--darker-gray);
            border-bottom: 1px solid var(--light-gray);
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-item:hover {
            background: var(--gradient-soft-pink);
            color: var(--primary-pink);
        }

        .autocomplete-item.highlighted {
            background: var(--gradient-soft-pink);
            color: var(--primary-pink);
        }

        .no-results {
            padding: 12px 15px;
            color: var(--dark-gray);
            font-style: italic;
            text-align: center;
        }

        /* Admin Code Field */
        .admin-code-field {
            display: none;
            margin-bottom: 1.5rem;
        }

        .admin-code-field.active {
            display: block;
            animation: fadeInUp 0.4s ease-out;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 10px;
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
                    <p>Start your journey with us and strengthen your bond across any distance. Connect with counselors, explore date ideas, and join our community.</p>
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

                <!-- Account Type Selector -->
                <div class="account-type-selector">
                    <button type="button" class="account-type-btn active" data-type="individual">
                        <i class="fas fa-user"></i>
                        <span>Individual</span>
                    </button>
                    <button type="button" class="account-type-btn" data-type="couple">
                        <i class="fas fa-heart"></i>
                        <span>Couple</span>
                    </button>
                </div>

                <form method="POST" action="" id="register-form">
                    <input type="hidden" name="account_type" id="account_type" value="individual">
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

                        <div class="form-group-modern autocomplete-wrapper">
                            <div class="input-wrapper">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input type="text" class="input-modern" id="country" name="customer_country" placeholder=" " required autocomplete="off">
                                <label for="country" class="label-modern">Country</label>
                                <span class="input-border"></span>
                            </div>
                            <div class="autocomplete-dropdown" id="country-dropdown"></div>
                        </div>
                    </div>

                    <div class="form-group-modern autocomplete-wrapper">
                        <div class="input-wrapper">
                            <i class="fas fa-home input-icon"></i>
                            <input type="text" class="input-modern" id="city" name="customer_city" placeholder=" " required autocomplete="off" disabled>
                            <label for="city" class="label-modern">City (Select country first)</label>
                            <span class="input-border"></span>
                        </div>
                        <div class="autocomplete-dropdown" id="city-dropdown"></div>
                    </div>

                    <!-- Partner Fields (for Couple Registration) -->
                    <div class="partner-fields" id="partner-fields">
                        <div class="partner-fields-title">
                            <i class="fas fa-user-friends"></i>
                            <span>Partner's Information</span>
                        </div>

                        <div class="form-row">
                            <div class="form-group-modern">
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" class="input-modern" id="partner_name" name="partner_name" placeholder=" ">
                                    <label for="partner_name" class="label-modern">Partner's Full Name</label>
                                    <span class="input-border"></span>
                                </div>
                            </div>

                            <div class="form-group-modern">
                                <div class="input-wrapper">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" class="input-modern" id="partner_email" name="partner_email" placeholder=" ">
                                    <label for="partner_email" class="label-modern">Partner's Email</label>
                                    <span class="input-border"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group-modern autocomplete-wrapper">
                                <div class="input-wrapper">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                    <input type="text" class="input-modern" id="partner_country" name="partner_country" placeholder=" " autocomplete="off">
                                    <label for="partner_country" class="label-modern">Partner's Country</label>
                                    <span class="input-border"></span>
                                </div>
                                <div class="autocomplete-dropdown" id="partner-country-dropdown"></div>
                            </div>

                            <div class="form-group-modern autocomplete-wrapper">
                                <div class="input-wrapper">
                                    <i class="fas fa-home input-icon"></i>
                                    <input type="text" class="input-modern" id="partner_city" name="partner_city" placeholder=" " autocomplete="off" disabled>
                                    <label for="partner_city" class="label-modern">Partner's City</label>
                                    <span class="input-border"></span>
                                </div>
                                <div class="autocomplete-dropdown" id="partner-city-dropdown"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Secret Code Field -->
                    <div class="admin-code-field" id="admin-code-field">
                        <div class="form-group-modern">
                            <div class="input-wrapper">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password" class="input-modern" id="admin_code" name="admin_code" placeholder=" ">
                                <label for="admin_code" class="label-modern">
                                    Admin Secret Code
                                    <span class="admin-badge"><i class="fas fa-shield-alt"></i> Admin Access</span>
                                </label>
                                <span class="input-border"></span>
                                <button type="button" class="toggle-password" onclick="togglePassword('admin_code', 'toggleAdminIcon')">
                                    <i class="fas fa-eye" id="toggleAdminIcon"></i>
                                </button>
                            </div>
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

    <script>
        // Countries and Cities Data
        let countriesCitiesData = {};

        // Load countries and cities data
        fetch('../data/countries_cities.json')
            .then(response => response.json())
            .then(data => {
                countriesCitiesData = data;
            })
            .catch(error => console.error('Error loading countries data:', error));

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

        // Account Type Switcher
        document.querySelectorAll('.account-type-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.getAttribute('data-type');

                // Update active state
                document.querySelectorAll('.account-type-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Update hidden input
                document.getElementById('account_type').value = type;

                // Show/hide partner fields
                const partnerFields = document.getElementById('partner-fields');
                if (type === 'couple') {
                    partnerFields.classList.add('active');
                    // Make partner fields required
                    document.getElementById('partner_name').required = true;
                    document.getElementById('partner_email').required = true;
                    document.getElementById('partner_country').required = true;
                    document.getElementById('partner_city').required = true;
                } else {
                    partnerFields.classList.remove('active');
                    // Make partner fields optional
                    document.getElementById('partner_name').required = false;
                    document.getElementById('partner_email').required = false;
                    document.getElementById('partner_country').required = false;
                    document.getElementById('partner_city').required = false;
                }
            });
        });

        // Autocomplete functionality
        function setupAutocomplete(inputId, dropdownId, dataSource, isCity = false, relatedInput = null) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            let currentFocus = -1;

            input.addEventListener('input', function() {
                const value = this.value.trim();
                dropdown.innerHTML = '';
                currentFocus = -1;

                if (value === '') {
                    dropdown.classList.remove('active');
                    return;
                }

                let matches = [];
                if (isCity && relatedInput) {
                    const selectedCountry = document.getElementById(relatedInput).value;
                    if (selectedCountry && countriesCitiesData[selectedCountry]) {
                        matches = countriesCitiesData[selectedCountry].filter(city =>
                            city.toLowerCase().startsWith(value.toLowerCase())
                        );
                    }
                } else {
                    matches = dataSource.filter(item =>
                        item.toLowerCase().startsWith(value.toLowerCase())
                    );
                }

                if (matches.length === 0) {
                    dropdown.innerHTML = '<div class="no-results">No matches found</div>';
                    dropdown.classList.add('active');
                    return;
                }

                matches.forEach(match => {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-item';
                    div.textContent = match;
                    div.addEventListener('click', function() {
                        input.value = match;
                        dropdown.classList.remove('active');
                        dropdown.innerHTML = '';

                        // If this is a country selector, enable the city field
                        if (!isCity) {
                            const cityInput = inputId === 'country' ? document.getElementById('city') :
                                             inputId === 'partner_country' ? document.getElementById('partner_city') : null;
                            if (cityInput) {
                                cityInput.disabled = false;
                                cityInput.value = '';
                                const cityLabel = cityInput.nextElementSibling;
                                if (cityLabel && cityLabel.classList.contains('label-modern')) {
                                    cityLabel.textContent = inputId === 'country' ? 'City' : "Partner's City";
                                }
                            }
                        }
                    });
                    dropdown.appendChild(div);
                });

                dropdown.classList.add('active');
            });

            // Keyboard navigation
            input.addEventListener('keydown', function(e) {
                const items = dropdown.querySelectorAll('.autocomplete-item');
                if (e.keyCode === 40) { // Down arrow
                    currentFocus++;
                    addActive(items);
                } else if (e.keyCode === 38) { // Up arrow
                    currentFocus--;
                    addActive(items);
                } else if (e.keyCode === 13) { // Enter
                    e.preventDefault();
                    if (currentFocus > -1 && items[currentFocus]) {
                        items[currentFocus].click();
                    }
                }
            });

            function addActive(items) {
                if (!items || items.length === 0) return false;
                removeActive(items);
                if (currentFocus >= items.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = items.length - 1;
                items[currentFocus].classList.add('highlighted');
                items[currentFocus].scrollIntoView({ block: 'nearest' });
            }

            function removeActive(items) {
                items.forEach(item => item.classList.remove('highlighted'));
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target !== input) {
                    dropdown.classList.remove('active');
                }
            });
        }

        // Initialize autocomplete when data is loaded
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const countries = Object.keys(countriesCitiesData);

                // Setup autocomplete for main user
                setupAutocomplete('country', 'country-dropdown', countries, false);
                setupAutocomplete('city', 'city-dropdown', [], true, 'country');

                // Setup autocomplete for partner
                setupAutocomplete('partner_country', 'partner-country-dropdown', countries, false);
                setupAutocomplete('partner_city', 'partner-city-dropdown', [], true, 'partner_country');
            }, 500);

            // Check for admin code trigger (Ctrl + Shift + A)
            let adminUnlocked = false;
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.shiftKey && e.keyCode === 65) { // Ctrl + Shift + A
                    e.preventDefault();
                    adminUnlocked = !adminUnlocked;
                    const adminField = document.getElementById('admin-code-field');
                    if (adminUnlocked) {
                        adminField.classList.add('active');
                        Swal.fire({
                            icon: 'info',
                            title: 'Admin Mode Activated',
                            text: 'Enter the admin secret code to register as an administrator.',
                            confirmButtonColor: '#d72660',
                            timer: 3000
                        });
                    } else {
                        adminField.classList.remove('active');
                    }
                }
            });
        });

        // Form submission
        $(document).ready(function() {
            $('#register-form').on('submit', function(e) {
                e.preventDefault();

                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();
                const accountType = $('#account_type').val();
                const adminCode = $('#admin_code').val();

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

                // Validate couple registration
                if (accountType === 'couple') {
                    const partnerName = $('#partner_name').val();
                    const partnerEmail = $('#partner_email').val();
                    const partnerCountry = $('#partner_country').val();
                    const partnerCity = $('#partner_city').val();

                    if (!partnerName || !partnerEmail || !partnerCountry || !partnerCity) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Information',
                            text: 'Please fill in all partner information for couple registration.',
                            confirmButtonColor: '#d72660'
                        });
                        return;
                    }
                }

                // Admin code validation
                const ADMIN_SECRET_CODE = 'DISTANTLOVE2025ADMIN';
                if (adminCode && adminCode !== ADMIN_SECRET_CODE) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Admin Code',
                        text: 'The admin secret code you entered is incorrect.',
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
                        if (response.success || response.status === 'success') {
                            // Show loading overlay
                            const overlay = document.getElementById('loadingOverlay');
                            overlay.classList.add('active');

                            // Show success message then redirect
                            setTimeout(function() {
                                const accountTypeText = accountType === 'couple' ? 'couple account' :
                                                       adminCode === ADMIN_SECRET_CODE ? 'admin account' : 'account';
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Welcome to DistantLove!',
                                    text: `Your ${accountTypeText} has been created successfully.`,
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
