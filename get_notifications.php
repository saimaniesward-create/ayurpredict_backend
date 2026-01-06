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
$stmt = $conn->prepare("SELECT id, title, message, type, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = [];
while ($row = $result->fetch_assoc()) {
    // Format timestamp: "Today, 9:00 AM" or "July 22, 10:00 AM"
    $ts = strtotime($row['created_at']);
    $dateStr = date('Y-m-d', $ts);
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    if ($dateStr == $today) {
        $timestamp = "Today, " . date('g:i A', $ts);
    } elseif ($dateStr == $yesterday) {
        $timestamp = "Yesterday, " . date('g:i A', $ts);
    } else {
        $timestamp = date('F j, g:i A', $ts);
    }
    $data[] = [
        "id" => (int)$row['id'],
        "title" => $row['title'],
        "message" => $row['message'],
        "type" => $row['type'],
        "timestamp" => $timestamp
    ];
}
echo json_encode(["status" => "success", "data" => $data]);
?>