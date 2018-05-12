<?php

	include("include/function.php"); 
	
	buyCheck();
	
	$buyed = false;
	$c_name = retCN($_POST["crypto"]);

	$row = SQL_Query("full", "SELECT `name`, `content`, `price`, `fiat_type` FROM `Items` WHERE `id` =".$_POST["id"]);
	$s_price = Conventer($row["price"],$c_name,$row["fiat_type"]); 
	
	if(isset($_POST["payment"]) and isset($_POST["tx_id"]) and $_POST["payment"] == 1) { 
		if (strlen($_POST["tx_id"]) == 64 or strlen($_POST["tx_id"]) == 66) { 
			$tx_id_clear = preg_replace("[^\w\d\s]","",$_POST["tx_id"]); 
			
			if (SQL_Query("full","SELECT COUNT(*) FROM `orders` WHERE `tx` = '".$tx_id_clear."'")['COUNT(*)'] != 0) { 
				header("Location: /");
				die();
			}
			
			$buyed = Find_Payment($tx_id_clear, $_POST["crypto"], $s_price); 
		}
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<style>
			body {
				padding: 10%;
			}
		
			input[type=text], select {
				text-align: center;
				width: 100%;
				padding: 12px 20px;
				margin: 8px 0;
				display: inline-block;
				border: 1px solid #ccc;
				border-radius: 4px;
				box-sizing: border-box;
			}

			input[type=submit] {
				width: 100%;
				background-color: #4CAF50;
				color: white;
				padding: 14px 20px;
				margin: 8px 0;
				border: none;
				border-radius: 4px;
				cursor: pointer;
			}

			input[type=submit]:hover {
				background-color: #45a049;
			}

			div {
				border-radius: 5px;
				background-color: #f2f2f2;
				padding: 20px;
			}
		</style>
		<title>Buy Item - Simple Crypto Shop</title>
	</head>
	<body>
			<?php
				if ($buyed) {
					print '
		<div>
			<center>
				<p>Thnx, for buy!</p>
				<h3>'.$row["content"].'</h3>
			</center>
		</div>
		';
					SQL_Query("nfull", "DELETE FROM `items` WHERE `id` = ".$_POST["id"]);
					SQL_Query("nfull", "INSERT INTO `orders`(`tx`, `crypto`) VALUES ('".$tx_id_clear."', '".$c_name."')");
				} else {
			print '
		<div>
			<form method="post">
				<center>
					<h2>Paste TX ID here to get items.</h2>
					<p>You want buy: '.$row["name"].'</p>
					<p>With '.$s_price.' '.$c_name.'</p>
				</center>
				<input type="text" id="itx_id" name="tx_id" placeholder="TX here" required>
				<input type="hidden" name="crypto" value="'.$_POST["crypto"].'">
				<input type="hidden" name="id" value="'.$_POST["id"].'">
				<input type="hidden" name="payment" value="1">
				<input type="submit" value="Submit">
			</form>
		</div>
		';
				}
			?>

	</body>
</html>