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

    // Debug logging - log received data
    error_log("Booking attempt - User: $user_id, Counselor: $counselor_name, Date: $session_date, Time: $session_time, Type: $session_type, Cost: $cost");

    // Validate inputs
    if (empty($counselor_name)) {
        throw new Exception('Counselor name is missing');
    }
    if (empty($session_date)) {
        throw new Exception('Session date is missing');
    }
    if (empty($session_time)) {
        throw new Exception('Session time is missing');
    }
    if (empty($session_type)) {
        throw new Exception('Session type is missing');
    }

    if ($cost <= 0) {
        throw new Exception('Invalid session cost: ' . $cost);
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

    error_log("Booking result: " . print_r($booking_result, true));

    if ($booking_result && isset($booking_result['booking_reference'])) {
        $response['status'] = 'success';
        $response['message'] = 'Session booked successfully';
        $response['booking_reference'] = $booking_result['booking_reference'];
        $response['order_id'] = $booking_result['order_id'];
    } else {
        throw new Exception('Failed to create booking. Database operation returned false.');
    }

} catch (Exception $e) {
    error_log("Booking error: " . $e->getMessage());
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
