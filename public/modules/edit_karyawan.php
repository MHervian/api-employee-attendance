<?php
// add curl module
require __DIR__ . '/../vendor/autoload.php';
use Curl\Curl;

$curl = new Curl();

// baca data form
$id_karyawan = $_POST['id_karyawan'];
$nama = $_POST['nama'];
$pass_akun = $_POST['password'];

// buat json
$data = [
    'id' => $id_karyawan,
    'nama' => $nama,
    'pass_akun' => $pass_akun,
];

// echo "<pre>";
// var_dump($data);
// echo "</pre>";
// return;

$curl->setHeader('Content-Type', 'application/json');
$curl->put('http://localhost:5000/karyawan', $data);
$curl->close();

header("location:http://localhost/php/attendance-app/karyawan.php");
