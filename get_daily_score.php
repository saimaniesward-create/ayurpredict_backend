<?php
header("Content-Type: application/json");
error_reporting(0);
include "db.php";

$user_id = $_GET['user_id'] ?? null;
$checkin_date = date("Y-m-d");

if (!$user_id) {
    echo json_encode(["status"=>"error", "message"=>"User ID required"]);
    exit;
}

// Fetch Today's Score
// Fetch Today's Score
$stmt = $conn->prepare("
    SELECT ds.vata_score, ds.pitta_score, ds.kapha_score, ds.dominant_dosha, bbs.score as balance_score 
    FROM dosha_scores ds
    LEFT JOIN body_balance_scores bbs ON ds.user_id = bbs.user_id AND ds.checkin_date = bbs.checkin_date
    WHERE ds.user_id = ? AND ds.checkin_date = ?
");
$stmt->bind_param("is", $user_id, $checkin_date);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => "success",
        "scores" => [
            "Vata" => $row['vata_score'],
            "Pitta" => $row['pitta_score'],
            "Kapha" => $row['kapha_score']
        ],
        "dominant" => $row['dominant_dosha'],
        "body_balance_score" => (int)$row['balance_score'] // Return Balance Score
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "No score found for today"]);
}
?>