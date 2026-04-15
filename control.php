<?php
include 'db.php';

header("Content-Type: text/plain");

// CREATE TABLE
$conn->query("CREATE TABLE IF NOT EXISTS control (
    id INT PRIMARY KEY,
    p1 INT, p2 INT, p3 INT, p4 INT,
    p5 INT, p6 INT, p7 INT, p8 INT
)");

$conn->query("INSERT IGNORE INTO control 
(id, p1,p2,p3,p4,p5,p6,p7,p8) 
VALUES (1,0,0,0,0,0,0,0,0)");

if(isset($_GET['set']))
{
    $res = $conn->query("SELECT * FROM control WHERE id=1");
    $current = $res->fetch_assoc();

    function val($key, $current){
        if(isset($_GET[$key])){
            return ($_GET[$key] == 1) ? 1 : 0;
        }
        return $current[$key];
    }

    $p1 = val('p1', $current);
    $p2 = val('p2', $current);
    $p3 = val('p3', $current);
    $p4 = val('p4', $current);
    $p5 = val('p5', $current);
    $p6 = val('p6', $current);
    $p7 = val('p7', $current);
    $p8 = val('p8', $current);

    $conn->query("UPDATE control SET 
        p1=$p1, p2=$p2, p3=$p3, p4=$p4,
        p5=$p5, p6=$p6, p7=$p7, p8=$p8
        WHERE id=1");

    echo "OK\n";
    echo "$p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8";
    exit;
}

// GET VALUES FOR ESP
$res = $conn->query("SELECT * FROM control WHERE id=1");
$row = $res->fetch_assoc();

echo $row['p1'].",".
     $row['p2'].",".
     $row['p3'].",".
     $row['p4'].",".
     $row['p5'].",".
     $row['p6'].",".
     $row['p7'].",".
     $row['p8'];
?>
