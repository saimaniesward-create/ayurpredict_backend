<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';

$user_id = 15;
echo "<h1>Recalculating Streak for User $user_id</h1>";

// 1. Get all unique check-in dates DESC
$sql = "SELECT DISTINCT DATE(created_at) as cdate FROM daily_checkins WHERE user_id = $user_id ORDER BY cdate DESC";
$res = $conn->query($sql);

$dates = [];
while ($row = $res->fetch_assoc()) {
    $dates[] = $row['cdate'];
}

echo "Found " . count($dates) . " check-ins.<br>";

if (empty($dates)) {
    die("No check-ins found.");
}

// 2. Algorithm: Count consecutive days from most recent
$streak = 1;
$current = strtotime($dates[0]); // Most recent
$today = strtotime(date("Y-m-d"));

// Check if the most recent check-in is today or yesterday (active streak)
// If most recent is 2 days ago, streak is broken -> 0? Or just not incrementing?
// Usually, if you miss a day, streak is 0.
// But current logic might be: "Current active streak".

$diff_today = ($today - $current) / (60*60*24);
if ($diff_today > 1) {
    echo "Streak Broken! Last checkin was " . $dates[0] . "<br>";
    $streak = 0;
} else {
    // Iterate backwards
    for ($i = 0; $i < count($dates) - 1; $i++) {
        $d1 = strtotime($dates[$i]);
        $d2 = strtotime($dates[$i+1]);
        
        $diff = ($d1 - $d2) / (60*60*24); // Days difference
        
        if ($diff == 1) {
            $streak++;
        } else {
            break; // Gap found
        }
    }
}

echo "<h2>Calculated Streak: $streak Days</h2>";

// 3. Update Users Table
$up = $conn->query("UPDATE users SET checkin_streak = $streak WHERE id = $user_id");
if ($up) {
    echo "Database Updated Successfully.";
} else {
    echo "Update Failed: " . $conn->error;
}
?>
