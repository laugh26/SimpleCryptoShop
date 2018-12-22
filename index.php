<?php

	include("include/db.php");

    $category = FALSE;
    $shop_info = DataBase("SELECT * FROM `settings`");
	
	if (DataBase("SELECT COUNT(*) FROM `items`")[0] > 0) {
	    $status = TRUE;
	} else {
	    $status = FALSE;
    }
    
    if (isset($_GET['category']) && is_numeric($_GET['category'])) {
        if (DataBase("SELECT COUNT(*) FROM `category` WHERE `id` = ".$_GET['category'])[0] != 1) {
            header('Location: /');
        } else {
            $category = TRUE;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="<?php echo $shop_info['shop_desc']; ?>">
		<meta name="keywords" content="<?php echo $shop_info['shop_keys']; ?>">
		<title>Items - <?php echo $shop_info['shop_name']; ?></title>
		<!-- Bootstrap core CSS -->
		<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="css/shop-homepage.css" rel="stylesheet">
	</head>
	<body>
		<!-- Navigation -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
			<div class="container">
				<a class="navbar-brand" href="/"><?php echo $shop_info['shop_name']; ?></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarResponsive">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item">
							<a class="nav-link" href="about">About</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="contact">Contact</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- Page Content -->
		<div class="container">
			<div class="row">
                <?php
                    if (!($category)) {
                        echo '<div class="col-lg-3">
                        <br>
                        <br>
                        <div class="list-group">';

                        $rezults = DataBase('SELECT * FROM `category`', TRUE, TRUE);

                        foreach ($rezults as $rezult) {
                            $count = DataBase('SELECT COUNT(`id`) FROM `items` WHERE `category` = '.$rezult['id'])[0];

                            echo '<a href="?category='.$rezult['id'] .
                                 '" class="list-group-item">'.$rezult['name'] .
                                 " ($count)</a>";
                        }

                        echo '</div>
                        </div>';
                    }
                ?>
				<!-- /.col-lg-3 -->
				<div class="col-lg-9">
					<div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
						<ol class="carousel-indicators">
							<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
							<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
							<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
						</ol>
						<div class="carousel-inner" role="listbox">
							<div class="carousel-item active">
								<img class="d-block img-fluid" src="<?php echo $shop_info['first_screen']; ?>" alt="First slide">
							</div>
							<div class="carousel-item">
								<img class="d-block img-fluid" src="<?php echo $shop_info['second_screen']; ?>" alt="Second slide">
							</div>
							<div class="carousel-item">
								<img class="d-block img-fluid" src="<?php echo $shop_info['third_screen']; ?>" alt="Third slide">
							</div>
						</div>
						<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
						</a>
					</div>
					<div class="row">
						<?php
							if ($status) {
                                if ($category) {
                                    $rezults = DataBase(
                                        'SELECT * FROM `items` WHERE `category` = '.$_GET['category'],
                                        TRUE,
                                        TRUE
                                    );
                                } else {
                                    $rezults = DataBase('SELECT * FROM `items`', TRUE, TRUE);
                                }

                                foreach ($rezults as $rezult) {
                                    $t_link = '/item.php?id='.$rezult['id'];
                                    echo "\n".'						<div class="col-lg-4 col-md-6 mb-4">'."\n";
                                    echo '							<div class="card h-100">'."\n";
                                    echo '								<a href="'.$t_link.'"><img class="card-img-top" src="'.$rezult['img'].'" alt=""></a>'."\n";
                                    echo '								<div class="card-body">'."\n";
                                    echo '									<h4 class="card-title">'."\n";
                                    echo '										<a href="'.$t_link.'">'.$rezult['name'].'</a>'."\n";
                                    echo '									</h4>'."\n";
                                    echo '									<h5>'.$rezult["fiat_price"]." ".$rezult["fiat_type"]."</h5>\n";
                                    echo '									<p class="card-text">'.$rezult['short_desc']."</p>\n";
                                    echo "								</div>\n";
                                    echo "							</div>\n";
                                    echo "						</div>\n";
                                }
							} else {
							    echo "No item`s in shop...";
							}
						?>
					</div>
					<!-- /.row -->
				</div>
				<!-- /.col-lg-9 -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container -->

		<!-- Bootstrap core JavaScript -->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	</body>
</html>