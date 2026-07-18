<?php
session_start();
include '../config/koneksi.php';

if(isset($_POST['daftar'])){

    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $kelas_id = (int) $_POST['kelas_id'];
    $tahun_ajaran_id = (int) $_POST['tahun_ajaran_id'];

    // Validasi input kosong
    if(empty($nama) || empty($username) || empty($password) || $kelas_id <= 0 || $tahun_ajaran_id <= 0){
        echo "<script>
        alert('Semua field harus diisi dengan benar!');
        window.location='register.php';
        </script>";
        exit;
    }

    // Cek username sudah dipakai atau belum
    $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' LIMIT 1");

    if(mysqli_num_rows($cek) > 0){
        echo "<script>
        alert('Username sudah digunakan! Silakan pilih username lain.');
        window.location='register.php';
        </script>";
        exit;
    }

    // Insert user baru dengan role siswa
    $query = mysqli_query($conn, "
    INSERT INTO users
    (nama, username, password, role, kelas_id, tahun_ajaran_id)
    VALUES
    ('$nama', '$username', '$password', 'siswa', '$kelas_id', '$tahun_ajaran_id')
    ");

    if($query){
        echo "
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
        body{
            font-family:'Poppins',sans-serif;
            text-align:center;
            padding-top:100px;
            background:linear-gradient(135deg,#eafff1,#f0fdf4);
        }
        .success-icon{
            font-size:60px;
            margin-bottom:15px;
        }
        h2{
            color:#16a34a;
            font-weight:700;
        }
        p{
            color:#6b7280;
            margin-top:8px;
        }
        </style>
        <div class='success-icon'>✅</div>
        <h2>Pendaftaran Berhasil!</h2>
        <p>Mengarahkan ke halaman login...</p>
        ";

        header("refresh:2;url=login.php");
    } else {
        echo "<script>
        alert('Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        window.location='register.php';
        </script>";
    }

} else {
    header("Location: register.php");
    exit;
}
?>
