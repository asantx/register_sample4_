<?php
require_once 'settings/core.php';

if (isUserLoggedIn()) {
	if (isAdmin()) {
		header("Location: admin/dashboard.php");
		exit();
	} else {
		header("Location: views/shop.php");
		exit();
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DistantLove - Where Love Knows No Distance</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="css/distantlove-theme.css">

	<style>
		* {
			font-family: 'Inter', 'Roboto', sans-serif;
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
			min-height: 100vh;
		}

		/* Hero Section */
		.hero-section {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			position: relative;
			overflow: hidden;
			padding-top: 80px;
		}

		.hero-content {
			text-align: center;
			z-index: 2;
			padding: 2rem;
			max-width: 900px;
		}

		.hero-title {
			font-family: 'Pacifico', cursive;
			color: var(--white);
			font-size: 5rem;
			margin-bottom: 1.5rem;
			letter-spacing: 3px;
			text-shadow: 0 4px 20px rgba(215, 38, 96, 0.4);
			animation: fadeInDown 1s ease-out;
		}

		.hero-subtitle {
			color: var(--white);
			font-size: 1.8rem;
			font-weight: 400;
			margin-bottom: 1rem;
			text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
			animation: fadeInUp 1s ease-out 0.3s both;
		}

		.hero-description {
			color: rgba(255, 255, 255, 0.95);
			font-size: 1.2rem;
			font-weight: 300;
			margin-bottom: 3rem;
			text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			animation: fadeInUp 1s ease-out 0.5s both;
		}

		.hero-cta {
			animation: scaleIn 0.8s ease-out 0.8s both;
			display: flex;
			gap: 1.5rem;
			justify-content: center;
			flex-wrap: wrap;
		}

		.hero-cta .btn {
			padding: 18px 45px;
			font-size: 1.2rem;
			font-weight: 600;
			border-radius: 50px;
			transition: all 0.3s ease;
			box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
		}

		.btn-primary-hero {
			background: white;
			color: var(--primary-pink);
			border: none;
		}

		.btn-primary-hero:hover {
			transform: translateY(-5px);
			box-shadow: 0 10px 35px rgba(0, 0, 0, 0.25);
			color: var(--primary-pink-dark);
		}

		.btn-secondary-hero {
			background: transparent;
			color: white;
			border: 3px solid white;
		}

		.btn-secondary-hero:hover {
			background: white;
			color: var(--primary-pink);
			transform: translateY(-5px);
			box-shadow: 0 10px 35px rgba(255, 255, 255, 0.3);
		}

		/* Floating Hearts Background */
		.floating-hearts {
			position: absolute;
			width: 100%;
			height: 100%;
			overflow: hidden;
			z-index: 1;
		}

		.floating-heart {
			position: absolute;
			font-size: 2rem;
			color: rgba(255, 255, 255, 0.25);
			animation: float-up 10s infinite ease-in;
		}

		@keyframes float-up {
			0% {
				bottom: -10%;
				opacity: 0.7;
				transform: translateX(0) rotate(0deg);
			}
			50% {
				opacity: 0.4;
			}
			100% {
				bottom: 110%;
				opacity: 0;
				transform: translateX(100px) rotate(360deg);
			}
		}

		.floating-heart:nth-child(1) { left: 10%; animation-duration: 8s; animation-delay: 0s; }
		.floating-heart:nth-child(2) { left: 20%; animation-duration: 12s; animation-delay: 2s; font-size: 1.5rem; }
		.floating-heart:nth-child(3) { left: 30%; animation-duration: 10s; animation-delay: 4s; }
		.floating-heart:nth-child(4) { left: 40%; animation-duration: 14s; animation-delay: 1s; font-size: 2.5rem; }
		.floating-heart:nth-child(5) { left: 50%; animation-duration: 9s; animation-delay: 3s; }
		.floating-heart:nth-child(6) { left: 60%; animation-duration: 11s; animation-delay: 5s; font-size: 1.8rem; }
		.floating-heart:nth-child(7) { left: 70%; animation-duration: 13s; animation-delay: 0.5s; }
		.floating-heart:nth-child(8) { left: 80%; animation-duration: 10s; animation-delay: 2.5s; font-size: 2.2rem; }
		.floating-heart:nth-child(9) { left: 90%; animation-duration: 12s; animation-delay: 4.5s; }

		/* Navigation Bar */
		.navbar-distantlove-main {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			background: rgba(255, 255, 255, 0.95);
			backdrop-filter: blur(20px);
			z-index: 1000;
			padding: 1rem 0;
			box-shadow: 0 2px 20px rgba(215, 38, 96, 0.1);
			animation: slideInDown 0.6s ease-out;
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
			font-family: 'Pacifico', cursive;
			font-size: 2rem;
			color: var(--primary-pink);
			text-decoration: none;
			display: flex;
			align-items: center;
			gap: 10px;
			transition: all var(--transition-normal);
		}

		.navbar-brand-main:hover {
			transform: scale(1.05);
			color: var(--primary-pink-dark);
		}

		.navbar-brand-main .love-heart {
			font-size: 1.8rem;
			animation: heartbeat 1.2s infinite;
		}

		.navbar-links {
			display: flex;
			gap: 15px;
			align-items: center;
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

		.nav-btn-login {
			background: white;
			color: var(--primary-pink);
			border: 2px solid var(--primary-pink);
		}

		.nav-btn-login:hover {
			background: var(--gradient-soft-pink);
			transform: translateY(-2px);
			box-shadow: var(--shadow-md);
		}

		.nav-btn-register {
			background: var(--gradient-rose);
			color: white;
			border: 2px solid transparent;
		}

		.nav-btn-register:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-hover);
		}

		/* Services Preview Section */
		.services-preview {
			padding: 6rem 0;
			background: white;
		}

		.section-header {
			text-align: center;
			margin-bottom: 4rem;
		}

		.section-title {
			font-family: 'Pacifico', cursive;
			font-size: 3rem;
			background: var(--gradient-rose);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
			margin-bottom: 1rem;
		}

		.section-subtitle {
			font-size: 1.2rem;
			color: var(--dark-gray);
			max-width: 700px;
			margin: 0 auto;
		}

		.service-preview-card {
			background: white;
			border-radius: 20px;
			padding: 3rem 2rem;
			text-align: center;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
			transition: all 0.4s ease;
			height: 100%;
			position: relative;
			overflow: hidden;
		}

		.service-preview-card::before {
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

		.service-preview-card:hover::before {
			opacity: 0.15;
		}

		.service-preview-card:hover {
			transform: translateY(-15px);
			box-shadow: 0 20px 60px rgba(215, 38, 96, 0.2);
		}

		.service-preview-icon {
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

		.service-preview-card:hover .service-preview-icon {
			transform: scale(1.1) rotate(5deg);
			background: var(--gradient-rose);
			color: white;
		}

		.service-preview-title {
			font-size: 1.8rem;
			font-weight: 700;
			color: var(--primary-pink);
			margin-bottom: 1rem;
			position: relative;
			z-index: 1;
		}

		.service-preview-description {
			color: var(--dark-gray);
			font-size: 1rem;
			line-height: 1.6;
			margin-bottom: 2rem;
			position: relative;
			z-index: 1;
		}

		/* Stats Section */
		.stats-section {
			background: var(--gradient-primary);
			padding: 5rem 0;
			color: var(--white);
		}

		.stat-item {
			text-align: center;
			padding: 2rem;
		}

		.stat-number {
			font-size: 3.5rem;
			font-weight: 700;
			color: var(--white);
			text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
		}

		.stat-label {
			font-size: 1.3rem;
			color: rgba(255, 255, 255, 0.95);
			margin-top: 0.5rem;
			font-weight: 500;
		}

		/* Testimonials Section */
		.testimonials-section {
			padding: 6rem 0;
			background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
		}

		.testimonial-card {
			background: white;
			border-radius: 20px;
			padding: 2.5rem;
			margin-bottom: 1.5rem;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
			position: relative;
			transition: all 0.3s ease;
		}

		.testimonial-card:hover {
			transform: translateY(-10px);
			box-shadow: 0 15px 50px rgba(215, 38, 96, 0.2);
		}

		.testimonial-quote {
			font-size: 3.5rem;
			color: var(--primary-pink);
			opacity: 0.2;
			position: absolute;
			top: 15px;
			left: 25px;
			font-family: 'Georgia', serif;
		}

		.testimonial-text {
			color: var(--dark-gray);
			font-style: italic;
			line-height: 1.8;
			margin-bottom: 1.5rem;
			padding-left: 2rem;
			font-size: 1.05rem;
		}

		.testimonial-author {
			display: flex;
			align-items: center;
			gap: 1rem;
			padding-left: 2rem;
		}

		.author-avatar {
			width: 55px;
			height: 55px;
			border-radius: 50%;
			background: var(--gradient-rose);
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-weight: 700;
			font-size: 1.3rem;
		}

		.author-info h5 {
			margin: 0;
			color: var(--primary-pink);
			font-weight: 700;
		}

		.author-info p {
			margin: 0;
			font-size: 0.95rem;
			color: var(--dark-gray);
		}

		/* CTA Section */
		.cta-section {
			background: white;
			padding: 6rem 0;
			text-align: center;
		}

		.cta-title {
			font-family: 'Pacifico', cursive;
			font-size: 3rem;
			background: var(--gradient-rose);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
			margin-bottom: 1.5rem;
		}

		.cta-text {
			font-size: 1.3rem;
			color: var(--dark-gray);
			margin-bottom: 3rem;
			max-width: 700px;
			margin-left: auto;
			margin-right: auto;
		}

		.cta-button {
			padding: 18px 50px;
			font-size: 1.3rem;
			background: var(--gradient-rose);
			color: white;
			border: none;
			border-radius: 50px;
			font-weight: 700;
			box-shadow: 0 6px 25px rgba(215, 38, 96, 0.3);
			transition: all var(--transition-normal);
			text-decoration: none;
			display: inline-block;
		}

		.cta-button:hover {
			transform: translateY(-5px);
			box-shadow: 0 10px 35px rgba(215, 38, 96, 0.4);
			color: white;
		}

		/* Scroll Down Indicator */
		.scroll-indicator {
			position: absolute;
			bottom: 30px;
			left: 50%;
			transform: translateX(-50%);
			z-index: 2;
			animation: float 2s ease-in-out infinite;
		}

		.scroll-indicator i {
			font-size: 2.5rem;
			color: var(--white);
			text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
		}

		/* Animations */
		@keyframes fadeInDown {
			from {
				opacity: 0;
				transform: translateY(-30px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes fadeInUp {
			from {
				opacity: 0;
				transform: translateY(30px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes scaleIn {
			from {
				opacity: 0;
				transform: scale(0.9);
			}
			to {
				opacity: 1;
				transform: scale(1);
			}
		}

		@keyframes slideInDown {
			from {
				transform: translateY(-100%);
			}
			to {
				transform: translateY(0);
			}
		}

		@keyframes heartbeat {
			0%, 100% { transform: scale(1); }
			25% { transform: scale(1.15); }
			50% { transform: scale(1); }
		}

		@keyframes float {
			0%, 100% { transform: translateY(0) translateX(-50%); }
			50% { transform: translateY(-15px) translateX(-50%); }
		}

		/* Responsive */
		@media (max-width: 768px) {
			.hero-title {
				font-size: 3.5rem;
			}

			.hero-subtitle {
				font-size: 1.4rem;
			}

			.hero-description {
				font-size: 1.1rem;
			}

			.hero-cta .btn {
				padding: 15px 35px;
				font-size: 1.1rem;
			}

			.stat-number {
				font-size: 2.5rem;
			}

			.section-title {
				font-size: 2.5rem;
			}

			.navbar-content {
				padding: 0 1rem;
			}

			.navbar-brand-main {
				font-size: 1.5rem;
			}

			.nav-btn {
				padding: 8px 18px;
				font-size: 0.85rem;
			}
		}

		@media (max-width: 576px) {
			.hero-title {
				font-size: 2.5rem;
				letter-spacing: 1px;
			}

			.hero-subtitle {
				font-size: 1.2rem;
			}

			.hero-description {
				font-size: 1rem;
			}

			.hero-cta {
				flex-direction: column;
				gap: 1rem;
			}

			.hero-cta .btn {
				width: 100%;
				max-width: 300px;
			}

			.navbar-brand-main {
				font-size: 1.3rem;
			}

			.navbar-links {
				gap: 8px;
			}

			.nav-btn {
				padding: 6px 15px;
				font-size: 0.8rem;
			}

			.section-title {
				font-size: 2rem;
			}

			.cta-title {
				font-size: 2.2rem;
			}

			.cta-text {
				font-size: 1.1rem;
			}
		}
	</style>
</head>

<body>
	<!-- Navigation Bar -->
	<nav class="navbar-distantlove-main">
		<div class="navbar-content">
			<a href="index.php" class="navbar-brand-main">
				<span class="love-heart">❤️</span>
				<span>DistantLove</span>
			</a>
			<div class="navbar-links">
				<a href="login/login.php" class="nav-btn nav-btn-login">
					<i class="fas fa-sign-in-alt"></i>
					<span>Login</span>
				</a>
				<a href="login/register.php" class="nav-btn nav-btn-register">
					<i class="fas fa-heart"></i>
					<span>Join Us</span>
				</a>
			</div>
		</div>
	</nav>

	<!-- Hero Section -->
	<section class="hero-section">
		<!-- Floating Hearts Background -->
		<div class="floating-hearts">
			<div class="floating-heart">❤️</div>
			<div class="floating-heart">❤️</div>
			<div class="floating-heart">❤️</div>
			<div class="floating-heart">❤️</div>
			<div class="floating-heart">❤️</div>
			<div class="floating-heart">❤️</div>
			<div class="floating-heart">❤️</div>
			<div class="floating-heart">❤️</div>
			<div class="floating-heart">❤️</div>
		</div>

		<!-- Hero Content -->
		<div class="hero-content">
			<h1 class="hero-title">DistantLove</h1>
			<p class="hero-subtitle">Your Complete Hub for Thriving Long Distance Relationships</p>
			<p class="hero-description">
				Expert counseling, creative date ideas, and a supportive community to help your love flourish across any distance
			</p>
			<div class="hero-cta">
				<a href="login/register.php" class="btn btn-primary-hero">
					<i class="fas fa-heart me-2"></i>Start Your Journey
				</a>
				<a href="#services" class="btn btn-secondary-hero">
					<i class="fas fa-info-circle me-2"></i>Learn More
				</a>
			</div>
		</div>

		<!-- Scroll Down Indicator -->
		<div class="scroll-indicator">
			<i class="fas fa-chevron-down"></i>
		</div>
	</section>

	<!-- Services Preview Section -->
	<section class="services-preview" id="services">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title">What We Offer</h2>
				<p class="section-subtitle">Everything you need to make your long-distance relationship stronger, happier, and more connected</p>
			</div>

			<div class="row g-4">
				<div class="col-md-4">
					<div class="service-preview-card">
						<div class="service-preview-icon">
							<i class="fas fa-comments"></i>
						</div>
						<h3 class="service-preview-title">Expert Counseling</h3>
						<p class="service-preview-description">
							Connect with licensed relationship therapists who specialize in long-distance relationships. Get personalized guidance and support.
						</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="service-preview-card">
						<div class="service-preview-icon">
							<i class="fas fa-lightbulb"></i>
						</div>
						<h3 class="service-preview-title">Creative Date Ideas</h3>
						<p class="service-preview-description">
							Discover 50+ fun and romantic virtual date ideas. Keep the spark alive with activities designed for couples separated by distance.
						</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="service-preview-card">
						<div class="service-preview-icon">
							<i class="fas fa-users"></i>
						</div>
						<h3 class="service-preview-title">Supportive Community</h3>
						<p class="service-preview-description">
							Join thousands of couples sharing their journeys. Find inspiration, advice, and support from people who truly understand.
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Stats Section -->
	<section class="stats-section">
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-6">
					<div class="stat-item">
						<div class="stat-number"><span class="counter" data-target="12000">0</span>+</div>
						<div class="stat-label">Happy Couples</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="stat-item">
						<div class="stat-number"><span class="counter" data-target="1247">0</span></div>
						<div class="stat-label">Success Stories</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="stat-item">
						<div class="stat-number"><span class="counter" data-target="87">0</span>+</div>
						<div class="stat-label">Countries</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="stat-item">
						<div class="stat-number">24/7</div>
						<div class="stat-label">Support Available</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Testimonials Section -->
	<section class="testimonials-section">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title" style="color: white;">Real Stories, Real Love</h2>
				<p class="section-subtitle" style="color: rgba(255, 255, 255, 0.95);">Hear from couples who've strengthened their bond with DistantLove</p>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="testimonial-card">
						<div class="testimonial-quote">"</div>
						<p class="testimonial-text">
							The counseling sessions saved our relationship. We learned how to communicate better and now we're stronger than ever. Can't wait to close the distance next year!
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
							The date ideas are so creative! We've tried virtual cooking together and online gaming nights. It makes the distance feel so much smaller. Highly recommend!
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
							Being part of the community has been amazing. Reading other couples' stories and knowing we're not alone makes such a difference. Worth every cedi!
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
							Dr. Sarah helped us navigate time zones and cultural differences. We're now engaged and planning our wedding! DistantLove was a game-changer for us.
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
	</section>

	<!-- CTA Section -->
	<section class="cta-section">
		<div class="container">
			<h2 class="cta-title">Ready to Strengthen Your Bond?</h2>
			<p class="cta-text">Join thousands of couples making long-distance love work. Start your journey with DistantLove today.</p>
			<a href="login/register.php" class="cta-button">
				<i class="fas fa-heart me-2"></i>Create Your Free Account
			</a>
		</div>
	</section>

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Counter Animation & Smooth Scroll -->
	<script>
		// Smooth scroll for scroll indicator and learn more button
		document.addEventListener('DOMContentLoaded', () => {
			const scrollIndicator = document.querySelector('.scroll-indicator');
			const learnMoreBtn = document.querySelector('a[href="#services"]');

			if (scrollIndicator) {
				scrollIndicator.addEventListener('click', () => {
					const servicesSection = document.querySelector('.services-preview');
					if (servicesSection) {
						servicesSection.scrollIntoView({ behavior: 'smooth' });
					}
				});
			}

			if (learnMoreBtn) {
				learnMoreBtn.addEventListener('click', (e) => {
					e.preventDefault();
					const servicesSection = document.querySelector('.services-preview');
					if (servicesSection) {
						servicesSection.scrollIntoView({ behavior: 'smooth' });
					}
				});
			}

			// Animated counter for stats
			function animateCounter(element) {
				const target = parseInt(element.getAttribute('data-target'));
				const duration = 2000;
				const step = target / (duration / 16);
				let current = 0;

				const timer = setInterval(() => {
					current += step;
					if (current >= target) {
						element.textContent = target.toLocaleString();
						clearInterval(timer);
					} else {
						element.textContent = Math.floor(current).toLocaleString();
					}
				}, 16);
			}

			// Intersection Observer for counter animation
			const observerOptions = {
				threshold: 0.5
			};

			const observer = new IntersectionObserver((entries) => {
				entries.forEach(entry => {
					if (entry.isIntersecting) {
						const counters = entry.target.querySelectorAll('.counter');
						counters.forEach(counter => animateCounter(counter));
						observer.unobserve(entry.target);
					}
				});
			}, observerOptions);

			const statsSection = document.querySelector('.stats-section');
			if (statsSection) {
				observer.observe(statsSection);
			}

			// Fade in service cards on scroll
			const serviceCards = document.querySelectorAll('.service-preview-card');
			if (serviceCards.length > 0) {
				const cardObserver = new IntersectionObserver((entries) => {
					entries.forEach((entry, index) => {
						if (entry.isIntersecting) {
							setTimeout(() => {
								entry.target.style.opacity = '1';
								entry.target.style.transform = 'translateY(0)';
							}, index * 150);
							cardObserver.unobserve(entry.target);
						}
					});
				}, { threshold: 0.1 });

				serviceCards.forEach(card => {
					card.style.opacity = '0';
					card.style.transform = 'translateY(30px)';
					card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
					cardObserver.observe(card);
				});
			}

			// Fade in testimonial cards on scroll
			const testimonialCards = document.querySelectorAll('.testimonial-card');
			if (testimonialCards.length > 0) {
				const testimonialObserver = new IntersectionObserver((entries) => {
					entries.forEach((entry, index) => {
						if (entry.isIntersecting) {
							setTimeout(() => {
								entry.target.style.opacity = '1';
								entry.target.style.transform = 'translateY(0)';
							}, index * 150);
							testimonialObserver.unobserve(entry.target);
						}
					});
				}, { threshold: 0.1 });

				testimonialCards.forEach(card => {
					card.style.opacity = '0';
					card.style.transform = 'translateY(30px)';
					card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
					testimonialObserver.observe(card);
				});
			}
		});
	</script>
</body>

</html>
