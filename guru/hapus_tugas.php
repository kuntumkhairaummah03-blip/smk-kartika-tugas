<?php
session_start();
include '../config/koneksi.php';

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $hapus = mysqli_query($conn,"
        DELETE FROM tugas
        WHERE id='$id'
    ");

    if($hapus){

        echo "
        <script>
        alert('Tugas berhasil dihapus');
        window.location='daftar_tugas.php';
        </script>
        ";

    }else{

        echo "
        <script>
        alert('Tugas gagal dihapus');
        window.location='daftar_tugas.php';
        </script>
        ";

    }

}else{

    header("Location: daftar_tugas.php");
}
?>