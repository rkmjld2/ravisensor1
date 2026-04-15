<?php
include 'db.php';
date_default_timezone_set("Asia/Kolkata");

// Handle sensor data saving (your original logic)
$s1 = $_GET['s1'] ?? 0;
$s2 = $_GET['s2'] ?? 0;
$s3 = $_GET['s3'] ?? 0;

if ($s1 || $s2 || $s3) {
    $sql = "INSERT INTO sensor_db (sensor1, sensor2, sensor3)
            VALUES ('$s1', '$s2', '$s3')";
    if ($conn->query($sql) === TRUE) {
        echo "INSERT OK";
    } else {
        echo "DB ERROR: " . $conn->error;
    }
    exit;
}

// Read control state from TiDB - FIXED mysqli
$res = $conn->query("SELECT * FROM control WHERE id=1");
$row = $res->fetch_assoc();  // <- CORRECT: $res->fetch_assoc()
?>
<!DOCTYPE html>
<html>
<head>
    <title>ESP8266 Control Panel - ravisensor1.onrender.com</title>
    <meta http-equiv="refresh" content="10">
    <style>
        body { font-family: Arial; margin: 20px; }
        .pin { display: inline-block; margin: 10px; padding: 10px; border: 2px solid #ccc; }
        .on { background: #4CAF50; color: white; }
        .off { background: #f44336; color: white; }
        button { padding: 10px 20px; font-size: 16px; margin: 5px; }
    </style>
</head>
<body>
<h2>ESP8266 8-Pin Control (TiDB Sync)</h2>

<!-- Current state from TiDB (same as ESP reads) -->
<div style="background: #e0f7fa; padding: 15px; margin: 10px 0;">
    <strong>Current DB State (ESP reads this):</strong> 
    <?php
    if ($row) {
        echo "P1:{$row['p1']} P2:{$row['p2']} P3:{$row['p3']} P4:{$row['p4']} ";
        echo "P5:{$row['p5']} P6:{$row['p6']} P7:{$row['p7']} P8:{$row['p8']}";
    } else {
        echo "No control data - check control table";
    }
    ?>
</div>

<!-- 8 Toggle Buttons - individual submit -->
<?php
$pin_names = ['P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'P7', 'P8'];
for ($i = 1; $i <= 8; $i++) {
    $p = 'p' . $i;
    $status = ($row && $row[$p]) ? 'on' : 'off';
    echo "<div class='pin $status'>";
    echo "<strong>{$pin_names[$i-1]}</strong><br>";
    echo "<form method='GET' action='control.php' style='display:inline;'>";
    echo "<input type='hidden' name='set' value='1'>";
    echo "<button type='submit' name='$p' value='1' style='background: #4CAF50; color:white;'>ON</button>";
    echo "</form>";
    echo "<form method='GET' action='control.php' style='display:inline;'>";
    echo "<input type='hidden' name='set' value='1'>";
    echo "<button type='submit' name='$p' value='0' style='background: #f44336; color:white;'>OFF</button>";
    echo "</form>";
    echo "</div>";
}
?>

<p><small>Auto-refresh every 10s. ESP syncs every 5s.</small></p>
</body>
</html>
