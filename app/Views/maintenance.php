<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Maintenance | Perpusdig</title>

<style>
*{
    box-sizing:border-box;
}
body{
    margin:0;
    font-family:'Segoe UI', Arial, sans-serif;
    background:linear-gradient(135deg,#fde2e4,#e0f7fa);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:16px;
}
.card{
    background:#fff;
    width:100%;
    max-width:420px;
    padding:22px;
    border-radius:16px;
    box-shadow:0 12px 30px rgba(0,0,0,.12);
    text-align:center;
}
h1{
    color:#e53935;
    margin:0 0 8px;
    font-size:22px;
}
.subtitle{
    color:#555;
    font-size:14px;
    line-height:1.5;
    margin-bottom:18px;
}
.math-box{
    background:#f8f9fa;
    padding:16px;
    border-radius:12px;
}
.question{
    font-size:18px;
    font-weight:600;
    margin-bottom:10px;
}
input{
    width:100%;
    padding:12px;
    font-size:16px;
    border-radius:10px;
    border:1px solid #ccc;
    text-align:center;
}
button{
    margin-top:12px;
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#4caf50;
    color:#fff;
    font-size:16px;
    font-weight:600;
}
button:active{
    transform:scale(.98);
}
.result{
    margin-top:10px;
    font-weight:600;
    font-size:14px;
}
.good{color:#2e7d32;}
.bad{color:#d32f2f;}
.footer{
    margin-top:14px;
    font-size:11px;
    color:#777;
}

/* ===== DESKTOP SEDIKIT LEBIH BESAR ===== */
@media (min-width:768px){
    h1{font-size:24px;}
    .question{font-size:20px;}
}
</style>
</head>

<body>

<div class="card">
    <h1>🚧 Maintenance</h1>
    <div class="subtitle">
        <b>Perpusdig</b> sedang dalam pemeliharaan total.<br>
        Sambil nunggu, ayo latihan hitung dasar 👇
    </div>

    <div class="math-box">
        <div class="question" id="question"></div>
        <input type="number" id="answer" placeholder="Jawaban kamu">
        <button onclick="checkAnswer()">Cek Jawaban</button>
        <div class="result" id="result"></div>
    </div>

    <div class="footer">
        © <?= date('Y') ?> Perpusdig · Belajar walau maintenance 🙂
    </div>
</div>

<script>
let a, b, op, correct;

function generateQuestion(){
    a = Math.floor(Math.random() * 10) + 1;
    b = Math.floor(Math.random() * 10) + 1;

    const ops = ['+', '-', '×'];
    op = ops[Math.floor(Math.random() * ops.length)];

    if(op === '+') correct = a + b;
    if(op === '-') correct = a - b;
    if(op === '×') correct = a * b;

    document.getElementById('question').innerText =
        `Berapa hasil dari ${a} ${op} ${b} ?`;
}

function checkAnswer(){
    const user = document.getElementById('answer').value;
    const result = document.getElementById('result');

    if(user === ""){
        result.innerHTML = "<span class='bad'>Isi jawabannya dulu 🙂</span>";
        return;
    }

    if(parseInt(user) === correct){
        result.innerHTML = "<span class='good'>✅ Benar! Mantap 👏</span>";
        setTimeout(() => {
            document.getElementById('answer').value = "";
            generateQuestion();
            result.innerHTML = "";
        }, 1000);
    } else {
        result.innerHTML = "<span class='bad'>❌ Salah, coba lagi 💪</span>";
    }
}

generateQuestion();
</script>

</body>
</html>
