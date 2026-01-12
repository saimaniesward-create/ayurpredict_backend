<?php
header('Content-Type: application/json');

// Get message from GET or POST
$message = "";
if (isset($_POST['message'])) {
    $message = $_POST['message'];
} elseif (isset($_GET['message'])) {
    $message = $_GET['message'];
}

if (empty($message)) {
    echo json_encode(["reply" => "Please say something!"]);
    exit();
}

// Path to Python
$pythonPath = "python"; // Or "C:\\Python39\\python.exe" if needed
$scriptPath = __DIR__ . "\\backend_python\\chat_bot.py";

// Escape the user message to be safe in shell
$escapedMessage = escapeshellarg($message);

// Execute
$command = "$pythonPath \"$scriptPath\" $escapedMessage";
$output = shell_exec($command);

// Output logic
if ($output === null) {
    echo json_encode(["reply" => "Error: No output from AI engine."]);
} else {
    // The Python script prints JSON, so we just pass it validly
    // But let's verify it is JSON first
    $decoded = json_decode($output, true);
    if ($decoded) {
        echo $output;
    } else {
        // If Python printed generic text or error
        echo json_encode(["reply" => "Raw Error: " . trim($output)]);
    }
}
?>
