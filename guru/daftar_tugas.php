<?php
session_start();
include '../config/koneksi.php';

$data = mysqli_query($conn,"
SELECT * FROM tugas
ORDER BY id DESC
");

$total_tugas = mysqli_num_rows($data);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Daftar Tugas</title>

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
}

.sidebar a i{
margin-right:10px;
}

.active{
background:#22c55e;
color:white !important;
}

/* MAIN */

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
}

.avatar{
width:90px;
height:90px;
border-radius:50%;
background:#22c55e;
display:flex;
align-items:center;
justify-content:center;
font-size:40px;
color:white;
}

/* STAT */

.stat{
background:white;
padding:25px;
border-radius:25px;
box-shadow:0 10px 25px rgba(0,0,0,.05);
margin-top:25px;
text-align:center;
color:#15803d;
}

.stat i{
font-size:40px;
margin-bottom:15px;
}

.stat h1{
font-size:42px;
font-weight:700;
}

/* TASK CARD */

.task-card{
background:white;
border-radius:25px;
padding:25px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
transition:.3s;
height:100%;
border-left:6px solid #22c55e;
}

.task-card:hover{
transform:translateY(-5px);
}

.task-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:15px;
gap:10px;
}

.task-header h4{
font-size:20px;
font-weight:700;
color:#15803d;
margin:0;
}

.deadline{
background:#dcfce7;
color:#15803d;
padding:8px 12px;
border-radius:10px;
font-size:13px;
font-weight:600;
white-space:nowrap;
}

.desc{
color:#555;
line-height:1.7;
margin-top:10px;
min-height:80px;
}

.task-footer{
display:flex;
gap:10px;
margin-top:20px;
}

.btn-edit{
background:#22c55e;
color:white;
padding:10px 18px;
border-radius:12px;
text-decoration:none;
font-weight:600;
}

.btn-delete{
background:#ef4444;
color:white;
padding:10px 18px;
border-radius:12px;
text-decoration:none;
font-weight:600;
}

.btn-edit:hover{
background:#16a34a;
color:white;
}

.btn-delete:hover{
background:#dc2626;
color:white;
}

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

.task-header{
flex-direction:column;
align-items:flex-start;
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

<a href="dashboard.php">
<i class="fas fa-chart-line"></i> Dashboard
</a>

<a href="buat_tugas.php">
<i class="fas fa-plus-circle"></i> Buat Tugas
</a>

<a href="daftar_tugas.php" class="active">
<i class="fas fa-book"></i> Daftar Tugas
</a>

<a href="nilai.php">
<i class="fas fa-star"></i> Penilaian
</a>

<a href="profil.php">
<i class="fas fa-user"></i> Profil Guru
</a>

<a href="../auth/logout.php">
<i class="fas fa-sign-out-alt"></i> Logout
</a>

</div>

<div class="main">

<div class="top-card">

<div>
<h1>Daftar Tugas</h1>
<p class="text-muted">
Kelola semua tugas yang telah dibuat
</p>
</div>

<div class="avatar">📚</div>

</div>

<div class="stat">
<i class="fas fa-book"></i>
<h1><?= $total_tugas ?></h1>
<p>Total Tugas</p>
</div>

<div class="row mt-4">

<?php mysqli_data_seek($data,0); ?>
<?php while($d=mysqli_fetch_assoc($data)){ ?>

<div class="col-md-6 mb-4">

<div class="task-card">

<div class="task-header">

<h4><?= $d['judul']; ?></h4>

<span class="deadline">
<i class="fas fa-calendar"></i>
<?= $d['deadline']; ?>
</span>

</div>

<div class="desc">
<?= $d['deskripsi']; ?>
</div>

<div class="task-footer">

<a href="edit_tugas.php?id=<?= $d['id']; ?>" class="btn-edit">
<i class="fas fa-edit"></i> Edit
</a>

<a href="hapus_tugas.php?id=<?= $d['id']; ?>"
class="btn-delete"
onclick="return confirm('Hapus tugas ini?')">
<i class="fas fa-trash"></i> Hapus
</a>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</body>
</html>
```
