<?php
session_start();
include '../config/koneksi.php';

if(!isset($_GET['id'])){
    header("Location: daftar_tugas.php");
    exit;
}

$id = intval($_GET['id']);

$data = mysqli_query($conn,"SELECT * FROM tugas WHERE id='$id'");
$t = mysqli_fetch_assoc($data);

if(!$t){
    header("Location: daftar_tugas.php");
    exit;
}

if(isset($_POST['update'])){

    $judul = mysqli_real_escape_string($conn,$_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn,$_POST['deskripsi']);
    $deadline = $_POST['deadline'];

    $update = mysqli_query($conn,"
        UPDATE tugas SET
        judul='$judul',
        deskripsi='$deskripsi',
        deadline='$deadline'
        WHERE id='$id'
    ");

    if($update){
        echo "
        <script>
        alert('Tugas berhasil diupdate');
        window.location='daftar_tugas.php';
        </script>
        ";
        exit;
    }else{
        echo "
        <script>
        alert('Gagal update tugas');
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Tugas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
min-height:100vh;
padding:40px 20px;
}

.container-edit{
max-width:900px;
margin:auto;
}

.header-card{
background:linear-gradient(135deg,#22c55e,#15803d);
padding:35px;
border-radius:25px;
color:white;
display:flex;
align-items:center;
gap:20px;
box-shadow:0 15px 35px rgba(0,0,0,.1);
}

.header-icon{
font-size:60px;
}

.header-card h1{
font-size:32px;
font-weight:700;
margin-bottom:5px;
}

.header-card p{
margin:0;
opacity:.9;
}

.form-card{
background:white;
padding:35px;
border-radius:25px;
margin-top:25px;
box-shadow:0 15px 35px rgba(0,0,0,.08);
}

label{
font-weight:600;
color:#15803d;
margin-bottom:8px;
display:block;
}

.form-control{
border-radius:15px;
padding:12px 15px;
}

textarea.form-control{
resize:none;
}

.button-group{
display:flex;
justify-content:space-between;
margin-top:30px;
}

.btn-back{
background:#e5e7eb;
color:#333;
text-decoration:none;
padding:12px 25px;
border-radius:12px;
font-weight:600;
}

.btn-back:hover{
background:#d1d5db;
color:#111;
}

.btn-update{
background:#22c55e;
border:none;
color:white;
padding:12px 30px;
border-radius:12px;
font-weight:600;
}

.btn-update:hover{
background:#16a34a;
}

@media(max-width:768px){

.header-card{
flex-direction:column;
text-align:center;
}

.button-group{
flex-direction:column;
gap:10px;
}

.btn-back,
.btn-update{
width:100%;
text-align:center;
}

}

</style>

</head>

<body>

<div class="container-edit">

<div class="header-card">

<div class="header-icon">📚</div>

<div>
<h1>Edit Tugas</h1>
<p>Perbarui data tugas yang sudah dibuat</p>
</div>

</div>

<div class="form-card">

<form method="POST">

<div class="mb-4">
<label>Judul Tugas</label>
<input
type="text"
name="judul"
class="form-control"
value="<?= htmlspecialchars($t['judul']); ?>"
required>
</div>

<div class="mb-4">
<label>Deskripsi Tugas</label>
<textarea
name="deskripsi"
rows="5"
class="form-control"
required><?= htmlspecialchars($t['deskripsi']); ?></textarea>
</div>

<div class="mb-4">
<label>Deadline</label>
<input
type="date"
name="deadline"
class="form-control"
value="<?= $t['deadline']; ?>"
required>
</div>

<div class="button-group">

<a href="daftar_tugas.php" class="btn-back">
← Kembali
</a>

<button type="submit" name="update" class="btn-update">
💾 Update Tugas
</button>

</div>

</form>

</div>

</div>

</body>
</html>

