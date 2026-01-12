<?php
include "db.php";

// 1. Get Distinct Categories
$sql = "SELECT DISTINCT category FROM recommendations";
$result = $conn->query($sql);

echo "--- CATEGORIES ---\n";
while($row = $result->fetch_assoc()) {
    echo $row['category'] . "\n";
}

// 2. Search for "breathing" or "pranayama" in content
$sql2 = "SELECT id, category, dosha, content FROM recommendations WHERE content LIKE '%breath%' OR content LIKE '%pranayama%' LIMIT 10";
$result2 = $conn->query($sql2);

echo "\n--- BREATHING/PRANAYAMA SEARCH ---\n";
while($row = $result2->fetch_assoc()) {
    echo "[{$row['category']} - {$row['dosha']}] {$row['content']}\n";
}
?>
