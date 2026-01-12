<?php
// signup_safe.php
// This script catches ALL errors (DB + Mail) and returns valid JSON.
// Replace your signup.php with this code.

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
header("Content-Type: application/json");
include "db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// Capture output buffer to prevent stray warnings from breaking JSON
ob_start();

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Fallback to POST if JSON is empty (for testing)
    $name     = trim($data['name']     ?? $_POST['name']     ?? '');
    $phone    = trim($data['phone']    ?? $_POST['phone']    ?? '');
    $email    = trim($data['email']    ?? $_POST['email']    ?? '');
    $password = trim($data['password'] ?? $_POST['password'] ?? '');

    // --- Validation ---
    if ($email === '') throw new Exception("Email is required");
    if ($password === '') throw new Exception("Password is required");

    // --- OTP & Hash ---
    $otp = rand(100000, 999999);
    $otp_expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // --- Insert User ---
    // Note: We use the 'phone' column here. Ensure it exists!
    $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password_hash, is_verified, reset_otp, reset_otp_expires) VALUES (?, ?, ?, ?, 0, ?, ?)");
    $stmt->bind_param("ssssss", $name, $phone, $email, $password_hash, $otp, $otp_expiry);
    $stmt->execute();

    // --- Send Email ---
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'saimaniesward@gmail.com';
    $mail->Password = 'zrwt ikkz jics izkb'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('saimaniesward@gmail.com', 'AyurPredict');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Verify Your AyurPredict Account';
    $mail->Body = "<h2>Email Verification</h2><h1>$otp</h1>";
    $mail->send();

    // Clear buffer before output
    ob_end_clean();
    echo json_encode(["status" => "success", "message" => "OTP sent!", "next" => "verify_otp"]);

} catch (mysqli_sql_exception $e) {
    ob_end_clean();
    if ($e->getCode() == 1062) {
        echo json_encode(["status" => "error", "message" => "Email already registered"]);
    } else {
        // Here we see the real SQL error safely
        echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
    }

} catch (Exception $e) {
    // This catches PHPMailer or other logic errors
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Server/Mail Error: " . $e->getMessage()]);
}
?>
