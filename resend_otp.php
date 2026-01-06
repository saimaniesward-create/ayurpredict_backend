<?php
header("Content-Type: application/json");
include "db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

/* Read JSON or form-data */
$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? $_POST['email'] ?? '');

if ($email === '') {
    echo json_encode([
        "status" => "error",
        "message" => "Email is required"
    ]);
    exit;
}

/* Check user */
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
    exit;
}

/* Generate OTP */
$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

/* Update OTP */
$update = $conn->prepare(
    "UPDATE users 
     SET reset_otp = ?, reset_otp_expires = ? 
     WHERE email = ?"
);
$update->bind_param("sss", $otp, $expiry, $email);
$update->execute();

/* Send OTP via Email (PHPMailer) */
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'saimaniesward@gmail.com';
    $mail->Password = 'zrwt ikkz jics izkb'; // Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('saimaniesward@gmail.com', 'AyurPredict');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'AyurPredict - New OTP Request';
    $mail->Body = "
        <h2>New OTP Request</h2>
        <p>Your new OTP is:</p>
        <h1>$otp</h1>
        <p>This OTP is valid for 10 minutes.</p>
    ";

    $mail->send();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Mailer Error: " . $mail->ErrorInfo]);
    exit;
}

echo json_encode([
    "status" => "success",
    "message" => "OTP resent successfully"
]);
