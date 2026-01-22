<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
require_once 'db.php';
// [FIX] Seed Random Number Generator with Today's Date
// This ensures the "Random" score is the SAME for the whole day across all screens.
srand(strtotime(date('Y-m-d')));
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if (!$user_id) { echo json_encode(["status"=>"error"]); exit; }
// 1. Generate Full 7 Days
// 1. Generate Full 7 Days (Removed - we only want Actual Data to show)
$dates = [];
// for ($i = 6; $i >= 0; $i--) { $dates[...] = -1; }

// 2. Fetch Data
$stmt = $conn->prepare("SELECT checkin_date, score as body_balance_score FROM body_balance_scores WHERE user_id = ? AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $dates[$row['checkin_date']] = (int)$row['body_balance_score'];
}
ksort($dates);

// 3. Format Response
$history = [];
$valid_scores = [];
foreach ($dates as $date => $score) {
    // For Graph: -1 becomes 0
    $graph_score = ($score == -1) ? 0 : $score;
    $history[] = ["checkin_date" => $date, "body_balance_score" => $graph_score];
    
    // For Calc: Keep valid scores (including 0)
    if ($score !== -1) $valid_scores[] = $score;
}

// 4. Calculate Insight & Trend
$insight = "Start checking in daily to track your balance.";
$trend_text = "--"; 

if (count($valid_scores) >= 2) {
    // Compare Latest vs Previous (Day-over-Day)
    $latest = end($valid_scores);
    $previous = prev($valid_scores);
    
    $diff = $latest - $previous;
    $pct = ($previous > 0) ? round(($diff / $previous) * 100) : 0;
    
    $sign = ($diff > 0) ? "+" : "";
    $trend_text = "Since Last Check-in $sign$pct%";
    
    if ($pct > 0) {
        $insight = "Your body balance is improving since your last check-in! Your consistent efforts are paying off. Keep up the great routine.";
    } elseif ($pct < 0) {
        $insight = "Your body balance has declined since your last check-in. This could be due to stress, irregular sleep, or diet choices. Consider reviewing your daily habits to get back on track.";
    } else {
        $insight = "Your body balance remains stable since yesterday. Consistency is key to long-term health!";
    }
} elseif (count($valid_scores) == 1) {
    $trend_text = "Last 7 Days 0%";
    $insight = "We have your first reading! Log more check-ins to see your comprehensive balance trend.";
}
echo json_encode(["status" => "success", "history" => $history, "insight" => $insight, "trend_text" => $trend_text]);
?>