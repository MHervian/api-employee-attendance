<?php
// add curl module
require __DIR__ . '/../vendor/autoload.php';
use Curl\Curl;

// baca data
$nama = $_POST['nama'];
$password = $_POST['password'];
$level = $_POST['level'];

// buat json
$data = [
    'nama' => "$nama",
    'pass_akun' => "$password",
];

$curl = new Curl();
$curl->setHeader('Content-Type', 'application/json');

if ($level === 'admin') {
    // Query API /admin/login
    $curl->post('http://localhost:5000/admin/login', $data);
} else {
    // Query API /karyawan/login
    $curl->post('http://localhost:5000/karyawan/login', $data);
}

// echo "<pre>";
// var_dump($curl->response);
// echo "</pre>";
// return;

$result = array_pop($curl->response->data);
$curl->close();

// check apakah data hasil query ada
if (!empty($result)) {
    session_start();
    $_SESSION['nama'] = $nama;
    $_SESSION['id'] = $result->id;
    $_SESSION['level'] = $level;
    header("location:http://localhost/php/attendance-app/dashboard.php");
} else {
    // lempar ke halaman login
    header("location:http://localhost/php/attendance-app/");
}
