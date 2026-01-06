<?php
error_reporting(0);
ini_set('display_errors', 0);
header("Content-Type: application/json; charset=UTF-8");
include "db.php"; 
$user_id = $_POST['user_id'] ?? $_GET['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "Authentication failed (No User ID)"]);
    exit;
}
// ... (Rest of update logic same as before, ensuring it returns success) ...
// --- REVISED UPDATE PROFILE ---
$name = $_POST['full_name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? ''; // Added phone
$gender = $_POST['gender'] ?? '';
$dob = $_POST['dob'] ?? null;
$country = $_POST['country'] ?? '';
// Handle Profile Photo Upload
$photo_path = null;
if (isset($_FILES['profile_photo'])) {
    $target_dir = "uploads/profiles/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $file_extension = pathinfo($_FILES["profile_photo"]["name"], PATHINFO_EXTENSION);
    $new_filename = $user_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
        $photo_path = "http://10.154.63.223/ayurpredict/" . $target_file;
    }
}
// UPDATE user_profiles table (Insert if not exists)
if ($photo_path) {
    $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, full_name, phone, gender, dob, country, profile_photo) 
                            VALUES (?, ?, ?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE full_name=VALUES(full_name), phone=VALUES(phone), 
                            gender=VALUES(gender), dob=VALUES(dob), country=VALUES(country), 
                            profile_photo=VALUES(profile_photo)");
    $stmt->bind_param("issssss", $user_id, $name, $phone, $gender, $dob, $country, $photo_path);
} else {
    $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, full_name, phone, gender, dob, country) 
                            VALUES (?, ?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE full_name=VALUES(full_name), phone=VALUES(phone), 
                            gender=VALUES(gender), dob=VALUES(dob), country=VALUES(country)");
    $stmt->bind_param("isssss", $user_id, $name, $phone, $gender, $dob, $country);
}
if ($stmt->execute()) {
    // Optionally also update the name in the users table for consistency
    $stmt_u = $conn->prepare("UPDATE users SET name=? WHERE id=?");
    $stmt_u->bind_param("si", $name, $user_id);
    $stmt_u->execute();
    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "DB Error: " . $conn->error]);
}
?>