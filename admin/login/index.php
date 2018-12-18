<?php
	session_start();
	include '../../include/db.php';

	$status = '';

	if (isset($_POST['user']) and isset($_POST['pass'])) {
		$user = hash_value(preg_replace("[^\w\d\s]", "", $_POST['user']));
        $passw = hash_value(preg_replace("[^\w\d\s]", "", $_POST['pass']));
		$rez = DataBase("SELECT COUNT(*) FROM `admins` WHERE `username` = '$user' AND `password` = '$passw'")[0];

		if ($rez == 1) {
			$_SESSION['user'] = $user;
			$_SESSION['password'] = $passw;
            header('Location: /admin/');
        } else {
			$status = 'Info: Bad login';
		}
	}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login In</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    </head>
    <body>
        <div class="form">
            <div class="form-panel one">
                <div class="form-header">
                    <h1>Account Login</h1>
                </div>
                <div class="form-content">
                    <form method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="user" required="required"/>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="pass" required="required"/>
                        </div>
                        <div class="form-group">
                            <button type="submit">Log In</button>
                        </div>
                        <span><?php echo $status; ?></span>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>