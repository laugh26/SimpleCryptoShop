<?php
    include 'inc/on.php';

    if (isset($_POST['id'])) {
        DataBase('DELETE FROM `items` WHERE `id` = '.$_POST['id']);
    }

    $shop_info = DataBase("SELECT * FROM `settings`");
    $itms = DataBase("SELECT COUNT(*) FROM `items`")[0];
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
		<title>Edit items | <?php echo $shop_info['shop_name']; ?></title>
	</head>
	<body class="bg-light">
		<nav class="navbar navbar-expand navbar-dark bg-primary">
			<a class="sidebar-toggle mr-3" href="#"><i class="fa fa-bars"></i></a>
			<a class="navbar-brand" href="/"><?php echo $shop_info['shop_name']; ?></a>
			<div class="navbar-collapse collapse">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown">
						<a href="#" id="dd_user" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['plainuser']; ?></a>
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
						<a href="#sm_base" data-toggle="collapse" data-ss1545484090="1" class="" aria-expanded="true">
							<i class="fa fa-fw fa-cube"></i> Manage
						</a>
						<ul id="sm_base" class="list-unstyled collapse show" style="">
							<li class="active"><a href="edit_items.php">Items</a></li>
							<li><a href="edit_categories.php">Categories</a></li>
						</ul>
					</li>
					<li><a href="settings.php"><i class="fa fa-fw fa-cog"></i> Settings</a></li>
				</ul>
			</div>
			<div class="content p-4">
                <div class="card">
                    <div class="card-header bg-white font-weight-bold">
                        List of items
                    </div>
                    <div class="card-body">
                        <table id="categories" class="table table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Fiat price</th>
                                    <th>Fiat type</th>
                                    <th class="actions sorting_disabled">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if ($itms > 0) {
                                    $items = DataBase(
                                        'SELECT * FROM `items`',
                                        TRUE,
                                        TRUE
                                    );

                                    foreach($items as $item) {
                                        echo "\n                                 <tr>
                                    <td>".$item['id'].'</td>
                                    <td>'.$item['name'].'</td>
                                    <td>'.$item['fiat_price'].'</td>
                                    <td>'.$item['fiat_type'].'</td>
                                    <td>
                                        <a class="btn btn-primary" href="item.php?id='.$item['id'].'"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-danger" onclick="xr('.$item['id'].');"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>'."\n";
                                    }
                                } else {
                                    echo '<tr>
                                <td colspan="6">No items</td>
                            </tr>'."\n";
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>
		</div>
		<script src="../vendor/jquery/jquery.min.js"></script>
		<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="js/datatables.min.js"></script>
		<script src="js/moment.min.js"></script>
		<script src="js/bootadmin.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#categories').DataTable();
            });
        </script>
        <script>

            function xr(s) {
                var xhr = new XMLHttpRequest();
                var body = 'id=' + s;

                xhr.open("POST", '', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                    location.reload();
                };

                xhr.send(body);
            }
        </script>
	</body>
</html>