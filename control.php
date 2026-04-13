<?php
include 'db.php';

// Create control table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS control (
    id INT PRIMARY KEY,
    pin1 INT,
    pin2 INT,
    pin3 INT
)");

$conn->query("INSERT IGNORE INTO control VALUES (1,0,0,0)");

// SET from web
if(isset($_GET['set']))
{
    $p1 = $_GET['p1'];
    $p2 = $_GET['p2'];
    $p3 = $_GET['p3'];

    $conn->query("UPDATE control SET pin1='$p1', pin2='$p2', pin3='$p3' WHERE id=1");

    echo "UPDATED";
    exit;
}

// GET for ESP8266
$result = $conn->query("SELECT * FROM control WHERE id=1");
$row = $result->fetch_assoc();

echo $row['pin1'] . "," . $row['pin2'] . "," . $row['pin3'];
?>