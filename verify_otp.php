<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header("Content-Type: application/json");
session_start();
include "db.php";

/* Read JSON or form-data */
$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? $_POST['email'] ?? '');
$otp   = trim((string)($data['otp'] ?? $_POST['otp'] ?? ''));

/* =========================
   1️⃣ EMAIL VALIDATION
   ========================= */
if ($email === '') {
    echo json_encode([
        "status" => "error",
        "field" => "email",
        "message" => "Email is required"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "field" => "email",
        "message" => "Invalid email format"
    ]);
    exit;
}

/* =========================
   2️⃣ OTP VALIDATION
   ========================= */
if ($otp === '') {
    echo json_encode([
        "status" => "error",
        "field" => "otp",
        "message" => "OTP is required"
    ]);
    exit;
}

if (!preg_match('/^\d{6}$/', $otp)) {
    echo json_encode([
        "status" => "error",
        "field" => "otp",
        "message" => "OTP must be a 6-digit number"
    ]);
    exit;
}

try {
    /* =========================
       3️⃣ FETCH OTP
       ========================= */
    $stmt = $conn->prepare(
        "SELECT reset_otp, reset_otp_expires
         FROM users
         WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row || !$row['reset_otp']) {
        echo json_encode([
            "status" => "invalid",
            "message" => "OTP not found"
        ]);
        exit;
    }

    /* =========================
       4️⃣ VERIFY OTP + EXPIRY
       ========================= */
    if (
        $row['reset_otp'] === $otp &&
        strtotime($row['reset_otp_expires']) > time()
    ) {

        // ✅ Mark OTP as verified (invalidate it for reuse)
        $update = $conn->prepare(
            "UPDATE users
             SET reset_otp = NULL,
                 reset_otp_expires = NULL
             WHERE email = ?"
        );
        $update->bind_param("s", $email);
        $update->execute();

        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_verified'] = true;

        echo json_encode([
            "status" => "verified",
            "message" => "OTP verified successfully. You may reset your password."
        ]);
        exit;
    }

    echo json_encode([
        "status" => "invalid",
        "message" => "Invalid or expired OTP"
    ]);

} catch (mysqli_sql_exception $e) {
    echo json_encode([
        "status" => "db_error",
        "message" => "Server error"
    ]);
}
?>
