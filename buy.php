<?php
    session_start();

    include 'include/function.php';
    include 'include/captcha/captcha.php';
    
    $cryptos = ['BTC', 'LTC', 'XMR', 'DASH', 'ETH'];
    $enemy = DataBase('SELECT COUNT(*) FROM `temp` WHERE `ip` = "'.md5($_SERVER['REMOTE_ADDR']).'"')[0];

    // Check if user have uncompleted order
    if ($enemy > 0) {
        $enemy = DataBase('SELECT `hash` FROM `temp` WHERE `ip` = "'.md5($_SERVER['REMOTE_ADDR']).'"')[0];
        $text = "<span>You have uncompleted order with <b>$enemy</b> <- hash. Please make payment or wait for delete.</span>";
    }

    // Check if it is "confirm action"
    elseif (isset($_SESSION['crypto']) && isset($_POST['captcha'])) {
        if ($_POST['captcha'] == $_SESSION['captcha']['code']) {

            // Check if item exist and have some data to sell now
            $item = DataBase('SELECT COUNT(*) FROM `items` WHERE `id` = '.$_SESSION['item_id'].' AND `quantity` >= 1')[0];

            if ($item != 0) {
                // All is - OK, let`s make temp
                $must_pay = Conventer($_SESSION['fprice'], [$_SESSION['crypto']], $_SESSION['ftype']);
                $ip = md5($_SERVER['REMOTE_ADDR']);
                $hash = md5($ip.md5(date('Y-m-d H:i:sP')));
                $content = get_item($_SESSION['item_id']);

                $vars = [
                    'ip', 'hash', 'name', 'crypto',
                    'crypto_price', 'fiat', 'fiat_price',
                    'item_id', 'content'
                ];

                $values = [
                    $ip, $hash, $_SESSION['iname'], $_SESSION['crypto'],
                    $must_pay, $_SESSION['ftype'], $_SESSION['fprice'],
                    $_SESSION['item_id'], $content
                ];

                InsertDB('temp', $vars, $values);
                $text = '<span>Successfully created order. Wait few seconds and you has been redirected.</span>
                <script>setTimeout(function() { location.href = "/order.php"; }, 1500);</script>';
                session_destroy();
                session_start();
                $_SESSION['order_hash'] = $hash;
            } else {
                // All data sold
                $text = '<span>Ooopppsss, it looks like someone bought the goods before you. Order not created, repeat purchase process.</span>
                <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
            }
        } else {
            // Wrong captcha
            $text = '<span>Bad captcha image</span>
            <script>setTimeout(function() { location.href = location.pathname+location.search; }, 1500);</script>';
        }
    }

    // Check for order confirmation
    elseif (isset($_GET['id']) && isset($_GET['c']) && is_numeric($_GET['id']) && in_array($_GET['c'], $cryptos)) {
        $_SESSION['crypto'] = $_GET['c'];
        $_SESSION['item_id'] = $_GET['id'];
        $_SESSION['captcha'] = simple_php_captcha();

        // Check for item exist and sell data availability
        $item = DataBase('SELECT COUNT(*) FROM `items` WHERE `id` = '.$_GET['id'].' AND `quantity` >= 1')[0];

        if ($item != 0) {
            $item = DataBase('SELECT `img`, `name`, `fiat_price`, `fiat_type` FROM `items` WHERE `id` = '.$_GET['id'].' AND `quantity` >= 1');

            // Save variables for prevent multiple DB-requests
            $_SESSION['ftype'] = $item['fiat_type'];
            $_SESSION['fprice'] = $item['fiat_price'];
            $_SESSION['iname'] = $item['name'];
            $_SESSION['iimg'] = $item['img'];

            // Show confirm form
            $text = '<form method="post">
                <div class="card h-100">
                    <img src="'.$item['img'].'" class="card-img-top">
                </div>
                <div class="input-group">
                    <p>You want to buy "<b>'.$item['name'].'</b>" for <b>'.$item['fiat_price'].' '.$item['fiat_type'].'</b> in '.$_GET['c'].'</p>
                </div>
                <p id="crypto"></p>
                <div class="form-group" style="text-align:center;">
                    <img src="'.$_SESSION['captcha']['image_src'].'">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="text" name="captcha" class="form-control" placeholder="Captcha">
                </div>
                <div class="row">
                    <div class="col pr-2">
                        <button type="submit" class="btn btn-block btn-primary">Confirm</button>
                    </div>
                </div>
            </form>
            ';
        } else {
            header('Location: /');
        }
    } else {
        header('Location: /');
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="admin/css/fontawesome-all.min.css">
		<link rel="stylesheet" href="admin/css/bootadmin.min.css">

        <title>Buy</title>
    </head>
    <body class="bg-light">
            <div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                           <?php echo $text; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>
</html>