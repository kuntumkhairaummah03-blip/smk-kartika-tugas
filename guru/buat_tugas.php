<?php
session_start();
include '../config/koneksi.php';

if(isset($_POST['simpan'])){

    $judul = mysqli_real_escape_string($conn,$_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn,$_POST['deskripsi']);
    $deadline = str_replace('T',' ',$_POST['deadline']);

    // Ambil ID guru login
    $id_guru = $_SESSION['id'];

    // === HANDLE KELAS TUJUAN ===
    $kelas_id = $_POST['kelas_id'];

    if($kelas_id === 'baru'){
        // Input kelas baru
        $nama_kelas_baru = mysqli_real_escape_string($conn, trim($_POST['kelas_baru']));

        if(empty($nama_kelas_baru)){
            echo "<script>alert('Nama kelas baru tidak boleh kosong!');window.location='buat_tugas.php';</script>";
            exit;
        }

        // Cek apakah kelas sudah ada
        $cekKelas = mysqli_query($conn, "SELECT id FROM kelas WHERE nama_kelas='$nama_kelas_baru' LIMIT 1");
        if(mysqli_num_rows($cekKelas) > 0){
            $kelas_id = mysqli_fetch_assoc($cekKelas)['id'];
        } else {
            mysqli_query($conn, "INSERT INTO kelas (nama_kelas) VALUES ('$nama_kelas_baru')");
            $kelas_id = mysqli_insert_id($conn);
        }
    }

    // === HANDLE TAHUN AJARAN ===
    $tahun_ajaran_id = $_POST['tahun_ajaran_id'];

    if($tahun_ajaran_id === 'baru'){
        // Input tahun ajaran baru
        $tahun_baru = mysqli_real_escape_string($conn, trim($_POST['tahun_ajaran_baru']));

        if(empty($tahun_baru)){
            echo "<script>alert('Tahun ajaran baru tidak boleh kosong!');window.location='buat_tugas.php';</script>";
            exit;
        }

        // Cek apakah tahun ajaran sudah ada
        $cekTA = mysqli_query($conn, "SELECT id FROM tahun_ajaran WHERE tahun='$tahun_baru' LIMIT 1");
        if(mysqli_num_rows($cekTA) > 0){
            $tahun_ajaran_id = mysqli_fetch_assoc($cekTA)['id'];
        } else {
            mysqli_query($conn, "INSERT INTO tahun_ajaran (tahun, status) VALUES ('$tahun_baru', 'Aktif')");
            $tahun_ajaran_id = mysqli_insert_id($conn);
        }
    }

    mysqli_query($conn,"
    INSERT INTO tugas
    (
        judul,
        deskripsi,
        deadline,
        id_guru,
        kelas_id,
        tahun_ajaran_id
    )
    VALUES
    (
        '$judul',
        '$deskripsi',
        '$deadline',
        '$id_guru',
        '$kelas_id',
        '$tahun_ajaran_id'
    )
    ");

    echo "
    <script>
    alert('Tugas berhasil dibuat');
    window.location='daftar_tugas.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Buat Tugas</title>

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

/* SIDEBAR */

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

/* =========================
   FORM TUGAS MODERN
========================= */

.form-box{
    background:#ffffff;
    padding:45px;
    border-radius:32px;
    box-shadow:
    0 15px 40px rgba(0,0,0,.06),
    0 3px 10px rgba(0,0,0,.03);
    margin-top:30px;
    border:1px solid #edf2f7;
}

.form-title{
    display:flex;
    align-items:center;
    gap:20px;
    margin-bottom:30px;
    padding-bottom:25px;
    border-bottom:1px solid #eef2f7;
}

.title-icon{
    width:75px;
    height:75px;
    border-radius:22px;
    background:linear-gradient(135deg,#22c55e,#15803d);
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:30px;
    box-shadow:0 10px 25px rgba(34,197,94,.25);
}

.form-title h3{
    margin:0;
    font-size:30px;
    font-weight:700;
    color:#14532d;
}

.form-title p{
    margin:5px 0 0;
    color:#64748b;
    font-size:15px;
}

.info-box{
    background:#f0fdf4;
    border-left:5px solid #22c55e;
    padding:18px 20px;
    border-radius:18px;
    margin-bottom:30px;
    color:#166534;
    font-weight:500;
}

label{
    display:block;
    margin-bottom:10px;
    font-size:15px;
    font-weight:600;
    color:#1f2937;
}

label i{
    color:#16a34a;
    margin-right:8px;
}

.form-control{
    border:2px solid #e5e7eb;
    border-radius:18px;
    padding:15px 18px;
    background:#fafafa;
    transition:all .3s ease;
    font-size:15px;
}

.form-control:hover{
    border-color:#bbf7d0;
}

.form-control:focus{
    border-color:#22c55e;
    background:#ffffff;
    box-shadow:0 0 0 5px rgba(34,197,94,.15);
}

textarea.form-control{
    min-height:180px;
    resize:vertical;
}

.form-control[type="file"]{
    padding:14px;
    background:white;
}

small.text-muted{
    display:block;
    margin-top:8px;
    color:#64748b !important;
}

.btn-simpan{
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
    border:none;
    border-radius:18px;
    padding:15px 35px;
    font-size:15px;
    font-weight:600;
    transition:.3s;
}

.btn-simpan:hover{
    transform:translateY(-3px);
    box-shadow:0 15px 30px rgba(34,197,94,.30);
}

.btn-simpan i{
    margin-right:8px;
}

.btn-light{
    border-radius:18px !important;
    padding:15px 30px !important;
}

.row{
    margin-bottom:5px;
}

/* === INPUT BARU (COMBO-BOX) === */

.input-baru-wrap{
    margin-top:12px;
    animation:slideDown .35s ease;
}

@keyframes slideDown{
    from{
        opacity:0;
        transform:translateY(-10px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

.input-baru-inner{
    position:relative;
    display:flex;
    align-items:center;
}

.input-baru-icon{
    position:absolute;
    left:18px;
    color:#16a34a;
    font-size:16px;
    z-index:1;
}

.input-baru-inner .form-control{
    padding-left:45px;
    border:2px dashed #86efac;
    background:#f0fdf4;
}

.input-baru-inner .form-control:focus{
    border-style:solid;
    border-color:#22c55e;
    background:#ffffff;
}

.input-baru-wrap small{
    display:block;
    margin-top:6px;
    font-size:12px;
    color:#64748b !important;
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

.form-box{
    padding:25px;
}

.form-title{
    flex-direction:column;
    align-items:flex-start;
}

.title-icon{
    width:65px;
    height:65px;
}

.top-card{
    flex-direction:column;
    gap:20px;
    text-align:center;
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

<a href="buat_tugas.php" class="active">
<i class="fas fa-plus-circle"></i> Buat Tugas
</a>

<a href="daftar_tugas.php">
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


<form method="POST">

<div class="form-box">

    <div class="form-title">

        <div class="title-icon">
            <i class="fas fa-file-circle-plus"></i>
        </div>

        <div>
            <h3>Form Tugas</h3>
            <p>Buat dan bagikan tugas kepada siswa dengan mudah</p>
        </div>

    </div>

    <div class="info-box">
        <i class="fas fa-circle-info"></i>
        Lengkapi seluruh data tugas sebelum disimpan agar siswa dapat melihat tugas sesuai kelas dan tahun ajaran yang dipilih.
    </div>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-4">

            <label>
                <i class="fas fa-book-open"></i>
                Judul Tugas
            </label>

            <input
            type="text"
            name="judul"
            class="form-control"
            placeholder="Contoh: Resume BAB 3 Pemrograman Web"
            required>

        </div>

        <div class="mb-4">

            <label>
                <i class="fas fa-file-lines"></i>
                Deskripsi Tugas
            </label>

            <textarea
            name="deskripsi"
            class="form-control"
            rows="6"
            placeholder="Tuliskan instruksi tugas secara lengkap..."
            required></textarea>

        </div>

        <div class="row">

            <div class="col-md-6 mb-4">

                <label>
                    <i class="fas fa-users"></i>
                    Kelas Tujuan
                </label>

                <select
                name="kelas_id"
                id="kelas_id"
                class="form-control"
                required
                onchange="toggleInputBaru('kelas')">

                    <option value="">
                        Pilih Kelas
                    </option>

                    <?php
                    $kelas = mysqli_query($conn,"
                    SELECT *
                    FROM kelas
                    ORDER BY nama_kelas
                    ");

                    while($k = mysqli_fetch_assoc($kelas)){
                    ?>

                    <option value="<?= $k['id']; ?>">
                        <?= $k['nama_kelas']; ?>
                    </option>

                    <?php } ?>

                    <option value="baru">➕ Input Kelas Baru...</option>

                </select>

                <div id="input_kelas_baru" class="input-baru-wrap" style="display:none;">
                    <div class="input-baru-inner">
                        <i class="fas fa-keyboard input-baru-icon"></i>
                        <input
                        type="text"
                        name="kelas_baru"
                        id="kelas_baru"
                        class="form-control"
                        placeholder="Contoh: XII RPL 1">
                    </div>
                    <small class="text-muted">Masukkan nama kelas baru yang belum ada di daftar.</small>
                </div>

            </div>

            <div class="col-md-6 mb-4">

                <label>
                    <i class="fas fa-calendar-check"></i>
                    Tahun Ajaran
                </label>

                <select
                name="tahun_ajaran_id"
                id="tahun_ajaran_id"
                class="form-control"
                required
                onchange="toggleInputBaru('tahun')">

                    <option value="">
                        Pilih Tahun Ajaran
                    </option>

                    <?php
                    $ta = mysqli_query($conn,"
                    SELECT *
                    FROM tahun_ajaran
                    ORDER BY id DESC
                    ");

                    while($t = mysqli_fetch_assoc($ta)){
                    ?>

                    <option value="<?= $t['id']; ?>">
                        <?= $t['tahun']; ?>
                    </option>

                    <?php } ?>

                    <option value="baru">➕ Input Tahun Ajaran Baru...</option>

                </select>

                <div id="input_tahun_baru" class="input-baru-wrap" style="display:none;">
                    <div class="input-baru-inner">
                        <i class="fas fa-keyboard input-baru-icon"></i>
                        <input
                        type="text"
                        name="tahun_ajaran_baru"
                        id="tahun_ajaran_baru"
                        class="form-control"
                        placeholder="Contoh: 2025/2026">
                    </div>
                    <small class="text-muted">Masukkan tahun ajaran baru, misal: 2025/2026.</small>
                </div>

            </div>

        </div>

        <div class="mb-4">

            <label>
                <i class="fas fa-calendar-days"></i>
                Deadline Pengumpulan
            </label>

            <input
            type="datetime-local"
            name="deadline"
            class="form-control"
            required>

            <small class="text-muted">
                Tentukan batas waktu pengumpulan tugas.
            </small>

        </div>

        <div class="mb-4">

            <label>
                <i class="fas fa-paperclip"></i>
                Lampiran Tugas (Opsional)
            </label>

            <input
            type="file"
            name="lampiran"
            class="form-control"
            accept=".pdf,.doc,.docx">

            <small class="text-muted">
                Format yang didukung: PDF, DOC, DOCX
            </small>

        </div>

        <div class="d-flex gap-3 flex-wrap">

            <button
            type="submit"
            name="simpan"
            class="btn-simpan">

                <i class="fas fa-save"></i>
                Simpan Tugas

            </button>

            <a
            href="daftar_tugas.php"
            class="btn btn-light border rounded-4 px-4 py-3">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

    </form>

</div>

</div>

<script>
function toggleInputBaru(tipe) {
    if (tipe === 'kelas') {
        var sel = document.getElementById('kelas_id');
        var wrap = document.getElementById('input_kelas_baru');
        var inp = document.getElementById('kelas_baru');

        if (sel.value === 'baru') {
            wrap.style.display = 'block';
            wrap.classList.add('slide-in');
            inp.required = true;
            inp.focus();
        } else {
            wrap.style.display = 'none';
            wrap.classList.remove('slide-in');
            inp.required = false;
            inp.value = '';
        }
    }

    if (tipe === 'tahun') {
        var sel = document.getElementById('tahun_ajaran_id');
        var wrap = document.getElementById('input_tahun_baru');
        var inp = document.getElementById('tahun_ajaran_baru');

        if (sel.value === 'baru') {
            wrap.style.display = 'block';
            wrap.classList.add('slide-in');
            inp.required = true;
            inp.focus();
        } else {
            wrap.style.display = 'none';
            wrap.classList.remove('slide-in');
            inp.required = false;
            inp.value = '';
        }
    }
}
</script>

</body>
</html>