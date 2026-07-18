<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['nama'])){
    $_SESSION['nama'] = "Guru SMK Kartika";
}

$total_tugas = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tugas"));
$total_pengumpulan = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM pengumpulan"));

$sudah_dinilai = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM pengumpulan
WHERE nilai IS NOT NULL
AND nilai <> ''
"));

$belum_dinilai = $total_pengumpulan - $sudah_dinilai;


$aktivitas = [];

$q_tugas = mysqli_query($conn,"
SELECT judul,id
FROM tugas
ORDER BY id DESC
LIMIT 2
");

while($a = mysqli_fetch_assoc($q_tugas)){
    $aktivitas[] = [
        'icon' => 'fa-book',
        'warna' => 'text-success',
        'teks' => 'Tugas baru dibuat : '.$a['judul']
    ];
}

/* Pengumpulan terbaru */
$q_kumpul = mysqli_query($conn,"
SELECT u.nama
FROM pengumpulan p
JOIN users u ON p.id_siswa=u.id
ORDER BY p.id DESC
LIMIT 2
");

while($a = mysqli_fetch_assoc($q_kumpul)){
    $aktivitas[] = [
        'icon' => 'fa-upload',
        'warna' => 'text-primary',
        'teks' => $a['nama'].' mengumpulkan tugas'
    ];
}

/* Nilai terbaru */
$q_nilai = mysqli_query($conn,"
SELECT u.nama,p.nilai
FROM pengumpulan p
JOIN users u ON p.id_siswa=u.id
WHERE p.nilai IS NOT NULL
ORDER BY p.id DESC
LIMIT 2
");

while($a = mysqli_fetch_assoc($q_nilai)){
    $aktivitas[] = [
        'icon' => 'fa-check-circle',
        'warna' => 'text-warning',
        'teks' => $a['nama'].' mendapat nilai '.$a['nilai']
    ];
}

$top_siswa = mysqli_query($conn,"
SELECT
u.nama,
MAX(p.nilai) as nilai
FROM pengumpulan p
INNER JOIN users u ON p.id_siswa = u.id
WHERE p.nilai IS NOT NULL
AND u.role='siswa'
GROUP BY u.id
ORDER BY nilai DESC
LIMIT 3
");

$tugas = mysqli_query($conn,"
SELECT
tugas.*,
kelas.nama_kelas,
tahun_ajaran.tahun
FROM tugas

LEFT JOIN kelas
ON tugas.kelas_id = kelas.id

LEFT JOIN tahun_ajaran
ON tugas.tahun_ajaran_id = tahun_ajaran.id

ORDER BY tugas.id DESC
LIMIT 5
");

$belum_kumpul = mysqli_query($conn,"
SELECT nama
FROM users
WHERE role='siswa'
AND id NOT IN (
    SELECT DISTINCT id_siswa
    FROM pengumpulan
)
LIMIT 5
");
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Guru</title>

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
background:linear-gradient(135deg,#eefbf2,#f7fff9);
}

/* ======================
   SIDEBAR
====================== */

.sidebar{
position:fixed;
left:20px;
top:20px;
width:260px;
height:calc(100vh - 40px);
padding:30px;
background:white;
border-radius:30px;
box-shadow:0 10px 30px rgba(0,0,0,.08);
overflow-y:auto;
}

.logo{
text-align:center;
margin-bottom:40px;
}

.logo .icon{
font-size:60px;
}

.logo h2{
color:#15803d;
font-weight:700;
margin-top:10px;
}

.logo p{
color:#666;
font-size:14px;
}

.sidebar a{
display:block;
padding:14px 18px;
margin-bottom:10px;
text-decoration:none;
color:#15803d;
font-weight:600;
border-radius:15px;
transition:.3s;
}

.sidebar a:hover{
background:#22c55e;
color:white;
transform:translateX(5px);
}

.sidebar a i{
margin-right:10px;
}

/* ======================
   MAIN
====================== */

.main{
margin-left:300px;
padding:30px;
}

.top-card{
background:white;
padding:35px;
border-radius:30px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 10px 25px rgba(0,0,0,.05);
margin-bottom:10px;
}

.avatar{
width:90px;
height:90px;
border-radius:50%;
background:linear-gradient(135deg,#22c55e,#15803d);
display:flex;
align-items:center;
justify-content:center;
font-size:40px;
color:white;
box-shadow:0 10px 25px rgba(34,197,94,.25);
}

.row-card{
display:flex;
gap:20px;
margin-top:30px;
width:100%;
}

/* ======================
   STAT CARD
====================== */

.stat{
flex:1;
background:white;
padding:30px;
border-radius:25px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
text-align:center;
color:#15803d;
min-height:200px;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
transition:.3s;
}

.stat:hover{
transform:translateY(-8px);
box-shadow:0 20px 40px rgba(34,197,94,.15);
}

.stat i{
font-size:40px;
margin-bottom:15px;
}

.stat h1{
font-size:42px;
font-weight:700;
margin-bottom:10px;
}

/* ======================
   CONTENT
====================== */

.content{
display:grid;
grid-template-columns:2.2fr 0.9fr;
gap:25px;
margin-top:25px;
align-items:start;
}

.box{
background:rgba(255,255,255,.97);
backdrop-filter:blur(10px);
padding:28px;
border-radius:28px;
box-shadow:
0 10px 30px rgba(0,0,0,.05),
0 1px 2px rgba(0,0,0,.03);
height:fit-content;
}

.calendar-box{
text-align:center;
}

/* ======================
   TABLE
====================== */

.table th{
color:#15803d;
}

/* ======================
   CALENDAR
====================== */

.calendar{
width:140px;
height:140px;
margin:auto;
border-radius:25px;
background:linear-gradient(135deg,#22c55e,#15803d);
color:white;
display:flex;
flex-direction:column;
justify-content:center;
box-shadow:0 15px 30px rgba(34,197,94,.25);
}

.day{
font-size:45px;
font-weight:700;
}

.month{
font-size:18px;
}

.year{
font-size:15px;
}

/* ======================
   TASK CARD
====================== */

.task-card{
display:flex;
justify-content:space-between;
align-items:center;
padding:18px;
background:#f8fafc;
border-radius:18px;
margin-bottom:15px;
transition:.3s;
border-left:5px solid #22c55e;
}

.task-card:hover{
transform:translateX(6px);
background:#f0fdf4;
}

.task-left{
display:flex;
align-items:center;
gap:15px;
}

.task-icon{
width:55px;
height:55px;
border-radius:15px;
background:linear-gradient(135deg,#22c55e,#15803d);
display:flex;
align-items:center;
justify-content:center;
color:white;
font-size:22px;
}

.task-card h5{
margin:0;
font-weight:600;
color:#0f172a;
max-width:500px;
word-break:break-word;
}

.task-card .badge{
padding:10px 15px;
font-size:13px;
border-radius:30px;
}

/* ======================
   TOP SISWA ULTRA MODERN
====================== */

.top-student-card{
position:relative;
display:flex;
align-items:center;
justify-content:space-between;
padding:20px 25px;
margin-bottom:18px;
border-radius:22px;
background:linear-gradient(135deg,#ffffff,#f8fffb);
border:1px solid rgba(34,197,94,.15);
overflow:hidden;
transition:.35s;
box-shadow:0 8px 25px rgba(0,0,0,.05);
}

.top-student-card::before{
content:'';
position:absolute;
left:0;
top:0;
width:6px;
height:100%;
border-radius:20px;
}

.top-student-card:hover{
transform:translateY(-6px) scale(1.01);
box-shadow:0 20px 40px rgba(34,197,94,.18);
}

.top-student-card.rank-1::before{
background:linear-gradient(#FFD700,#F59E0B);
}

.top-student-card.rank-2::before{
background:linear-gradient(#E5E7EB,#9CA3AF);
}

.top-student-card.rank-3::before{
background:linear-gradient(#D97706,#92400E);
}

.student-left{
display:flex;
align-items:center;
gap:18px;
}

.rank-badge{
width:70px;
height:70px;
border-radius:20px;
display:flex;
align-items:center;
justify-content:center;
font-size:32px;
box-shadow:0 10px 20px rgba(0,0,0,.12);
}

.rank-1 .rank-badge{
background:linear-gradient(135deg,#FFD700,#F59E0B);
}

.rank-2 .rank-badge{
background:linear-gradient(135deg,#E5E7EB,#9CA3AF);
}

.rank-3 .rank-badge{
background:linear-gradient(135deg,#D97706,#92400E);
}

.student-detail h5{
margin:0;
font-size:20px;
font-weight:700;
color:#111827;
}

.student-detail span{
display:block;
margin-top:3px;
font-size:13px;
color:#64748b;
}

.student-point{
min-width:85px;
height:85px;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
font-size:24px;
font-weight:800;
color:white;
background:linear-gradient(135deg,#22c55e,#15803d);
box-shadow:0 10px 25px rgba(34,197,94,.25);
}

.rank-1 .student-point{
background:linear-gradient(135deg,#FFD700,#F59E0B);
}

.rank-2 .student-point{
background:linear-gradient(135deg,#9CA3AF,#6B7280);
}

.rank-3 .student-point{
background:linear-gradient(135deg,#D97706,#92400E);
}

.ranking-badge{
background:linear-gradient(135deg,#22c55e,#15803d);
color:white;
padding:10px 18px;
border-radius:30px;
font-size:13px;
font-weight:700;
letter-spacing:.5px;
}

/* ======================
   ACTIVITY
====================== */

.activity-item{
display:flex;
align-items:center;
gap:10px;
padding:12px;
margin-bottom:10px;
background:#f8fafc;
border-radius:12px;
}

.activity-item i{
color:#22c55e;
}

/* ======================
   RESPONSIVE
====================== */

@media(max-width:1000px){

.sidebar{
position:relative;
width:100%;
height:auto;
left:0;
top:0;
margin-bottom:20px;
}

.main{
margin-left:0;
}

.content{
grid-template-columns:1fr;
}

.row-card{
flex-direction:column;
}

.top-card{
flex-direction:column;
gap:20px;
text-align:center;
}

/* ======================
   BELUM MENGUMPULKAN
====================== */

.belum-item{
display:flex;
align-items:center;
gap:12px;
padding:12px;
margin-bottom:10px;
background:#fff5f5;
border-radius:14px;
border-left:4px solid #ef4444;
transition:.3s;
}

.belum-item:hover{
transform:translateX(4px);
}

.belum-avatar{
width:42px;
height:42px;
border-radius:50%;
background:linear-gradient(135deg,#ef4444,#dc2626);
color:white;
font-weight:700;
display:flex;
align-items:center;
justify-content:center;
}

.belum-item small{
color:#6b7280;
}

/* ======================
   SUCCESS CARD
====================== */

.all-done-card{
text-align:center;
padding:25px 15px;
background:linear-gradient(135deg,#ecfdf5,#dcfce7);
border-radius:20px;
border:1px solid #bbf7d0;
}

.done-icon{
width:55px;
height:55px;
font-size:24px;
margin:auto;
margin-bottom:15px;
background:linear-gradient(135deg,#22c55e,#15803d);
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
font-size:30px;
color:white;
box-shadow:0 10px 25px rgba(34,197,94,.25);
}

.all-done-card h5{
margin-bottom:6px;
font-weight:700;
font-size:16px;
color:#15803d;
}

.all-done-card p{
margin:0;
font-size:12px;
line-height:1.5;
color:#6b7280;
}

.section-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.section-header h4{
margin:0;
font-weight:700;
font-size:20px;
display:flex;
align-items:center;
gap:8px;
}

.section-badge{
background:linear-gradient(135deg,#ef4444,#dc2626);
color:white;
padding:6px 12px;
border-radius:10px;
font-size:11px;
font-weight:600;
}

.belum-title{
font-size:18px !important;
white-space:nowrap;
display:flex;
align-items:center;
justify-content:center;
gap:8px;
}

}

</style>
</head>
<body>

<div class="sidebar">

<div class="logo">
<div class="icon"><img src="../assets/css/images/logo_kartika.png" style="width:60px;height:auto;"></div>
<h2>SMK Kartika</h2>
<p>Sistem Pengumpulan Tugas</p>
</div>

<a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
<a href="buat_tugas.php"><i class="fas fa-plus-circle"></i> Buat Tugas</a>
<a href="daftar_tugas.php"><i class="fas fa-book"></i> Daftar Tugas</a>
<a href="nilai.php"><i class="fas fa-star"></i> Penilaian</a>
<a href="profil.php"><i class="fas fa-user"></i> Profil Guru</a>
<a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

</div>

<div class="main">

<div class="top-card">

<div>
<h1>TEACHER</h1>
<p class="text-muted">
Selamat Datang, <?= $_SESSION['nama']; ?>
</p>
</div>

<div class="avatar">👨‍🏫</div>

</div>

<div class="row-card">


<div class="stat">
<i class="fas fa-book"></i>
<h1><?= $total_tugas ?></h1>
<p>Total Tugas</p>
</div>

<div class="stat">
<i class="fas fa-folder-open"></i>
<h1><?= $total_pengumpulan ?></h1>
<p>Pengumpulan</p>
</div>

<div class="stat">
<i class="fas fa-check-circle"></i>
<h1><?= $sudah_dinilai ?></h1>
<p>Sudah Dinilai</p>
</div>

<div class="stat">
<i class="fas fa-clock"></i>
<h1><?= $belum_dinilai ?></h1>
<p>Belum Dinilai</p>
</div>

</div>
<?php
$persen = 0;

if($total_pengumpulan > 0){
$persen = round(($sudah_dinilai/$total_pengumpulan)*100);
}
?>

<div class="box mt-4">

<div class="d-flex justify-content-between mb-2">
<h4>Progress Penilaian</h4>
<b><?= $persen ?>%</b>
</div>

<div class="progress" style="height:18px;border-radius:20px;">
<div class="progress-bar bg-success"
style="width:<?= $persen ?>%">
</div>
</div>

<p class="mt-3 text-muted">
<?= $sudah_dinilai ?> tugas sudah dinilai dari
<?= $total_pengumpulan ?> pengumpulan.
</p>

</div>
<div class="content">

    <!-- KOLOM KIRI -->
    <div>

        <div class="box">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">
                    <i class="fas fa-fire text-success"></i>
                    Tugas Terbaru
                </h3>

                <span class="badge bg-success px-3 py-2 rounded-pill">
                    <?= mysqli_num_rows($tugas); ?> Tugas
                </span>
            </div>

            <?php
            mysqli_data_seek($tugas,0);

            while($d=mysqli_fetch_assoc($tugas)){

                $sisa_hari = ceil(
                    (strtotime($d['deadline']) - time()) / 86400
                );

                if($sisa_hari <= 1){
                    $status = "Urgent";
                    $badge = "danger";
                    $icon = "fa-triangle-exclamation";
                }elseif($sisa_hari <= 3){
                    $status = "Segera";
                    $badge = "warning";
                    $icon = "fa-clock";
                }else{
                    $status = "Aktif";
                    $badge = "success";
                    $icon = "fa-circle-check";
                }
            ?>

            <div class="task-card mb-3">

                <div class="task-left">

                    <div class="task-icon">
                        <i class="fas fa-book-open"></i>
                    </div>

                    <div>
                        <h5 class="mb-1">
                            <?= htmlspecialchars($d['judul']); ?>
                        </h5>

                        <small class="text-muted">
                            <i class="fas fa-calendar-alt"></i>
                            Deadline :
                            <?= date('d M Y', strtotime($d['deadline'])); ?>
                        </small>
                    </div>

                </div>

                <span class="badge bg-<?= $badge ?>">
                    <i class="fas <?= $icon ?>"></i>
                    <?= $status ?>
                </span>

            </div>

            <?php } ?>

        </div>

  <div class="box mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">
            🏆 Top Siswa Terbaik
        </h3>

        <span class="ranking-badge">
            Ranking
        </span>
    </div>

    <?php
    mysqli_data_seek($top_siswa,0);
    $rank = 1;

    while($s = mysqli_fetch_assoc($top_siswa)){
    ?>

    <div class="top-student-card rank-<?= $rank ?>">

        <div class="student-left">

            <div class="rank-badge">
                <?php
                if($rank == 1){
                    echo "🥇";
                }elseif($rank == 2){
                    echo "🥈";
                }else{
                    echo "🥉";
                }
                ?>
            </div>

            <div class="student-detail">
                <h5><?= htmlspecialchars($s['nama']) ?></h5>
                <span>
                    <?= $rank == 1 ? 'Juara 1' : ($rank == 2 ? 'Juara 2' : 'Juara 3') ?>
                </span>
            </div>

        </div>

        <div class="student-point">
            <?= $s['nilai'] ?>
        </div>

    </div>

    <?php
    $rank++;
    }
    ?>

</div>

    </div>
<!-- BOX KALENDER -->
<div class="box calendar-box">

    <h4>Kalender</h4>

    <div class="calendar">
        <div class="day"><?= date('d'); ?></div>
        <div class="month"><?= date('M'); ?></div>
        <div class="year"><?= date('Y'); ?></div>
    </div>

    <h5 class="mt-3"><?= date('l'); ?></h5>

    <h3 id="clock" class="text-success fw-bold"></h3>

    <p><?= date('d F Y'); ?></p>

</div>


<!-- BOX AKTIVITAS -->
<div class="box mt-4">

    <h4 class="mb-3">
        <i class="fas fa-bell text-success"></i>
        Aktivitas Terbaru
    </h4>

 <?php foreach($aktivitas as $a){ ?>

<div class="activity-item">

    <i class="fas <?= $a['icon'] ?> <?= $a['warna'] ?>"></i>

    <?= htmlspecialchars($a['teks']) ?>

</div>

<?php } ?>

</div>


<!-- BOX BELUM KUMPUL -->
<div class="box mt-4">

    <div class="section-header">
        <h4 class="belum-title">
            <i class="fas fa-user-clock text-danger"></i>
            Belum Kumpul
        </h4>
    </div>

    <?php
    if(mysqli_num_rows($belum_kumpul) > 0){
        while($siswa = mysqli_fetch_assoc($belum_kumpul)){
    ?>

    <div class="belum-item">

        <div class="belum-avatar">
            <?= strtoupper(substr($siswa['nama'],0,1)); ?>
        </div>

        <div>
            <strong><?= htmlspecialchars($siswa['nama']); ?></strong>
            <br>
            <small>Belum mengumpulkan tugas</small>
        </div>

    </div>

    <?php
        }
    }else{
    ?>

    <div class="all-done-card">
        <div class="done-icon">🎉</div>
        <h5>Semua Tugas Terkumpul</h5>
        <p>Tidak ada siswa yang belum mengumpulkan tugas.</p>
    </div>

    <?php } ?>

</div>

        </div>

    </div>

</div>