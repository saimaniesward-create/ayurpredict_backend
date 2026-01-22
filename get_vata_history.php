<?php
header("Content-Type: application/json");
error_reporting(0);
include 'db.php';

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) { echo json_encode(["status"=>"error"]); exit; }

// 1. Generate Full 7 Days (So graph shows empty space correctly)
// 1. Generate Full 7 Days (Removed - we only want Actual Data to show)
$dates = []; 
// for ($i = 6; $i >= 0; $i--) { $dates[...] = -1; }

// 2. Fetch Data & Fill
$stmt = $conn->prepare("SELECT checkin_date, vata_score FROM dosha_scores WHERE user_id = ? AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $dates[$row['checkin_date']] = (int)$row['vata_score'];
}
ksort($dates);

// 3. Format Response (ALWAYS 7 Items)
$history = [];
$valid_scores = [];
foreach ($dates as $date => $score) {
    // For the graph: -1 (missing) becomes 0
    $graph_score = ($score == -1) ? 0 : $score;
    $history[] = ["checkin_date" => $date, "vata_score" => $graph_score];
    
    // For calculation: Only include if REAL data exists (score != -1)
    if ($score !== -1) $valid_scores[] = $score;
}

// 4. Calculate Detailed Insight
$insight = "Start checking in daily to track your Vata balance.";
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
        $insight = "Your Vata score has increased since your last check-in. This elevation might be caused by irregular routines, cold weather, or anxiety. To bring it back to balance, focus on eating warm, cooked foods, keeping a consistent sleep schedule, and avoiding cold or dry environments.";
    } elseif ($pct < 0) {
        $insight = "Your Vata score has decreased since your last check-in. This could be due to positive changes in your diet or reduced stress levels. Consider reviewing your recent activities and peaceful meals to identify what is keeping you grounded.";
    } else {
         $trend_text = "Stable";
         $insight = "Your Vata energy has remained stable since yesterday. Consistency is key to health in Ayurveda. Keep up your routine!";
    }
} elseif (count($valid_scores) == 1) {
    $trend_text = "Last 7 Days 0%";
    $insight = "We have your first reading! Continue logging your sleep and diet for a few more days to see how your Vata energy fluctuates.";
}

echo json_encode(["status" => "success", "history" => $history, "insight" => $insight, "trend_text" => $trend_text]);
?>