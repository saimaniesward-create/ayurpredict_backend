<?php
header("Content-Type: application/json");
error_reporting(0);
include 'db.php';

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) { echo json_encode(["status"=>"error"]); exit; }

$dates = [];
for ($i = 6; $i >= 0; $i--) { $dates[date('Y-m-d', strtotime("-$i days"))] = 0; }

$stmt = $conn->prepare("SELECT checkin_date, kapha_score FROM dosha_scores WHERE user_id = ? AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) { $dates[$row['checkin_date']] = (int)$row['kapha_score']; }

$history = [];
$valid_scores = [];
foreach ($dates as $date => $score) {
    $history[] = ["checkin_date" => $date, "kapha_score" => $score];
    if ($score > 0) $valid_scores[] = $score;
}

$insight = "Start checking in daily to track your Kapha balance.";
$trend_text = "Stable";

if (count($valid_scores) >= 2) {
    $latest = end($valid_scores);
    $oldest = reset($valid_scores);
    $diff = $latest - $oldest;
    $pct = ($oldest > 0) ? round(($diff / $oldest) * 100) : 0;
    
    $sign = ($diff > 0) ? "+" : "";
    $trend_text = "Last 7 Days $sign$pct%";

    if ($pct > 0) {
        $insight = "Your Kapha score has increased, which may lead to feelings of heaviness or lethargy. This can result from a sedentary lifestyle or heavy, oily foods. Incorporating vigorous exercise, waking up early, and eating light, spicy foods can help energize your system.";
    } elseif ($pct < 0) {
        $insight = "Your Kapha score is decreasing, showing that you are staying active and energized. This reduction is a good sign that your metabolism is stimulated. Keep up with your physical activities and varied diet to prevent stagnation.";
    }
} elseif (count($valid_scores) == 1) {
    $trend_text = "Last 7 Days 0%";
    $insight = "We have your first Kapha reading! Continue to log your daily habits to see how stable or heavy your energy feels over time.";
}

echo json_encode(["status" => "success", "history" => $history, "insight" => $insight, "trend_text" => $trend_text]);
?>