<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "smk_kartika_tugas"
);

if(!$conn){
    die("Koneksi Gagal");
}