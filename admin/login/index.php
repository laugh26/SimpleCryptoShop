<?php
    session_start();

	include '../../include/db.php';
	include '../../include/captcha/captcha.php';

	if (isset($_POST['user']) && isset($_POST['pass']) && isset($_SESSION['captcha']['code']) && isset($_POST['captcha'])) {
        if ($_POST['captcha'] == $_SESSION['captcha']['code']) {
            $user = hash_value(preg_replace("[^\w\d\s]", "", $_POST['user']));
            $passw = hash_value(preg_replace("[^\w\d\s]", "", $_POST['pass']));
            $rez = DataBase("SELECT COUNT(*) FROM `admins` WHERE `username` = '$user' AND `password` = '$passw'")[0];

            if ($rez == 1) {
                $_SESSION['user'] = $user;
                $_SESSION['password'] = $passw;
                header('Location: /admin/');
            }
        }
    }

    $_SESSION['captcha'] = simple_php_captcha();

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/fontawesome-all.min.css">
		<link rel="stylesheet" href="../css/datatables.min.css">
		<link rel="stylesheet" href="../css/bootadmin.min.css">

        <title>Login In</title>
    </head>
    <body class="bg-light">
            <div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-4">
                    <h1 class="text-center mb-4">Simple Shop</h1>
                    <div class="card">
                        <div class="card-body">
                            <form method="post">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input type="text" name="user" class="form-control" placeholder="Username">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-key"></i></span>
                                    </div>
                                    <input type="password" name="pass" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group" style="text-align:center;">
                                    <img src="<?php echo $_SESSION['captcha']['image_src']; ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="text" name="captcha" class="form-control" placeholder="Captcha">
                                </div>
                                <div class="row">
                                    <div class="col pr-2">
                                        <button type="submit" class="btn btn-block btn-primary">Login</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="../../vendor/jquery/jquery.min.js"></script>
		<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="../js/datatables.min.js"></script>
		<script src="../js/moment.min.js"></script>
		<script src="./js/bootadmin.min.js"></script>

    </body>
</html>