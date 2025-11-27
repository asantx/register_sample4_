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
		/* Hero Section */
		.hero-section {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			position: relative;
			overflow: hidden;
		}

		.hero-content {
			text-align: center;
			z-index: 2;
			padding: 2rem;
		}

		.hero-title {
			font-family: 'Pacifico', cursive;
			color: var(--white);
			font-size: 4.5rem;
			margin-bottom: 1rem;
			letter-spacing: 3px;
			text-shadow: 0 4px 20px rgba(215, 38, 96, 0.4);
			animation: fadeInDown 1s ease-out;
		}

		.hero-subtitle {
			color: var(--white);
			font-size: 1.5rem;
			font-weight: 300;
			margin-bottom: 2rem;
			text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
			animation: fadeInUp 1s ease-out 0.3s both;
		}

		.hero-cta {
			animation: scaleIn 0.8s ease-out 0.6s both;
		}

		.hero-cta .btn {
			margin: 0 10px;
			padding: 15px 40px;
			font-size: 1.1rem;
			font-weight: 600;
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
			color: rgba(255, 255, 255, 0.3);
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

		/* Features Section */
		.features-section {
			padding: 5rem 0;
			background: var(--white);
		}

		.feature-card {
			text-align: center;
			padding: 2rem;
			border-radius: var(--radius-lg);
			transition: all var(--transition-normal);
			background: var(--gradient-soft-pink);
			margin-bottom: 2rem;
		}

		.feature-card:hover {
			transform: translateY(-10px);
			box-shadow: var(--shadow-hover);
		}

		.feature-icon {
			font-size: 3rem;
			color: var(--primary-pink);
			margin-bottom: 1rem;
			animation: float 3s ease-in-out infinite;
		}

		.feature-title {
			font-size: 1.5rem;
			font-weight: 600;
			color: var(--primary-pink-dark);
			margin-bottom: 0.5rem;
		}

		.feature-description {
			color: var(--dark-gray);
			line-height: 1.6;
		}

		/* Stats Section */
		.stats-section {
			background: var(--gradient-primary);
			padding: 4rem 0;
			color: var(--white);
		}

		.stat-item {
			text-align: center;
			padding: 2rem;
		}

		.stat-number {
			font-size: 3rem;
			font-weight: 700;
			color: var(--white);
			text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
		}

		.stat-label {
			font-size: 1.2rem;
			color: rgba(255, 255, 255, 0.9);
			margin-top: 0.5rem;
		}

		/* Navigation Bar */
		.navbar-distantlove-main {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			background: rgba(255, 255, 255, 0.95);
			backdrop-filter: blur(20px);
			z-index: var(--z-sticky);
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
			font-family: var(--font-heading);
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
			font-size: 2rem;
			color: var(--white);
			text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
		}

		/* Product Preview Section */
		.preview-section {
			padding: 5rem 0;
			background: white;
		}

		.preview-card {
			background: white;
			border-radius: var(--radius-lg);
			overflow: hidden;
			box-shadow: var(--shadow-md);
			transition: all var(--transition-normal);
			animation: fadeInUp 0.6s ease-out;
		}

		.preview-card:hover {
			transform: translateY(-10px);
			box-shadow: var(--shadow-hover);
		}

		.preview-image {
			width: 100%;
			height: 250px;
			background: var(--gradient-soft-pink);
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 4rem;
			color: var(--primary-pink);
			position: relative;
			overflow: hidden;
		}

		.preview-image i {
			transition: transform var(--transition-slow);
		}

		.preview-card:hover .preview-image i {
			transform: scale(1.2) rotate(10deg);
		}

		.preview-badge {
			position: absolute;
			top: 15px;
			right: 15px;
			background: var(--gradient-rose);
			color: white;
			padding: 8px 16px;
			border-radius: 25px;
			font-size: 0.85rem;
			font-weight: 600;
			box-shadow: var(--shadow-md);
		}

		.preview-info {
			padding: 1.5rem;
		}

		.preview-category {
			color: var(--primary-pink-dark);
			font-size: 0.85rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 1px;
			margin-bottom: 0.5rem;
		}

		.preview-title {
			font-size: 1.2rem;
			font-weight: 700;
			color: var(--darker-gray);
			margin-bottom: 0.5rem;
		}

		.preview-description {
			color: var(--dark-gray);
			font-size: 0.9rem;
			line-height: 1.6;
			margin-bottom: 1rem;
		}

		.preview-price {
			font-size: 1.5rem;
			font-weight: 700;
			color: var(--primary-pink);
			margin-bottom: 1rem;
		}

		.preview-rating {
			display: flex;
			align-items: center;
			gap: 5px;
			color: var(--accent-gold);
			margin-bottom: 1rem;
		}

		.cta-section {
			background: var(--gradient-primary);
			padding: 4rem 0;
			text-align: center;
			color: white;
		}

		.cta-title {
			font-family: var(--font-heading);
			font-size: 2.5rem;
			margin-bottom: 1rem;
			color: white;
		}

		.cta-text {
			font-size: 1.2rem;
			margin-bottom: 2rem;
			opacity: 0.95;
		}

		.cta-button {
			padding: 15px 50px;
			font-size: 1.2rem;
			background: white;
			color: var(--primary-pink);
			border: none;
			border-radius: 50px;
			font-weight: 700;
			box-shadow: var(--shadow-xl);
			transition: all var(--transition-normal);
			text-decoration: none;
			display: inline-block;
		}

		.cta-button:hover {
			transform: translateY(-5px);
			box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
			color: var(--primary-pink-dark);
		}

		/* Responsive */
		@media (max-width: 768px) {
			.hero-title {
				font-size: 3rem;
			}

			.hero-subtitle {
				font-size: 1.2rem;
			}

			.hero-cta .btn {
				padding: 12px 30px;
				font-size: 1rem;
				margin: 5px;
			}

			.stat-number {
				font-size: 2rem;
			}

			.feature-icon {
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
				font-size: 2rem;
				letter-spacing: 1px;
			}

			.hero-subtitle {
				font-size: 1rem;
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

			.cta-title {
				font-size: 2rem;
			}

			.cta-text {
				font-size: 1rem;
			}
		}
	</style>
</head>

<body>
	<!-- Navigation Bar -->
	<nav class="navbar-distantlove-main">
		<div class="navbar-content">
			<a href="index.php" class="navbar-brand-main">
				<span class="love-heart">&#10084;&#65039;</span>
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
			<div class="floating-heart">&#10084;&#65039;</div>
			<div class="floating-heart">&#10084;&#65039;</div>
			<div class="floating-heart">&#10084;&#65039;</div>
			<div class="floating-heart">&#10084;&#65039;</div>
			<div class="floating-heart">&#10084;&#65039;</div>
			<div class="floating-heart">&#10084;&#65039;</div>
			<div class="floating-heart">&#10084;&#65039;</div>
			<div class="floating-heart">&#10084;&#65039;</div>
			<div class="floating-heart">&#10084;&#65039;</div>
		</div>

		<!-- Hero Content -->
		<div class="hero-content">
			<h1 class="hero-title">DistantLove</h1>
			<p class="hero-subtitle">"Love knows no distance, it has no boundaries."</p>
			<div class="hero-cta">
				<a href="login/register.php" class="btn btn-distantlove btn-lg">
					<i class="fas fa-heart me-2"></i>Start Your Journey
				</a>
			</div>
		</div>

		<!-- Scroll Down Indicator -->
		<div class="scroll-indicator">
			<i class="fas fa-chevron-down"></i>
		</div>
	</section>

	<!-- Features Section -->
	<section class="features-section">
		<div class="container">
			<h2 class="text-center heading-fancy mb-5">Why Choose DistantLove?</h2>
			<div class="row">
				<div class="col-md-4">
					<div class="feature-card">
						<div class="feature-icon">
							<i class="fas fa-heart"></i>
						</div>
						<h3 class="feature-title">Thoughtful Gifts</h3>
						<p class="feature-description">Carefully curated products that speak the language of love, perfect for expressing your feelings across any distance.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="feature-card">
						<div class="feature-icon">
							<i class="fas fa-shipping-fast"></i>
						</div>
						<h3 class="feature-title">Fast Delivery</h3>
						<p class="feature-description">Swift and reliable shipping to ensure your love reaches its destination on time, every time.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="feature-card">
						<div class="feature-icon">
							<i class="fas fa-award"></i>
						</div>
						<h3 class="feature-title">Premium Quality</h3>
						<p class="feature-description">Only the finest products that match the depth of your emotions and the strength of your bond.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Product Preview Section -->
	<section class="preview-section">
		<div class="container">
			<h2 class="text-center heading-fancy mb-5">Experience the Love</h2>
			<p class="text-center" style="color: var(--dark-gray); font-size: 1.1rem; margin-bottom: 3rem; max-width: 700px; margin-left: auto; margin-right: auto;">
				Discover thoughtfully curated gifts that speak the language of love. Each product is selected to help you express your feelings, no matter the distance.
			</p>
			<div class="row g-4">
				<!-- Product Preview 1 -->
				<div class="col-md-4">
					<div class="preview-card">
						<div class="preview-image">
							<i class="fas fa-gift"></i>
							<div class="preview-badge">Popular</div>
						</div>
						<div class="preview-info">
							<div class="preview-category">Gift Sets</div>
							<h3 class="preview-title">Love Letter Bundle</h3>
							<p class="preview-description">A beautiful collection of handcrafted items perfect for expressing your deepest feelings.</p>
							<div class="preview-rating">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<span style="color: var(--dark-gray); margin-left: 8px;">(5.0)</span>
							</div>
							<div class="preview-price">₦12,500</div>
						</div>
					</div>
				</div>

				<!-- Product Preview 2 -->
				<div class="col-md-4">
					<div class="preview-card">
						<div class="preview-image">
							<i class="fas fa-heart"></i>
							<div class="preview-badge">Trending</div>
						</div>
						<div class="preview-info">
							<div class="preview-category">Personalized</div>
							<h3 class="preview-title">Custom Photo Frame</h3>
							<p class="preview-description">Capture your favorite memories in our premium handcrafted frames with personalized engravings.</p>
							<div class="preview-rating">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star-half-alt"></i>
								<span style="color: var(--dark-gray); margin-left: 8px;">(4.8)</span>
							</div>
							<div class="preview-price">₦8,900</div>
						</div>
					</div>
				</div>

				<!-- Product Preview 3 -->
				<div class="col-md-4">
					<div class="preview-card">
						<div class="preview-image">
							<i class="fas fa-rose"></i>
							<div class="preview-badge">New</div>
						</div>
						<div class="preview-info">
							<div class="preview-category">Premium</div>
							<h3 class="preview-title">Eternal Rose Bouquet</h3>
							<p class="preview-description">Preserved roses that last forever, symbolizing everlasting love and commitment.</p>
							<div class="preview-rating">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<span style="color: var(--dark-gray); margin-left: 8px;">(5.0)</span>
							</div>
							<div class="preview-price">₦15,750</div>
						</div>
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
						<div class="stat-number"><span class="counter" data-target="10000">0</span>+</div>
						<div class="stat-label">Happy Customers</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="stat-item">
						<div class="stat-number"><span class="counter" data-target="5000">0</span>+</div>
						<div class="stat-label">Products Delivered</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="stat-item">
						<div class="stat-number"><span class="counter" data-target="50">0</span>+</div>
						<div class="stat-label">Countries Reached</div>
					</div>
				</div>
				<div class="col-md-3 col-6">
					<div class="stat-item">
						<div class="stat-number"><span class="counter" data-target="99">0</span>%</div>
						<div class="stat-label">Satisfaction Rate</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- CTA Section -->
	<section class="cta-section">
		<div class="container">
			<h2 class="cta-title">Ready to Spread the Love?</h2>
			<p class="cta-text">Join thousands of happy customers who trust DistantLove to deliver their heartfelt messages.</p>
			<a href="login/register.php" class="cta-button">
				<i class="fas fa-heart me-2"></i>Create Your Account
			</a>
		</div>
	</section>

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Counter Animation & Smooth Scroll -->
	<script>
		// Smooth scroll for scroll indicator
		document.addEventListener('DOMContentLoaded', () => {
			const scrollIndicator = document.querySelector('.scroll-indicator');
			if (scrollIndicator) {
				scrollIndicator.addEventListener('click', () => {
					const featuresSection = document.querySelector('.features-section');
					if (featuresSection) {
						featuresSection.scrollIntoView({ behavior: 'smooth' });
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

			// Fade in preview cards on scroll
			const previewCards = document.querySelectorAll('.preview-card');
			if (previewCards.length > 0) {
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

				previewCards.forEach(card => {
					card.style.opacity = '0';
					card.style.transform = 'translateY(30px)';
					card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
					cardObserver.observe(card);
				});
			}
		});
	</script>
</body>

</html>