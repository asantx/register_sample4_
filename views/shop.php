<?php
session_start();
require_once '../settings/core.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DistantLove - Your Long Distance Relationship Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/distantlove-theme.css">
    <style>
        * {
            font-family: 'Inter', 'Roboto', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Modern Navigation */
        .navbar-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(215, 38, 96, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
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

        .nav-links-modern {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link-modern {
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link-modern::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-rose);
            transition: width 0.3s ease;
        }

        .nav-link-modern:hover::after {
            width: 100%;
        }

        .nav-link-modern:hover {
            color: var(--primary-pink);
        }

        /* Hero Section */
        .hero-section {
            padding: 4rem 0 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '💕';
            position: absolute;
            font-size: 20rem;
            opacity: 0.05;
            top: -80px;
            right: -50px;
            animation: float 6s ease-in-out infinite;
        }

        .hero-title {
            font-family: 'Pacifico', cursive;
            font-size: 3.5rem;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            position: relative;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: white;
            margin-bottom: 2rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: white;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        /* Services Grid */
        .services-container {
            padding: 3rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title {
            font-family: 'Pacifico', cursive;
            font-size: 2.5rem;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            color: white;
            font-size: 1.1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-soft-pink);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .service-card:hover::before {
            opacity: 0.15;
        }

        .service-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 60px rgba(215, 38, 96, 0.2);
        }

        .service-icon {
            width: 100px;
            height: 100px;
            background: var(--gradient-soft-pink);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 3rem;
            color: var(--primary-pink);
            position: relative;
            z-index: 1;
            transition: all 0.4s ease;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
            background: var(--gradient-rose);
            color: white;
        }

        .service-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .service-description {
            color: var(--dark-gray);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .service-features {
            text-align: left;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            color: var(--dark-gray);
        }

        .feature-item i {
            color: var(--primary-pink);
            font-size: 1.1rem;
        }

        .service-btn {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
            position: relative;
            z-index: 1;
            text-decoration: none;
            display: inline-block;
        }

        .service-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.4);
            color: white;
        }

        /* Testimonials Section */
        .testimonials-section {
            padding: 4rem 0;
            background: white;
            margin: 4rem 0;
            border-radius: 30px;
            box-shadow: 0 15px 50px rgba(215, 38, 96, 0.15);
        }

        .testimonial-card {
            background: var(--gradient-soft-pink);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .testimonial-quote {
            font-size: 3rem;
            color: var(--primary-pink);
            opacity: 0.3;
            position: absolute;
            top: 10px;
            left: 20px;
        }

        .testimonial-text {
            color: var(--dark-gray);
            font-style: italic;
            line-height: 1.7;
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-left: 2rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-rose);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .author-info h5 {
            margin: 0;
            color: var(--primary-pink);
            font-weight: 700;
        }

        .author-info p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--dark-gray);
        }

        /* How It Works Section */
        .how-it-works {
            padding: 4rem 0;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .step-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: var(--gradient-rose);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            box-shadow: 0 5px 20px rgba(215, 38, 96, 0.3);
        }

        .step-title {
            color: var(--primary-pink);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .step-description {
            color: var(--dark-gray);
            line-height: 1.6;
        }

        /* Premium Banner */
        .premium-banner {
            background: linear-gradient(135deg, #d72660 0%, #a8325e 100%);
            padding: 4rem 2rem;
            margin: 4rem 0;
            border-radius: 30px;
            box-shadow: 0 15px 50px rgba(215, 38, 96, 0.3);
            position: relative;
            overflow: hidden;
        }

        .premium-banner::before {
            content: '💕';
            position: absolute;
            font-size: 15rem;
            opacity: 0.1;
            top: -50px;
            right: -50px;
            animation: float 6s ease-in-out infinite;
        }

        .premium-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .premium-title {
            font-family: 'Pacifico', cursive;
            font-size: 2.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .premium-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
        }

        .premium-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
            text-align: left;
        }

        .premium-feature {
            display: flex;
            align-items: start;
            gap: 1rem;
        }

        .premium-feature i {
            color: white;
            font-size: 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .premium-feature-content h4 {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .premium-feature-content p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin: 0;
        }

        .premium-btn {
            background: white;
            color: var(--primary-pink);
            border: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }

        .premium-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(0, 0, 0, 0.3);
        }

        /* CTA Section */
        .cta-section {
            text-align: center;
            padding: 4rem 0;
        }

        .cta-title {
            font-family: 'Pacifico', cursive;
            font-size: 2.5rem;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .cta-btn-primary {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .cta-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(215, 38, 96, 0.4);
            color: white;
        }

        .cta-btn-secondary {
            background: white;
            color: var(--primary-pink);
            border: 2px solid white;
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .cta-btn-secondary:hover {
            background: transparent;
            color: white;
            transform: translateY(-3px);
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .service-card {
                padding: 2rem 1.5rem;
            }

            .premium-title {
                font-size: 2rem;
            }

            .nav-links-modern {
                flex-direction: column;
                gap: 1rem;
            }

            .hero-stats {
                gap: 1.5rem;
            }

            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Modern Navigation -->
    <nav class="navbar-modern">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a href="shop.php" class="navbar-brand-modern">
                    <span class="love-heart">❤️</span> DistantLove
                </a>
                <div class="nav-links-modern">
                    <a href="shop.php" class="nav-link-modern">
                        <i class="fas fa-heart"></i> Home
                    </a>
                    <a href="orders.php" class="nav-link-modern">
                        <i class="fas fa-box"></i> My Journey
                    </a>
                    <a href="../login/logout.php" class="nav-link-modern">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Welcome to DistantLove</h1>
            <p class="hero-subtitle">Your Complete Hub for Thriving Long Distance Relationships</p>

            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">12K+</div>
                    <div class="stat-label">Happy Couples</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1,247</div>
                    <div class="stat-label">Success Stories</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">87</div>
                    <div class="stat-label">Countries</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Container -->
    <div class="container services-container">
        <div class="section-header">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">Everything you need to make your long-distance love flourish</p>
        </div>

        <div class="row g-4">
            <!-- Counseling Sessions -->
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="service-title">Expert Counseling</h3>
                    <p class="service-description">
                        Connect with experienced relationship counselors who specialize in long-distance relationships.
                    </p>
                    <div class="service-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Licensed therapists</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Video & phone sessions</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Flexible scheduling</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>From GH₵ 1,520/hour</span>
                        </div>
                    </div>
                    <a href="counseling.php" class="service-btn">
                        <i class="fas fa-calendar-check"></i> Book a Session
                    </a>
                </div>
            </div>

            <!-- Date Ideas -->
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="service-title">Creative Date Ideas</h3>
                    <p class="service-description">
                        Discover fun and romantic date ideas perfect for couples separated by distance.
                    </p>
                    <div class="service-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>50+ unique ideas</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Step-by-step guides</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Free & premium options</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Budget-friendly</span>
                        </div>
                    </div>
                    <a href="date_ideas.php" class="service-btn">
                        <i class="fas fa-heart"></i> Explore Ideas
                    </a>
                </div>
            </div>

            <!-- Community -->
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="service-title">Supportive Community</h3>
                    <p class="service-description">
                        Join thousands of couples sharing experiences, tips, and lessons learned the hard way.
                    </p>
                    <div class="service-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Real success stories</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Expert advice</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Safe & supportive space</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Free to join</span>
                        </div>
                    </div>
                    <a href="community.php" class="service-btn">
                        <i class="fas fa-user-friends"></i> Join Community
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="container how-it-works">
        <div class="section-header">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Start your journey to a stronger relationship in 4 simple steps</p>
        </div>

        <div class="steps-container">
            <div class="step-card">
                <div class="step-number">1</div>
                <h4 class="step-title">Create Your Account</h4>
                <p class="step-description">
                    Sign up for free in less than a minute. No credit card required to get started.
                </p>
            </div>

            <div class="step-card">
                <div class="step-number">2</div>
                <h4 class="step-title">Choose Your Service</h4>
                <p class="step-description">
                    Browse our counseling sessions, date ideas, or join the community to connect with others.
                </p>
            </div>

            <div class="step-card">
                <div class="step-number">3</div>
                <h4 class="step-title">Book & Connect</h4>
                <p class="step-description">
                    Schedule sessions with expert counselors or start exploring creative date ideas instantly.
                </p>
            </div>

            <div class="step-card">
                <div class="step-number">4</div>
                <h4 class="step-title">Grow Together</h4>
                <p class="step-description">
                    Build stronger communication, deeper trust, and create lasting memories across any distance.
                </p>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="container">
        <div class="testimonials-section">
            <div class="section-header">
                <h2 class="section-title">Love Stories</h2>
                <p class="section-subtitle" style="color: var(--dark-gray);">Hear from couples who've strengthened their bond with DistantLove</p>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="testimonial-card">
                            <div class="testimonial-quote">"</div>
                            <p class="testimonial-text">
                                "The counseling sessions saved our relationship. We learned how to communicate better and now we're stronger than ever. Can't wait to close the distance next year!"
                            </p>
                            <div class="testimonial-author">
                                <div class="author-avatar">AK</div>
                                <div class="author-info">
                                    <h5>Ama & Kwame</h5>
                                    <p>Ghana 🇬🇭 - UK 🇬🇧 • 2 years LDR</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="testimonial-card">
                            <div class="testimonial-quote">"</div>
                            <p class="testimonial-text">
                                "The date ideas are so creative! We've tried virtual cooking together and online gaming nights. It makes the distance feel so much smaller. Highly recommend!"
                            </p>
                            <div class="testimonial-author">
                                <div class="author-avatar">FJ</div>
                                <div class="author-info">
                                    <h5>Fifi & Joel</h5>
                                    <p>Accra 🇬🇭 - Toronto 🇨🇦 • 1 year LDR</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="testimonial-card">
                            <div class="testimonial-quote">"</div>
                            <p class="testimonial-text">
                                "Being part of the community has been amazing. Reading other couples' stories and knowing we're not alone makes such a difference. Worth every cedi!"
                            </p>
                            <div class="testimonial-author">
                                <div class="author-avatar">EK</div>
                                <div class="author-info">
                                    <h5>Efua & Kofi</h5>
                                    <p>Kumasi 🇬🇭 - Dubai 🇦🇪 • 3 years LDR</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="testimonial-card">
                            <div class="testimonial-quote">"</div>
                            <p class="testimonial-text">
                                "Dr. Sarah helped us navigate time zones and cultural differences. We're now engaged and planning our wedding! DistantLove was a game-changer for us."
                            </p>
                            <div class="testimonial-author">
                                <div class="author-avatar">AB</div>
                                <div class="author-info">
                                    <h5>Abena & Ben</h5>
                                    <p>Ghana 🇬🇭 - USA 🇺🇸 • Closed the distance!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Premium Subscription Banner -->
    <div class="container">
        <div class="premium-banner">
            <div class="premium-content">
                <h2 class="premium-title">Upgrade to DistantLove Premium</h2>
                <p class="premium-subtitle">Unlock exclusive features and take your relationship to the next level</p>

                <div class="premium-features">
                    <div class="premium-feature">
                        <i class="fas fa-star"></i>
                        <div class="premium-feature-content">
                            <h4>Unlimited Community Access</h4>
                            <p>Full access to all community posts, stories, and exclusive discussions</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-envelope"></i>
                        <div class="premium-feature-content">
                            <h4>Weekly Expert Advice</h4>
                            <p>Personalized relationship tips from counselors delivered to your inbox</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-percent"></i>
                        <div class="premium-feature-content">
                            <h4>20% Off Sessions</h4>
                            <p>Exclusive discount on all counseling sessions and consultations</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-gift"></i>
                        <div class="premium-feature-content">
                            <h4>Premium Date Ideas</h4>
                            <p>Access to exclusive, creative date ideas and relationship challenges</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-calendar-alt"></i>
                        <div class="premium-feature-content">
                            <h4>Priority Booking</h4>
                            <p>Get first access to counselor schedules and premium time slots</p>
                        </div>
                    </div>
                    <div class="premium-feature">
                        <i class="fas fa-headset"></i>
                        <div class="premium-feature-content">
                            <h4>24/7 Support</h4>
                            <p>Round-the-clock priority support for any questions or concerns</p>
                        </div>
                    </div>
                </div>

                <button class="premium-btn" onclick="initiatePremiumSubscription()">
                    <i class="fas fa-crown"></i> Subscribe Now - GH₵ 320/month
                </button>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="container cta-section">
        <h2 class="cta-title">Ready to Strengthen Your Bond?</h2>
        <p style="color: white; font-size: 1.2rem; margin-bottom: 2rem;">Join thousands of couples making long-distance love work</p>
        <div class="cta-buttons">
            <?php if (isUserLoggedIn()): ?>
                <a href="counseling.php" class="cta-btn-primary">
                    <i class="fas fa-calendar-check"></i> Book Your First Session
                </a>
                <a href="community.php" class="cta-btn-secondary">
                    <i class="fas fa-users"></i> Explore Community
                </a>
            <?php else: ?>
                <a href="../login/register.php" class="cta-btn-primary">
                    <i class="fas fa-heart"></i> Get Started Free
                </a>
                <a href="../login/login.php" class="cta-btn-secondary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Subscription Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header" style="background: var(--gradient-rose); color: white; border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title"><i class="fas fa-crown"></i> Subscribe to Premium</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <div class="text-center mb-4">
                        <h3 style="color: var(--primary-pink); font-weight: 700;">GH₵ 320/month</h3>
                        <p style="color: var(--dark-gray);">Cancel anytime, no commitment</p>
                    </div>

                    <form id="subscriptionForm">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--dark-gray); font-weight: 600;">Card Number</label>
                            <input type="text" class="form-control" placeholder="1234 5678 9012 3456" style="border-radius: 10px; padding: 12px;">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: var(--dark-gray); font-weight: 600;">Expiry Date</label>
                                <input type="text" class="form-control" placeholder="MM/YY" style="border-radius: 10px; padding: 12px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: var(--dark-gray); font-weight: 600;">CVV</label>
                                <input type="text" class="form-control" placeholder="123" style="border-radius: 10px; padding: 12px;">
                            </div>
                        </div>
                        <button type="submit" class="service-btn w-100" style="padding: 15px;">
                            <i class="fas fa-lock"></i> Complete Subscription
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function initiatePremiumSubscription() {
            <?php if (!isUserLoggedIn()): ?>
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please login or register to subscribe to premium',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Register',
                    confirmButtonColor: '#d72660',
                    cancelButtonColor: '#a8325e'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../login/login.php';
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = '../login/register.php';
                    }
                });
                return;
            <?php endif; ?>

            // Show loading and go directly to payment
            Swal.fire({
                title: 'Initializing Payment...',
                html: `
                    <div style="padding: 20px; text-align: left;">
                        <p><strong>Subscription:</strong> Premium Monthly</p>
                        <p><strong>Amount:</strong> GH₵ 320/month</p>
                        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 10px;">
                            <strong style="color: #d72660;">Premium Benefits:</strong>
                            <ul style="margin: 10px 0 0 0; padding-left: 20px; font-size: 14px;">
                                <li>Unlimited community access</li>
                                <li>20% off all counseling sessions</li>
                                <li>Access to premium date ideas</li>
                                <li>Weekly expert advice emails</li>
                                <li>Priority booking</li>
                                <li>24/7 support</li>
                            </ul>
                        </div>
                    </div>
                `,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Use user's email from session
            const email = '<?php echo $_SESSION['user_email'] ?? ''; ?>';

            // Prepare subscription data
            const subscriptionData = {
                plan: 'monthly',
                duration: 30
            };

            // Initialize Paystack payment
            const paymentData = new FormData();
            paymentData.append('email', email);
            paymentData.append('amount', 320); // GH₵ 320/month
            paymentData.append('payment_type', 'premium');
            paymentData.append('service_data', JSON.stringify(subscriptionData));

            fetch('../actions/paystack_init_transaction.php', {
                method: 'POST',
                body: paymentData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Premium payment initialization response:', data);

                if (data.status && data.data && data.data.authorization_url) {
                    // Redirect directly to Paystack payment page
                    window.location.href = data.data.authorization_url;
                } else {
                    Swal.fire({
                        title: 'Payment Initialization Failed',
                        html: `
                            <p>${data.message || 'Unable to initialize payment. Please try again.'}</p>
                            <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 12px; text-align: left;">
                                <strong>Debug Info:</strong><br>
                                Status: ${data.status}<br>
                                ${data.message ? 'Message: ' + data.message : ''}
                            </div>
                        `,
                        icon: 'error',
                        confirmButtonColor: '#d72660'
                    });
                }
            })
            .catch(error => {
                console.error('Premium payment error:', error);
                Swal.fire({
                    title: 'Error Processing Payment',
                    html: `
                        <p>Unable to connect to payment gateway.</p>
                        <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 12px; text-align: left;">
                            <strong>Error details:</strong><br>
                            ${error.message || 'Network error occurred'}
                        </div>
                    `,
                    icon: 'error',
                    confirmButtonColor: '#d72660'
                });
            });
        }
    </script>
</body>

</html>
