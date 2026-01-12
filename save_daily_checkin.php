<?php
header("Content-Type: application/json");
error_reporting(0); // Production Mode
include "db.php";
// 1. INPUT
$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'] ?? null;
$checkin_date = date("Y-m-d");
if (!$user_id) {
    echo json_encode(["status"=>"error", "message"=>"User ID required"]);
    exit;
}
// 2. SAVE INPUTS
$stmt = $conn->prepare("INSERT INTO daily_checkins (user_id, checkin_date, sleep_hours, sleep_quality, stress_level, morning_energy, evening_energy, body_dryness, body_heat, body_heaviness, cold_body, sweet_craving, spicy_craving, elimination, hydration_level, mood, physical_activity, digestion, appetite) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE sleep_hours=VALUES(sleep_hours), sleep_quality=VALUES(sleep_quality), stress_level=VALUES(stress_level), morning_energy=VALUES(morning_energy), evening_energy=VALUES(evening_energy), body_dryness=VALUES(body_dryness), body_heat=VALUES(body_heat), body_heaviness=VALUES(body_heaviness), cold_body=VALUES(cold_body), sweet_craving=VALUES(sweet_craving), spicy_craving=VALUES(spicy_craving), elimination=VALUES(elimination), hydration_level=VALUES(hydration_level), mood=VALUES(mood), physical_activity=VALUES(physical_activity), digestion=VALUES(digestion), appetite=VALUES(appetite)");
$stmt->bind_param("issssssiiiiiisissss", $user_id, $checkin_date, $data['sleep_hours'], $data['sleep_quality'], $data['stress_level'], $data['morning_energy'], $data['evening_energy'], $data['body_dryness'], $data['body_heat'], $data['body_heaviness'], $data['cold_body'], $data['sweet_craving'], $data['spicy_craving'], $data['elimination'], $data['hydration_level'], $data['mood'], $data['physical_activity'], $data['digestion'], $data['appetite']);
$stmt->execute();
// 3. LOGIC (SCALED UP: 10, 15, 20)
$vata = 0; $pitta = 0; $kapha = 0;
// Sleep
$sleep = (float)($data['sleep_hours'] ?? 7);
if ($sleep < 6) $vata += 15;       // High Imbalance
elseif ($sleep > 9) $kapha += 15; // High Imbalance
else $pitta += 10;                // Balanced/Moderate
// Sleep Quality
$sleep_quality = strtolower($data['sleep_quality'] ?? '');
if ($sleep_quality === 'poor') $vata += 20;      // Very High Impact
elseif ($sleep_quality === 'moderate') $pitta += 10; 
elseif ($sleep_quality === 'good') $kapha += 10;
// Stress
$stress = $data['stress_level'] ?? 'low';
if (is_numeric($stress)) {
    if ($stress > 7) $vata += 20;      // High Stress = High Vata
    elseif ($stress > 4) $pitta += 15; // Med Stress
    else $kapha += 10;                 // Low Stress
} else {
    $stress = strtolower($stress);
    if ($stress === 'high') $vata += 20;
    elseif ($stress === 'medium') $pitta += 15;
    elseif ($stress === 'low') $kapha += 10;
}
// Energy
$m_energy = strtolower($data['morning_energy'] ?? 'normal');
if ($m_energy === 'low') $kapha += 15;
elseif ($m_energy === 'high') $pitta += 15;
$e_energy = strtolower($data['evening_energy'] ?? 'normal');
if ($e_energy === 'low') $kapha += 10;
elseif ($e_energy === 'high') $vata += 15;
// Body
if (!empty($data['body_dryness'])) $vata += 20;
if (!empty($data['body_heat'])) $pitta += 20;
if (!empty($data['body_heaviness'])) $kapha += 20;
if (!empty($data['cold_body'])) { $vata += 10; $kapha += 10; }
if (!empty($data['sweet_craving'])) $kapha += 15;
if (!empty($data['spicy_craving'])) $pitta += 15;
// Elimination
$elimination = strtolower($data['elimination'] ?? '');
if (strpos($elimination, 'dry') !== false || strpos($elimination, 'hard') !== false) $vata += 15;
if (strpos($elimination, 'loose') !== false) $pitta += 15;
if (strpos($elimination, 'heavy') !== false) $kapha += 15;
// Mood
$mood = strtolower($data['mood'] ?? '');
if ($mood === 'anxious') $vata += 20;
if ($mood === 'irritable') $pitta += 20;
if ($mood === 'low') $kapha += 20;
// Digestion
$digestion = strtolower($data['digestion'] ?? '');
if ($digestion === 'bloated') $vata += 20;
if ($digestion === 'light') $vata += 10;
if ($digestion === 'heavy') $kapha += 20;
if (strpos($digestion, 'burn') !== false || strpos($digestion, 'acid') !== false) $pitta += 20;
// Appetite
$appetite = strtolower($data['appetite'] ?? '');
if ($appetite === 'low') $kapha += 15;
if ($appetite === 'strong') $pitta += 15;
if ($appetite === 'variable') $vata += 15;
// Hydration
$hydration = (int)($data['hydration_level'] ?? 2);
if ($hydration < 2) $vata += 20;      // Dangerous
elseif ($hydration > 3) $kapha += 10; 
// Activity
$activity = strtolower($data['physical_activity'] ?? '');
if ($activity === 'none') $kapha += 15;
elseif ($activity === 'light') $kapha += 10;
elseif ($activity === 'intense') { $vata += 15; $pitta += 15; }
// Dominant
$dominant = "Vata";
$max_score = $vata;
if ($pitta > $max_score) { $max_score = $pitta; $dominant = "Pitta"; }
if ($kapha > $max_score) { $max_score = $kapha; $dominant = "Kapha"; }
// 4. SAVE SCORES
$stmt_score = $conn->prepare("INSERT INTO dosha_scores (user_id, checkin_date, vata_score, pitta_score, kapha_score, dominant_dosha) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE vata_score=VALUES(vata_score), pitta_score=VALUES(pitta_score), kapha_score=VALUES(kapha_score), dominant_dosha=VALUES(dominant_dosha)");
if ($stmt_score) {
    $stmt_score->bind_param("isiiis", $user_id, $checkin_date, $vata, $pitta, $kapha, $dominant);
    $stmt_score->execute();
}

// 4.1. CALCULATE & SAVE BALANCE SCORE (Added Fix)
// Formula: 100 - (Total Imbalance / 3)
$total_imbalance = $vata + $pitta + $kapha;
$b_score = 0;

if ($total_imbalance > 0) {
    $b_score = 100 - ($total_imbalance / 3);
    // Clamp
    if ($b_score < 10) $b_score = 10;
    if ($b_score > 100) $b_score = 100;
} 
// If total_imbalance is 0, score remains 0 (No data)

// Save to body_balance_scores
$stmt_bal = $conn->prepare("INSERT INTO body_balance_scores (user_id, checkin_date, score) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE score=VALUES(score)");
if ($stmt_bal) {
    // Explicit Cast to Integer to be safe
    $final_b_score = (int)$b_score;
    $stmt_bal->bind_param("isi", $user_id, $checkin_date, $final_b_score);
    if (!$stmt_bal->execute()) {
        // If failed, log it (visible in PHPMailer or error logs if enabled) or append to message
        // For now, we rely on the status.
    }
}

// --- 5. CONSISTENCY (STREAK) LOGIC ---
$currentStreak = 0;
try {
    $userQ = $conn->prepare("SELECT checkin_streak, last_checkin_date FROM users WHERE id = ?");
    if ($userQ) {
        $userQ->bind_param("i", $user_id);
        $userQ->execute();
        $res = $userQ->get_result();
        if ($res && $uRes = $res->fetch_assoc()) {
            $currentStreak = $uRes['checkin_streak'] ?? 0;
            $lastDate = $uRes['last_checkin_date'] ?? '0000-00-00';
            $today = date("Y-m-d");
            $yesterday = date("Y-m-d", strtotime("-1 day"));
            if ($lastDate != $today) {
                if ($lastDate == $yesterday) {
                    $currentStreak += 1;
                } else {
                    $currentStreak = 1;
                }
                $updUser = $conn->prepare("UPDATE users SET checkin_streak = ?, last_checkin_date = ? WHERE id = ?");
                if ($updUser) {
                    $updUser->bind_param("isi", $currentStreak, $today, $user_id);
                    $updUser->execute();
                }
            }
        }
    }
} catch (Exception $e) {
    // Silence streak errors to prevent main checkin from failing
}
// 6. OUTPUT (Always return JSON)
echo json_encode([
    "status" => "success",
    "message" => "Check-in Saved",
    "scores" => ["Vata" => (int)$vata, "Pitta" => (int)$pitta, "Kapha" => (int)$kapha],
    "balance_score" => (int)$b_score, // [FIX] Added for Android Toast
    "dominant" => $dominant,
    "current_streak" => (int)$currentStreak
]);
?>