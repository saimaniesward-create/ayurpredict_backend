<?php
header("Content-Type: application/json");
error_reporting(0);
include 'db.php';

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) { echo json_encode(["status"=>"error"]); exit; }

$dates = [];
for ($i = 6; $i >= 0; $i--) { $dates[date('Y-m-d', strtotime("-$i days"))] = 0; }

$stmt = $conn->prepare("SELECT checkin_date, pitta_score FROM dosha_scores WHERE user_id = ? AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) { $dates[$row['checkin_date']] = (int)$row['pitta_score']; }

$history = [];
$valid_scores = [];
foreach ($dates as $date => $score) {
    $history[] = ["checkin_date" => $date, "pitta_score" => $score];
    if ($score > 0) $valid_scores[] = $score;
}

$insight = "Start checking in daily to track your Pitta balance.";
$trend_text = "Stable";

if (count($valid_scores) >= 2) {
    $latest = end($valid_scores);
    $oldest = reset($valid_scores);
    $diff = $latest - $oldest;
    $pct = ($oldest > 0) ? round(($diff / $oldest) * 100) : 0;
    
    $sign = ($diff > 0) ? "+" : "";
    $trend_text = "Last 7 Days $sign$pct%";

    if ($pct > 0) {
        $insight = "Your Pitta score has risen recently. You might be feeling more heat, irritability, or inflammation. This can trigger from spicy foods or excessive work pressure. Cooling activities like walking in nature and staying hydrated will help soothe this imbalance.";
    } elseif ($pct < 0) {
        $insight = "Your Pitta score is decreasing, indicating a cooling down of your system. This suggests your efforts to stay calm and avoid overheating are working. Continue with cooling foods like melons and cucumbers to maintain this healthy trend.";
    }
} elseif (count($valid_scores) == 1) {
    $trend_text = "Last 7 Days 0%";
    $insight = "We have your first Pitta reading! Log more check-ins to reveal patterns in your body's heat and transformation energy.";
}

echo json_encode(["status" => "success", "history" => $history, "insight" => $insight, "trend_text" => $trend_text]);
?>