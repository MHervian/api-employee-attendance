<?php
// add curl module
require __DIR__ . '/../vendor/autoload.php';

use Curl\Curl;

$curl = new Curl();

// read data
$id_karyawan = $_POST['id_karyawan'];
$geolocation_out = $_POST['geolocation_out'];
$clock_out = date("H:i:s");
$today = $_POST['today'];

// buat json
$data = [
    'id_karyawan' => $id_karyawan,
    'geolocation_out' => $geolocation_out,
    'clock_out' => $clock_out,
    'today' => $today,
];

// echo "<pre>";
// var_dump($data);
// echo "</pre>";

$curl->setHeader('Content-Type', 'application/json');
$curl->put('http://localhost:5000/absensi/checkout', $data);
$curl->close();

header("location:http://localhost/php/attendance-app/absensi.php");
