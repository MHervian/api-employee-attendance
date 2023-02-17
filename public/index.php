<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - Aplikasi Absensi</title>
    <link href="src/css/styles.css" rel="stylesheet" />
    <script src="src/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Login Karyawan</h3>
                                </div>
                                <div class="card-body">
                                    <form action="modules/login.php" method="post">
                                        <input type="hidden" name="level" value="karyawan" />
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputUsername" type="text" placeholder="Username" name="nama" />
                                            <label for="inputUsername">Name</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" type="password" placeholder="Password" name="password" />
                                            <label for="inputPassword">Password</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="loginadmin.php">Login sebagai Admin?</a>
                                            <button class="btn btn-primary">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- <script src="src/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->
    <script src="src/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="src/js/scripts.js"></script>
</body>

</html>