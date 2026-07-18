<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_SESSION['id'];

// Ambil data siswa termasuk kelas dan tahun ajaran
$siswa_data = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT u.*, k.nama_kelas, ta.tahun AS tahun_ajaran
FROM users u
LEFT JOIN kelas k ON u.kelas_id = k.id
LEFT JOIN tahun_ajaran ta ON u.tahun_ajaran_id = ta.id
WHERE u.id='$id_siswa'
"));

$kelas_id = $siswa_data['kelas_id'];
$tahun_ajaran_id = $siswa_data['tahun_ajaran_id'];
$nama_kelas = $siswa_data['nama_kelas'] ?? 'Belum dipilih';
$tahun_ajaran = $siswa_data['tahun_ajaran'] ?? 'Belum dipilih';

// Hitung tugas hanya untuk kelas DAN tahun ajaran siswa
$total_tugas = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tugas WHERE kelas_id='$kelas_id' AND tahun_ajaran_id='$tahun_ajaran_id'"));

$total_kumpul = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM pengumpulan WHERE id_siswa='$id_siswa'
"));

$rata = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT AVG(nilai) as rata FROM pengumpulan WHERE id_siswa='$id_siswa'
"));

$nilai_rata = round($rata['rata'] ?? 0);

$progress = ($total_tugas > 0) ? round(($total_kumpul/$total_tugas)*100) : 0;
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard Siswa</title>

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
    overflow-x:hidden;
    background:
    radial-gradient(circle at 10% 20%, #bbf7d0 0%, transparent 25%),
    radial-gradient(circle at 90% 10%, #86efac 0%, transparent 30%),
    radial-gradient(circle at 50% 90%, #dcfce7 0%, transparent 35%),
    linear-gradient(135deg,#f0fdf4,#ffffff);
    position:relative;
}

/* Background blur blobs */

body::before{
    content:'';
    position:fixed;
    width:400px;
    height:400px;
    background:#22c55e;
    border-radius:50%;
    filter:blur(180px);
    opacity:.18;
    top:-150px;
    left:-100px;
    z-index:-1;
}

body::after{
    content:'';
    position:fixed;
    width:350px;
    height:350px;
    background:#16a34a;
    border-radius:50%;
    filter:blur(160px);
    opacity:.15;
    bottom:-100px;
    right:-100px;
    z-index:-1;
}

/* TOPBAR */

.topbar{
    position:fixed;
    top:25px;
    right:25px;
    z-index:1000;
}

.logout-btn{
    padding:14px 24px;
    border-radius:50px;
    text-decoration:none;
    color:white;
    font-weight:600;
    background:linear-gradient(135deg,#ef4444,#dc2626);
    box-shadow:
    0 10px 30px rgba(239,68,68,.3);
    transition:.4s;
}

.logout-btn:hover{
    transform:translateY(-4px);
}

/* MAIN */

.main{
    padding:40px;
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:25px;
}

/* GLASS CARD */

.card,
.profile,
.menu,
.progress-box{
    background:rgba(255,255,255,.65);
    backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,.7);
    box-shadow:
    0 20px 40px rgba(0,0,0,.05),
    inset 0 1px 1px rgba(255,255,255,.8);
}

/* LEFT RIGHT */

.left,
.right{
    display:flex;
    flex-direction:column;
    gap:25px;
}

/* HERO */

.welcome{
    position:relative;
    overflow:hidden;
    border-radius:35px;
    padding:45px;
    background:
    linear-gradient(
    135deg,
    #16a34a,
    #22c55e
    );
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:
    0 25px 50px rgba(34,197,94,.35);
}

.welcome::before{
    content:'';
    position:absolute;
    width:300px;
    height:300px;
    border-radius:50%;
    background:rgba(255,255,255,.12);
    right:-100px;
    top:-120px;
}

.welcome::after{
    content:'';
    position:absolute;
    width:180px;
    height:180px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
    left:50%;
    bottom:-100px;
}

.welcome h2{
    font-size:48px;
    font-weight:700;
    margin-bottom:10px;
}

.welcome p{
    font-size:18px;
    opacity:.95;
}

.icon{
    font-size:90px;
    z-index:2;
    animation:float 4s ease-in-out infinite;
}

@keyframes float{
    0%,100%{
        transform:translateY(0);
    }
    50%{
        transform:translateY(-15px);
    }
}



.stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
}

.card{
    padding:35px;
    border-radius:30px;
    text-align:center;
    transition:.5s;
    position:relative;
    overflow:hidden;
}

.card::before{
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(
    135deg,
    rgba(34,197,94,.05),
    transparent
    );
}

.card:hover{
    transform:
    translateY(-10px)
    rotateX(5deg);
    box-shadow:
    0 30px 50px rgba(34,197,94,.15);
}

.card i{
    font-size:45px;
    margin-bottom:15px;
}

.card:nth-child(1) i{
    color:#22c55e;
}

.card:nth-child(2) i{
    color:#0ea5e9;
}

.card:nth-child(3) i{
    color:#f59e0b;
}

.card h3{
    font-size:42px;
    color:#111827;
}

.card p{
    color:#6b7280;
    font-weight:500;
}

.chart-card{
    background:rgba(255,255,255,.7);
    backdrop-filter:blur(20px);
    border-radius:30px;
    padding:30px;
    box-shadow:0 20px 40px rgba(0,0,0,.05);
}

.card-header{
    margin-bottom:20px;
}

.card-header h3{
    color:#15803d;
}



.profile{
    padding:35px;
    border-radius:35px;
    text-align:center;
}

.profile i{
    font-size:90px;
    color:#22c55e;
    margin-bottom:10px;
}

.profile h4{
    font-size:30px;
    color:#15803d;
}

.profile p{
    color:#6b7280;
}



.progress-box{
    border-radius:35px;
    padding:30px;
}

.progress-box h3{
    font-size:32px;
    margin-bottom:20px;
    color:#111827;
}

.bar{
    height:18px;
    border-radius:50px;
    overflow:hidden;
    background:#e5e7eb;
}

.fill{
    height:100%;
    border-radius:50px;
    background:
    linear-gradient(
    90deg,
    #16a34a,
    #4ade80,
    #86efac
    );
    box-shadow:
    0 0 20px rgba(34,197,94,.5);
}


.menu{
    border-radius:35px;
    padding:30px;
}

.menu h3{
    font-size:30px;
    margin-bottom:15px;
}

.menu a{
    display:flex;
    align-items:center;
    gap:12px;
    text-decoration:none;
    padding:18px;
    margin-top:12px;
    border-radius:18px;
    font-weight:600;
    color:#15803d;
    background:white;
    transition:.4s;
}

.menu a:hover{
    transform:translateX(10px);
    background:
    linear-gradient(
    135deg,
    #16a34a,
    #22c55e
    );
    color:white;
    box-shadow:
    0 15px 30px rgba(34,197,94,.25);
}


::-webkit-scrollbar{
    width:10px;
}

::-webkit-scrollbar-thumb{
    background:#22c55e;
    border-radius:20px;
}

/* RESPONSIVE */

@media(max-width:1000px){

    .main{
        grid-template-columns:1fr;
    }

    .stats{
        grid-template-columns:1fr;
    }

    .welcome{
        flex-direction:column;
        text-align:center;
        gap:20px;
    }

    .welcome h2{
        font-size:34px;
    }

    .icon{
        font-size:70px;
    }
}

</style>

</head>

<body>

<div class="topbar">

</div>


<div class="main">


<div class="left">

<div class="welcome">
<div>
<h2>Halo, <?= $_SESSION['nama']; ?></h2>
<p>Kelas: <?= $nama_kelas; ?> &bull; TA: <?= $tahun_ajaran; ?> — Semangat belajar hari ini 🚀</p>
</div>

<div class="icon">
<i class="fas fa-user-graduate"></i>
</div>
</div>

<div class="stats">

<div class="card">
<i class="fas fa-book"></i>
<h3><?= $total_tugas ?></h3>
<p>Tugas</p>
</div>

<div class="card">
<i class="fas fa-upload"></i>
<h3><?= $total_kumpul ?></h3>
<p>Dikumpulkan</p>
</div>

<div class="card">
<i class="fas fa-star"></i>
<h3><?= $nilai_rata ?></h3>
<p>Nilai</p>
</div>

</div>
<div class="chart-card">
    <div class="card-header">
        <h3>📈 Grafik Nilai Saya</h3>
    </div>

    <canvas id="nilaiChart"></canvas>
</div>

</div>

<div class="right">

<div class="profile">
<i class="fas fa-user-circle"></i>
<h4><?= $_SESSION['nama']; ?></h4>
<p class="mb-1">Siswa Aktif</p>
<span style="display:inline-block;padding:6px 14px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);color:#166534;font-size:13px;font-weight:600;border-radius:50px;margin-bottom:6px;"><i class="fas fa-school" style="margin-right:5px;"></i><?= $nama_kelas; ?></span>
<span style="display:inline-block;padding:6px 14px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#1e40af;font-size:13px;font-weight:600;border-radius:50px;"><i class="fas fa-calendar" style="margin-right:5px;"></i><?= $tahun_ajaran; ?></span>
</div>

<div class="progress-box">
<h5>Progress Tugas</h5>

<div class="bar">
<div class="fill" style="width:<?= $progress ?>%"></div>
</div>

<p style="margin-top:10px;color:gray;font-size:13px;">
<?= $progress ?>% selesai
</p>

</div>

<div class="menu">
<h5>Menu Cepat</h5>
<div class="quick-menu">

    <h3>Menu Cepat</h3>

    <a href="tugas.php" class="menu-link">
        📚 Lihat Tugas
    </a>

    <a href="riwayat.php" class="menu-link">
        📤 Lihat Riwayat
    </a>

    <a href="nilai.php" class="menu-link">
        ⭐ Lihat Nilai
    </a>

    <a href="../auth/logout.php" class="logout-menu">
        <i class="fas fa-sign-out-alt"></i>
        Logout
    </a>

</div>
</div>

</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('nilaiChart');

new Chart(ctx,{
    type:'line',
    data:{
        labels:[
            'Tugas 1',
            'Tugas 2',
            'Tugas 3',
            'Tugas 4',
            'Tugas 5'
        ],
        datasets:[{
            data:[75,90,85,95,88],
            borderColor:'#22c55e',
            backgroundColor:'rgba(34,197,94,.2)',
            fill:true,
            tension:.4
        }]
    },
    options:{
        responsive:true,
        plugins:{
            legend:{
                display:false
            }
        }
    }
});
</script>
</body>
</html>