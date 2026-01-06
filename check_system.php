<?php
// check_system.php
// Diagnostic tool to check why Python isn't running
header("Content-Type: text/plain");
echo "--- SYSTEM DIAGNOSTICS ---\n";
// 1. Check if exec() is enabled
if(function_exists('exec')) {
    echo "[PASS] exec() function is enabled.\n";
} else {
    echo "[FAIL] exec() function is DISABLED in php.ini.\n";
}
// 2. Check Python Version
$output = [];
$code = -1;
exec("python --version 2>&1", $output, $code);
if ($code === 0) {
    echo "[PASS] Python is found: " . implode(" ", $output) . "\n";
} else {
    echo "[FAIL] 'python' command not found. Try 'python3' or check PATH.\n";
    print_r($output);
}
// 3. Check Directory Permissions
$testFile = __DIR__ . "/backend_python/test_write.txt";
if (file_put_contents($testFile, "test")) {
    echo "[PASS] PHP can write to backend_python folder.\n";
    unlink($testFile);
} else {
    echo "[FAIL] PHP cannot write to backend_python folder (Permission Denied).\n";
}
// 4. Try Running the Script Directly
echo "\n--- ATTEMPTING TO RUN RECOMMENDATION SCRIPT ---\n";
$script = __DIR__ . "/backend_python/recommend_dosha.py";
if (file_exists($script)) {
    echo "[PASS] Script file exists at: $script\n";
    
    // Run validation
    $cmd = "python \"$script\" 1 2>&1"; // Dummy user ID 1
    echo "Command: $cmd\n";
    $out = [];
    $ret = -1;
    exec($cmd, $out, $ret);
    
    echo "Return Code: $ret\n";
    echo "Output:\n";
    print_r($out);
} else {
    echo "[FAIL] Script file NOT found at: $script\n";
}
?>