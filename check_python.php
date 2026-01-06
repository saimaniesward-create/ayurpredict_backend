<?php
header("Content-Type: text/plain");
echo "=== python DIAGNOSTIC TOOL ===\n\n";
// 1. Check Current Check Directory
echo "1. Current Directory: " . __DIR__ . "\n";
// 2. Check for Python in PATH
echo "2. Checking for Python in PATH...\n";
$pythonPath = shell_exec("where python");
if ($pythonPath) {
    echo "   [SUCCESS] Python found at: " . trim($pythonPath) . "\n";
} else {
    echo "   [ERROR] 'python' command NOT found. XAMPP cannot see Python.\n";
    echo "   SOLUTION: Restart your computer or just Restart Apache in XAMPP Control Panel.\n";
}
// 3. Check Python Version
echo "\n3. Checking Python Version...\n";
$version = shell_exec("python --version 2>&1");
echo "   Output: " . ($version ? trim($version) : "No output (Failed)") . "\n";
// 4. Check Connection to Script
echo "\n4. Testing predict_dosha.py...\n";
$startScript = __DIR__ . DIRECTORY_SEPARATOR . "backend_python" . DIRECTORY_SEPARATOR . "predict_dosha.py";
echo "   Looking for script at: $startScript\n";
if (file_exists($startScript)) {
    echo "   [SUCCESS] File exists.\n";
    
    // Try running it
    echo "   Attempting to run script with User ID 1...\n";
    $cmd = "python \"$startScript\" 1 2>&1";
    $output = shell_exec($cmd);
    echo "\n   --- SCRIPT OUTPUT START ---\n";
    echo $output;
    echo "\n   --- SCRIPT OUTPUT END ---\n";
} else {
    echo "   [ERROR] File NOT found!\n";
    echo "   Make sure you copied the 'backend_python' folder into '" . __DIR__ . "'\n";
}
echo "\n=== END DIAGNOSTIC ===";
?>