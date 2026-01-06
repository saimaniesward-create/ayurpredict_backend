<?php
// get_recommendations_py.php
// A PHP Wrapper to execute the Python Recommendation Engine
header("Content-Type: application/json");
// 1. Get User ID from POST
if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
} else {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}
// 2. Define Command to Run Python
// Note: We use __DIR__ to find the folder relative to this PHP file
$pythonPath = "python"; // Assumes 'python' is in System PATH
$scriptPath = __DIR__ . "/backend_python/recommend_dosha.py"; // Path to your new Python script
// Escape arguments for safety
$escapedUserId = escapeshellarg($userId);
$command = "$pythonPath \"$scriptPath\" $escapedUserId";
// 3. Execute Command
$output = [];
$returnCode = 0;
exec($command, $output, $returnCode);
// 4. Return Output
if ($returnCode === 0 && !empty($output)) {
    // Python prints JSON to stdout, we just capture and echo it
    echo implode("\n", $output);
} else {
    // Fallback error handling
    echo json_encode([
        "status" => "error", 
        "message" => "Python script failed", 
        "debug_cmd" => $command,
        "debug_output" => $output
    ]);
}
?>