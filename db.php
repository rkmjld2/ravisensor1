<?php
$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$port = 4000;
$user = "ax6KHc1BNkyuaor.root";
$pass = "EP8isIWoEOQk7DSr";
$dbname = "sensor";

date_default_timezone_set("Asia/Kolkata");

$conn = mysqli_init();

// SSL for TiDB Cloud
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
