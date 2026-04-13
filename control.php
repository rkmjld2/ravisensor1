<?php
include 'db.php';

// ===============================
// CREATE TABLE (8 PINS)
// ===============================
$conn->query("CREATE TABLE IF NOT EXISTS control (
    id INT PRIMARY KEY,
    p1 INT, p2 INT, p3 INT, p4 INT,
    p5 INT, p6 INT, p7 INT, p8 INT
)");

// INSERT DEFAULT ROW IF NOT EXISTS
$conn->query("INSERT IGNORE INTO control 
(id, p1,p2,p3,p4,p5,p6,p7,p8) 
VALUES (1,0,0,0,0,0,0,0,0)");


// ===============================
// SET VALUES (SAFE UPDATE)
// ===============================
if(isset($_GET['set']))
{
    // GET CURRENT VALUES FROM DB
    $res = $conn->query("SELECT * FROM control WHERE id=1");
    $current = $res->fetch_assoc();

    // USE NEW VALUE IF PROVIDED, ELSE KEEP OLD
    $p1 = isset($_GET['p1']) ? $_GET['p1'] : $current['p1'];
    $p2 = isset($_GET['p2']) ? $_GET['p2'] : $current['p2'];
    $p3 = isset($_GET['p3']) ? $_GET['p3'] : $current['p3'];
    $p4 = isset($_GET['p4']) ? $_GET['p4'] : $current['p4'];
    $p5 = isset($_GET['p5']) ? $_GET['p5'] : $current['p5'];
    $p6 = isset($_GET['p6']) ? $_GET['p6'] : $current['p6'];
    $p7 = isset($_GET['p7']) ? $_GET['p7'] : $current['p7'];
    $p8 = isset($_GET['p8']) ? $_GET['p8'] : $current['p8'];

    // UPDATE DATABASE
    $conn->query("UPDATE control SET 
        p1='$p1', p2='$p2', p3='$p3', p4='$p4',
        p5='$p5', p6='$p6', p7='$p7', p8='$p8'
        WHERE id=1");

    echo "UPDATED";
    exit;
}


// ===============================
// GET VALUES
// ===============================
$res = $conn->query("SELECT * FROM control WHERE id=1");
$row = $res->fetch_assoc();

echo $row['p1'].",".$row['p2'].",".$row['p3'].",".$row['p4'].",".
     $row['p5'].",".$row['p6'].",".$row['p7'].",".$row['p8'];
?>
