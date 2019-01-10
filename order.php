<?php
    session_start();

    include 'include/function.php';
    include 'include/captcha/captcha.php';

    $enemy = DataBase('SELECT COUNT(*) FROM `temp` WHERE `ip` = "'.md5($_SERVER['REMOTE_ADDR']).'"')[0];

    // First check if user sends form where he submit txid
    if (isset($_SESSION['order_hash']) && isset($_POST['txid']) && isset($_POST['captcha'])) {
        
        // Check captcha
        if ($_POST['captcha'] == $_SESSION['captcha']['code']) {
            
            // Clear special chars
            $tx_id = preg_replace("[^\w\d\s]", "", $_POST['txid']);
            $hoo = $_SESSION['order_hash'];

            // Check exist in `temp` and check maybe TXID already exist
            $ha = DataBase('SELECT COUNT(*) FROM `temp` WHERE `hash` = "'.$hoo.'"')[0];
            $ti = DataBase('SELECT COUNT(*) FROM `payments` WHERE `txid` = "'.$tx_id.'"')[0];

            if ($ha == 0) {
                $text = '<span>No hash found.</span>
                <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
            } elseif ($ti == 1) {
                $text = '<span>TXID already in DB.</span>
                <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
            } else {

                // If all good - get next steps
                $ti = DataBase('SELECT * FROM `temp` WHERE `hash` = "'.$hoo.'"');
                $rez = Find_Payment($tx_id, $ti['crypto'], $ti['crypto_price']);

                // If user maked payment
                if ($rez) {
                    $vars = [
                        'product', 'txid',
                        'system', 'fiat'
                    ];

                    $values = [
                        $ti['name'], $tx_id,
                        $ti['crypto'], $ti['fiat_price'].' '.$ti['fiat']
                    ];

                    // Delete from `temp` and add to successfull payments
                    DataBase('DELETE FROM `temp` WHERE `hash` = "'.$hoo.'"');
                    InsertDB('payments', $vars, $values);

                    // Return content as .txt file, user downloads this
                    header('Content-Length: ' . strlen($ti['content']));
                    header('Content-type: application/txt');
                    header('Content-Disposition: attachment; filename="order_'.$hoo.'.txt"');
                    print($ti['content']);
                    exit;
                } else {
                    $text = '<span>Payment not founded.</span>
                <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
                }
            }
        } else {
            $text = '<span>Bad captcha image.</span>
                <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
        }
    }

    // If user search order by hash
    elseif (isset($_POST['find_hash']) && isset($_POST['hash']) && isset($_POST['captcha'])) {
        if ($_POST['captcha'] == $_SESSION['captcha']['code']) {
            $hoo = preg_replace("[^\w\d\s]", "", $_POST['hash']);
            $ha = DataBase('SELECT COUNT(*) FROM `temp` WHERE `hash` = "'.$hoo.'"')[0];

            if ($ha == 1) {
                $_SESSION['order_hash'] = $hoo;
                header('Location: '.$_SERVER['REQUEST_URI']);
            } else {
                $text = '<span>Hash not founded.</span>
                <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
            }
        } else {
            $text = '<span>Bad captcha image.</span>
                <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
        }
    }

    // Maybe user already have HASH in $_SESSION
    elseif (isset($_SESSION['order_hash'])) {
        $_SESSION['captcha'] = simple_php_captcha();
        $ha = DataBase('SELECT COUNT(*) FROM `temp` WHERE `hash` = "'.$_SESSION['order_hash'].'"')[0];
        $ti = DataBase('SELECT * FROM `temp` WHERE `hash` = "'.$_SESSION['order_hash'].'"');

        // But we need check to prevent errors if hash droped from `temp`
        if ($ha == 1) {
            $wallet = DataBase('SELECT `'.$ti['crypto'].'` FROM `wallets`')[0];

            $text = '<form method="post">
                    <div class="input-group">
                        <p>You want to buy "<b>'.$ti['name'].'</b>" for <b>'.$ti['fiat_price'].' '.$ti['fiat'].'</b> ('.$ti['crypto_price'].' '.$ti['crypto'].')</p>
                    </div>
                    <div class="input-group mb-3">
                        <span>You must pay to: <b>'.$ti['end'].'</b></span>
                    </div>
                    <div class="input-group mb-3">
                        <span>Wallet: <b>'.$wallet.'</b></span>
                    </div>
                    <div class="form-group" style="text-align:center;">
                        <img title="Captcha" src="'.$_SESSION['captcha']['image_src'].'">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                        </div>
                        <input type="text" name="txid" class="form-control" placeholder="TXID">
                    </div>
                    <small class="form-text text-muted">Remember! Send an amount equal to or greater than the one shown to you in crypto by one transaction</small>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="text" name="captcha" class="form-control" placeholder="Captcha">
                    </div>
                    <div class="row">
                        <div class="col pr-2">
                            <button type="submit" class="btn btn-block btn-primary">Check</button>
                        </div>
                    </div>
                </form>';
        } else {
            unset($_SESSION['order_hash']);
            $text = '<span>Session hash not founded.</span>
            <script>setTimeout(function() { location.href = location.pathname; }, 1500);</script>';
        }
    }

    // Maybe user have record in `temp` but hash not stored on $_SESSION var
    elseif ($enemy > 0) {
        $enemy = DataBase('SELECT `hash` FROM `temp` WHERE `ip` = "'.md5($_SERVER['REMOTE_ADDR']).'"')[0];
        $_SESSION['order_hash'] = $enemy;
        header('Location: '.$_SERVER['REQUEST_URI']);
    }

    // User don`t have nothing and he want to find hash. We need show form to he)
    else {
        $_SESSION['captcha'] = simple_php_captcha();
        $text = '<form method="post">
            <div class="form-group" style="text-align:center;">
                <img title="Captcha" src="'.$_SESSION['captcha']['image_src'].'">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                </div>
                <input type="text" name="hash" class="form-control" placeholder="Paste hash order here">
            </div>
            <small class="form-text text-muted">Don`t use special chars.</small>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input type="text" name="captcha" class="form-control" placeholder="Captcha">
            </div>
            <div class="row">
                <div class="col pr-2">
                    <button type="submit" class="btn btn-block btn-primary">Check</button>
                </div>
            </div>
            <input type="hidden" name="find_hash" value="yes">
        </form>';
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