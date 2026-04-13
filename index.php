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

// TOGGLE PIN (CORRECT LOGIC)
async function togglePin(i){

    let state = await getState();   // ✅ GET CURRENT SERVER STATE

    state[i] = state[i] ? 0 : 1;    // toggle only one pin

    send(state);
    updateButtons(state);
}

// SEND FULL STATE
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

// UPDATE BUTTON COLORS
function updateButtons(state){
    for(let i=0;i<8;i++){
        let btn = document.getElementById("btn"+i);
        btn.className = state[i] ? "on" : "off";
    }
}

// LOAD INITIAL STATE
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

// AUTO REFRESH BUTTON STATUS
setInterval(async ()=>{
    let state = await getState();
    updateButtons(state);
}, 2000);

// INIT
init();

// GRAPH (unchanged)
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
