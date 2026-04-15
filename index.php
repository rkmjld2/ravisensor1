<?php
include 'db.php';
date_default_timezone_set("Asia/Kolkata");

// Handle sensor data saving (existing)
$s1 = $_GET['s1'] ?? 0;
$s2 = $_GET['s2'] ?? 0;
$s3 = $_GET['s3'] ?? 0;

if ($s1 || $s2 || $s3) {
    $sql = "INSERT INTO sensor_db (sensor1, sensor2, sensor3) VALUES ('$s1', '$s2', '$s3')";
    if ($conn->query($sql) === TRUE) {
        echo "INSERT OK";
    } else {
        echo "DB ERROR: " . $conn->error;
    }
    exit;
}

// Read control state for display
$res = $conn->query("SELECT * FROM control WHERE id=1");
$row = $res ? $res->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>ESP8266 Control - ravisensor1.onrender.com</title>
    <meta http-equiv="refresh" content="10">
    <style>
        body{font-family:Arial;margin:20px;background:#f5f5f5;}
        .status{background:#e3f2fd;padding:15px;margin:20px 0;border-radius:5px;}
        .pin{display:inline-block;margin:10px;padding:15px;border:2px solid #ddd;border-radius:8px;}
        .on{background:#4CAF50;color:white;}
        .off{background:#f44336;color:white;}
        button{padding:8px 16px;font-size:14px;margin:3px;border:none;border-radius:4px;cursor:pointer;}
        .on-btn{background:#4CAF50;color:white;}
        .off-btn{background:#f44336;color:white;}
    </style>
</head>
<body>
<h1>🔌 ESP8266 8-Pin Control Panel</h1>

<!-- TiDB State (exact CSV ESP reads) -->
<div class="status">
    <strong>📊 Current State (ESP reads: CSV format):</strong><br>
    <?php if ($row): ?>
        P1:<?=$row['p1']?> P2:<?=$row['p2']?> P3:<?=$row['p3']?> P4:<?=$row['p4']?><br>
        P5:<?=$row['p5']?> P6:<?=$row['p6']?> P7:<?=$row['p7']?> P8:<?=$row['p8']?>
        <small> → ESP CSV: <?=$row['p1']?>,<?=$row['p2']?>,<?=$row['p3']?>,<?=$row['p4']?>,<?=$row['p5']?>,<?=$row['p6']?>,<?=$row['p7']?>,<?=$row['p8']?></small>
    <?php else: ?>
        No control data yet
    <?php endif; ?>
</div>

<!-- 8 Toggle Buttons -->
<h2>⚡ Control Pins (Click → TiDB → ESP)</h2>
<?php
$pins = ['P1','P2','P3','P4','P5','P6','P7','P8'];
for($i=1; $i<=8; $i++):
    $p = 'p'.$i;
    $val = $row ? $row[$p] : 0;
    $status = $val ? 'on' : 'off';
?>
<div class="pin <?=$status?>">
    <strong><?=$pins[$i-1]?> (D<?=$i-1?>)</strong><br>
    <form method="GET" action="control.php" style="display:inline;">
        <input type="hidden" name="set" value="1">
        <button type="submit" name="<?=$p?>" value="1" class="on-btn">🟢 ON</button>
    </form>
    <form method="GET" action="control.php" style="display:inline;">
        <input type="hidden" name="set" value="1">
        <button type="submit" name="<?=$p?>" value="0" class="off-btn">🔴 OFF</button>
    </form>
    <br><small>Current: <?= $val ? 'ON' : 'OFF' ?></small>
</div>
<?php endfor; ?>

<p><small>Auto-refresh 10s | ESP polls 5s | Debug: ESP Serial Monitor</small></p>
</body>
</html>
