<?php
session_start();
include '../config/koneksi.php';

if(!isset($_GET['id'])){
    die("ID tidak ditemukan");
}

$id = $_GET['id'];


$query = mysqli_query($conn,"
SELECT
p.*,
u.nama,
t.judul
FROM pengumpulan p
JOIN users u ON p.id_siswa=u.id
JOIN tugas t ON p.id_tugas=t.id
WHERE p.id='$id'
");

$d = mysqli_fetch_assoc($query);

if(!$d){
    die("Data tidak ditemukan");
}


if(isset($_POST['simpan'])){

    $nilai = $_POST['nilai'];
$komentar = mysqli_real_escape_string($conn, $_POST['komentar']);

mysqli_query($conn,"
UPDATE pengumpulan
SET
    nilai='$nilai',
    komentar='$komentar'
WHERE id='$id'
");
    echo "
    <script>
    alert('Nilai berhasil disimpan');
    window.location='nilai.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Nilai</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:linear-gradient(135deg,#ecfdf5,#f8fafc);
    min-height:100vh;
    padding:40px;
    font-family:'Poppins',sans-serif;
}

.card-nilai{
    max-width:900px;
    margin:auto;
    background:#fff;
    border-radius:30px;
    padding:40px;
    box-shadow:0 20px 50px rgba(0,0,0,.08);
}

.info-box{
    background:#f8fffa;
    border:1px solid #dcfce7;
    border-radius:20px;
    padding:22px;
    margin-bottom:20px;
}

.nilai-box{
    background:#f0fdf4;
    border:1px solid #bbf7d0;
    border-radius:25px;
    padding:25px;
    margin-top:20px;
}

.form-label{
    font-weight:700;
    color:#15803d;
    margin-bottom:10px;
}

.form-control{
    border-radius:15px;
    border:1px solid #d1d5db;
    font-size:18px;
}

input[name="nilai"]{
    height:70px;
    font-size:32px;
    font-weight:700;
    text-align:center;
}

textarea{
    resize:none;
    min-height:140px;
}

.btn-group{
    display:flex;
    gap:15px;
    margin-top:30px;
    flex-wrap:wrap;
}

.btn-save{
    background:#22c55e;
    color:white;
    border:none;
    width:230px;
    height:60px;
    border-radius:15px;
    font-size:18px;
    font-weight:600;
    transition:.3s;
}

.btn-save:hover{
    transform:translateY(-2px);
    background:#16a34a;
}

.btn-back{
    width:230px;
    height:60px;
    background:#e5e7eb;
    color:#111827;
    border-radius:15px;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    font-size:18px;
    font-weight:600;
}

.btn-download{
    background:#ecfdf5;
    color:#15803d;
    padding:14px 22px;
    border-radius:15px;
    text-decoration:none;
    font-weight:600;
    transition:.3s;
}

.btn-download:hover{
    background:#dcfce7;
}
.file-card{
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:#ffffff;
    border:1px solid #dcfce7;
    border-radius:18px;
    padding:20px;
    margin-top:15px;
}

.file-info{
    display:flex;
    align-items:center;
    gap:15px;
}

.file-icon{
    width:60px;
    height:60px;
    border-radius:15px;
    background:#dcfce7;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
}

.file-info h5{
    margin:0;
    font-weight:600;
}

.file-info small{
    color:#6b7280;
}

.btn-download{
    background:#22c55e;
    color:white;
    text-decoration:none;
    padding:12px 24px;
    border-radius:12px;
    font-weight:600;
    transition:.3s;
}

.btn-download:hover{
    background:#16a34a;
    color:white;
}

@media(max-width:768px){

    .file-card{
        flex-direction:column;
        gap:15px;
        align-items:flex-start;
    }

}
</style>
</head>

<body>

<div class="card-nilai">

<div class="header">
<div class="info-box">
    <h5 style="margin:0;color:#16a34a;">
        📚 Penilaian Tugas Siswa
    </h5>
    <small style="color:#6b7280;">
        Berikan nilai dan komentar untuk membantu siswa memahami hasil pekerjaannya.
    </small>
</div>
<div class="avatar">⭐</div>
</div>

<div class="info-box">
<label>Nama Siswa</label>
<input type="text" class="form-control" value="<?= $d['nama']; ?>" readonly>
</div>

<div class="info-box">
<label>Judul Tugas</label>
<input type="text" class="form-control" value="<?= $d['judul']; ?>" readonly>
</div>

<div class="info-box">

    <label class="form-label">
        📎 File Tugas Siswa
    </label>

    <div class="file-card">

        <div class="file-info">
            <div class="file-icon">📄</div>

            <div>
                <h5><?= $d['file_tugas']; ?></h5>
                <small>File yang diunggah siswa</small>
            </div>
        </div>

        <a href="../uploads/<?= $d['file_tugas']; ?>"
           target="_blank"
           class="btn-download">
            ⬇ Download
        </a>

    </div>

</div>

<form method="POST">

<form method="POST">

<div class="nilai-box">

    <label class="form-label">
        Nilai Siswa (0 - 100)
    </label>

    <input
        type="number"
        name="nilai"
        class="form-control"
        value="<?= $d['nilai'] ?? '' ?>"
        min="0"
        max="100"
        required
    >

    <div style="margin-top:25px;">
        <label class="form-label">
            Komentar Guru
        </label>

        <textarea
            name="komentar"
            class="form-control"
            placeholder="Berikan saran, kritik, masukan atau apresiasi kepada siswa..."><?= htmlspecialchars($d['komentar'] ?? '') ?></textarea>
    </div>

</div>

<div class="btn-group">
    <button type="submit" name="simpan" class="btn-save">
        💾 Simpan Nilai
    </button>

    <a href="nilai.php" class="btn-back">
        ← Kembali
    </a>
</div>

</form>

</div>

</body>
</html>