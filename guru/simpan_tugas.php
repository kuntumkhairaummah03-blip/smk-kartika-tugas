<?php
session_start();
include '../config/koneksi.php';

if(isset($_POST['simpan'])){

    $judul = mysqli_real_escape_string($conn,$_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn,$_POST['deskripsi']);
    $deadline = str_replace('T',' ',$_POST['deadline']);

    $kelas_id = $_POST['kelas_id'];

    mysqli_query($conn,"
    INSERT INTO tugas
    (
        judul,
        deskripsi,
        deadline,
        kelas_id
    )
    VALUES
    (
        '$judul',
        '$deskripsi',
        '$deadline',
        '$kelas_id'
    )
    ");

    echo "
    <script>
    alert('Tugas berhasil dibuat');
    window.location='daftar_tugas.php';
    </script>
    ";
}
     else {
        echo "<script>
        alert('Gagal menambahkan tugas: ".mysqli_error($conn)."');
        </script>";
    }
?>