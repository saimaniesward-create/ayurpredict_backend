<?php
// get_recommendations_py.php
header("Content-Type: application/json");
error_reporting(0);
// --- 1. DEBUG LOGGING ---
$logFile = __DIR__ . "/php_debug.log";
$method = $_SERVER['REQUEST_METHOD'];
$rawInput = file_get_contents('php://input');
file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] Method: $method | Raw: $rawInput\n", FILE_APPEND);
// --- 2. UNIVERSAL INPUT HANDLING ---
$userId = null;
// A. Try JSON Body (Retrofit @Body often sends this)
$jsonData = json_decode($rawInput, true);
if (isset($jsonData['user_id'])) {
    $userId = $jsonData['user_id'];
}
// B. Try POST/GET Parameters (Retrofit @Field or Browser params)
if (!$userId && isset($_REQUEST['user_id'])) {
    $userId = $_REQUEST['user_id'];
}
file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] Extracted UserID: " . ($userId ? $userId : "NULL") . "\n", FILE_APPEND);
if (!$userId) {
    echo json_encode(["status" => "error", "message" => "User ID required (Not found in POST/JSON)"]);
    exit;
}
// --- 3. EXECUTE PYTHON ---
$pythonPath = "python";
$scriptPath = __DIR__ . "/backend_python/recommend_dosha.py";
$escapedUserId = escapeshellarg($userId);
$command = "$pythonPath \"$scriptPath\" $escapedUserId";
$output = [];
$returnCode = 0;
exec($command, $output, $returnCode);
// --- 4. OUTPUT HANDLING ---
if ($returnCode === 0 && !empty($output)) {
    $finalJson = implode("\n", $output);
    echo $finalJson;
    // Log success (shortened)
    file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] Success. Output Len: " . strlen($finalJson) . "\n", FILE_APPEND);
} else {
    $errJson = json_encode([
        "status" => "error", 
        "message" => "Python execution failed/empty", 
        "debug_cmd" => $command,
        "debug_output" => $output
    ]);
    echo $errJson;
    file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] FAILED. " . $errJson . "\n", FILE_APPEND);
}
?>