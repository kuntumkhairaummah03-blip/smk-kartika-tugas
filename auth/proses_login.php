<?php
session_start();
include '../config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn,"
SELECT * FROM users
WHERE username='$username'
AND password='$password'
");

$data = mysqli_fetch_assoc($query);

if($data){

$_SESSION['id']=$data['id'];
$_SESSION['nama']=$data['nama'];
$_SESSION['role']=$data['role'];
$_SESSION['kelas_id']=$data['kelas_id'] ?? null;
$_SESSION['tahun_ajaran_id']=$data['tahun_ajaran_id'] ?? null;

/* smooth redirect delay (UX feel) */
if($data['role']=="guru"){
header("refresh:1;url=../guru/dashboard.php");
}else{
header("refresh:1;url=../siswa/dashboard.php");
}

echo "<style>
body{font-family:Poppins;text-align:center;padding-top:100px;}
</style>";

echo "<h2 style='color:#16a34a'>Login Berhasil</h2>";
echo "<p>Mengarahkan ke dashboard...</p>";

}else{

echo "<script>
alert('Login gagal! Cek username & password');
window.location='login.php';
</script>";

}
?>