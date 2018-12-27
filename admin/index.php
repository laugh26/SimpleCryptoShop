<?php
	include 'inc/on.php';

	$shop_info = DataBase("SELECT * FROM `settings`");
	$temp = DataBase("SELECT COUNT(*) FROM `temp`")[0];
	$sales = DataBase("SELECT COUNT(*) FROM `payments`")[0];
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
		<title>Dashboard | <?php echo $shop_info['shop_name']; ?></title>
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
					<li class="active"><a href="#"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a></li>
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
					<li><a href="settings.php"><i class="fa fa-fw fa-cog"></i> Settings</a></li>
				</ul>
			</div>
			<div class="content p-4">
				<h2 class="mb-4">Dashboard</h2>
				<div class="row mb-4">
					<div class="col-md">
						<div class="d-flex border">
							<div class="bg-primary text-light p-4">
								<div class="d-flex align-items-center h-100">
									<i class="fa fa-3x fa-fw fa-spinner fa-spin"></i>
								</div>
							</div>
							<div class="flex-grow-1 bg-white p-4">
								<p class="text-uppercase text-secondary mb-0">Temp</p>
								<h3 class="font-weight-bold mb-0"><?php echo $temp; ?></h3>
							</div>
						</div>
					</div>
					<div class="col-md">
						<div class="d-flex border">
							<div class="bg-danger text-light p-4">
								<div class="d-flex align-items-center h-100">
									<i class="fa fa-3x fa-fw fa-shopping-cart"></i>
								</div>
							</div>
							<div class="flex-grow-1 bg-white p-4">
								<p class="text-uppercase text-secondary mb-0">Sales</p>
								<h3 class="font-weight-bold mb-0"><?php echo $sales; ?></h3>
							</div>
						</div>
					</div>
					<div class="col-md">
						<div class="d-flex border">
							<div class="bg-info text-light p-4">
								<div class="d-flex align-items-center h-100">
									<i class="fa fa-3x fa-fw fa-users"></i>
								</div>
							</div>
							<div class="flex-grow-1 bg-white p-4">
								<p class="text-uppercase text-secondary mb-0">???</p>
								<h3 class="font-weight-bold mb-0">???</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header bg-white font-weight-bold">
						Recent Orders
					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<tr>
									<th scope="col">Order ID</th>
									<th scope="col">Item</th>
									<th scope="col">TXID</th>
									<th scope="col">Fiat</th>
									<th scope="col">System</th>
									<th scope="col">Date</th>
								</tr>
							</thead>
							<tbody>
								<?php

									if ($sales > 0) {
										$payments = DataBase(
											'SELECT * FROM `payments` ORDER BY `date` DESC LIMIT 10',
											TRUE,
											TRUE
										);
	
										foreach($payments as $payment) {
											echo "<tr>
										<td>".$payment['id']."</td>
										<td>".$payment['product']."</td>
										<td>".$payment['txid']."</td>
										<td>".$payment['fiat']."</td>
										<td>".$payment['system']."</td>
										<td>".$payment['date']."</td>
									</tr>\n";
										}
									} else {
										echo '<tr>
									<td colspan="6">No sales</td>
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
	</body>
</html>