<?php
error_reporting(0);
ini_set('display_errors', 0);
header("Content-Type: application/json; charset=UTF-8");
if (file_exists('db.php')) include 'db.php';
else include 'db_connect.php'; 
// Extract User ID from parameter
$user_id = $_GET['user_id'] ?? $_POST['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "No User ID found. Check login."]);
    exit;
}
// --- SECURE: Get Profile from user_profiles table ---
$stmt = $conn->prepare("SELECT u.id, u.email, p.full_name as profile_name, p.phone, p.gender, p.dob, p.country, p.profile_photo 
                        FROM users u 
                        LEFT JOIN user_profiles p ON u.id = p.user_id 
                        WHERE u.id = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => "success",
        "user" => [
            "id" => (int)$row['id'],
            "full_name" => $row['profile_name'] ?? "User", // Fallback if profile row doesn't exist yet
            "email" => $row['email'],
            "phone" => $row['phone'] ?? "", 
            "gender" => $row['gender'] ?? "",
            "dob" => $row['dob'] ?? "",
            "country" => $row['country'] ?? "",
            "profile_photo" => $row['profile_photo'] ?? ""
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Session invalid (User ID $user_id not found)"]);
}
?>