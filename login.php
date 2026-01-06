<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include "db.php";

/* Read JSON or form-data */
$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? $_POST['email'] ?? '');
$password = trim($data['password'] ?? $_POST['password'] ?? '');

/* =========================
1️⃣ EMAIL VALIDATION
========================= */
if ($email === '') {
    echo json_encode(["status" => "error", "field" => "email", "message" => "Email is required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "field" => "email", "message" => "Invalid email format"]);
    exit;
}

/* Gmail-only restriction */
if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
    echo json_encode(["status" => "error", "field" => "email", "message" => "Only Gmail addresses are allowed"]);
    exit;
}

/* =========================
2️⃣ PASSWORD EMPTY CHECK
========================= */
if ($password === '') {
    echo json_encode(["status" => "error", "field" => "password", "message" => "Password is required"]);
    exit;
}

/* =========================
3️⃣ CHECK USER & FETCH NAME
========================= */
try {
    // [FIX] We now JOIN with user_profiles to get the updated name ("Sai") if it exists
    $stmt = $conn->prepare(
        "SELECT u.id, u.name, u.password_hash, u.is_verified, p.full_name as profile_name 
         FROM users u 
         LEFT JOIN user_profiles p ON u.id = p.user_id 
         WHERE u.email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    /* =========================
    4️⃣ CHECK VERIFICATION STATUS
    ========================= */
    if (!$user) {
        echo json_encode(["status" => "error", "field" => "email", "message" => "Email not registered"]);
        exit;
    }

    if ($user['is_verified'] == 0) {
        echo json_encode(["status" => "error", "field" => "email", "message" => "Account not verified. Please verify OTP."]);
        exit;
    }

    /* =========================
    5️⃣ VERIFY PASSWORD
    ========================= */
    if (!password_verify($password, $user['password_hash'])) {
        echo json_encode(["status" => "error", "field" => "password", "message" => "Incorrect password"]);
        exit;
    }

    /* =========================
    ✅ LOGIN SUCCESS
    ========================= */
    $api_token = bin2hex(random_bytes(32)); 

    // Store token in DB
    $update = $conn->prepare("UPDATE users SET api_token = ? WHERE id = ?");
    $update->bind_param("si", $api_token, $user['id']);
    $update->execute();

    // [FIX] Choose: Profile Name ("Sai") or Registration Name ("User")
    $finalName = !empty($user['profile_name']) ? $user['profile_name'] : $user['name'];

    // [FIX] Return the proper structure
    echo json_encode([
        "status" => "success",
        "message" => "Login successful",
        "api_token" => $api_token,
        "user_id" => $user['id'], 
        "user" => [              // Android App NEEDS this!
            "id" => $user['id'],
            "name" => $finalName,
            "email" => $email,
            "is_verified" => $user['is_verified']
        ]
    ]);

} catch (mysqli_sql_exception $e) {
    echo json_encode([
        "status" => "db_error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>