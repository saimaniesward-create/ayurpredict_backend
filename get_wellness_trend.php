<?php
header("Content-Type: application/json");
error_reporting(0);
include 'db.php';

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["status"=>"error", "message"=>"User ID required"]);
    exit;
}

// Fetch Last 7 Days of Body Balance Score from NEW TABLE
$stmt = $conn->prepare("
    SELECT checkin_date, score as body_balance_score 
    FROM body_balance_scores 
    WHERE user_id = ? 
    ORDER BY checkin_date DESC 
    LIMIT 7
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
while ($row = $result->fetch_assoc()) {
    // Ensure we send an integer, default to 0 if null
    $row['body_balance_score'] = (int)($row['body_balance_score'] ?? 0);
    $history[] = $row;
}

// Reverse so it goes Mon -> Sun
echo json_encode([
    "status" => "success", 
    "history" => array_reverse($history)
]);
?>
