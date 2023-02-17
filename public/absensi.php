<?php
session_start();
if (!isset($_SESSION['nama'])) {
    header("location:http://localhost/php/attendance-app");
}

/** Query data absensi hari ini */
// add curl module
require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;

// create json
$data = [
    'nama' => $_SESSION['nama'],
    'today' => date("Y-m-d"),
];

$curl = new Curl();
$curl->setHeader('Content-Type', 'application/json');

// Query API /absensi/login
$curl->post('http://localhost:5000/absensi/today', $data);

$result = $curl->response;
$curl->close();

// echo "<pre>";
// var_dump($result);
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
    <title>Absensi - Aplikasi Absensi</title>
    <link href="src/css/styles.css" rel="stylesheet" />
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
                    <h1 class="mt-4">Absensi Hari Ini</h1>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4>Check-In Absensi</h4>
                            <?php
                            if ($result->data !== null) {
                                $geolocation = explode(";", $result->data->geolocation_in);
                            ?>
                                <p>Pukul: <span style="font-weight: bolder;"><?= $result->data->clock_in ?></span></p>
                                <p>Tanggal: <span style="font-weight: bolder;"><?= $result->data->tanggal_kerja ?></span></p>
                                <p>Lattitude: <span style="font-weight: bolder;"><?= $geolocation[0] ?></span>, longitude: <span style="font-weight: bolder;"><?= $geolocation[1] ?></span></p>
                            <?php
                            } else {
                            ?>
                                <form action="modules/checkin.php" method="post">
                                    <input type="hidden" id="geolocationIn" name="geolocation_in" value="" />
                                    <input type="hidden" name="id_karyawan" value="<?= $_SESSION['id'] ?>" />
                                    <button class="btn btn-primary">Submit Check-In</button>
                                </form>
                                <script>
                                    var geoInput = document.getElementById('geolocationIn');

                                    if (navigator.geolocation) {
                                        navigator.geolocation.getCurrentPosition(showPosition, showError);
                                    } else {
                                        alert("Geolocation is not supported by this browser.");
                                    }

                                    function showPosition(position) {
                                        geoInput.value = position.coords.latitude + ";" + position.coords.longitude;
                                    }

                                    function showError(error) {
                                        switch (error.code) {
                                            case error.PERMISSION_DENIED:
                                                alert("User denied the request for Geolocation.");
                                                console.log("User denied the request for Geolocation.");
                                                break;
                                            case error.POSITION_UNAVAILABLE:
                                                alert("Location information is unavailable.");
                                                console.log("Location information is unavailable.");
                                                break;
                                            case error.TIMEOUT:
                                                alert("The request to get user location timed out.");
                                                console.log("The request to get user location timed out.");
                                                break;
                                            case error.UNKNOWN_ERROR:
                                                alert("An unknown error occurred.");
                                                console.log("An unknown error occurred.");
                                                break;
                                        }
                                    }
                                </script>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    if (!empty($result->data)) {
                    ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h4>Check-Out Absensi</h4>
                                <?php
                                if ($result->data->clock_out !== 'None') {
                                    $geolocation = explode(";", $result->data->geolocation_out);
                                ?>
                                    <p>Pukul: <span style="font-weight: bolder;"><?= $result->data->clock_out ?></span></p>
                                    <p>Tanggal: <span style="font-weight: bolder;"><?= $result->data->tanggal_kerja ?></span></p>
                                    <p>Lat: <span style="font-weight: bolder;"><?= $geolocation[0] ?></span>, long: <span style="font-weight: bolder;"><?= $geolocation[1] ?></span></p>
                                <?php
                                } else {
                                ?>
                                    <form action="modules/checkout.php" method="post">
                                        <input type="hidden" id="geolocationOut" name="geolocation_out" value="" />
                                        <input type="hidden" name="today" value="<?= $result->data->tanggal_kerja ?>" />
                                        <input type="hidden" name="id_karyawan" value="<?= $_SESSION['id'] ?>" />
                                        <button class="btn btn-primary">Submit Check-Out</button>
                                    </form>
                                    <script>
                                        var geoInput = document.getElementById('geolocationOut');

                                        if (navigator.geolocation) {
                                            navigator.geolocation.getCurrentPosition(showPosition, showError);
                                        } else {
                                            alert("Geolocation is not supported by this browser.");
                                        }

                                        function showPosition(position) {
                                            geoInput.value = position.coords.latitude + ";" + position.coords.longitude;
                                        }

                                        function showError(error) {
                                            switch (error.code) {
                                                case error.PERMISSION_DENIED:
                                                    alert("User denied the request for Geolocation.");
                                                    console.log("User denied the request for Geolocation.");
                                                    break;
                                                case error.POSITION_UNAVAILABLE:
                                                    alert("Location information is unavailable.");
                                                    console.log("Location information is unavailable.");
                                                    break;
                                                case error.TIMEOUT:
                                                    alert("The request to get user location timed out.");
                                                    console.log("The request to get user location timed out.");
                                                    break;
                                                case error.UNKNOWN_ERROR:
                                                    alert("An unknown error occurred.");
                                                    console.log("An unknown error occurred.");
                                                    break;
                                            }
                                        }
                                    </script>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php
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
    <script src="src/js/scripts.js"></script>
</body>

</html>