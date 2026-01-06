<?php
header("Content-Type: application/json; charset=UTF-8");

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

// 1. Point to the Python script (Relative Path)
// This looks for proper folder structure: htdocs/ayurpredict/backend_python/
$pythonScript = __DIR__ . DIRECTORY_SEPARATOR . "backend_python" . DIRECTORY_SEPARATOR . "predict_dosha.py";

// 2. Execute Python
// Ensure 'python' is in your Windows System PATH
$command = "python \"$pythonScript\" $user_id 2>&1";

$output = shell_exec($command);

// 3. Return Output
if ($output) {
    echo $output;
} else {
    echo json_encode(["status" => "error", "message" => "Python execution failed. Check paths."]);
}
?>