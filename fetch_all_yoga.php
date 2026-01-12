<?php
include "db.php";

$doshas = ['Vata', 'Pitta', 'Kapha'];
foreach ($doshas as $d) {
    echo "--- $d ---\n";
    $sql = "SELECT content FROM recommendations WHERE category='yoga' AND dosha='$d'";
    $result = $conn->query($sql);
    $items = [];
    while($row = $result->fetch_assoc()) {
        $items[] = $row['content'];
    }
    echo json_encode($items, JSON_PRETTY_PRINT) . "\n\n";
}
?>
