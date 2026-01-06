<?php
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
$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? $_POST['email'] ?? '');

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

try {
    /* =========================
       2️⃣ CHECK EMAIL EXISTS
       ========================= */
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    if ($stmt->get_result()->num_rows === 0) {
        echo json_encode([
            "status" => "error",
            "field" => "email",
            "message" => "Email not registered"
        ]);
        exit;
    }

    /* =========================
       3️⃣ GENERATE OTP
       ========================= */
    $otp = rand(100000, 999999);
    $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    /* =========================
       4️⃣ STORE OTP IN USERS
       ========================= */
    $stmt = $conn->prepare(
        "UPDATE users
         SET reset_otp = ?, reset_otp_expires = ?
         WHERE email = ?"
    );
    $stmt->bind_param("sss", $otp, $expires, $email);
    $stmt->execute();

    /* =========================
       5️⃣ SEND OTP TO EMAIL
       ========================= */
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // 🔴 CHANGE THESE TWO LINES ONLY
    $mail->Username = 'saimaniesward@gmail.com';      // YOUR GMAIL
    $mail->Password = 'zrwt ikkz jics izkb';        // GMAIL APP PASSWORD

    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('yourgmail@gmail.com', 'AyurPredict');
    $mail->addAddress($email);

    $mail->Subject = 'Your OTP for Password Reset';
    $mail->Body =
        "Your OTP is: $otp\n\n" .
        "This OTP is valid for 10 minutes.\n\n" .
        "If you did not request this, please ignore.";

    $mail->send();

    echo json_encode([
        "status" => "otp_sent",
        "message" => "OTP sent to your email"
    ]); 

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "OTP could not be sent. Try again."
    ]);
}
?>
