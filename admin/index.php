<?php
    include 'inc/on.php';

    if (isset($_POST['new_item'])) {
        $name = htmlspecialchars($_POST['name']);
        $sdesc = htmlspecialchars($_POST['sdesc']);
        $fdesc = htmlspecialchars($_POST['fdesc']);
        $price = $_POST['price'];
        $fiat = $_POST['fiat_type'];
        $img = $_POST['icon'];
        $content = $_POST['cont'];
        $category = $_POST['category'];

        $vars = [
            'name', 'img', 'short_desc', 'full_desc',
            'fiat_price', 'fiat_type', 'content', 'category'
        ];

        $values = [
            $name, $img, $sdesc, $fdesc,
            $price, $fiat, $content, $category
        ];

        InsertDB('items', $vars, $values);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Add Item</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="../css/my.css" />
        <script src="main.js"></script>
    </head>
    <body class="adm_body">
        <div class="adm_menu">
            <div class="adm_logo">
                <span>SCS</span>
            </div>
            
            <a href="#"><button class="menu_list selected">Add Item</button></a>
            
            <form method="post">
                <input type="hidden" name="page" value="show">
                <button class="menu_list">
                    Orders
                </button>
            </form>
            
        </div>

        <div class="adm_content">
            <center>
                <h3>Add new item to shop</h3>
            </center>

            <form method="post">
                <label for="iname">Item Name</label>
                <input type="text" id="iname" name="name" placeholder="Paste item name" required>
                <br>
                <br>
                <label for="icat">Item Category</label>
                <select name="category" >
                    <option value="" disabled selected>Select category</option>
                    <?php

                        $rezults = DataBase('SELECT * FROM `category`', TRUE, TRUE);

                        foreach ($rezults as $rezult) {
                            echo '<option value="'.$rezult['id'].'">'.$rezult['name'].'</option>';
                        }

                    ?>
                </select>
                <br>
                <br>
                <label for="idesc">Item Short Description</label>
                <input type="text" id="idesc" name="sdesc" placeholder="Paste short item description. Min len 35, max 50" required>
                <br>
                <br>
                <label for="idesc">Item Full Description</label>
                <br>
                <input type="text" id="idesc" name="fdesc" placeholder="Paste full item description." required>
                <br>
                <br>
                <label for="iicon">Item Image</label>
                <input type="text" id="iicon" name="icon" placeholder="Link to image here. Ex http://example.com/item.png" required>
                <br>
                <br>
                <label for="icont">Item Data</label>
                <input type="text" id="icont" name="cont" placeholder="Item data here" required>
                <br>
                <br>
                <label for="ipric">Item Price</label>
                <div class="adm_content2">
                    <input type="text" id="ipric" name="price" placeholder="Fiat price" required>
                    <select name="fiat_type" >
                        <option value="" disabled selected>Select fiat</option>
                        <option value="UAH">UAH</option>
                        <option value="USD">USD</option>
                        <option value="GBP">GBP</option>
                        <option value="RUB">RUB</option>
                        <option value="EUR">EUR</option>
                        <option value="CZK">CZK</option>
                        <option value="BLN">BLN</option>
                        <option value="PLN">PLN</option>
                        <option value="CHF">CHF</option>
                        <option value="AED">AED</option>
                    </select>
                </div>
                <br>
                <br>
                <input type="hidden" name="new_item" value="1">
                <input type="submit" value="Submit" class="add_item">
            </form>
        </div>
    </body>
</html>