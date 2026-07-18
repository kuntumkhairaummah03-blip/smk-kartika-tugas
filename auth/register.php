<?php
session_start();
include '../config/koneksi.php';

// Ambil daftar kelas dari database
$kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas");

// Ambil daftar tahun ajaran
$tahun_ajaran = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Daftar Akun Siswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
background:linear-gradient(135deg,#eafff1,#f0fdf4);
padding:20px;
}

/* back button */
.back{
position:fixed;
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
z-index:10;
}

.back:hover{
transform:translateX(-4px);
}

/* card */
.card-register{
width:440px;
max-width:100%;
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
margin-bottom:5px;
}

h2{
font-weight:700;
color:#15803d;
font-size:24px;
margin-bottom:5px;
}

.subtitle{
color:#6b7280;
font-size:14px;
margin-bottom:25px;
}

.form-group{
text-align:left;
margin-bottom:16px;
position:relative;
}

.form-group label{
display:block;
font-size:13px;
font-weight:600;
color:#374151;
margin-bottom:6px;
}

.form-group label i{
color:#16a34a;
margin-right:6px;
}

.form-group input,
.form-group select{
width:100%;
height:48px;
border:2px solid #e5e7eb;
border-radius:14px;
padding:0 16px;
font-size:14px;
background:#fafafa;
transition:.3s;
outline:none;
font-family:'Poppins',sans-serif;
}

.form-group select{
cursor:pointer;
appearance:none;
-webkit-appearance:none;
background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2316a34a' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
background-repeat:no-repeat;
background-position:right 16px center;
padding-right:40px;
}

.form-group input:hover,
.form-group select:hover{
border-color:#bbf7d0;
}

.form-group input:focus,
.form-group select:focus{
box-shadow:0 0 0 3px rgba(34,197,94,.2);
border-color:#22c55e;
background:white;
}

/* Kelas info badge */
.kelas-info{
display:none;
margin-top:8px;
padding:10px 14px;
background:linear-gradient(135deg,#f0fdf4,#dcfce7);
border:1px solid #bbf7d0;
border-radius:12px;
font-size:12px;
color:#166534;
font-weight:500;
animation:slideDown .3s ease;
}

@keyframes slideDown{
from{opacity:0;transform:translateY(-8px);}
to{opacity:1;transform:translateY(0);}
}

.kelas-info i{
margin-right:4px;
}

.btn-register{
width:100%;
margin-top:20px;
background:linear-gradient(135deg,#22c55e,#16a34a);
color:white;
padding:14px;
border-radius:14px;
border:none;
font-weight:600;
font-size:15px;
transition:.3s;
cursor:pointer;
display:flex;
align-items:center;
justify-content:center;
gap:8px;
}

.btn-register:hover{
transform:translateY(-3px);
box-shadow:0 12px 25px rgba(34,197,94,.3);
}

.login-link{
margin-top:18px;
font-size:13px;
color:#6b7280;
}

.login-link a{
color:#16a34a;
font-weight:600;
text-decoration:none;
}

.login-link a:hover{
text-decoration:underline;
}

/* divider */
.divider{
display:flex;
align-items:center;
gap:12px;
margin:20px 0;
}

.divider::before,
.divider::after{
content:'';
flex:1;
height:1px;
background:#e5e7eb;
}

.divider span{
font-size:12px;
color:#9ca3af;
}

/* loading */
.loading{
display:none;
margin-top:10px;
font-size:13px;
color:#666;
}
</style>
</head>

<body>

<a href="login.php" class="back">
<i class="fas fa-arrow-left"></i>
</a>

<div class="card-register">

<div class="icon">
    <img src="../assets/css/images/logo_kartika.png" style="width:60px;height:auto;">
</div>

<h2>Daftar Akun Siswa</h2>
<p class="subtitle">Buat akun untuk mulai mengumpulkan tugas</p>

<form method="POST" action="proses_register.php" onsubmit="showLoading()">

    <div class="form-group">
        <label><i class="fas fa-user"></i> Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
    </div>

    <div class="form-group">
        <label><i class="fas fa-at"></i> Username</label>
        <input type="text" name="username" placeholder="Buat username unik" required>
    </div>

    <div class="form-group">
        <label><i class="fas fa-lock"></i> Password</label>
        <input type="password" name="password" placeholder="Buat password" required>
    </div>

    <div class="divider"><span>Kelas & Tahun Ajaran</span></div>

    <div class="form-group">
        <label><i class="fas fa-school"></i> Kelas</label>
        <select name="kelas_id" id="kelas_id" required onchange="showKelasInfo()">
            <option value="">-- Pilih Kelas Kamu --</option>
            <?php while($k = mysqli_fetch_assoc($kelas)){ ?>
            <option value="<?= $k['id']; ?>"><?= $k['nama_kelas']; ?></option>
            <?php } ?>
        </select>

        <div class="kelas-info" id="kelasInfo">
            <i class="fas fa-info-circle"></i>
            Tugas yang diberikan guru akan tampil sesuai kelas yang kamu pilih.
        </div>
    </div>

    <div class="form-group">
        <label><i class="fas fa-calendar-alt"></i> Tahun Ajaran</label>
        <select name="tahun_ajaran_id" id="tahun_ajaran_id" required onchange="showTahunInfo()">
            <option value="">-- Pilih Tahun Ajaran --</option>
            <?php while($t = mysqli_fetch_assoc($tahun_ajaran)){ ?>
            <option value="<?= $t['id']; ?>" <?= ($t['status'] == 'Aktif') ? 'selected' : ''; ?>>
                <?= $t['tahun']; ?> <?= ($t['status'] == 'Aktif') ? '(Aktif)' : ''; ?>
            </option>
            <?php } ?>
        </select>

        <div class="kelas-info" id="tahunInfo" style="display:none;">
            <i class="fas fa-info-circle"></i>
            Pastikan kamu memilih tahun ajaran yang sedang berjalan agar tugas tidak bentrok dengan tahun sebelumnya.
        </div>
    </div>

    <button type="submit" name="daftar" class="btn-register" id="btnRegister">
        <i class="fas fa-user-plus"></i> Daftar Sekarang
    </button>

    <div class="loading" id="loadingText">
        ⏳ Memproses pendaftaran...
    </div>

</form>

<div class="login-link">
    Sudah punya akun? <a href="login.php">Login di sini</a>
</div>

</div>

<script>
function showKelasInfo(){
    var sel = document.getElementById('kelas_id');
    var info = document.getElementById('kelasInfo');

    if(sel.value !== ''){
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
}

function showLoading(){
    document.getElementById("loadingText").style.display = "block";
    document.getElementById("btnRegister").innerHTML = "<i class='fas fa-spinner fa-spin'></i> Memproses...";
}

function showTahunInfo(){
    var sel = document.getElementById('tahun_ajaran_id');
    var info = document.getElementById('tahunInfo');

    if(sel.value !== ''){
        info.style.display = 'block';
    } else {
        info.style.display = 'none';
    }
}
</script>

</body>
</html>
