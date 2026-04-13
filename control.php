<?php
include 'db.php';

// Create table
$conn->query("CREATE TABLE IF NOT EXISTS control (
    id INT PRIMARY KEY,
    p1 INT, p2 INT, p3 INT, p4 INT,
    p5 INT, p6 INT, p7 INT, p8 INT
)");

$conn->query("INSERT IGNORE INTO control VALUES (1,0,0,0,0,0,0,0,0)");

// SET values
if(isset($_GET['set']))
{
    $p1=$_GET['p1']; $p2=$_GET['p2']; $p3=$_GET['p3']; $p4=$_GET['p4'];
    $p5=$_GET['p5']; $p6=$_GET['p6']; $p7=$_GET['p7']; $p8=$_GET['p8'];

    $conn->query("UPDATE control SET 
    p1='$p1', p2='$p2', p3='$p3', p4='$p4',
    p5='$p5', p6='$p6', p7='$p7', p8='$p8'
    WHERE id=1");

    echo "UPDATED";
    exit;
}

// GET values
$res = $conn->query("SELECT * FROM control WHERE id=1");
$row = $res->fetch_assoc();

echo $row['p1'].",".$row['p2'].",".$row['p3'].",".$row['p4'].",".
     $row['p5'].",".$row['p6'].",".$row['p7'].",".$row['p8'];
?>
