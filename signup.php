<?php
/* =========================
   HARD JSON GUARANTEE
   ========================= */
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
header("Content-Type: application/json");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include "db.php";

/* =========================
   PHPMailer setup
   ========================= */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

/* =========================
   Read JSON or form-data
   ========================= */
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

$name     = trim($data['name']     ?? $_POST['name']     ?? '');
$phone    = trim($data['phone']    ?? $_POST['phone']    ?? '');
$email    = trim($data['email']    ?? $_POST['email']    ?? '');
$password = trim($data['password'] ?? $_POST['password'] ?? '');

/* =========================
   1️⃣ NAME VALIDATION
   ========================= */
if ($name === '') {
    echo json_encode(["status"=>"error","field"=>"name","message"=>"Name is required"]);
    exit;
}
if (!preg_match("/^[A-Za-z ]{2,}$/", $name)) {
    echo json_encode(["status"=>"error","field"=>"name","message"=>"Name should contain only letters and spaces"]);
    exit;
}

/* =========================
   2️⃣ EMAIL VALIDATION
   ========================= */
if ($email === '') {
    echo json_encode(["status"=>"error","field"=>"email","message"=>"Email is required"]);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status"=>"error","field"=>"email","message"=>"Invalid email format"]);
    exit;
}
if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
    echo json_encode(["status"=>"error","field"=>"email","message"=>"Only Gmail addresses are allowed"]);
    exit;
}

/* =========================
   3️⃣ PASSWORD VALIDATION
   ========================= */
if ($password === '') {
    echo json_encode(["status"=>"error","field"=>"password","message"=>"Password is required"]);
    exit;
}
if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
    echo json_encode([
        "status"=>"error",
        "field"=>"password",
        "message"=>"Password must be at least 8 chars, include uppercase, number & special char"
    ]);
    exit;
}

/* =========================
   4️⃣ PHONE VALIDATION
   ========================= */
if ($phone !== '' && !preg_match("/^[6-9]\d{9}$/", $phone)) {
    echo json_encode(["status"=>"error","field"=>"phone","message"=>"Invalid mobile number"]);
    exit;
}

/* =========================
   5️⃣ OTP + HASH
   ========================= */
$otp = rand(100000, 999999);
$otp_expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));
$password_hash = password_hash($password, PASSWORD_BCRYPT);

/* =========================
   6️⃣ INSERT USER + EMAIL
   ========================= */
try {
    $stmt = $conn->prepare(
        "INSERT INTO users
        (name, phone, email, password_hash, is_verified, reset_otp, reset_otp_expires)
        VALUES (?, ?, ?, ?, 0, ?, ?)"
    );
    $stmt->bind_param("ssssss", $name, $phone, $email, $password_hash, $otp, $otp_expiry);
    $stmt->execute();

    /* =========================
       SEND OTP EMAIL
       ========================= */
    try {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'saimaniesward@gmail.com';
        $mail->Password = 'tmcm pmar ndny eybi'; // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('saimaniesward@gmail.com', 'AyurPredict');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your AyurPredict Account';
        $mail->Body = "
            <h2>Email Verification</h2>
            <p>Your OTP:</p>
            <h1>$otp</h1>
            <p>Valid for 10 minutes</p>
        ";

        $mail->send();

        echo json_encode([
            "status" => "success",
            "message" => "OTP sent to email",
            "next" => "verify_otp"
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "OTP delivery failed: " . $mail->ErrorInfo
        ]);
    }

} catch (mysqli_sql_exception $e) {

    if ($e->getCode() == 1062) {
        echo json_encode([
            "status" => "error",
            "field" => "email",
            "message" => "Email already registered"
        ]);
    } else {
        echo json_encode([
            "status" => "db_error",
            "message" => "Server error"
        ]);
    }
}

ob_end_flush();
exit;
