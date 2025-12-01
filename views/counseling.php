<?php
session_start();
require_once '../settings/core.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counseling Sessions - DistantLove</title>
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

        /* Counselor Cards */
        .counselors-container {
            padding: 2rem 0 4rem;
        }

        .counselor-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            margin-bottom: 2rem;
        }

        .counselor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(215, 38, 96, 0.2);
        }

        .counselor-header {
            background: var(--gradient-soft-pink);
            padding: 2rem;
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .counselor-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--gradient-rose);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 5px 20px rgba(215, 38, 96, 0.3);
        }

        .counselor-info h3 {
            color: var(--primary-pink);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .counselor-title {
            color: var(--dark-gray);
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .counselor-rating {
            color: var(--accent-gold);
            font-size: 1rem;
        }

        .counselor-rating .stars {
            margin-right: 0.5rem;
        }

        .counselor-body {
            padding: 2rem;
        }

        .counselor-bio {
            color: var(--dark-gray);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .counselor-specialties {
            margin-bottom: 1.5rem;
        }

        .counselor-specialties h5 {
            color: var(--primary-pink);
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .specialty-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .specialty-tag {
            background: var(--gradient-soft-pink);
            color: var(--primary-pink);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .counselor-contact {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--dark-gray);
        }

        .contact-item i {
            color: var(--primary-pink);
            font-size: 1.2rem;
            width: 30px;
        }

        .counselor-availability {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .counselor-availability h5 {
            color: var(--primary-pink);
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }

        .availability-info {
            color: var(--dark-gray);
            font-size: 0.95rem;
        }

        .book-btn {
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 38, 96, 0.3);
            width: 100%;
            font-size: 1.1rem;
        }

        .book-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(215, 38, 96, 0.4);
        }

        /* Booking Modal */
        .booking-modal-content {
            border-radius: 20px;
            border: none;
        }

        .booking-modal-header {
            background: var(--gradient-rose);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem;
        }

        .booking-form-group {
            margin-bottom: 1.5rem;
        }

        .booking-form-group label {
            color: var(--dark-gray);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .booking-form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .booking-form-control:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(215, 38, 96, 0.1);
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.75rem;
        }

        .time-slot {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .time-slot:hover {
            border-color: var(--primary-pink);
            background: var(--gradient-soft-pink);
        }

        .time-slot.selected {
            background: var(--gradient-rose);
            color: white;
            border-color: var(--primary-pink);
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
        }

        @media (max-width: 768px) {
            .counselor-header {
                flex-direction: column;
                text-align: center;
            }

            .page-title {
                font-size: 2.5rem;
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
            <h1 class="page-title">Counseling Sessions</h1>
            <p class="page-subtitle">Connect with expert relationship counselors specializing in long-distance relationships</p>
        </div>
    </div>

    <!-- Counselors Container -->
    <div class="container counselors-container">
        <!-- Counselor 1 - Ghanaian -->
        <div class="counselor-card">
            <div class="counselor-header">
                <div class="counselor-avatar">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="counselor-info">
                    <h3>Dr. Akosua Mensah</h3>
                    <p class="counselor-title">Licensed Relationship Therapist, Ph.D.</p>
                    <div class="counselor-rating">
                        <span class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </span>
                        <span>5.0 (142 reviews)</span>
                    </div>
                </div>
            </div>
            <div class="counselor-body">
                <p class="counselor-bio">
                    Based in Accra, Dr. Mensah has over 12 years of experience in couples therapy, specializing in helping long-distance couples maintain strong emotional connections. She has helped hundreds of Ghanaian and international couples navigate the unique challenges of distance with evidence-based strategies and compassionate guidance rooted in both Western and African relationship values.
                </p>

                <div class="counselor-specialties">
                    <h5><i class="fas fa-star"></i> Specialties</h5>
                    <div class="specialty-tags">
                        <span class="specialty-tag">Communication Skills</span>
                        <span class="specialty-tag">Trust Building</span>
                        <span class="specialty-tag">Conflict Resolution</span>
                        <span class="specialty-tag">Cultural Adaptation</span>
                    </div>
                </div>

                <div class="counselor-contact">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>akosua.mensah@distantlove.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+233 24 567 8901</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-video"></i>
                        <span>Video & Phone Sessions</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>GH₵ 1,920/hour</span>
                    </div>
                </div>

                <div class="counselor-availability">
                    <h5><i class="fas fa-calendar-alt"></i> Availability</h5>
                    <p class="availability-info">Monday - Friday: 9:00 AM - 6:00 PM GMT<br>Saturday: 10:00 AM - 2:00 PM GMT</p>
                </div>

                <button class="book-btn" onclick="openBookingModal('Dr. Akosua Mensah', 1920)">
                    <i class="fas fa-calendar-check"></i> Book a Session
                </button>
            </div>
        </div>

        <!-- Counselor 2 - Ghanaian -->
        <div class="counselor-card">
            <div class="counselor-header">
                <div class="counselor-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="counselor-info">
                    <h3>Kwame Asante, LMFT</h3>
                    <p class="counselor-title">Licensed Marriage & Family Therapist</p>
                    <div class="counselor-rating">
                        <span class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </span>
                        <span>4.9 (108 reviews)</span>
                    </div>
                </div>
            </div>
            <div class="counselor-body">
                <p class="counselor-bio">
                    Operating from Kumasi, Kwame brings a warm, practical approach to couples counseling, with special expertise in helping Ghanaian diaspora families and international couples. His sessions focus on building sustainable routines, respecting cultural values, and creating meaningful shared experiences despite the distance.
                </p>

                <div class="counselor-specialties">
                    <h5><i class="fas fa-star"></i> Specialties</h5>
                    <div class="specialty-tags">
                        <span class="specialty-tag">Diaspora Couples</span>
                        <span class="specialty-tag">International Relationships</span>
                        <span class="specialty-tag">Future Planning</span>
                        <span class="specialty-tag">Family Integration</span>
                    </div>
                </div>

                <div class="counselor-contact">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>kwame.asante@distantlove.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+233 50 234 5678</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-video"></i>
                        <span>Video Sessions Only</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>GH₵ 1,520/hour</span>
                    </div>
                </div>

                <div class="counselor-availability">
                    <h5><i class="fas fa-calendar-alt"></i> Availability</h5>
                    <p class="availability-info">Tuesday - Saturday: 12:00 PM - 8:00 PM GMT<br>Flexible scheduling for international clients</p>
                </div>

                <button class="book-btn" onclick="openBookingModal('Kwame Asante, LMFT', 1520)">
                    <i class="fas fa-calendar-check"></i> Book a Session
                </button>
            </div>
        </div>

        <!-- Counselor 3 - International (British) -->
        <div class="counselor-card">
            <div class="counselor-header">
                <div class="counselor-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="counselor-info">
                    <h3>Dr. Sarah Mitchell</h3>
                    <p class="counselor-title">Clinical Psychologist, Psy.D. (UK)</p>
                    <div class="counselor-rating">
                        <span class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </span>
                        <span>5.0 (189 reviews)</span>
                    </div>
                </div>
            </div>
            <div class="counselor-body">
                <p class="counselor-bio">
                    Based in London with extensive experience working with international couples, Dr. Mitchell specializes in helping young couples and those in their first long-distance relationship. Her sessions incorporate modern communication tools and focus on building strong foundations for lasting connections across any distance. She has a special interest in UK-Ghana relationships.
                </p>

                <div class="counselor-specialties">
                    <h5><i class="fas fa-star"></i> Specialties</h5>
                    <div class="specialty-tags">
                        <span class="specialty-tag">Young Couples</span>
                        <span class="specialty-tag">Digital Communication</span>
                        <span class="specialty-tag">Anxiety Management</span>
                        <span class="specialty-tag">UK-Africa Relationships</span>
                    </div>
                </div>

                <div class="counselor-contact">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>sarah.mitchell@distantlove.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+44 20 7123 4567</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-video"></i>
                        <span>Video, Phone & Chat</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>GH₵ 1,760/hour</span>
                    </div>
                </div>

                <div class="counselor-availability">
                    <h5><i class="fas fa-calendar-alt"></i> Availability</h5>
                    <p class="availability-info">Monday - Thursday: 2:00 PM - 10:00 PM GMT<br>Sunday: 11:00 AM - 5:00 PM GMT</p>
                </div>

                <button class="book-btn" onclick="openBookingModal('Dr. Sarah Mitchell', 1760)">
                    <i class="fas fa-calendar-check"></i> Book a Session
                </button>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content booking-modal-content">
                <div class="modal-header booking-modal-header">
                    <h5 class="modal-title"><i class="fas fa-calendar-check"></i> Book Your Session</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <form id="bookingForm">
                        <input type="hidden" id="counselorName" name="counselorName">
                        <input type="hidden" id="sessionCost" name="sessionCost">
                        <input type="hidden" id="selectedTimeSlot" name="selectedTimeSlot">

                        <div class="booking-form-group">
                            <label><i class="fas fa-user"></i> Counselor</label>
                            <input type="text" class="booking-form-control" id="displayCounselor" readonly>
                        </div>

                        <div class="booking-form-group">
                            <label><i class="fas fa-calendar"></i> Select Date</label>
                            <input type="date" class="booking-form-control" id="sessionDate" name="sessionDate" required>
                        </div>

                        <div class="booking-form-group">
                            <label><i class="fas fa-clock"></i> Select Time Slot</label>
                            <div class="time-slots" id="timeSlots">
                                <div class="time-slot" onclick="selectTimeSlot(this, '9:00 AM')">9:00 AM</div>
                                <div class="time-slot" onclick="selectTimeSlot(this, '10:00 AM')">10:00 AM</div>
                                <div class="time-slot" onclick="selectTimeSlot(this, '11:00 AM')">11:00 AM</div>
                                <div class="time-slot" onclick="selectTimeSlot(this, '1:00 PM')">1:00 PM</div>
                                <div class="time-slot" onclick="selectTimeSlot(this, '2:00 PM')">2:00 PM</div>
                                <div class="time-slot" onclick="selectTimeSlot(this, '3:00 PM')">3:00 PM</div>
                                <div class="time-slot" onclick="selectTimeSlot(this, '4:00 PM')">4:00 PM</div>
                                <div class="time-slot" onclick="selectTimeSlot(this, '5:00 PM')">5:00 PM</div>
                            </div>
                        </div>

                        <div class="booking-form-group">
                            <label><i class="fas fa-video"></i> Session Type</label>
                            <select class="booking-form-control" id="sessionType" name="sessionType" required>
                                <option value="">Select session type</option>
                                <option value="video">Video Call</option>
                                <option value="phone">Phone Call</option>
                                <option value="chat">Text Chat</option>
                            </select>
                        </div>

                        <div class="booking-form-group">
                            <label><i class="fas fa-comment"></i> What would you like to discuss? (Optional)</label>
                            <textarea class="booking-form-control" rows="4" id="sessionNotes" name="sessionNotes" placeholder="Share any specific topics or concerns you'd like to address..."></textarea>
                        </div>

                        <div style="background: var(--gradient-soft-pink); padding: 1.5rem; border-radius: 10px; margin-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600; color: var(--primary-pink);">Total Cost:</span>
                                <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary-pink);" id="displayCost">$0</span>
                            </div>
                        </div>

                        <button type="submit" class="book-btn">
                            <i class="fas fa-check-circle"></i> Confirm Booking
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
        let bookingModal;

        function openBookingModal(counselorName, cost) {
            <?php if (!isUserLoggedIn()): ?>
                Swal.fire({
                    title: 'Login Required',
                    text: 'Please login to book a counseling session',
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

            document.getElementById('counselorName').value = counselorName;
            document.getElementById('displayCounselor').value = counselorName;
            document.getElementById('sessionCost').value = cost;
            document.getElementById('displayCost').textContent = 'GH₵ ' + cost.toLocaleString();

            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('sessionDate').min = tomorrow.toISOString().split('T')[0];

            // Reset time slot selection
            document.querySelectorAll('.time-slot').forEach(slot => slot.classList.remove('selected'));
            document.getElementById('selectedTimeSlot').value = '';

            bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
            bookingModal.show();
        }

        function selectTimeSlot(element, time) {
            document.querySelectorAll('.time-slot').forEach(slot => slot.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('selectedTimeSlot').value = time;
        }

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const timeSlot = document.getElementById('selectedTimeSlot').value;
            if (!timeSlot) {
                Swal.fire({
                    title: 'Please Select a Time',
                    text: 'Please select a time slot for your session',
                    icon: 'warning',
                    confirmButtonColor: '#d72660'
                });
                return;
            }

            const counselor = document.getElementById('counselorName').value;
            const date = document.getElementById('sessionDate').value;
            const sessionType = document.getElementById('sessionType').value;
            const cost = document.getElementById('sessionCost').value;

            // Debug logging
            console.log('Form values before submission:');
            console.log('Counselor:', counselor);
            console.log('Date:', date);
            console.log('Time:', timeSlot);
            console.log('Type:', sessionType);
            console.log('Cost:', cost);

            // Prompt for email confirmation before payment
            Swal.fire({
                title: 'Confirm Your Email',
                html: `
                    <p>Please confirm your email address for payment receipt:</p>
                    <input type="email" id="payment-email" class="swal2-input" placeholder="Your email" value="<?php echo $_SESSION['user_email'] ?? ''; ?>">
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d72660',
                confirmButtonText: 'Proceed to Payment',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const email = document.getElementById('payment-email').value;
                    if (!email || !email.includes('@')) {
                        Swal.showValidationMessage('Please enter a valid email');
                        return false;
                    }
                    return email;
                }
            }).then((result) => {
                if (!result.isConfirmed) return;

                const paymentEmail = result.value;

                Swal.fire({
                    title: 'Initializing Payment...',
                    text: 'Redirecting to payment gateway',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Prepare service data
                const serviceData = {
                    counselor_name: counselor,
                    session_date: date,
                    session_time: timeSlot,
                    session_type: sessionType,
                    session_notes: document.getElementById('sessionNotes').value
                };

                // Initialize Paystack payment
                const paymentData = new FormData();
                paymentData.append('email', paymentEmail);
                paymentData.append('amount', cost);
                paymentData.append('payment_type', 'counseling');
                paymentData.append('service_data', JSON.stringify(serviceData));

                fetch('../actions/paystack_init_transaction.php', {
                    method: 'POST',
                    body: paymentData
                })
                .then(response => {
                    // Check if response is OK
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Payment initialization response:', data);

                    if (data.status && data.data && data.data.authorization_url) {
                        // Redirect to Paystack payment page
                        Swal.fire({
                            title: 'Redirecting to Payment',
                            html: `
                                <p><strong>Amount:</strong> GH₵ ${parseFloat(cost).toLocaleString()}</p>
                                <p><strong>Counselor:</strong> ${counselor}</p>
                                <p><strong>Session Date:</strong> ${date} at ${timeSlot}</p>
                                <br>
                                <p>You will be redirected to complete your payment securely...</p>
                            `,
                            icon: 'info',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = data.data.authorization_url;
                        });
                    } else {
                        // Show detailed error message
                        console.error('Payment initialization failed:', data);
                        Swal.fire({
                            title: 'Payment Initialization Failed',
                            html: `
                                <p>${data.message || 'Unable to initialize payment. Please try again.'}</p>
                                <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 12px; text-align: left;">
                                    <strong>Debug Info:</strong><br>
                                    Status: ${data.status}<br>
                                    ${data.message ? 'Message: ' + data.message : ''}
                                </div>
                                <p style="margin-top: 15px; font-size: 11px; color: #999;">
                                    Try the <a href="../test_paystack.php" target="_blank">test page</a> to diagnose the issue.
                                </p>
                            `,
                            icon: 'error',
                            confirmButtonColor: '#d72660',
                            width: '500px'
                        });
                    }
                })
                .catch(error => {
                    console.error('Payment error:', error);
                    Swal.fire({
                        title: 'Error Processing Payment',
                        html: `
                            <p>Unable to connect to payment gateway.</p>
                            <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 12px; text-align: left;">
                                <strong>Error details:</strong><br>
                                ${error.message || 'Network error occurred'}
                            </div>
                            <p style="margin-top: 15px; font-size: 12px; color: #666;">
                                Possible causes:<br>
                                • Not logged in<br>
                                • Network connection issue<br>
                                • Payment gateway configuration<br>
                                <a href="../test_paystack.php" target="_blank">Run diagnostic test</a>
                            </p>
                        `,
                        icon: 'error',
                        confirmButtonColor: '#d72660',
                        width: '500px'
                    });
                });
            });
        });
    </script>
</body>

</html>
