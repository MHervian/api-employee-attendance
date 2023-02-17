<?php
// add curl module
require __DIR__ . '/../vendor/autoload.php';
use Curl\Curl;

$curl = new Curl();

// baca param data dari url
$id_admin = $_GET['id_admin'];

// buat json
// $data = [
//     'id' => $id_karyawan,
// ];

// echo "<pre>";
// var_dump($data);
// echo "</pre>";
// return;

// $curl->setHeader('Content-Type', 'application/json');
$curl->delete('http://localhost:5000/admin/'. $id_admin);
$curl->close();

header("location:http://localhost/php/attendance-app/admin.php");
