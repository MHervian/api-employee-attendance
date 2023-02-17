<?php
// add curl module
require __DIR__ . '/../vendor/autoload.php';
use Curl\Curl;

$curl = new Curl();

// baca data form
$id_admin = $_POST['id_admin'];
$nama = $_POST['nama'];
$pass_akun = $_POST['password'];

// buat json
$data = [
    'id' => $id_admin,
    'nama' => $nama,
    'pass_akun' => $pass_akun,
];

// echo "<pre>";
// var_dump($data);
// echo "</pre>";
// return;

$curl->setHeader('Content-Type', 'application/json');
$curl->put('http://localhost:5000/admin', $data);
$curl->close();

header("location:http://localhost/php/attendance-app/admin.php");
