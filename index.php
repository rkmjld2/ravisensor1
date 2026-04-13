<?php
include 'db.php';

// Last record for status
$res = $conn->query("SELECT * FROM sensor_db ORDER BY id DESC LIMIT 1");
$last = $res->fetch_assoc();

$status = "DISCONNECTED";

if($last){
    if(time() - strtotime($last['timestamp']) < 30){  // 🔴 FIXED (15 → 30)
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
button{padding:10px;margin:5px;border:none;color:white;border-radius:5px;}
.on{background:green;}
.off{background:red;}
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
<h2>🎛 8 Channel Control</h2>

<!-- ✅ IMPORTANT (WAS MISSING) -->
<div id="buttons"></div>

<button onclick="allOff()" style="background:black;">All OFF</button>

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

// FETCH CURRENT STATE FROM SERVER
async function getState(){
    let res = await fetch("control.php");
    let text = await res.text();
    return text.split(",").map(Number);
}

// CREATE BUTTONS
let container = document.getElementById("buttons");

for(let i=0;i<8;i++){
    let btn = document.createElement("button");
    btn.innerHTML = "D"+(i+1);
    btn.className = "off";
    btn.id = "btn"+i;

    btn.onclick = function(){
        togglePin(i);
    };

    container.appendChild(btn);
}

// TOGGLE PIN
async function togglePin(i){
    let state = await getState();
    state[i] = state[i] ? 0 : 1;
    send(state);
    updateButtons(state);
}

// SEND STATE
function send(state){
    fetch(`control.php?set=1
        &p1=${state[0]}
        &p2=${state[1]}
        &p3=${state[2]}
        &p4=${state[3]}
        &p5=${state[4]}
        &p6=${state[5]}
        &p7=${state[6]}
        &p8=${state[7]}`);
}

// UPDATE BUTTON UI
function updateButtons(state){
    for(let i=0;i<8;i++){
        let btn = document.getElementById("btn"+i);
        btn.className = state[i] ? "on" : "off";
    }
}

// INIT LOAD
async function init(){
    let state = await getState();
    updateButtons(state);
}

// ALL OFF
function allOff(){
    let state = [0,0,0,0,0,0,0,0];
    send(state);
    updateButtons(state);
}

// AUTO REFRESH
setInterval(async ()=>{
    let state = await getState();
    updateButtons(state);
}, 2000);

// INIT
init();

// GRAPH
new Chart(document.getElementById('chart'),{
    type:'line',
    data:{
        labels: <?= json_encode($labels) ?>,
        datasets:[
            {label:'S1',data: <?= json_encode($s1) ?>},
            {label:'S2',data: <?= json_encode($s2) ?>},
            {label:'S3',data: <?= json_encode($s3) ?>
}
        ]
    }
});
</script>

</body>
</html>
