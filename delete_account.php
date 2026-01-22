<?php
header("Content-Type: application/json");
error_reporting(0);
include 'db.php';

$user_id = $_POST['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

// Start Transaction to ensure all data is deleted or none
$conn->begin_transaction();

try {
    // 1. Delete Daily Checkins
    $stmt = $conn->prepare("DELETE FROM daily_checkins WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 2. Delete Dosha Scores
    $stmt = $conn->prepare("DELETE FROM dosha_scores WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 3. Delete Body Balance Scores
    $stmt = $conn->prepare("DELETE FROM body_balance_scores WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 4. Delete Notifications
    $stmt = $conn->prepare("DELETE FROM notifications WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 5. Delete User Profile
    $stmt = $conn->prepare("DELETE FROM user_profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 6. Delete Main User Account
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Commit changes
    $conn->commit();

    echo json_encode(["status" => "success", "message" => "Account deleted successfully"]);

} catch (Exception $e) {
    // Rollback if any error occurs
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => "Deletion failed: " . $e->getMessage()]);
}

$conn->close();
?>
