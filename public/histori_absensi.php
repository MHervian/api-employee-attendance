<?php
session_start();
if (!isset($_SESSION['nama'])) {
    header("location:http://localhost/php/attendance-app");
}

/** Query data absensi hari ini */
// add curl module
require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;

$curl = new Curl();
$curl->setHeader('Content-Type', 'application/json');

// Query API /absensi/login
$curl->get('http://localhost:5000/absensi/years/' . $_SESSION['id']);

$years = $curl->response->years;
$curl->close();

$months = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];

// echo "<pre>";
// var_dump($years);
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Histori Absensi - Aplikasi Absensi</title>
    <link href="src/css/styles.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" type="text/css" href="src/css/jquery.dataTables.css"> -->
    <link rel="stylesheet" type="text/css" href="src/css/jquery.dataTables.css">
    <script src="src/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Absensi App</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></div>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="modules/logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <?php ($_SESSION['level'] == 'karyawan') ? require("parts/navkaryawan.php") : require("parts/navadmin.php"); ?>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= $_SESSION['nama'] ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Histori Absensi</h1>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="histori_absensi.php" method="post" class="rounded-top p-2">
                                <input type="hidden" name="cari" value="1" />
                                <input type="hidden" name="id_karyawan" value="<?= $_SESSION['id'] ?>" />
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="bulan" class="form-control">
                                            <option value="">Pilih Bulan ...</option>
                                            <?php
                                            foreach ($months as $key => $month) {
                                            ?>
                                                <option value="<?= $key ?>"><?= $month ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="tahun" class="form-control">
                                            <option value="">Pilih Tahun ...</option>
                                            <?php
                                            foreach ($years as $year) {
                                            ?>
                                                <option value="<?= $year->tahun ?>"><?= $year->tahun ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Cari Histori</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                    if (isset($_POST['cari'])) {
                        // Melakukan query API berdasarkan bulan dan tahun
                        $curl = new Curl();
                        $curl->setHeader('Content-Type', 'application/json');

                        $tanggal = $_POST['tahun'] . "-" . $_POST['bulan'];
                        $id_karyawan = $_POST['id_karyawan'];
                        // Query API /absensi/login
                        $curl->get('http://localhost:5000/absensi/' . $id_karyawan . "/" . $tanggal);

                        // echo "<pre>";
                        // var_dump($curl->response->data);
                        // echo "</pre>";

                        $results = (!empty($curl->response->data)) ? $curl->response->data : array();
                        $curl->close();

                        $bulan = (!empty($months[$_POST['bulan']])) ? $months[$_POST['bulan']] : null;
                        $tahun = (!empty($_POST['tahun'])) ? $_POST['tahun'] : null;
                        $tanggal = $bulan . " " . $tahun;

                    ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    Hasil Pencarian Absensi:
                                    <span style="font-weight: bolder;"><?= $tanggal ?></span>
                                </div>
                                <?php
                                if (count($results) > 0) {
                                ?>
                                    <div class="card-body">
                                        <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Clock-In</th>
                                                    <th>Geolocation-In (Lat;Long)</th>
                                                    <th>Clock-Out</th>
                                                    <th>Geolocation-Out (Lat;Long)</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Clock-In</th>
                                                    <th>Geolocation-In (Lat;Long)</th>
                                                    <th>Clock-Out</th>
                                                    <th>Geolocation-Out (Lat;Long)</th>
                                                </tr>
                                            </tfoot>
                                            <?php
                                            $no = 1;
                                            foreach ($results as $result) {
                                                // Konversi time ke string
                                                $tgl_masuk = date("d M Y", strtotime($result->tanggal_kerja));
                                            ?>
                                                <tbody>
                                                    <tr>
                                                        <td><?= $no ?></td>
                                                        <td><?= $tgl_masuk ?></td>
                                                        <td><?= $result->clock_in ?></td>
                                                        <td><?= $result->geolocation_in ?></td>
                                                        <td><?= $result->clock_out ?></td>
                                                        <td><?= $result->geolocation_out ?></td>
                                                    </tr>
                                                </tbody>
                                            <?php
                                            }
                                            ?>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    <?php
                                } else {
                    ?>
                        <p class="bg-info text-center p-2">Data absensi tidak ditemukan.</p>
                <?php
                                }
                            }
                ?>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2022</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="src/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- <script src="src/js/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="src/js/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="src/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="src/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable();
        });
    </script>
</body>

</html>