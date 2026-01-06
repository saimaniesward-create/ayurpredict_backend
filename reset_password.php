<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();
include "db.php";

/* Read JSON or form-data */
$data = json_decode(file_get_contents("php://input"), true);

$email    = trim($data['email'] ?? $_POST['email'] ?? '');
$password = trim($data['password'] ?? $_POST['password'] ?? '');

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

if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
    echo json_encode([
        "status" => "error",
        "field" => "email",
        "message" => "Only Gmail addresses are allowed"
    ]);
    exit;
}

/* =========================
   2️⃣ CHECK SESSION VERIFICATION (SECURITY FIX)
   ========================= */
if (!isset($_SESSION['reset_verified']) || $_SESSION['reset_verified'] !== true || $_SESSION['reset_email'] !== $email) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized password reset request. Please verify OTP first."
    ]);
    exit;
}

/* =========================
   3️⃣ PASSWORD VALIDATION
   ========================= */
if ($password === '') {
    echo json_encode([
        "status" => "error",
        "field" => "password",
        "message" => "Password is required"
    ]);
    exit;
}

if (!preg_match(
    "/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
    $password
)) {
    echo json_encode([
        "status" => "error",
        "field" => "password",
        "message" =>
            "Password must be at least 8 characters and include 1 uppercase letter, 1 number, and 1 special character"
    ]);
    exit;
}

/* =========================
   4️⃣ UPDATE PASSWORD + CLEAR OTP
   ========================= */
$password_hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare(
    "UPDATE users
     SET password_hash = ?,
         reset_otp = NULL,
         reset_otp_expires = NULL
     WHERE email = ?"
);
$stmt->bind_param("ss", $password_hash, $email);
$stmt->execute();

echo json_encode([
    "status" => "password_updated",
    "message" => "Password reset successful"
]);
?>
