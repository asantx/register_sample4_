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

		/* Menu Tray */
		.menu-tray-love {
			position: fixed;
			top: 20px;
			right: 20px;
			background: rgba(255, 255, 255, 0.97);
			border: 2px solid var(--primary-pink);
			border-radius: 16px;
			padding: 10px 18px;
			box-shadow: var(--shadow-lg);
			z-index: var(--z-fixed);
			display: flex;
			align-items: center;
			backdrop-filter: blur(10px);
			animation: slideInRight 0.5s ease-out;
		}

		.menu-tray-love .love-heart {
			color: var(--primary-pink);
			font-size: 2rem;
			animation: heartbeat 1.2s infinite;
		}

		.menu-tray-love a {
			margin-left: 12px;
			color: var(--primary-pink);
			border-color: var(--primary-pink);
			font-weight: 500;
			transition: all var(--transition-normal);
		}

		.menu-tray-love a:hover {
			background: var(--primary-pink);
			color: var(--white);
			transform: translateY(-2px);
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
		}

		@media (max-width: 576px) {
			.hero-title {
				font-size: 2rem;
				letter-spacing: 1px;
			}

			.hero-subtitle {
				font-size: 1rem;
			}

			.menu-tray-love {
				top: 10px;
				right: 10px;
				padding: 8px 12px;
			}

			.menu-tray-love .love-heart {
				font-size: 1.5rem;
			}

			.menu-tray-love a {
				font-size: 0.85rem;
				padding: 6px 12px;
			}
		}
	</style>
</head>

<body>
	<!-- Menu Tray -->
	<div class="menu-tray-love menu-tray">
		<span class="love-heart">&#10084;&#65039;</span>
		<!-- Buttons will be populated by js/index.js -->
	</div>

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
				<a href="views/shop.php" class="btn btn-distantlove btn-lg">
					<i class="fas fa-shopping-bag me-2"></i>Shop Now
				</a>
				<a href="login/register.php" class="btn btn-soft-pink btn-lg">
					<i class="fas fa-heart me-2"></i>Join Us
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

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="js/index.js"></script>

	<!-- Counter Animation -->
	<script>
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

		document.addEventListener('DOMContentLoaded', () => {
			const statsSection = document.querySelector('.stats-section');
			if (statsSection) {
				observer.observe(statsSection);
			}
		});
	</script>
</body>

</html>