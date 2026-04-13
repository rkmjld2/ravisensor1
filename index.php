<?php
include 'db.php';

// Last record for status
$res = $conn->query("SELECT * FROM sensor_db ORDER BY id DESC LIMIT 1");
$last = $res->fetch_assoc();

$status = "DISCONNECTED";

if($last){
    if(time() - strtotime($last['timestamp']) < 15){
        $status = "CONNECTED";
    }
}

// Data for graph
$result = $conn->query("SELECT * FROM sensor_db ORDER BY id DESC LIMIT 50");

$data = [];
while($row = $result->fetch_assoc()){
    $data[] = $row;
}

$data = array_reverse($data);

$labels=[]; $s1=[]; $s2=[]; $s3=[];

foreach($data as $row){
    $labels[] = $row['id'];
    $s1[] = $row['sensor1'];
    $s2[] = $row['sensor2'];
    $s3[] = $row['sensor3'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sensor Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<meta http-equiv="refresh" content="10">

<style>
body{font-family:Arial;background:#f4f6f8;padding:20px;}
.card{background:white;padding:15px;margin-bottom:20px;border-radius:10px;}
button{padding:10px;margin:5px;background:#007bff;color:white;border:none;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #ddd;padding:8px;text-align:center;}
</style>
</head>

<body>

<div class="card">
<h2>📡 Status:
<span style="color:<?=($status=='CONNECTED')?'green':'red'?>">
<?= $status ?>
</span>
</h2>
</div>

<div class="card">
<h2>🎛 Control</h2>
<button onclick="send(1,0,0)">Pin1</button>
<button onclick="send(0,1,0)">Pin2</button>
<button onclick="send(0,0,1)">Pin3</button>
<button onclick="send(0,0,0)">All OFF</button>
</div>

<div class="card">
<h2>📈 Graph</h2>
<canvas id="chart"></canvas>
</div>

<div class="card">
<h2>📊 Data</h2>

<table>
<tr><th>ID</th><th>S1</th><th>S2</th><th>S3</th><th>Time</th></tr>

<?php foreach(array_reverse($data) as $row): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['sensor1'] ?></td>
<td><?= $row['sensor2'] ?></td>
<td><?= $row['sensor3'] ?></td>
<td><?= $row['timestamp'] ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>

<script>
function send(p1,p2,p3){
    fetch(`control.php?set=1&p1=${p1}&p2=${p2}&p3=${p3}`)
}

new Chart(document.getElementById('chart'),{
    type:'line',
    data:{
        labels: <?= json_encode($labels) ?>,
        datasets:[
            {label:'S1',data: <?= json_encode($s1) ?>},
            {label:'S2',data: <?= json_encode($s2) ?>},
            {label:'S3',data: <?= json_encode($s3) ?>}
        ]
    }
});
</script>

</body>
</html>