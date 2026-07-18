<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

$id_siswa = $_SESSION['id'];

$data = mysqli_query($conn,"
SELECT * FROM pengumpulan
WHERE id_siswa='$id_siswa'
ORDER BY id DESC
");
?>

<!DOCTYPE html>

<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Pengumpulan Saya</title>

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
    background:linear-gradient(135deg,#dcfce7,#f0fdf4,#ffffff);
    min-height:100vh;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 30px;
}

.btn-top{
    text-decoration:none;
    color:white;
    padding:10px 18px;
    border-radius:12px;
    font-weight:600;
}

.btn-back{
    background:#16a34a;
}

.btn-logout{
    background:#ef4444;
}


.main{
    max-width:1200px;
    margin:auto;
    padding:0 20px 40px;
}


.header{
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:white;
    padding:40px;
    border-radius:25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    box-shadow:0 15px 35px rgba(34,197,94,.25);
}

.header h2{
    font-size:40px;
    font-weight:700;
    margin-bottom:8px;
}

.header p{
    margin:0;
    opacity:.9;
}

.icon{
    font-size:50px;
    opacity:.9;
}


.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:20px;
}


.card-task{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.3s;
}

.card-task:hover{
    transform:translateY(-5px);
}

.title{
    font-size:20px;
    font-weight:700;
    color:#15803d;
    margin-bottom:10px;
}

.sub{
    color:#6b7280;
    margin-bottom:15px;
    font-size:14px;
}

.nilai{
    display:inline-block;
    padding:8px 14px;
    border-radius:50px;
    font-size:13px;
    font-weight:600;
}

.nilai-hijau{
    background:#dcfce7;
    color:#15803d;
}

.nilai-kuning{
    background:#fef9c3;
    color:#a16207;
}

.nilai-merah{
    background:#fee2e2;
    color:#b91c1c;
}

.komentar{
    margin-top:15px;
    padding:15px;
    border-left:4px solid #22c55e;
    background:#f0fdf4;
    border-radius:10px;
    font-size:14px;
    color:#14532d;
}

.btn-view{
    display:inline-block;
    margin-top:15px;
    text-decoration:none;
    background:#16a34a;
    color:white;
    padding:10px 18px;
    border-radius:12px;
    font-weight:600;
}

.btn-view:hover{
    background:#15803d;
    color:white;
}

.kosong{
    background:white;
    padding:40px;
    border-radius:20px;
    text-align:center;
    color:#6b7280;
    box-shadow:0 10px 25px rgba(0,0,0,.05);
}

@media(max-width:768px){

    .header{
        flex-direction:column;
        text-align:center;
        gap:20px;
    }

    .header h2{
        font-size:30px;
    }

    .topbar{
        flex-direction:column;
        gap:10px;
    }
}
</style>

</head>

<body>

<div class="topbar">

<a href="dashboard.php" class="btn-top btn-back">
<i class="fas fa-home"></i> Dashboard
</a>

</div>

<div class="main">

<div class="header">
    <div>
        <h2>Pengumpulan Saya</h2>
        <p>Riwayat tugas yang sudah kamu kirim</p>
    </div>

<div class="icon">
    <i class="fas fa-upload"></i>
</div>

</div>

<?php if(mysqli_num_rows($data)==0){ ?>

<div class="kosong">
    <h4>Belum Ada Pengumpulan</h4>
    <p>Silakan kumpulkan tugas terlebih dahulu.</p>
</div>

<?php } else { ?>

<div class="grid">

<?php while($d=mysqli_fetch_assoc($data)){ ?>

<div class="card-task">

<div class="title">
    Tugas: <?= $d['id_tugas']; ?>
</div>

<div class="sub">
    <i class="fas fa-calendar"></i>
    <?= $d['tanggal_upload']; ?>
</div>

<?php
$nilai = $d['nilai'];

if($nilai !== NULL){

    if($nilai >= 80){
        echo '<span class="nilai nilai-hijau">Nilai: '.$nilai.'</span>';
    }
    elseif($nilai >= 60){
        echo '<span class="nilai nilai-kuning">Nilai: '.$nilai.'</span>';
    }
    else{
        echo '<span class="nilai nilai-merah">Nilai: '.$nilai.'</span>';
    }

}else{
    echo '<span class="nilai nilai-kuning">Belum Dinilai</span>';
}
?>

<?php if(!empty($d['komentar'])){ ?>

<div class="komentar">
    <strong>Komentar Guru:</strong><br>
    <?= htmlspecialchars($d['komentar']); ?>
</div>
<?php } ?>

<a class="btn-view"
href="../uploads/<?= $d['file_tugas']; ?>"
target="_blank">
Lihat File </a>

</div>

<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>
