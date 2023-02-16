<?php
// add curl module
require __DIR__ . '/../vendor/autoload.php';

use Curl\Curl;

$curl = new Curl();

// read data
$id_karyawan = $_POST['id_karyawan'];
$geolocation_in = $_POST['geolocation_in'];
$clock_in = date("H:i:s");
$today = date("Y-m-d");

// buat json
$data = [
    'id_karyawan' => $id_karyawan,
    'geolocation_in' => $geolocation_in,
    'clock_in' => $clock_in,
    'today' => $today,
];

// echo "<pre>";
// var_dump($data);
// echo "</pre>";

$curl->setHeader('Content-Type', 'application/json');
$curl->post('http://localhost:5000/absensi/checkin', $data);
$curl->close();

header("location:http://localhost/php/attendance-app/absensi.php");
