<?php
    include 'inc/on.php';
    
    $updst = '';

    if (isset($_POST['update_slides'])) {
        $vars = [
            'first_screen',
            'second_screen',
            'third_screen'
        ];

        $values = [
            $_POST['first_slide'],
            $_POST['second_slide'],
            $_POST['third_slide']
        ];

        UpdateDB('settings', $vars, $values);
    } elseif (isset($_POST['update_wallets'])) {
        $vars = [
            'BTC',
            'ETH',
            'LTC',
            'DASH',
            'XMR',
            'XMRV'
        ];

        $values = [
            $_POST['BTC'],
            $_POST['ETH'],
            $_POST['LTC'],
            $_POST['DASH'],
            $_POST['XMR'],
            $_POST['XMRV']
        ];

        UpdateDB('wallets', $vars, $values);
    } elseif (isset($_POST['update_settings'])) {
        $vars = [
            'shop_name',
            'shop_desc',
            'shop_keys'
        ];

        $values = [
            $_POST['shop_name'],
            $_POST['shop_desc'],
            $_POST['shop_keys']
        ];

        UpdateDB('settings', $vars, $values);
    } elseif (isset($_POST['update_passw'])) {
        if (hash_value($_POST['opass']) == $_SESSION['password']) {
            UpdateDB('admins', ['password'], [hash_value($_POST['npass'])], ' WHERE `password` = "'.hash_value($_POST['opass']).'"');
            $updst = 'Successfull.';
        } else {
            $updst = 'Wrong old password.';
        }
    }

    $shop_info = DataBase("SELECT * FROM `settings`");
    $walt_info = DataBase("SELECT * FROM `wallets`");
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/fontawesome-all.min.css">
		<link rel="stylesheet" href="css/datatables.min.css">
		<link rel="stylesheet" href="css/bootadmin.min.css">
		<title>Settings | <?php echo $shop_info['shop_name']; ?></title>
	</head>
	<body class="bg-light">
		<nav class="navbar navbar-expand navbar-dark bg-primary">
			<a class="sidebar-toggle mr-3" href="#"><i class="fa fa-bars"></i></a>
			<a class="navbar-brand" href="/"><?php echo $shop_info['shop_name']; ?></a>
			<div class="navbar-collapse collapse">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown">
						<a href="#" id="dd_user" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Admin</a>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd_user">
							<a href="logout.php" class="dropdown-item">Logout</a>
						</div>
					</li>
				</ul>
			</div>
		</nav>
		<div class="d-flex">
			<div class="sidebar sidebar-dark bg-dark">
				<ul class="list-unstyled">
					<li><a href="index.php"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a></li>
					<li><a href="add.php"><i class="fa fa-fw fa-edit"></i> Add item</a></li>
					<li><a href="orders.php"><i class="fa fa-fw fa-table"></i> Orders</a></li>
					<li>
						<a href="#sm_base" data-toggle="collapse" data-ss1545484090="1" class="" aria-expanded="false">
							<i class="fa fa-fw fa-cube"></i> Manage
						</a>
						<ul id="sm_base" class="list-unstyled collapse" style="">
							<li><a href="edit_items.php">Items</a></li>
							<li><a href="edit_categories.php">Categories</a></li>
						</ul>
					</li>
					<li class="active"><a href="settings.php"><i class="fa fa-fw fa-cog"></i> Settings</a></li>
				</ul>
			</div>
			<div class="content p-4">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-white font-weight-bold">
                                Basic Settings
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="shop_name">Shop name</label>
                                        <input type="text" id="shop_name" class="form-control mr-sm-2" name="shop_name" placeholder="Shop name" value="<?php echo $shop_info['shop_name']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="shop_desc">Shop desc.</label>
                                        <input type="text" id="shop_desc" class="form-control mr-sm-2" name="shop_desc" placeholder="Shop description" value="<?php echo $shop_info['shop_desc']; ?>" required>
                                        <small class="form-text text-muted">For &lt;meta name=&quot;description&quot;</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="shop_keys">Shop keywords</label>
                                        <input type="text" id="shop_keys" class="form-control mr-sm-2" name="shop_keys" placeholder="Shop keywords" value="<?php echo $shop_info['shop_keys']; ?>" required>
                                        <small class="form-text text-muted">For &lt;meta name=&quot;keywords&quot;</small>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Update</button>
                                    </div>
                                    <input type="hidden" name="update_settings" value="yes">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-white font-weight-bold">
                                Wallets
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="btc">Bitcoin</label>
                                        <input type="text" id="btc" class="form-control mr-sm-2" name="BTC" placeholder="BTC wallet address" value="<?php echo $walt_info['BTC']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="eth">Ethereum</label>
                                        <input type="text" id="eth" class="form-control mr-sm-2" name="ETH" placeholder="ETH wallet address" value="<?php echo $walt_info['ETH']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="ltc">Litecoin</label>
                                        <input type="text" id="ltc" class="form-control mr-sm-2" name="LTC" placeholder="LTC wallet address" value="<?php echo $walt_info['LTC']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="dash">DASH</label>
                                        <input type="text" id="dash" class="form-control mr-sm-2" name="DASH" placeholder="DASH wallet address" value="<?php echo $walt_info['DASH']; ?>" required>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="xmr">Monero</label>
                                        <input type="text" id="xmr" class="form-control mr-sm-2" name="XMR" placeholder="Monero wallet address" value="<?php echo $walt_info['XMR']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="xmrv">Monero PVKey</label>
                                        <input type="text" id="xmrv" class="form-control mr-sm-2" name="XMRV" placeholder="Monero Private View Key" value="<?php echo $walt_info['XMRV']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Update</button>
                                    </div>
                                    <input type="hidden" name="update_wallets" value="yes">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-white font-weight-bold">
                                Slides
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="first_slide">First slide</label>
                                        <input type="text" id="first_slide" class="form-control mr-sm-2" name="first_slide" placeholder="URL to img for first slide" value="<?php echo $shop_info['first_screen']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="second_slide">Second slide</label>
                                        <input type="text" id="second_slide" class="form-control mr-sm-2" name="second_slide" placeholder="URL to img for second slide" value="<?php echo $shop_info['second_screen']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="third_slide">Third slide</label>
                                        <input type="text" id="third_slide" class="form-control mr-sm-2" name="third_slide" placeholder="URL to img for third slide" value="<?php echo $shop_info['third_screen']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Update</button>
                                    </div>
                                    <input type="hidden" name="update_slides" value="yes">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-white font-weight-bold">
                                Change current password
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="opass">Old password</label>
                                        <input type="text" id="opass" class="form-control mr-sm-2" name="opass" placeholder="Your old password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="npass">New password</label>
                                        <input type="text" id="npass" class="form-control mr-sm-2" name="npass" placeholder="New password" required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Update</button>
                                    </div>
                                    <input type="hidden" name="update_passw" value="yes">
                                    <small class="form-text text-muted"><?php echo $updst; ?></small>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<script src="../vendor/jquery/jquery.min.js"></script>
		<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="js/datatables.min.js"></script>
		<script src="js/moment.min.js"></script>
		<script src="js/bootadmin.min.js"></script>
	</body>
</html>