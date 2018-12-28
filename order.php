<?php
    session_start();

    include 'include/function.php';
    include 'include/captcha/captcha.php';

    $enemy = DataBase('SELECT COUNT(*) FROM `temp` WHERE `ip` = "'.md5($_SERVER['REMOTE_ADDR']).'"')[0];


    if (isset($_POST['hash']) && isset($_POST['txid'])) {
        $tx_id = preg_replace("[^\w\d\s]", "", $_POST['txid']);
        $hoo = preg_replace("[^\w\d\s]", "", $_POST['hash']);

        $ha = DataBase('SELECT COUNT(*) FROM `temp` WHERE `hash` = "'.$hoo.'"')[0];
        $ti = DataBase('SELECT COUNT(*) FROM `payments` WHERE `txid` = "'.$tx_id.'"')[0];

        if ($ha == 0) {
            $text = '<span>No hash found.</span>
            <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
        } elseif ($ti == 1) {
            $text = '<span>TXID already in DB.</span>
            <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
        } else {
            $ti = DataBase('SELECT * FROM `temp` WHERE `hash` = "'.$hoo.'"');
            $rez = Find_Payment($tx_id, $ti['crypto'], $ti['crypto_price']);

            if ($rez) {
                DataBase('DELETE FROM `temp` WHERE `hash` = "'.$ha.'"');

                

                header('Content-Length: ' . strlen($ti['content']));
                header('Content-type: application/txt');
                header('Content-Disposition: attachment; filename="order_'.$ha.'.txt"');
                print($ti['content']);
            } else {
                $text = '<span>Payment not founded.</span>
            <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
            }
        }
    }
    
    elseif ($enemy > 0) {
        $enemy = DataBase('SELECT `hash` FROM `temp` WHERE `ip` = "'.md5($_SERVER['REMOTE_ADDR']).'"')[0];
        $text = "<span>You have uncompleted order with <b>$enemy</b> <- hash. Please make payment or wait for delete.</span>";
    }

    // Check for order confirmation
    elseif (isset($_GET['id'])) {
    
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