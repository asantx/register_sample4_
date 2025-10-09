<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>DistantLove</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
	<style>
		body {
			background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 100%);
			min-height: 100vh;
			font-family: 'Roboto', sans-serif;
		}
		.love-header {
			font-family: 'Pacifico', cursive;
			color: #d72660;
			font-size: 3rem;
			margin-top: 60px;
			letter-spacing: 2px;
			text-shadow: 0 2px 8px #fff3f6;
		}
		.love-heart {
			color: #d72660;
			font-size: 2.5rem;
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
		.love-quote {
			color: #a8325e;
			font-size: 1.2rem;
			margin-top: 18px;
			font-style: italic;
		}
		.love-illustration {
			margin-top: 40px;
			margin-bottom: 30px;
			text-align: center;
		}
		.love-illustration svg {
			width: 120px;
			height: 120px;
		}
	</style>
</head>
<body>
	<div class="menu-tray-love">
		<span class="love-heart">&#10084;&#65039;</span>
		<a href="login/register.php" class="btn btn-outline-danger btn-sm">Register</a>
		<a href="login/login.php" class="btn btn-outline-danger btn-sm">Login</a>
	</div>
	<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 100vh;">
		<div class="love-header text-center">DistantLove</div>
		<div class="love-illustration">
			<!-- Simple SVG heart illustration -->
			<svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M50 82s-30-18.5-30-40.5C20 27 35 20 50 36.5 65 20 80 27 80 41.5 80 63.5 50 82 50 82z" fill="#d72660" stroke="#fff" stroke-width="3"/>
			</svg>
		</div>
		<div class="love-quote text-center">"Love knows no distance, it has no boundaries."</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
