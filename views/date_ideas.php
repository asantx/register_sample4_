<?php
session_start();
require_once '../settings/core.php';
require_once '../controllers/customer_controller.php';

// Check if user is premium
$isPremium = false;
if (isUserLoggedIn() && isset($_SESSION['user_id'])) {
    // Check from session first (updated after payment)
    if (isset($_SESSION['is_premium']) && $_SESSION['is_premium']) {
        $isPremium = true;
    } else {
        // Check from database
        $isPremium = check_premium_status_ctr($_SESSION['user_id']);
        // Update session
        $_SESSION['is_premium'] = $isPremium;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date Ideas - DistantLove</title>
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
        }

        /* Navigation */
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

        /* Page Header */
        .page-header {
            padding: 3rem 0 2rem;
            text-align: center;
        }

        .page-title {
            font-family: 'Pacifico', cursive;
            font-size: 3rem;
            background: var(--gradient-rose);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.2rem;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 3rem;
        }

        .filter-tab {
            background: white;
            color: var(--dark-gray);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .filter-tab:hover, .filter-tab.active {
            background: var(--gradient-rose);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.3);
        }

        /* Date Ideas Grid */
        .ideas-container {
            padding: 2rem 0 4rem;
        }

        .idea-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            margin-bottom: 2rem;
            height: 100%;
        }

        .idea-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(215, 38, 96, 0.2);
        }

        .idea-image {
            height: 250px;
            background: var(--gradient-soft-pink);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            position: relative;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .idea-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .idea-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--gradient-rose);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(215, 38, 96, 0.3);
        }

        .premium-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(255, 215, 0, 0.3);
        }

        .idea-content {
            padding: 2rem;
        }

        .idea-title {
            color: var(--primary-pink);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .idea-description {
            color: var(--dark-gray);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .idea-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        .meta-item i {
            color: var(--primary-pink);
        }

        .idea-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .idea-tag {
            background: var(--gradient-soft-pink);
            color: var(--primary-pink);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .view-details-btn {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
            width: 100%;
        }

        .view-details-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.4);
        }

        .premium-overlay {
            position: relative;
            cursor: pointer;
        }

        .premium-overlay::after {
            content: '🔒 Premium Only';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-pink);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
            flex-direction: column;
            text-align: center;
            padding: 2rem;
        }

        .premium-overlay:hover::after {
            content: '✨ Unlock Premium\A\AGet access to exclusive date ideas and features\A\AClick to upgrade now!';
            white-space: pre-wrap;
            background: linear-gradient(135deg, rgba(215, 38, 96, 0.95) 0%, rgba(244, 105, 144, 0.95) 100%);
            color: white;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .premium-overlay:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 50px rgba(215, 38, 96, 0.4);
        }

        /* Details Modal */
        .details-modal-content {
            border-radius: 20px;
            border: none;
        }

        .details-modal-header {
            background: var(--gradient-rose);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }

        .step-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .step-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .step-number {
            background: var(--gradient-rose);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .step-content h5 {
            color: var(--primary-pink);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .step-content p {
            color: var(--dark-gray);
            margin: 0;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2.5rem;
            }

            .filter-tabs {
                gap: 0.5rem;
            }

            .filter-tab {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .nav-links-modern {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar-modern">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a href="shop.php" class="navbar-brand-modern">
                    <span class="love-heart">❤️</span> DistantLove
                </a>
                <div class="nav-links-modern">
                    <a href="shop.php" class="nav-link-modern">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                    <a href="orders.php" class="nav-link-modern">
                        <i class="fas fa-box"></i> My Bookings
                    </a>
                    <a href="../login/logout.php" class="nav-link-modern">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Long Distance Date Ideas</h1>
            <p class="page-subtitle">Creative ways to stay connected and keep the romance alive across the miles</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="container">
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterIdeas('all')">All Ideas</button>
            <button class="filter-tab" onclick="filterIdeas('free')">Free</button>
            <button class="filter-tab" onclick="filterIdeas('video')">Video Dates</button>
            <button class="filter-tab" onclick="filterIdeas('creative')">Creative</button>
            <button class="filter-tab" onclick="filterIdeas('surprise')">Surprises</button>
        </div>
    </div>

    <!-- Date Ideas Container -->
    <div class="container ideas-container">
        <div class="row" id="ideasGrid">
            <!-- Free Ideas -->
            <div class="col-md-6 col-lg-4 idea-item" data-category="free video">
                <div class="idea-card">
                    <div class="idea-image">
                        <img src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=800&h=600&fit=crop" alt="Virtual Movie Night">
                        <span class="idea-badge">Free</span>
                    </div>
                    <div class="idea-content">
                        <h3 class="idea-title">Virtual Movie Night</h3>
                        <p class="idea-description">
                            Watch the same movie together while video calling. Set up your favorite streaming service, grab some popcorn, and enjoy quality time together.
                        </p>
                        <div class="idea-meta">
                            <span class="meta-item"><i class="fas fa-clock"></i> 2-3 hours</span>
                            <span class="meta-item"><i class="fas fa-dollar-sign"></i> Free</span>
                        </div>
                        <div class="idea-tags">
                            <span class="idea-tag">Relaxing</span>
                            <span class="idea-tag">Easy Setup</span>
                        </div>
                        <button class="view-details-btn" onclick="showDetails('movie')">
                            <i class="fas fa-info-circle"></i> View Details
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 idea-item" data-category="free video creative">
                <div class="idea-card">
                    <div class="idea-image">
                        <img src="https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=800&h=600&fit=crop" alt="Online Gaming Session">
                        <span class="idea-badge">Free</span>
                    </div>
                    <div class="idea-content">
                        <h3 class="idea-title">Online Gaming Session</h3>
                        <p class="idea-description">
                            Play multiplayer games together online. From casual mobile games to cooperative PC adventures, gaming is a fun way to bond.
                        </p>
                        <div class="idea-meta">
                            <span class="meta-item"><i class="fas fa-clock"></i> 1-4 hours</span>
                            <span class="meta-item"><i class="fas fa-dollar-sign"></i> Free</span>
                        </div>
                        <div class="idea-tags">
                            <span class="idea-tag">Interactive</span>
                            <span class="idea-tag">Fun</span>
                        </div>
                        <button class="view-details-btn" onclick="showDetails('gaming')">
                            <i class="fas fa-info-circle"></i> View Details
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 idea-item" data-category="free video">
                <div class="idea-card">
                    <div class="idea-image">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&h=600&fit=crop" alt="Cook Together">
                        <span class="idea-badge">Free</span>
                    </div>
                    <div class="idea-content">
                        <h3 class="idea-title">Cook Together</h3>
                        <p class="idea-description">
                            Choose a recipe, shop for ingredients separately, then cook the same meal together over video call. Enjoy your creation "together"!
                        </p>
                        <div class="idea-meta">
                            <span class="meta-item"><i class="fas fa-clock"></i> 1-2 hours</span>
                            <span class="meta-item"><i class="fas fa-dollar-sign"></i> Free</span>
                        </div>
                        <div class="idea-tags">
                            <span class="idea-tag">Romantic</span>
                            <span class="idea-tag">Creative</span>
                        </div>
                        <button class="view-details-btn" onclick="showDetails('cooking')">
                            <i class="fas fa-info-circle"></i> View Details
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 idea-item" data-category="surprise creative">
                <div class="idea-card">
                    <div class="idea-image">
                        <img src="https://images.unsplash.com/photo-1516205651411-aef33a44f7c2?w=800&h=600&fit=crop" alt="Love Letter Exchange">
                        <span class="idea-badge">Surprise</span>
                    </div>
                    <div class="idea-content">
                        <h3 class="idea-title">Love Letter Exchange</h3>
                        <p class="idea-description">
                            Write heartfelt handwritten letters to each other. The anticipation and personal touch of physical mail adds romance to your relationship.
                        </p>
                        <div class="idea-meta">
                            <span class="meta-item"><i class="fas fa-clock"></i> 30 min</span>
                            <span class="meta-item"><i class="fas fa-money-bill-wave"></i> GH₵ 80-160</span>
                        </div>
                        <div class="idea-tags">
                            <span class="idea-tag">Romantic</span>
                            <span class="idea-tag">Thoughtful</span>
                        </div>
                        <button class="view-details-btn" onclick="showDetails('letters')">
                            <i class="fas fa-info-circle"></i> View Details
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 idea-item" data-category="video creative">
                <div class="idea-card">
                    <div class="idea-image">
                        <img src="https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?w=800&h=600&fit=crop" alt="Virtual Art Class">
                        <span class="idea-badge">Creative</span>
                    </div>
                    <div class="idea-content">
                        <h3 class="idea-title">Virtual Art Class</h3>
                        <p class="idea-description">
                            Follow an online art tutorial together. Paint, draw, or craft while video chatting. No artistic skill required - just have fun!
                        </p>
                        <div class="idea-meta">
                            <span class="meta-item"><i class="fas fa-clock"></i> 1-2 hours</span>
                            <span class="meta-item"><i class="fas fa-money-bill-wave"></i> GH₵ 160-480</span>
                        </div>
                        <div class="idea-tags">
                            <span class="idea-tag">Creative</span>
                            <span class="idea-tag">Memorable</span>
                        </div>
                        <button class="view-details-btn" onclick="showDetails('art')">
                            <i class="fas fa-info-circle"></i> View Details
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 idea-item" data-category="surprise">
                <div class="idea-card">
                    <div class="idea-image">
                        <img src="https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=800&h=600&fit=crop" alt="Care Package Surprise">
                        <span class="idea-badge">Surprise</span>
                    </div>
                    <div class="idea-content">
                        <h3 class="idea-title">Care Package Surprise</h3>
                        <p class="idea-description">
                            Send a thoughtful care package filled with their favorite things, inside jokes, and little surprises to brighten their day.
                        </p>
                        <div class="idea-meta">
                            <span class="meta-item"><i class="fas fa-clock"></i> 1 hour</span>
                            <span class="meta-item"><i class="fas fa-money-bill-wave"></i> GH₵ 320-800</span>
                        </div>
                        <div class="idea-tags">
                            <span class="idea-tag">Thoughtful</span>
                            <span class="idea-tag">Romantic</span>
                        </div>
                        <button class="view-details-btn" onclick="showDetails('care')">
                            <i class="fas fa-info-circle"></i> View Details
                        </button>
                    </div>
                </div>
            </div>

            <!-- Premium Ideas -->
            <div class="col-md-6 col-lg-4 idea-item" data-category="video creative">
                <div class="idea-card <?php echo !$isPremium ? 'premium-overlay' : ''; ?>">
                    <div class="idea-image">
                        <img src="https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=800&h=600&fit=crop" alt="Virtual Escape Room">
                        <span class="premium-badge"><i class="fas fa-crown"></i> Premium</span>
                    </div>
                    <div class="idea-content">
                        <h3 class="idea-title">Virtual Escape Room</h3>
                        <p class="idea-description">
                            Solve puzzles and mysteries together in a professional virtual escape room. Work as a team to beat the clock and strengthen your bond.
                        </p>
                        <div class="idea-meta">
                            <span class="meta-item"><i class="fas fa-clock"></i> 1-2 hours</span>
                            <span class="meta-item"><i class="fas fa-money-bill-wave"></i> GH₵ 480-960</span>
                        </div>
                        <div class="idea-tags">
                            <span class="idea-tag">Team Building</span>
                            <span class="idea-tag">Exciting</span>
                        </div>
                        <button class="view-details-btn" onclick="showPremiumPrompt()">
                            <i class="fas fa-crown"></i> Premium Content
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 idea-item" data-category="creative surprise">
                <div class="idea-card <?php echo !$isPremium ? 'premium-overlay' : ''; ?>">
                    <div class="idea-image">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop" alt="Personalized Scavenger Hunt">
                        <span class="premium-badge"><i class="fas fa-crown"></i> Premium</span>
                    </div>
                    <div class="idea-content">
                        <h3 class="idea-title">Personalized Scavenger Hunt</h3>
                        <p class="idea-description">
                            Create a custom scavenger hunt for your partner with clues leading to meaningful locations or memories. Complete guide included.
                        </p>
                        <div class="idea-meta">
                            <span class="meta-item"><i class="fas fa-clock"></i> 2-3 hours</span>
                            <span class="meta-item"><i class="fas fa-money-bill-wave"></i> GH₵ 240-640</span>
                        </div>
                        <div class="idea-tags">
                            <span class="idea-tag">Romantic</span>
                            <span class="idea-tag">Adventurous</span>
                        </div>
                        <button class="view-details-btn" onclick="showPremiumPrompt()">
                            <i class="fas fa-crown"></i> Premium Content
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 idea-item" data-category="video">
                <div class="idea-card <?php echo !$isPremium ? 'premium-overlay' : ''; ?>">
                    <div class="idea-image">
                        <img src="https://images.unsplash.com/photo-1503095396549-807759245b35?w=800&h=600&fit=crop" alt="Virtual Theater Experience">
                        <span class="premium-badge"><i class="fas fa-crown"></i> Premium</span>
                    </div>
                    <div class="idea-content">
                        <h3 class="idea-title">Virtual Theater Experience</h3>
                        <p class="idea-description">
                            Attend live-streamed performances, concerts, or comedy shows together. Curated list of the best virtual events included.
                        </p>
                        <div class="idea-meta">
                            <span class="meta-item"><i class="fas fa-clock"></i> 2-3 hours</span>
                            <span class="meta-item"><i class="fas fa-money-bill-wave"></i> GH₵ 320-1,600</span>
                        </div>
                        <div class="idea-tags">
                            <span class="idea-tag">Cultural</span>
                            <span class="idea-tag">Special</span>
                        </div>
                        <button class="view-details-btn" onclick="showPremiumPrompt()">
                            <i class="fas fa-crown"></i> Premium Content
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content details-modal-content">
                <div class="modal-header details-modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;" id="modalBody">
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function filterIdeas(category) {
            const items = document.querySelectorAll('.idea-item');
            const tabs = document.querySelectorAll('.filter-tab');

            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            items.forEach(item => {
                if (category === 'all' || item.dataset.category.includes(category)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        const ideaDetails = {
            movie: {
                title: '🍿 Virtual Movie Night',
                steps: [
                    { title: 'Choose a Movie', content: 'Pick a movie you both want to watch. Use platforms like Netflix, Disney+, or Amazon Prime.' },
                    { title: 'Set Up Watch Party', content: 'Use browser extensions like Teleparty (Netflix Party) to sync your viewing, or simply start the movie at the same time.' },
                    { title: 'Prepare Snacks', content: 'Make popcorn, get your favorite drinks, and create a cozy movie atmosphere.' },
                    { title: 'Video Call Setup', content: 'Keep a video call open (muted during the movie) so you can see each other\'s reactions.' },
                    { title: 'Discuss After', content: 'After the movie, discuss your favorite parts, theories, and what to watch next!' }
                ]
            },
            gaming: {
                title: '🎮 Online Gaming Session',
                steps: [
                    { title: 'Choose Your Game', content: 'Pick a multiplayer game you both enjoy. Options: Among Us, Minecraft, Stardew Valley, or mobile games like Words With Friends.' },
                    { title: 'Set Up Accounts', content: 'Make sure you both have accounts and the necessary subscriptions or downloads.' },
                    { title: 'Schedule Time', content: 'Pick a time when you can both play uninterrupted for at least an hour.' },
                    { title: 'Voice Chat', content: 'Use Discord, game chat, or video call to communicate while playing.' },
                    { title: 'Make it Regular', content: 'Consider making this a weekly tradition to have something to look forward to!' }
                ]
            },
            cooking: {
                title: '🍳 Cook Together',
                steps: [
                    { title: 'Choose a Recipe', content: 'Pick a recipe that\'s new to both of you. Try something from your partner\'s culture or childhood favorites.' },
                    { title: 'Shop Together', content: 'Make grocery lists and shop at the same time (virtually), helping each other find ingredients.' },
                    { title: 'Prep Your Kitchen', content: 'Set up your phone or tablet so you can see each other while cooking.' },
                    { title: 'Cook Step-by-Step', content: 'Follow the recipe together, helping each other and laughing at any mishaps.' },
                    { title: 'Virtual Dinner Date', content: 'Plate your food nicely, light candles, and enjoy your meal together over video call!' }
                ]
            },
            letters: {
                title: '💌 Love Letter Exchange',
                steps: [
                    { title: 'Get Supplies', content: 'Buy nice stationery, pens, and maybe some stickers or wax seals for a special touch.' },
                    { title: 'Write from the Heart', content: 'Share your feelings, favorite memories, dreams for the future, or even poetry.' },
                    { title: 'Add Personal Touches', content: 'Include photos, pressed flowers, your perfume/cologne, or small flat keepsakes.' },
                    { title: 'Mail It', content: 'Send your letters at the same time so they arrive around the same date.' },
                    { title: 'Schedule Opening', content: 'Plan to open your letters together on a video call to share the experience!' }
                ]
            },
            art: {
                title: '🎨 Virtual Art Class',
                steps: [
                    { title: 'Choose Your Medium', content: 'Decide on painting, drawing, origami, or any craft you both want to try.' },
                    { title: 'Find a Tutorial', content: 'Pick a YouTube tutorial or online class you\'ll follow together.' },
                    { title: 'Get Supplies', content: 'Buy or gather the same materials so you\'re working with similar tools.' },
                    { title: 'Set Up Video Call', content: 'Position cameras so you can see each other\'s work progress.' },
                    { title: 'Create & Share', content: 'Follow the tutorial together, share your finished pieces, and keep them as memories!' }
                ]
            },
            care: {
                title: '📦 Care Package Surprise',
                steps: [
                    { title: 'Brainstorm Ideas', content: 'Think about their favorite snacks, comfort items, inside jokes, and things they need.' },
                    { title: 'Shop Thoughtfully', content: 'Include: favorite candy, a handwritten note, photos, a playlist QR code, their favorite tea/coffee.' },
                    { title: 'Add Personal Items', content: 'Include something with your scent, a small piece of your clothing, or a item that reminds them of you.' },
                    { title: 'Package Creatively', content: 'Wrap items individually for the joy of unwrapping, add tissue paper and decorations.' },
                    { title: 'Surprise Timing', content: 'Send it as a surprise or track it so you can video call when it arrives!' }
                ]
            }
        };

        function showDetails(type) {
            const details = ideaDetails[type];
            document.getElementById('modalTitle').textContent = details.title;

            let stepsHTML = '<ul class="step-list">';
            details.steps.forEach((step, index) => {
                stepsHTML += `
                    <li class="step-item">
                        <div class="step-number">${index + 1}</div>
                        <div class="step-content">
                            <h5>${step.title}</h5>
                            <p>${step.content}</p>
                        </div>
                    </li>
                `;
            });
            stepsHTML += '</ul>';

            document.getElementById('modalBody').innerHTML = stepsHTML;

            const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
            modal.show();
        }

        // Add click handlers to premium overlay cards
        document.addEventListener('DOMContentLoaded', function() {
            const premiumCards = document.querySelectorAll('.premium-overlay');
            premiumCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    showPremiumPrompt();
                });
            });
        });

        function showPremiumPrompt() {
            Swal.fire({
                title: '✨ Unlock Premium Access',
                html: `
                    <div style="text-align: left; padding: 1rem;">
                        <p style="text-align: center; font-size: 1.1rem; margin-bottom: 1.5rem;">
                            Upgrade to <strong>DistantLove Premium</strong> to unlock exclusive date ideas and detailed guides!
                        </p>

                        <div style="background: linear-gradient(135deg, #ffdde1 0%, #ee9ca7 20%); padding: 1.5rem; border-radius: 15px; margin-bottom: 1.5rem;">
                            <h4 style="color: #d72660; margin-bottom: 1rem;">
                                <i class="fas fa-crown"></i> Premium Benefits:
                            </h4>
                            <ul style="margin-left: 1.5rem; line-height: 2;">
                                <li><i class="fas fa-check-circle" style="color: #d72660;"></i> 50+ exclusive date ideas</li>
                                <li><i class="fas fa-check-circle" style="color: #d72660;"></i> Detailed step-by-step guides</li>
                                <li><i class="fas fa-check-circle" style="color: #d72660;"></i> Printable date planning worksheets</li>
                                <li><i class="fas fa-check-circle" style="color: #d72660;"></i> 20% off counseling sessions</li>
                                <li><i class="fas fa-check-circle" style="color: #d72660;"></i> Priority customer support</li>
                                <li><i class="fas fa-check-circle" style="color: #d72660;"></i> Monthly relationship tips newsletter</li>
                            </ul>
                        </div>

                        <p style="text-align: center; font-size: 1.3rem; font-weight: 700; color: #d72660;">
                            Only <span style="font-size: 1.5rem;">GH₵ 320</span>/month
                        </p>
                        <p style="text-align: center; color: #666; font-size: 0.9rem;">
                            Cancel anytime. No hidden fees.
                        </p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-crown"></i> Upgrade to Premium',
                cancelButtonText: 'Maybe Later',
                confirmButtonColor: '#d72660',
                cancelButtonColor: '#6c757d',
                width: '600px'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Redirecting...',
                        text: 'Taking you to our premium plans',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'shop.php#premium';
                    });
                }
            });
        }
    </script>
</body>

</html>
