<?php
error_reporting(0);
ini_set('display_errors', 0);
header("Content-Type: application/json; charset=UTF-8");
include "db.php"; 
$user_id = $_GET['user_id'] ?? null;
$date = $_GET['date'] ?? date("Y-m-d");
if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}
$stmt = $conn->prepare("SELECT * FROM daily_checkins WHERE user_id = ? AND checkin_date = ?");
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => "success",
        "data" => $row
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "No check-in found for this date"]);
}
?>
