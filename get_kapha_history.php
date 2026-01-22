<?php
header("Content-Type: application/json");
error_reporting(0);
include 'db.php';

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) { echo json_encode(["status"=>"error"]); exit; }

// 1. Generate Full 7 Days (Removed - we only want Actual Data to show)
$dates = [];
// for ($i = 6; $i >= 0; $i--) { $dates[...] = -1; }

$stmt = $conn->prepare("SELECT checkin_date, kapha_score FROM dosha_scores WHERE user_id = ? AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) { $dates[$row['checkin_date']] = (int)$row['kapha_score']; }
ksort($dates);

$history = [];
$valid_scores = [];
foreach ($dates as $date => $score) {
    $graph_score = ($score == -1) ? 0 : $score;
    $history[] = ["checkin_date" => $date, "kapha_score" => $graph_score];
    if ($score !== -1) $valid_scores[] = $score;
}

$insight = "Start checking in daily to track your Kapha balance.";
$trend_text = "Stable";

if (count($valid_scores) >= 2) {
    // Compare Latest vs Previous (Day-over-Day change)
    $latest = end($valid_scores);
    $previous = prev($valid_scores);
    
    $diff = $latest - $previous;
    $pct = ($previous > 0) ? round(($diff / $previous) * 100) : 0;
    
    $sign = ($diff > 0) ? "+" : "";
    $trend_text = "Since Last Check-in $sign$pct%";

    if ($pct > 0) {
        $insight = "Your Kapha score has increased since your last check-in, which may lead to feelings of heaviness or lethargy. This can result from a sedentary lifestyle or heavy, oily foods. Incorporating vigorous exercise, waking up early, and eating light, spicy foods can help energize your system.";
    } elseif ($pct < 0) {
        $insight = "Your Kapha score is decreasing since your last check-in, showing that you are staying active and energized. This reduction is a good sign that your metabolism is stimulated. Keep up with your physical activities and varied diet to prevent stagnation.";
    } else {
         $trend_text = "Stable";
         $insight = "Your Kapha energy feels stable and grounded since yesterday. This stability supports strong immunity. Keep staying active!";
    }
} elseif (count($valid_scores) == 1) {
    $trend_text = "Last 7 Days 0%";
    $insight = "We have your first Kapha reading! Continue to log your daily habits to see how stable or heavy your energy feels over time.";
}

echo json_encode(["status" => "success", "history" => $history, "insight" => $insight, "trend_text" => $trend_text]);
?>