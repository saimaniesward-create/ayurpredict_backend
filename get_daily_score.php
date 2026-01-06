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
$stmt = $conn->prepare("SELECT vata_score, pitta_score, kapha_score, dominant_dosha FROM dosha_scores WHERE user_id = ? AND checkin_date = ?");
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
        "dominant" => $row['dominant_dosha']
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "No score found for today"]);
}
?>