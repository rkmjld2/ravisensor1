<?php
include 'db.php';

$s1 = $_GET['s1'] ?? 0;
$s2 = $_GET['s2'] ?? 0;
$s3 = $_GET['s3'] ?? 0;

// DEBUG: show values
echo "Received: $s1, $s2, $s3 <br>";

$sql = "INSERT INTO sensor_db (sensor1, sensor2, sensor3)
        VALUES ('$s1', '$s2', '$s3')";

if ($conn->query($sql) === TRUE) {
    echo "INSERT OK";
} else {
    echo "ERROR: " . $conn->error;
}
?>
