<?php
header("Content-Type: application/json");
require 'db.php';
$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'] ?? null;
$vata = $data['vata'] ?? 0;
$pitta = $data['pitta'] ?? 0;
$kapha = $data['kapha'] ?? 0;
$checkin_date = date("Y-m-d");
if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}
$imbalance = abs($vata - 50) + abs($pitta - 50) + abs($kapha - 50);
$score = 100 - $imbalance;
if ($score < 0) $score = 0;
if ($score > 100) $score = 100;
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