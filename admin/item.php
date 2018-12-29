<?php
	include 'inc/on.php';
	
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        if (DataBase("SELECT COUNT(*) FROM `items` WHERE `id` = ".$_GET['id'])[0] == 0) {
			header('Location: /admin');
		}
    } else {
		header('Location: /admin');
	}

	$status = '';
	$shop_info = DataBase("SELECT * FROM `settings`");

    if (isset($_POST['update_item'])) {
        $name = htmlspecialchars($_POST['name']);
        $sdesc = htmlspecialchars($_POST['sdesc']);
        $fdesc = escpe_val($_POST['fdesc']);
        $price = $_POST['price'];
        $fiat = $_POST['fiat_type'];
        $img = $_POST['item_image'];
		$content = $_POST['cont'];
		$quantity = sizeof(explode(PHP_EOL, $_POST['cont']));
		
		if (isset($_POST['category'])) {
			$category = $_POST['category'];
		} else {
			$category = NULL;
		}

        $vars = [
            'name', 'img', 'short_desc', 'full_desc',
			'fiat_price', 'fiat_type', 'content', 'category',
			'quantity'
        ];

        $values = [
            $name, $img, $sdesc, $fdesc,
			$price, $fiat, $content, $category,
			$quantity
        ];

		UpdateDB('items', $vars, $values, ' WHERE `id` = '.$_GET['id']);
		$status = '<div class="alert alert-success alert-dismissible" role="alert">
						Item updated successfully!
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>';
	}
	
	$item_info = DataBase("SELECT * FROM `items` WHERE `id` = ".$_GET['id']);
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
		<link rel="stylesheet" href="minified/themes/default.min.css" />
		<script src="minified/sceditor.min.js"></script>
		<title>Edit Item | <?php echo $shop_info['shop_name']; ?></title>
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
					<li><a href="/admin"><i class="fa fa-fw fa-tachometer-alt"></i> Dashboard</a></li>
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
				<?php echo $status; ?>
				<div class="row mb-4">
					<div class="col-md-8">
						<div class="card">
							<div class="card-header bg-white font-weight-bold">
								Add Form
							</div>
							<div class="card-body">
								<form method="post">
									<div class="form-group">
										<div class="form-inline">
											<input type="text" onkeypress="preview_set('name');" id="item_name" class="form-control mr-sm-2" name="name" maxlength="20" placeholder="Item name" value="<?php echo $item_info['name']; ?>" required>
											<select name="category" class="form-control">
												<option value="" disabled>Select category</option>
												<?php
													$rezults = DataBase('SELECT * FROM `category`', TRUE, TRUE);

													foreach ($rezults as $rezult) {
														echo '<option value="'.$rezult['id'].'"'.($item_info['category'] == $rezult['id'] ? ' selected' : '').'>'.$rezult['name'].'</option>'."\n";
													}
												?>
											</select>
										</div>
										<small class="form-text text-muted">Max. 20</small>
									</div>
									<div class="form-group">
										<label for="item_img">Item image</label>
										<input type="text" onkeypress="preview_set('img');" id="item_img" class="form-control mr-sm-2" name="item_image" placeholder="Item image" value="<?php echo $item_info['img']; ?>" required>
									</div>
									<div class="form-group">
										<label for="item_sdesc">Item short desc</label>
										<input type="text" onkeypress="preview_set('sdesc');" class="form-control" id="item_sdesc" class="form-control mr-sm-2" name="sdesc" maxlength="90" placeholder="Short desc" value="<?php echo $item_info['short_desc']; ?>" required>
										<small class="form-text text-muted">Max. 90</small>
									</div>
									<div class="form-group">
										<label for="item_fdesc">Item full desc</label>
										<textarea class="form-control" placeholder="Paste full item desc" id="item_fdesc" name="fdesc" rows="10" required><?php echo $item_info['full_desc']; ?></textarea>
									</div>
									<div class="form-group">
										<label for="item_cnt">Item content</label>
										<textarea type="text" id="item_cnt" class="form-control mr-sm-2" name="cont" placeholder="Item content(-s)" required><?php echo $item_info['content']; ?></textarea>
										<small class="form-text text-muted">Separate by new line</small>
									</div>
									<div class="form-group">
										<label for="item_price">Item price</label>
										<div class="form-inline">
											<input type="number" onkeypress="preview_set('price');" id="item_price" class="form-control" name="price" placeholder="Fiat price" value="<?php echo $item_info['fiat_price']; ?>" required>
											<div class="input-group-append">
												<select onchange="preview_set('cur');" id="item_cur" name="fiat_type" class="form-control" required>
													<?php
														$fiat_list = [
															'UAH', 'USD',
															'GBP', 'RUB',
															'EUR', 'CZK',
															'BLN', 'PLN',
															'CHF', 'AED'
														];

														foreach($fiat_list as $_fiat) {
															echo '<option value="'.$_fiat.'"'.($item_info['fiat_type'] == $_fiat ? ' selected' : '').">$_fiat</option>";
														}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<button class="btn btn-primary" type="submit">Update item</button>
									</div>
									<input type="hidden" name="update_item" value="yes">
								</form>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card">
							<div class="card-header bg-white font-weight-bold">
								Preview
							</div>
							<div class="card-body">
								<div class="card h-100">
									<img id="prev_img" class="card-img-top" src="<?php echo $item_info['img']; ?>" alt="" data-ss1545481278="1">
									<div class="card-body">
										<h4 id="prev_name" class="card-title">
											<?php echo $item_info['name']; ?>
										</h4>
										<h5 id="prev_price"><?php echo $item_info['fiat_price']; ?> <?php echo $item_info['fiat_type']; ?></h5>
										<p id="prev_sdesc" class="card-text"><?php echo $item_info['short_desc']; ?></p>
									</div>
								</div>
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
		<script src="minified/formats/xhtml.min.js"></script>
		<script>
			var textarea = document.getElementById('item_fdesc');

			sceditor.create(textarea, {
				format: 'xhtml',
				style: 'minified/themes/content/default.min.css'
			});
		</script>
		<script>
			function sleep(ms) {
				return new Promise(resolve => setTimeout(resolve, ms));
			}

			async function preview_set(what) {
				await sleep(250);

				if (what == 'price' || what == 'cur') {
					var price = document.getElementById('item_price').value;
					var fiat = document.getElementById('item_cur').value;
					document.getElementById('prev_price').innerText = price + ' ' + fiat;
				} else if (what == 'img') {
					document.getElementById('prev_' + what).src = document.getElementById('item_' + what).value;
				} else {
					document.getElementById('prev_' + what).innerText = document.getElementById('item_' + what).value;
				}
			}
		</script>
	</body>
</html>