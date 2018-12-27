<?php
    include 'inc/on.php';

    if (isset($_POST['new_cat']) && isset($_POST['cat_name'])) {
        InsertDB(
            'category',
            ['name'],
            [escpe_val($_POST['cat_name'])]
        );
        header("Location: /admin/edit_categories.php");
    } elseif (isset($_POST['id']) && isset($_POST['act'])) {
        if ($_POST['act'] == 'del') {
            DataBase('DELETE FROM `category` WHERE `id` = '.$_POST['id']);
        } elseif ($_POST['act'] == 'edit' && isset($_POST['name'])) {
            UpdateDB(
                'category',
                ['name'],
                [escpe_val($_POST['name'])],
                ' WHERE `id` = '.$_POST['id']
            );
        }
    }

    $shop_info = DataBase("SELECT * FROM `settings`");
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
		<title>Edit categories | <?php echo $shop_info['shop_name']; ?></title>
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
						<a href="#sm_base" data-toggle="collapse" data-ss1545484090="1" class="" aria-expanded="true">
							<i class="fa fa-fw fa-cube"></i> Manage
						</a>
						<ul id="sm_base" class="list-unstyled collapse show" style="">
							<li><a href="edit_items.php">Items</a></li>
							<li class="active"><a href="edit_categories.php">Categories</a></li>
						</ul>
					</li>
					<li><a href="settings.php"><i class="fa fa-fw fa-cog"></i> Settings</a></li>
				</ul>
			</div>
			<div class="content p-4">
                <div class="row mb-4">
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header bg-white font-weight-bold">
                                List of categories
                            </div>
                            <div class="card-body">
                                <table id="categories" class="table table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th class="actions sorting_disabled">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        if ($sales > 0) {
                                            $payments = DataBase(
                                                'SELECT * FROM `category`',
                                                TRUE,
                                                TRUE
                                            );

                                            foreach($payments as $payment) {
                                                echo "\n                                 <tr>
                                            <td>".$payment['id'].'</td>
                                            <td><input type="text" id="c'.$payment['id'].'" class="form-control mr-sm-2" value="'.$payment['name'].'"></td>
                                            <td>
                                                <button class="btn btn-primary" onclick="xr(2, '.$payment['id'].');"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-danger" onclick="xr(1, '.$payment['id'].');"><i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>'."\n";
                                            }
                                        } else {
                                            echo '<tr>
                                        <td colspan="3">No categorys</td>
                                    </tr>'."\n";
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header bg-white font-weight-bold">
                                Add category
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="cat_name">Category name</label>
                                        <input type="text" id="cat_name" class="form-control mr-sm-2" name="cat_name" placeholder="Category name" required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Add new category</button>
                                    </div>
                                    <input type="hidden" name="new_cat" value="yes">
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
        <script>
            $(document).ready(function () {
                $('#categories').DataTable();
            });
        </script>
        <script>

            function xr(f, s) {
                var xhr = new XMLHttpRequest();
                var body = 'id=' + s;

                if (f == 1) {
                    body += '&act=del';
                } else {
                    var v = document.getElementById('c'+s).value;
                    body += '&act=edit&name=' + encodeURIComponent(v);
                }

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