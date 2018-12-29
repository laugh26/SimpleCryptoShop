<?php

	include "include/function.php";

    $shop_info = DataBase("SELECT * FROM `settings`");

    if (!(isset($_GET['id'])) && !(is_numeric($_GET['id']))) {
        header('Location: /');
    } elseif (DataBase("SELECT COUNT(`name`) FROM `items` WHERE `id` = ".$_GET['id'])[0] == 0) {
        header('Location: /');
    }

    $information = DataBase("SELECT * FROM `items` WHERE `id` = ".$_GET['id']);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo $shop_info['shop_desc']; ?>">
    <meta name="keywords" content="<?php echo $shop_info['shop_keys']; ?>">
    <title><?php echo $information['name'].' - '.$shop_info['shop_name']; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/shop-item.css" rel="stylesheet">

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
              <a class="nav-link" href="/about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/contact">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
            <h1 class="my-4">Price</h1>
            <div class="list-group">
                <?php

                    $prices = retPrice(['BTC', 'XMR', 'ETH', 'LTC', 'DASH'], $information['fiat_type']);

                    echo "
                        <span>1 BTC = ".$prices[0]." ".$information['fiat_type']."</span>
                        <span>1 XMR = ".$prices[1]." ".$information['fiat_type']."</span>
                        <span>1 ETH = ".$prices[2]." ".$information['fiat_type']."</span>
                        <span>1 LTC = ".$prices[3]." ".$information['fiat_type']."</span>
                        <span>1 DASH = ".$prices[4]." ".$information['fiat_type']."</span>
                    ";

                ?>
            </div>
        </div>
        <!-- /.col-lg-3 -->

        <div class="col-lg-9">

          <div class="card mt-4" style="margin-bottom: 50px;">
            <img class="card-img-top img-fluid" src="<?php echo $information['img']; ?>" alt="">
            <div class="card-body">
              <h2 class="card-title"><?php echo $information['name']; ?></h2>
              <h5><?php echo $information['fiat_price'].' '.$information['fiat_type']; ?></h5>
              <p class="card-text"><?php echo $information['full_desc']; ?></p>
              <a href="/buy.php?id=<?php echo $information['id']; ?>&c=BTC" class="btn btn-info"><img src="img/btc.png"></a>
              <a href="/buy.php?id=<?php echo $information['id']; ?>&c=ETH" class="btn btn-info"><img src="img/eth.png"></a>
              <a href="/buy.php?id=<?php echo $information['id']; ?>&c=XMR" class="btn btn-info"><img src="img/xmr.png"></a>
              <a href="/buy.php?id=<?php echo $information['id']; ?>&c=LTC" class="btn btn-info"><img src="img/ltc.png"></a>
              <a href="/buy.php?id=<?php echo $information['id']; ?>&c=DASH" class="btn btn-info"><img src="img/dash.png"></a>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-lg-9 -->

      </div>

    </div>
    <!-- /.container -->

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>

</html>
