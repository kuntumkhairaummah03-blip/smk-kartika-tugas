<?php session_start(); ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
height:100vh;
display:flex;
align-items:center;
justify-content:center;
background:linear-gradient(135deg,#eafff1,#f0fdf4);
}

/* back button smooth */
.back{
position:absolute;
top:20px;
left:20px;
width:45px;
height:45px;
background:white;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
text-decoration:none;
color:#16a34a;
box-shadow:0 10px 25px rgba(0,0,0,.1);
transition:.3s;
}

.back:hover{
transform:translateX(-4px);
}

/* card */
.card{
width:380px;
background:white;
padding:40px;
border-radius:30px;
box-shadow:0 25px 60px rgba(0,0,0,.12);
text-align:center;

opacity:0;
transform:scale(.95);
animation:fadeIn .6s ease forwards;
}

@keyframes fadeIn{
to{
opacity:1;
transform:scale(1);
}
}

.icon{
font-size:60px;
color:#16a34a;
}

h2{
font-weight:700;
color:#15803d;
}

input{
height:48px;
border-radius:12px !important;
margin-top:12px;
transition:.3s;
}

input:focus{
box-shadow:0 0 0 3px rgba(34,197,94,.2);
border-color:#22c55e;
}

.btn{
width:100%;
margin-top:18px;
background:#22c55e;
color:white;
padding:12px;
border-radius:12px;
border:none;
font-weight:600;
transition:.3s;
}

.btn:hover{
background:#16a34a;
transform:translateY(-3px);
box-shadow:0 10px 20px rgba(34,197,94,.2);
}

/* loading state */
.loading{
display:none;
margin-top:10px;
font-size:13px;
color:#666;
}
</style>
</head>

<body>

<a href="../index.php" class="back">
<i class="fas fa-arrow-left"></i>
</a>

<div class="card">

<div class="icon"><img src="../assets/css/images/logo_kartika.png" style="width:60px;height:auto;"></div>

<h2>Login</h2>
<p class="text-muted">Masuk ke akun Anda</p>

<form method="POST" action="proses_login.php" onsubmit="showLoading()">

<input type="text" name="username" class="form-control" placeholder="Username" required>

<input type="password" name="password" class="form-control" placeholder="Password" required>

<button class="btn" id="btnLogin">
<i class="fas fa-right-to-bracket"></i> Login
</button>

<div class="loading" id="loadingText">
⏳ Memproses login...
</div>

</form>

<div style="margin-top:18px;font-size:13px;color:#6b7280;">
    Belum punya akun? <a href="register.php" style="color:#16a34a;font-weight:600;text-decoration:none;">Daftar di sini</a>
</div>

</div>

<script>
function showLoading(){
document.getElementById("loadingText").style.display="block";
document.getElementById("btnLogin").innerHTML="Loading...";
}
</script>

</body>
</html>