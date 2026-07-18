<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_SESSION['id'];

$siswa = mysqli_fetch_assoc(
    mysqli_query($conn,"
    SELECT *
    FROM users
    WHERE id='$id_siswa'
    ")
);

$kelas_id = $siswa['kelas_id'];
$tahun_ajaran_id = $siswa['tahun_ajaran_id'];

$data = mysqli_query($conn,"
SELECT *
FROM tugas
WHERE kelas_id='$kelas_id'
AND tahun_ajaran_id='$tahun_ajaran_id'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Daftar Tugas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#eefaf1,#f7fffa);
    min-height:100vh;
}


.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:25px 40px 10px;
}

.btn-dashboard,
.btn-logout{
    text-decoration:none !important;
    color:white !important;
    padding:12px 24px;
    border-radius:14px;
    font-weight:600;
    display:inline-flex;
    align-items:center;
    gap:8px;
    transition:.3s;
    box-shadow:0 8px 20px rgba(0,0,0,.10);
}

.btn-dashboard{
    background:#16a34a;
}

.btn-dashboard:hover{
    background:#15803d;
    transform:translateY(-2px);
}

.btn-logout{
    background:#ef4444;
}

.btn-logout:hover{
    background:#dc2626;
    transform:translateY(-2px);
}



.header{
    margin:15px 40px 30px;
    padding:40px;
    border-radius:28px;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:white;
    position:relative;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(34,197,94,.25);
}

.header::before{
    content:'';
    position:absolute;
    width:220px;
    height:220px;
    border-radius:50%;
    background:rgba(255,255,255,.12);
    top:-90px;
    right:-90px;
}

.header h2{
    font-size:46px;
    font-weight:700;
    margin-bottom:10px;
    position:relative;
    z-index:2;
}

.header p{
    font-size:18px;
    opacity:.95;
    position:relative;
    z-index:2;
}

.header .icon{
    position:absolute;
    right:40px;
    top:50%;
    transform:translateY(-50%);
    font-size:65px;
    color:rgba(255,255,255,.9);
    z-index:2;
}


.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
    gap:25px;
    padding:0 40px 40px;
}



.card-task{
    background:white;
    border-radius:25px;
    padding:28px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.3s;
    display:flex;
    flex-direction:column;
    height:100%;
}

.card-task:hover{
    transform:translateY(-6px);
    box-shadow:0 20px 40px rgba(34,197,94,.15);
}

.title{
    font-size:26px;
    font-weight:700;
    color:#15803d;
    margin-bottom:10px;
}

.desc{
    color:#6b7280;
    line-height:1.8;
    margin-bottom:15px;
    flex:1;
}

.deadline{
    font-size:15px;
    font-weight:600;
    margin-bottom:18px;
}



.btn-upload{
    display:inline-block;
    text-decoration:none !important;
    color:white !important;
    background:#22c55e;
    padding:12px 22px;
    border-radius:12px;
    font-weight:600;
    transition:.3s;
}

.btn-upload:hover{
    background:#16a34a;
    transform:translateY(-2px);
}



.status-btn{
    border:none !important;
    border-radius:12px !important;
    padding:12px 20px !important;
    font-weight:600 !important;
    width:fit-content;
}



.empty{
    background:white;
    padding:70px;
    border-radius:25px;
    text-align:center;
    color:#6b7280;
    grid-column:1/-1;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}


@media(max-width:768px){

    .topbar{
        padding:20px;
        flex-direction:column;
        gap:10px;
    }

    .btn-dashboard,
    .btn-logout{
        width:100%;
        justify-content:center;
    }

    .header{
        margin:15px;
        padding:25px;
    }

    .header h2{
        font-size:32px;
    }

    .header p{
        font-size:15px;
    }

    .header .icon{
        display:none;
    }

    .grid{
        padding:15px;
        grid-template-columns:1fr;
    }

    .card-task{
        padding:22px;
    }

    .title{
        font-size:22px;
    }
}

</style>

</head>

<body>

<div class="topbar">

    <a href="dashboard.php" class="btn-dashboard">
        <i class="fas fa-home"></i> Dashboard
    </a>

</div>

<div class="main">

<div class="header">

    <div>
        <h2>Daftar Tugas</h2>
        <p>Pilih tugas dan upload hasil kamu</p>
    </div>

    <div class="icon">
        <i class="fas fa-book"></i>
    </div>

</div>

<div class="grid">

<?php if(mysqli_num_rows($data) > 0){ ?>

<?php while($d = mysqli_fetch_assoc($data)){ ?>

<?php

$id_tugas = $d['id'];

$cek = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM pengumpulan
        WHERE id_tugas='$id_tugas'
        AND id_siswa='$id_siswa'"
    )
);

$sudah_kumpul = $cek > 0;

$deadline_lewat =
strtotime($d['deadline']) < time();

?>

<div class="card-task">

<div class="title">
<?= htmlspecialchars($d['judul']); ?>
</div>

<div class="desc">
<?= htmlspecialchars($d['deskripsi']); ?>
</div>

<div class="deadline"
style="color: <?= ($deadline_lewat && !$sudah_kumpul) ? '#dc2626' : '#16a34a'; ?>;">

⏰ Deadline:
<?= date('d M Y H:i', strtotime($d['deadline'])); ?>

</div>

<?php if($sudah_kumpul){ ?>

<button class="status-btn btn btn-success" disabled>
✔ Selesai
</button>

<?php } elseif($deadline_lewat){ ?>

<button class="status-btn btn btn-danger" disabled>
✖ Ditutup
</button>

<?php } else { ?>

<a
class="btn-upload status-btn"
href="upload_tugas.php?id=<?= $d['id']; ?>">
Upload Tugas
</a>

<?php } ?>

</div>

<?php } ?>

<?php } else { ?>

<div class="empty">
Belum ada tugas yang tersedia.
</div>

<?php } ?>

</div>

</div>

</body>
</html>