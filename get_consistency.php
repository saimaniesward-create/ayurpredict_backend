<?php
error_reporting(0);
ini_set('display_errors', 0);
header("Content-Type: application/json; charset=UTF-8");
include "db.php"; 
$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}
// 1. Fetch Streak
$stmt = $conn->prepare("SELECT checkin_streak FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$streak = (int)($row['checkin_streak'] ?? 0);
// 2. Define Milestones/Badges
$badges = [
    [
        "title" => "3-Day Streak",
        "description" => "You've checked in for 3 days in a row!",
        "unlocked" => ($streak >= 3),
        "milestone" => 3
    ],
    [
        "title" => "7-Day Streak",
        "description" => "You've checked in for 7 days in a row!",
        "unlocked" => ($streak >= 7),
        "milestone" => 7
    ],
    [
        "title" => "14-Day Streak",
        "description" => "You've checked in for 14 days in a row!",
        "unlocked" => ($streak >= 14),
        "milestone" => 14
    ]
];
// Determine Next Goal for the Progress Bar
$nextGoal = 10;
if ($streak >= 10) $nextGoal = 30; // Scale up
echo json_encode([
    "status" => "success",
    "current_streak" => $streak,
    "next_goal" => $nextGoal,
    "badges" => $badges
]);
?>