<?php
header("Content-Type: application/json");
error_reporting(0);
include 'db.php';

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) { echo json_encode(["status"=>"error"]); exit; }

// 1. Generate Full 7 Days (So graph shows empty space correctly)
$dates = [];
for ($i = 6; $i >= 0; $i--) {
    $dates[date('Y-m-d', strtotime("-$i days"))] = 0; 
}

// 2. Fetch Data & Fill
$stmt = $conn->prepare("SELECT checkin_date, vata_score FROM dosha_scores WHERE user_id = ? AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $dates[$row['checkin_date']] = (int)$row['vata_score'];
}

// 3. Format Response (ALWAYS 7 Items)
$history = [];
$valid_scores = [];
foreach ($dates as $date => $score) {
    $history[] = ["checkin_date" => $date, "vata_score" => $score];
    if ($score > 0) $valid_scores[] = $score;
}

// 4. Calculate Detailed Insight
$insight = "Start checking in daily to track your Vata balance.";
$trend_text = "Stable";

if (count($valid_scores) >= 2) {
    $latest = end($valid_scores);
    $oldest = reset($valid_scores);
    $diff = $latest - $oldest;
    $pct = ($oldest > 0) ? round(($diff / $oldest) * 100) : 0;
    
    $sign = ($diff > 0) ? "+" : "";
    $trend_text = "Last 7 Days $sign$pct%";

    if ($pct > 0) {
        $insight = "Your Vata score has increased over the past week. This elevation might be caused by irregular routines, cold weather, or anxiety. To bring it back to balance, focus on eating warm, cooked foods, keeping a consistent sleep schedule, and avoiding cold or dry environments.";
    } elseif ($pct < 0) {
        $insight = "Your Vata score has decreased slightly over the past week. This could be due to positive changes in your diet or reduced stress levels. Consider reviewing your recent activities and peaceful meals to identify what is keeping you grounded.";
    }
} elseif (count($valid_scores) == 1) {
    $trend_text = "Last 7 Days 0%";
    $insight = "We have your first reading! Continue logging your sleep and diet for a few more days to see how your Vata energy fluctuates.";
}

echo json_encode(["status" => "success", "history" => $history, "insight" => $insight, "trend_text" => $trend_text]);
?>