<?php
session_start();
if (!isset($_SESSION['nama'])) {
    header("location:http://localhost/php/attendance-app");
}

/** Kueri data admin by id */
// add curl module
require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;

$results = null;
$title = "Input";
$path_proses = "modules/create_admin.php";
if (isset($_GET['id_admin'])) {
    $curl = new Curl();
    $id_admin = $_GET['id_admin'];

    // Query API /admin 
    $curl->get('http://localhost:5000/admin/' . $id_admin);

    $results = (!empty($curl->response->data)) ? $curl->response->data[0] : array();
    $curl->close();
    $title = "Edit";
    $path_proses = "modules/edit_admin.php";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Form Admin - Aplikasi Absensi</title>
    <link href="src/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="src/css/jquery.dataTables.css">
    <script src="src/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
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
                    <h1 class="mt-4">Form <?= $title ?> Data Admin</h1>
                    <div class="card mb-4 w-50">
                        <div class="card-body">
                            <form action="<?= $path_proses ?>" method="post">
                                <?php
                                if (!empty($results)) {
                                    // echo "<pre>";
                                    // var_dump($results);
                                    // echo "</pre>";
                                ?>
                                    <input type="hidden" name="id_admin" value="<?= $results->id ?>" />
                                    <div class="mb-3">
                                        <label for="inputUsername">Nama Admin</label>
                                        <input class="form-control" id="inputUsername" type="text" placeholder="Username" name="nama" value="<?= $results->nama ?>" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputPassword">Password</label>
                                        <input class="form-control" id="inputPassword" type="password" placeholder="Password" name="password" value="<?= $results->pass_akun ?>" />
                                    </div>
                                    <div class="mt-4 mb-0">
                                        <button class="btn btn-secondary" type="reset">Reset Form</button>
                                        <button class="btn btn-primary" type="submit">Submit Data</button>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <div class="mb-3">
                                        <label for="inputUsername">Nama Admin</label>
                                        <input class="form-control" id="inputUsername" type="text" placeholder="Username" name="nama" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputPassword">Password</label>
                                        <input class="form-control" id="inputPassword" type="password" placeholder="Password" name="password" />
                                    </div>
                                    <div class="mt-4 mb-0">
                                        <button class="btn btn-secondary" type="reset">Reset Form</button>
                                        <button class="btn btn-primary" type="submit">Submit Data</button>
                                    </div>
                                <?php
                                }
                                ?>
                            </form>
                        </div>
                    </div>
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
    <script src="src/js/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="src/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable();
        });
    </script>
</body>

</html>