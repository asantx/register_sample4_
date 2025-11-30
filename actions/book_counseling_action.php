<?php
session_start();
header('Content-Type: application/json');

$response = array();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Please login to book a session';
    echo json_encode($response);
    exit();
}

require_once '../controllers/order_controller.php';

try {
    // Get form data
    $counselor_name = $_POST['counselorName'] ?? '';
    $session_date = $_POST['sessionDate'] ?? '';
    $session_time = $_POST['selectedTimeSlot'] ?? '';
    $session_type = $_POST['sessionType'] ?? '';
    $cost = $_POST['sessionCost'] ?? 0;
    $session_notes = $_POST['sessionNotes'] ?? '';
    $user_id = $_SESSION['user_id'];

    // Validate inputs
    if (empty($counselor_name) || empty($session_date) || empty($session_time) || empty($session_type)) {
        throw new Exception('Please fill in all required fields');
    }

    if ($cost <= 0) {
        throw new Exception('Invalid session cost');
    }

    // Create booking
    $booking_result = create_counseling_booking_ctr(
        $user_id,
        $counselor_name,
        $session_date,
        $session_time,
        $session_type,
        $cost,
        $session_notes
    );

    if ($booking_result && isset($booking_result['booking_reference'])) {
        $response['status'] = 'success';
        $response['message'] = 'Session booked successfully';
        $response['booking_reference'] = $booking_result['booking_reference'];
        $response['order_id'] = $booking_result['order_id'];
    } else {
        throw new Exception('Failed to create booking. Please try again.');
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
