<?php
header("Content-Type: application/json");
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id = $_GET['user_id'];

    if (empty($user_id)) {
        echo json_encode(["status" => "error", "message" => "User ID required"]);
        exit;
    }

    $weekly_data = [];

    // Loop through 5 weeks (0..4) to have enough history to calc trend
    for ($i = 0; $i < 5; $i++) {
        $start_day = $i * 7;
        $end_day = ($i * 7) + 6;
        
        $sql = "SELECT 
                    COALESCE(AVG(c.sleep_hours), 0) as avg_sleep, 
                    COALESCE(AVG(c.hydration_level), 0) as avg_hydration, 
                    COALESCE(AVG(
                        CASE 
                            WHEN c.stress_level = 'Low' THEN 2
                            WHEN c.stress_level = 'Medium' OR c.stress_level = 'Moderate' THEN 5
                            WHEN c.stress_level = 'High' THEN 8
                            WHEN c.stress_level REGEXP '^[0-9]+$' THEN c.stress_level
                            ELSE 0 
                        END
                    ), 0) as avg_stress,
                    COALESCE(AVG(b.score), 0) as avg_dosha
                FROM daily_checkins c
                LEFT JOIN body_balance_scores b 
                ON DATE(c.created_at) = b.checkin_date AND c.user_id = b.user_id
                WHERE c.user_id = '$user_id' 
                AND DATE(c.created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL $end_day DAY) AND DATE_SUB(CURDATE(), INTERVAL $start_day DAY)";

        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $weekly_data[] = [
            "week_index" => $i + 1, 
            "avg_sleep" => round((float)$row['avg_sleep'], 1),
            "avg_hydration" => round((float)$row['avg_hydration'], 1),
            "avg_stress" => round((float)$row['avg_stress'], 1),
            "avg_dosha" => round((float)$row['avg_dosha'], 0)
        ];
    }
    
    // Calculate Trends
    $final_data = [];
    
    function getTrend($current, $prev) {
        if ($prev == 0) return "N/A";
        $diff = $current - $prev;
        $pct = ($diff / $prev) * 100;
        return ($pct > 0 ? "+" : "") . round($pct) . "%";
    }

    function getStressTrend($current, $prev) {
         if ($prev == 0) return "N/A";
         if ($current > $prev) return "Increased";
         if ($current < $prev) return "Decreased";
         return "Stable";
    }

    for ($i = 0; $i < 4; $i++) { // Only need first 4
        $item = $weekly_data[$i];
        $prev = $weekly_data[$i+1]; 

        $item['sleep_trend'] = getTrend($item['avg_sleep'], $prev['avg_sleep']);
        $item['hydration_trend'] = getTrend($item['avg_hydration'], $prev['avg_hydration']);
        $item['dosha_trend'] = getTrend($item['avg_dosha'], $prev['avg_dosha']);
        $item['stress_trend'] = getStressTrend($item['avg_stress'], $prev['avg_stress']);
        
        $final_data[] = $item;
    }

    echo json_encode(["status" => "success", "data" => $final_data]);

} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
$conn->close();
?>