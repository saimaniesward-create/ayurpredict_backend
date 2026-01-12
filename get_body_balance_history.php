<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
require_once 'db.php';
// [FIX] Seed Random Number Generator with Today's Date
// This ensures the "Random" score is the SAME for the whole day across all screens.
srand(strtotime(date('Y-m-d')));
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if (!$user_id) { echo json_encode(["status"=>"error"]); exit; }
// 1. Generate Full 7 Days (for the standardized layout)
$dates = [];
for ($i = 6; $i >= 0; $i--) { 
    $dates[date('Y-m-d', strtotime("-$i days"))] = 0; 
}
// 2. Fetch Data
$stmt = $conn->prepare("SELECT checkin_date, score as body_balance_score FROM body_balance_scores WHERE user_id = ? AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $dates[$row['checkin_date']] = (int)$row['body_balance_score'];
}
// 3. Format Response
$history = [];
$valid_scores = [];
foreach ($dates as $date => $score) {
    // [FIX] REMOVED random fake score generation
    // Real data only.
    
    $history[] = ["checkin_date" => $date, "body_balance_score" => $score];
    if ($score > 0) $valid_scores[] = $score;
}
// 4. Calculate Insight & Trend
$insight = "Start checking in daily to track your balance.";
$trend_text = "--"; // Default for new users

if (count($valid_scores) >= 2) {
    $latest = end($valid_scores);
    $oldest = reset($valid_scores);
    $diff = $latest - $oldest;
    // Avoid division by zero if oldest was magically 0 (unlikely now)
    $pct = ($oldest > 0) ? round(($diff / $oldest) * 100) : 0;
    
    $sign = ($diff > 0) ? "+" : "";
    $trend_text = "Last 7 Days $sign$pct%";
    if ($pct > 0) {
        $insight = "Your body balance is improving! Your consistent efforts are paying off. Keep up the great routine.";
    } elseif ($pct < 0) {
        $insight = "Your body balance has declined recently. This could be due to stress, irregular sleep, or diet choices. Consider reviewing your daily habits to get back on track.";
    } else {
        $insight = "Your body balance remains stable. Consistency is key to long-term health!";
    }
} elseif (count($valid_scores) == 1) {
    $trend_text = "Last 7 Days 0%";
    $insight = "We have your first reading! Log more check-ins to see your comprehensive balance trend.";
}
echo json_encode(["status" => "success", "history" => $history, "insight" => $insight, "trend_text" => $trend_text]);
?>