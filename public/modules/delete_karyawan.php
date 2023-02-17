<?php
// add curl module
require __DIR__ . '/../vendor/autoload.php';
use Curl\Curl;

$curl = new Curl();

// baca param data dari url
$id_karyawan = $_GET['id_karyawan'];

// buat json
// $data = [
//     'id' => $id_karyawan,
// ];

// echo "<pre>";
// var_dump($data);
// echo "</pre>";
// return;

// $curl->setHeader('Content-Type', 'application/json');
$curl->delete('http://localhost:5000/karyawan/'. $id_karyawan);
$curl->close();

header("location:http://localhost/php/attendance-app/karyawan.php");
