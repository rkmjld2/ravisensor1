<?php
include 'db.php';

$s1 = mysqli_real_escape_string($conn, $_GET['s1']);
$s2 = mysqli_real_escape_string($conn, $_GET['s2']);
$s3 = mysqli_real_escape_string($conn, $_GET['s3']);

$sql = "INSERT INTO sensor_db (sensor1, sensor2, sensor3)
        VALUES ('$s1', '$s2', '$s3')";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERROR";
}
?>