<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header("Content-Type: application/json");
include "db.php";

/* Read JSON or form-data */
$data = json_decode(file_get_contents("php://input"), true);

// FIX 1: specific checks for $_POST if $data is empty
$email = $data['email'] ?? $_POST['email'] ?? '';
$otp   = $data['otp']   ?? $_POST['otp']   ?? '';

if (empty($email) || empty($otp)) {
    echo json_encode([
        "status" => "error",
        "message" => "Email and OTP are required"
    ]);
    exit;
}

try {
    // Fetch user
    $query = "SELECT reset_otp, reset_otp_expires, is_verified FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode([
            "status" => "error",
            "message" => "User not found"
        ]);
        exit;
    }

    $user = $result->fetch_assoc();

    // Already verified
    if ($user['is_verified'] == 1) {
        // FIX 2: Return "verified" so the app proceeds
        echo json_encode([
            "status" => "verified",
            "message" => "Account already verified"
        ]);
        exit;
    }

    // Check OTP
    if ($otp !== $user['reset_otp']) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid OTP"
        ]);
        exit;
    }

    // Check expiry
    if (strtotime($user['reset_otp_expires']) < time()) {
        echo json_encode([
            "status" => "error",
            "message" => "OTP expired"
        ]);
        exit;
    }

    // Verify account
    $update = "UPDATE users 
               SET is_verified = 1, reset_otp = NULL, reset_otp_expires = NULL 
               WHERE email = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // FIX 3: Return "verified" to match Android App check
    echo json_encode([
        "status" => "verified",
        "message" => "Account verified successfully"
    ]);

} catch (mysqli_sql_exception $e) {
    echo json_encode([
        "status" => "db_error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>