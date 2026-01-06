<?php
function saveDoshaScores($conn, $user_id, $checkin_date, $vata, $pitta, $kapha) {
    try {
        $stmt = $conn->prepare("
            INSERT INTO dosha_scores (
                user_id, checkin_date, vata_score, pitta_score, kapha_score, body_balance_score
            ) VALUES (?, ?, ?, ?, ?, 0)
            ON DUPLICATE KEY UPDATE
                vata_score = VALUES(vata_score),
                pitta_score = VALUES(pitta_score),
                kapha_score = VALUES(kapha_score)
        ");
        
        $stmt->bind_param("isiii", $user_id, $checkin_date, $vata, $pitta, $kapha);
        
        if (!$stmt->execute()) {
            // Log error or handle it
            error_log("Failed to save dosha scores: " . $stmt->error);
            return false;
        }
        return true;
    } catch (Exception $e) {
        error_log("Error saving dosha scores: " . $e->getMessage());
        return false;
    }
}
?>
