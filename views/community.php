<?php
session_start();
require_once '../settings/core.php';

// Check if user is premium (for demo purposes)
$isPremium = false; // This would come from database in production
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community - DistantLove</title>
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
        }

        /* Create Post Section */
        .create-post-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .create-post-btn {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
            width: 100%;
            font-size: 1.1rem;
        }

        .create-post-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.4);
        }

        /* Filter Tabs */
        .community-filters {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .filter-pills {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-pill {
            background: var(--gradient-soft-pink);
            color: var(--primary-pink);
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-pill:hover, .filter-pill.active {
            background: var(--gradient-rose);
            color: white;
            transform: translateY(-2px);
        }

        /* Community Posts */
        .community-container {
            padding: 2rem 0 4rem;
        }

        .post-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            transition: all 0.4s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(215, 38, 96, 0.15);
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .post-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gradient-rose);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .post-author-info h4 {
            margin: 0;
            color: var(--primary-pink);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .post-meta {
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        .post-badge {
            display: inline-block;
            background: var(--gradient-soft-pink);
            color: var(--primary-pink);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .premium-badge-gold {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: white;
        }

        .post-content h3 {
            color: var(--primary-pink);
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }

        .post-text {
            color: var(--dark-gray);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .post-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .post-tag {
            background: #f0f0f0;
            color: var(--dark-gray);
            padding: 0.4rem 1rem;
            border-radius: 15px;
            font-size: 0.85rem;
        }

        .post-actions {
            display: flex;
            gap: 2rem;
            padding-top: 1rem;
            border-top: 2px solid #f0f0f0;
        }

        .post-action-btn {
            background: none;
            border: none;
            color: var(--dark-gray);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .post-action-btn:hover {
            color: var(--primary-pink);
        }

        .post-action-btn i {
            font-size: 1.2rem;
        }

        .post-action-btn.liked {
            color: var(--primary-pink);
        }

        /* Premium Locked Content */
        .premium-locked {
            position: relative;
            overflow: hidden;
        }

        .premium-overlay-blur {
            filter: blur(5px);
            pointer-events: none;
        }

        .premium-unlock-banner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 10;
        }

        .premium-unlock-banner h4 {
            color: var(--primary-pink);
            margin-bottom: 1rem;
        }

        /* Create Post Modal */
        .post-modal-content {
            border-radius: 20px;
            border: none;
        }

        .post-modal-header {
            background: var(--gradient-rose);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }

        .post-form-group {
            margin-bottom: 1.5rem;
        }

        .post-form-group label {
            color: var(--dark-gray);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .post-form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .post-form-control:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(215, 38, 96, 0.1);
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

            .post-header {
                flex-direction: column;
                text-align: center;
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
            <h1 class="page-title">Community Stories</h1>
            <p class="page-subtitle">Share your journey and learn from couples who understand the distance</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container community-container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Create Post Section -->
                <?php if (isUserLoggedIn()): ?>
                <div class="create-post-section">
                    <button class="create-post-btn" onclick="openCreatePostModal()">
                        <i class="fas fa-pen"></i> Share Your Story
                    </button>
                </div>
                <?php endif; ?>

                <!-- Community Filters -->
                <div class="community-filters">
                    <div class="filter-pills">
                        <button class="filter-pill active" onclick="filterPosts('all')">All Stories</button>
                        <button class="filter-pill" onclick="filterPosts('success')">Success Stories</button>
                        <button class="filter-pill" onclick="filterPosts('advice')">Advice</button>
                        <button class="filter-pill" onclick="filterPosts('challenges')">Challenges</button>
                        <button class="filter-pill" onclick="filterPosts('tips')">Tips & Tricks</button>
                    </div>
                </div>

                <!-- Community Posts -->
                <div id="postsContainer">
                    <!-- Post 1 - Public -->
                    <div class="post-card" data-category="success">
                        <div class="post-header">
                            <div class="post-avatar">SJ</div>
                            <div class="post-author-info">
                                <h4>Sarah & James <span class="post-badge">2 years LDR</span></h4>
                                <p class="post-meta"><i class="fas fa-clock"></i> 3 days ago · USA 🇺🇸 - UK 🇬🇧</p>
                            </div>
                        </div>
                        <div class="post-content">
                            <h3>We Finally Closed the Distance! 💕</h3>
                            <p class="post-text">
                                After 2 years of video calls, time zone struggles, and counting down to visits, we finally did it! James got his work visa approved and moved to the US last week. I still can't believe I get to wake up next to him every day now.
                                <br><br>
                                For anyone struggling right now - it's worth it. Every tear, every goodbye at the airport, every "I miss you" text. Keep your end goal in sight and communicate openly about your plans. We used a shared Google doc to track visa requirements and it saved us so much stress!
                            </p>
                            <div class="post-tags">
                                <span class="post-tag">#ClosedTheDistance</span>
                                <span class="post-tag">#SuccessStory</span>
                                <span class="post-tag">#NeverGiveUp</span>
                            </div>
                        </div>
                        <div class="post-actions">
                            <button class="post-action-btn" onclick="toggleLike(this)">
                                <i class="far fa-heart"></i>
                                <span>247</span>
                            </button>
                            <button class="post-action-btn">
                                <i class="far fa-comment"></i>
                                <span>43</span>
                            </button>
                            <button class="post-action-btn">
                                <i class="far fa-share-square"></i>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>

                    <!-- Post 2 - Public -->
                    <div class="post-card" data-category="advice tips">
                        <div class="post-header">
                            <div class="post-avatar">MK</div>
                            <div class="post-author-info">
                                <h4>Maria & Kenji <span class="post-badge">5 years LDR</span></h4>
                                <p class="post-meta"><i class="fas fa-clock"></i> 5 days ago · Mexico 🇲🇽 - Japan 🇯🇵</p>
                            </div>
                        </div>
                        <div class="post-content">
                            <h3>Our Top 5 Communication Rules That Saved Our Relationship</h3>
                            <p class="post-text">
                                Being 14 hours apart taught us that quality > quantity. Here's what works for us:
                                <br><br>
                                1. <strong>One "good morning" video message daily</strong> - Even if we can't talk live, seeing each other's face starts the day right<br>
                                2. <strong>Weekly "state of us" check-ins</strong> - Scheduled time to discuss feelings, not just daily updates<br>
                                3. <strong>Shared photo album</strong> - We add random pics throughout the day. Feels like we're still together<br>
                                4. <strong>No major decisions when tired/upset</strong> - Sleep on it, talk tomorrow<br>
                                5. <strong>Always have the next visit planned</strong> - Having a countdown keeps hope alive
                                <br><br>
                                Remember: The goal isn't constant communication, it's meaningful connection. 💕
                            </p>
                            <div class="post-tags">
                                <span class="post-tag">#CommunicationTips</span>
                                <span class="post-tag">#LDRAdvice</span>
                                <span class="post-tag">#RelationshipGoals</span>
                            </div>
                        </div>
                        <div class="post-actions">
                            <button class="post-action-btn" onclick="toggleLike(this)">
                                <i class="far fa-heart"></i>
                                <span>892</span>
                            </button>
                            <button class="post-action-btn">
                                <i class="far fa-comment"></i>
                                <span>156</span>
                            </button>
                            <button class="post-action-btn">
                                <i class="far fa-share-square"></i>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>

                    <!-- Post 3 - Public -->
                    <div class="post-card" data-category="challenges">
                        <div class="post-header">
                            <div class="post-avatar">AL</div>
                            <div class="post-author-info">
                                <h4>Alex & Lisa <span class="post-badge">1 year LDR</span></h4>
                                <p class="post-meta"><i class="fas fa-clock"></i> 1 week ago · Canada 🇨🇦 - Australia 🇦🇺</p>
                            </div>
                        </div>
                        <div class="post-content">
                            <h3>How We Survived Our First Major Fight Across Time Zones</h3>
                            <p class="post-text">
                                Last month we had our biggest argument yet. I said something hurtful right before she had to go to work (my midnight, her morning). We couldn't talk for 8 hours and it was torture.
                                <br><br>
                                Lessons learned the hard way:<br>
                                • Never end a call angry, even if it means being late<br>
                                • Text "I love you, we'll work this out" even when mad<br>
                                • Time zones make cooling off harder - be extra patient<br>
                                • Voice messages > texts when emotions are high<br>
                                <br>
                                We're stronger now because of it. Distance forces you to communicate better because you CAN'T storm off to another room. You have to face it together, even from 10,000 miles apart.
                            </p>
                            <div class="post-tags">
                                <span class="post-tag">#RealTalk</span>
                                <span class="post-tag">#ConflictResolution</span>
                                <span class="post-tag">#GrowingTogether</span>
                            </div>
                        </div>
                        <div class="post-actions">
                            <button class="post-action-btn" onclick="toggleLike(this)">
                                <i class="far fa-heart"></i>
                                <span>534</span>
                            </button>
                            <button class="post-action-btn">
                                <i class="far fa-comment"></i>
                                <span>87</span>
                            </button>
                            <button class="post-action-btn">
                                <i class="far fa-share-square"></i>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>

                    <!-- Premium Locked Posts -->
                    <?php if (!$isPremium): ?>
                    <div class="post-card premium-locked" data-category="success advice">
                        <div class="premium-overlay-blur">
                            <div class="post-header">
                                <div class="post-avatar">TC</div>
                                <div class="post-author-info">
                                    <h4>Taylor & Chris <span class="post-badge premium-badge-gold"><i class="fas fa-crown"></i> Premium</span></h4>
                                    <p class="post-meta"><i class="fas fa-clock"></i> 2 days ago</p>
                                </div>
                            </div>
                            <div class="post-content">
                                <h3>The Secret to Maintaining Intimacy from 5000 Miles Away</h3>
                                <p class="post-text">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...
                                </p>
                            </div>
                        </div>
                        <div class="premium-unlock-banner">
                            <h4><i class="fas fa-crown"></i> Premium Content</h4>
                            <p>Upgrade to read exclusive stories and in-depth advice from our community</p>
                            <button class="create-post-btn" onclick="showPremiumPrompt()">
                                Unlock Premium
                            </button>
                        </div>
                    </div>

                    <div class="post-card premium-locked" data-category="tips">
                        <div class="premium-overlay-blur">
                            <div class="post-header">
                                <div class="post-avatar">RN</div>
                                <div class="post-author-info">
                                    <h4>Rachel & Noah <span class="post-badge premium-badge-gold"><i class="fas fa-crown"></i> Premium</span></h4>
                                    <p class="post-meta"><i class="fas fa-clock"></i> 4 days ago</p>
                                </div>
                            </div>
                            <div class="post-content">
                                <h3>50 Creative Date Ideas We've Tried (With Reviews!)</h3>
                                <p class="post-text">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua...
                                </p>
                            </div>
                        </div>
                        <div class="premium-unlock-banner">
                            <h4><i class="fas fa-crown"></i> Premium Content</h4>
                            <p>Access hundreds of exclusive posts and tips</p>
                            <button class="create-post-btn" onclick="showPremiumPrompt()">
                                Upgrade to Premium
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Premium Upgrade Banner -->
                <?php if (!$isPremium): ?>
                <div class="create-post-section" style="background: var(--gradient-rose); color: white; text-align: center;">
                    <h4 style="color: white; margin-bottom: 1rem;"><i class="fas fa-crown"></i> Go Premium!</h4>
                    <p style="margin-bottom: 1.5rem;">Get unlimited access to all community posts and exclusive content</p>
                    <button class="create-post-btn" style="background: white; color: var(--primary-pink);" onclick="showPremiumPrompt()">
                        Upgrade Now
                    </button>
                </div>
                <?php endif; ?>

                <!-- Community Stats -->
                <div class="create-post-section" style="margin-top: 2rem;">
                    <h4 style="color: var(--primary-pink); margin-bottom: 1.5rem;"><i class="fas fa-chart-line"></i> Community Stats</h4>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; justify-content: space-between; padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0;">
                            <span style="color: var(--dark-gray);">Total Members</span>
                            <strong style="color: var(--primary-pink);">12,547</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0;">
                            <span style="color: var(--dark-gray);">Stories Shared</span>
                            <strong style="color: var(--primary-pink);">3,892</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0;">
                            <span style="color: var(--dark-gray);">Success Stories</span>
                            <strong style="color: var(--primary-pink);">1,247</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--dark-gray);">Countries Represented</span>
                            <strong style="color: var(--primary-pink);">87</strong>
                        </div>
                    </div>
                </div>

                <!-- Popular Tags -->
                <div class="create-post-section" style="margin-top: 2rem;">
                    <h4 style="color: var(--primary-pink); margin-bottom: 1.5rem;"><i class="fas fa-tags"></i> Popular Tags</h4>
                    <div class="post-tags">
                        <span class="post-tag">#ClosedTheDistance</span>
                        <span class="post-tag">#FirstMeet</span>
                        <span class="post-tag">#LDRAdvice</span>
                        <span class="post-tag">#MilitaryLove</span>
                        <span class="post-tag">#InternationalCouple</span>
                        <span class="post-tag">#VisaJourney</span>
                        <span class="post-tag">#DateIdeas</span>
                        <span class="post-tag">#StayStrong</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Post Modal -->
    <div class="modal fade" id="createPostModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content post-modal-content">
                <div class="modal-header post-modal-header">
                    <h5 class="modal-title"><i class="fas fa-pen"></i> Share Your Story</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <form id="createPostForm">
                        <div class="post-form-group">
                            <label><i class="fas fa-heading"></i> Title</label>
                            <input type="text" class="post-form-control" placeholder="Give your story a title..." required>
                        </div>

                        <div class="post-form-group">
                            <label><i class="fas fa-align-left"></i> Your Story</label>
                            <textarea class="post-form-control" rows="8" placeholder="Share your experience, advice, or ask for support..." required></textarea>
                        </div>

                        <div class="post-form-group">
                            <label><i class="fas fa-tag"></i> Category</label>
                            <select class="post-form-control" required>
                                <option value="">Select a category...</option>
                                <option value="success">Success Story</option>
                                <option value="advice">Advice</option>
                                <option value="challenges">Challenges</option>
                                <option value="tips">Tips & Tricks</option>
                                <option value="question">Question</option>
                            </select>
                        </div>

                        <div class="post-form-group">
                            <label><i class="fas fa-hashtag"></i> Tags (Optional)</label>
                            <input type="text" class="post-form-control" placeholder="e.g., #FirstMeet #VisaJourney">
                        </div>

                        <button type="submit" class="create-post-btn">
                            <i class="fas fa-paper-plane"></i> Post to Community
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
        function filterPosts(category) {
            const posts = document.querySelectorAll('.post-card');
            const pills = document.querySelectorAll('.filter-pill');

            pills.forEach(pill => pill.classList.remove('active'));
            event.target.classList.add('active');

            posts.forEach(post => {
                if (category === 'all' || post.dataset.category.includes(category)) {
                    post.style.display = 'block';
                } else {
                    post.style.display = 'none';
                }
            });
        }

        function toggleLike(button) {
            const icon = button.querySelector('i');
            const count = button.querySelector('span');

            if (button.classList.contains('liked')) {
                button.classList.remove('liked');
                icon.classList.remove('fas');
                icon.classList.add('far');
                count.textContent = parseInt(count.textContent) - 1;
            } else {
                button.classList.add('liked');
                icon.classList.remove('far');
                icon.classList.add('fas');
                count.textContent = parseInt(count.textContent) + 1;
            }
        }

        function openCreatePostModal() {
            <?php if (!isUserLoggedIn()): ?>
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please login to share your story with the community',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#d72660'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../login/login.php';
                    }
                });
                return;
            <?php endif; ?>

            const modal = new bootstrap.Modal(document.getElementById('createPostModal'));
            modal.show();
        }

        document.getElementById('createPostForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Posting...',
                text: 'Sharing your story with the community',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                Swal.fire({
                    title: 'Story Posted!',
                    text: 'Your story has been shared with the DistantLove community',
                    icon: 'success',
                    confirmButtonColor: '#d72660'
                }).then(() => {
                    location.reload();
                });
            }, 2000);
        });

        function showPremiumPrompt() {
            Swal.fire({
                title: 'Upgrade to Premium',
                html: '<p>Get unlimited access to all community posts and exclusive content!</p><br><p><strong>Premium Benefits:</strong></p><ul style="text-align: left; margin-left: 2rem;"><li>Read all premium stories and advice</li><li>Access exclusive discussion threads</li><li>Priority support from counselors</li><li>Weekly expert relationship tips</li><li>20% off counseling sessions</li></ul>',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-crown"></i> Upgrade for GH₵ 320/month',
                cancelButtonText: 'Maybe Later',
                confirmButtonColor: '#d72660',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'shop.php#premium';
                }
            });
        }
    </script>
</body>

</html>
