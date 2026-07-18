<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>SMK KARTIKA PADANG | Sistem Pengumpulan Tugas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
    min-height:100vh;
    background:linear-gradient(135deg,#eafff1,#f0fdf4,#dcfce7);
    display:flex;
    justify-content:center;
    align-items:center;
    position:relative;
    overflow-x:hidden;
    overflow-y:auto;
    padding:40px 20px;
}

/* FLOATING ICONS */
.floating{
position:absolute;
font-size:35px;
opacity:.15;
color:#16a34a;
animation:float 8s infinite ease-in-out;
}

.f1{top:10%;left:10%;}
.f2{top:20%;right:15%;animation-delay:1s;}
.f3{bottom:15%;left:20%;animation-delay:2s;}
.f4{bottom:20%;right:10%;animation-delay:3s;}
.f5{top:50%;left:5%;animation-delay:4s;}
.f6{top:60%;right:5%;animation-delay:5s;}

@keyframes float{
0%,100%{transform:translateY(0) rotate(0deg);}
50%{transform:translateY(-25px) rotate(10deg);}
}

/* GLOW EFFECT */
.glow{
position:absolute;
width:500px;
height:500px;
border-radius:50%;
background:#22c55e25;
filter:blur(120px);
animation:pulse 6s infinite ease-in-out;
}

@keyframes pulse{
0%,100%{transform:scale(1);}
50%{transform:scale(1.2);}
}

.card-box{
    width:950px;
    max-width:100%;
    background:rgba(255,255,255,.82);
    backdrop-filter:blur(25px);
    border-radius:35px;
    padding:60px 50px;
    text-align:center;
    box-shadow:0 25px 70px rgba(0,0,0,.12);
    position:relative;
    z-index:2;
    animation:fadeIn 1s ease;
}

/* fade in */
@keyframes fadeIn{
from{opacity:0;transform:translateY(30px);}
to{opacity:1;transform:translateY(0);}
}

/* ICON */
.logo{
font-size:85px;
color:#16a34a;
animation:bounce 3s infinite;
}

@keyframes bounce{
0%,100%{transform:translateY(0);}
50%{transform:translateY(-10px);}
}

/* TEXT */
h1{
font-size:40px;
font-weight:800;
color:#15803d;
margin-top:10px;
}

p{
color:#555;
margin-top:10px;
line-height:1.6;
}

/* BUTTON */
.btn-main{
display:inline-block;
margin-top:25px;
padding:14px 38px;
border-radius:18px;
background:#22c55e;
color:white;
font-weight:600;
text-decoration:none;
transition:.3s;
box-shadow:0 15px 30px rgba(34,197,94,.25);
}

.btn-main:hover{
transform:translateY(-5px) scale(1.03);
background:#16a34a;
}

.features{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
    margin-top:40px;
}

.feature{
    background:#fff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.35s;
}

.feature:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 40px rgba(0,0,0,.15);
}

.feature:nth-child(2){animation-delay:1s;}
.feature:nth-child(3){animation-delay:2s;}

@keyframes floatCard{
0%,100%{transform:translateY(0);}
50%{transform:translateY(-8px);}
}

.feature i{
font-size:28px;
color:#16a34a;
margin-bottom:10px;
}

.feature h5{
font-size:14px;
font-weight:600;
}

@media(max-width:768px){

.card-box{
    padding:35px 25px;
}

h1{
    font-size:28px;
}

.features{
    grid-template-columns:1fr;
}

.btn-main{
    width:100%;
    margin:10px 0;
}

.logo img{
    width:70px !important;
}

}
</style>

</head>

<body>

<!-- FLOATING -->
<div class="floating f1">📚</div>
<div class="floating f2"><img src="assets/css/images/logo_kartika.png" style="width:45px;height:auto;"></div>
<div class="floating f3">📝</div>
<div class="floating f4">💻</div>
<div class="floating f5">📊</div>
<div class="floating f6">📁</div>

<!-- GLOW -->
<div class="glow" style="top:-200px;left:-200px;"></div>
<div class="glow" style="bottom:-200px;right:-200px;"></div>

<!-- CENTER CARD -->
<div class="card-box">

<div class="logo"><img src="assets/css/images/logo_kartika.png" style="width:85px;height:auto;"></div>

<h4 style="color:#166534;font-weight:700;letter-spacing:2px;"><h1>SMK KARTIKA PADANG</h1>

<p>
Platform digital resmi SMK Kartika Padang untuk pengumpulan tugas, penilaian, dan pengelolaan pembelajaran yang cepat, aman, modern, dan efisien.
</p>

<a href="auth/login.php" class="btn-main">
<i class="fas fa-right-to-bracket"></i> Masuk Sistem
</a>

<a href="auth/register.php" class="btn-main" style="background:#15803d;margin-left:10px;box-shadow:0 15px 30px rgba(21,128,61,.25);">
<i class="fas fa-user-plus"></i> Daftar Siswa
</a>

<div class="features">

<div class="feature">
<i class="fas fa-upload"></i>
<h5>Upload</h5>
</div>

<div class="feature">
<i class="fas fa-star"></i>
<h5>Nilai</h5>
</div>

<div class="feature">
<i class="fas fa-chart-line"></i>
<h5>Dashboard</h5>
</div>

</div>

<hr style="margin:35px 0 20px;">

<p style="font-size:14px;color:#6b7280;margin:0;">
© 2026 <strong>SMK KARTIKA PADANG</strong><br>
Sistem Pengumpulan Tugas Berbasis Web
</p>
</html>