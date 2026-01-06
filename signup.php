<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header("Content-Type: application/json");
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
$data = json_decode(file_get_contents("php://input"), true);

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
        "message"=>"Password must be at least 8 characters and include 1 uppercase, 1 number, and 1 special character"
    ]);
    exit;
}

/* =========================
   4️⃣ PHONE VALIDATION (OPTIONAL)
   ========================= */
if ($phone !== '' && !preg_match("/^[6-9]\d{9}$/", $phone)) {
    echo json_encode(["status"=>"error","field"=>"phone","message"=>"Enter valid 10-digit mobile number"]);
    exit;
}

/* =========================
   5️⃣ GENERATE OTP
   ========================= */
$otp = rand(100000, 999999);
$otp_expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

/* =========================
   6️⃣ HASH PASSWORD
   ========================= */
$password_hash = password_hash($password, PASSWORD_BCRYPT);

/* =========================
   7️⃣ INSERT USER & SEND OTP
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
       SEND OTP EMAIL (INLINE)
       ========================= */
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // 🔴 SAME CREDENTIALS AS forgot_password.php
    $mail->Username = 'saimaniesward@gmail.com';
    $mail->Password = 'zrwt ikkz jics izkb'; // Gmail App Password

    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('saimaniesward@gmail.com', 'AyurPredict');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Verify Your AyurPredict Account';

    $mail->Body = "
        <h2>Email Verification</h2>
        <p>Your OTP is:</p>
        <h1>$otp</h1>
        <p>This OTP is valid for 10 minutes.</p>
        <p>If you did not request this, please ignore.</p>
    ";

    $mail->send();

    echo json_encode([
        "status" => "success",
        "message" => "OTP sent to your email. Please verify your account.",
        "next" => "verify_otp"
    ]);

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
?>
