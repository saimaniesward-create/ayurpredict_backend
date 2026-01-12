<?php
header("Content-Type: application/json");
require 'db.php';

// Input: { "user_id": 1, "vata": 60, "pitta": 40, "kapha": 30 }
$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'] ?? null;
$vata = $data['vata'] ?? 0;
$pitta = $data['pitta'] ?? 0;
$kapha = $data['kapha'] ?? 0;
$checkin_date = date("Y-m-d"); // Assume calculating for today

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

// 1. Calculate Score
// Fix: Handle 0 input (No Checkin) and use Subtractive Formula
$total_imbalance = $vata + $pitta + $kapha;

if ($total_imbalance == 0) {
    $score = 0; // No valid check-in data found
} else {
    // Formula: Default 100 - (Total Imbalance / 3)
    // Higher VPK score means more symptoms => Lower Balance Score
    $score = 100 - ($total_imbalance / 3);
    
    // Clamp between 10 (min) and 100 (max)
    if ($score < 10) $score = 10;
    if ($score > 100) $score = 100;
}

// 2. Save to Separate Table
$stmt = $conn->prepare("INSERT INTO body_balance_scores (user_id, checkin_date, score) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE score=VALUES(score)");
$stmt->bind_param("isi", $user_id, $checkin_date, $score);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Balance Score Calculated",
        "body_balance_score" => $score
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Database Error"]);
}
?>
