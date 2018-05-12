<?php

	function Auth() {
		include 'settings.php';
		
		if(isset($_GET["key"]) and $_GET["key"] == $admin) {}
		else { die; }
	}
	
	function Find_Payment($tx_id, $crypto, $price) {
		include 'settings.php';
		
		if($crypto == "monero") {
			$var = file('https://xmrchain.net/myoutputs/'.$tx_id."/$xmr/$vwk");
			for($i=160; $i != sizeof($var); $i++)
				if(strstr($var[$i], $price)) {
					$buyed = true;
				}
		} else if($crypto == "ethereum") {
			$massive = implode('', file("https://api.ethplorer.io/getTxInfo/$tx_id?apiKey=freekey"));
				$massive = json_decode($massive, true);
				
				if($massive['to']==$eth)
					if($massive['value'] >= $price)
						return true;
		} else if($crypto == "bitcoin") {
			$massive = implode('', file("https://blockchain.info/rawtx/$tx_id"));
				$massive = json_decode($massive, true);
					
				for($i =0; $i != count($massive['out']); $i++)
					if($massive['out'][$i]['addr'] == $btc)
						if($massive['out'][$i]['value'] >= $price*10**8)
							return true;
		} else if($crypto == "litecoin") {
			$massive = implode('', file("https://chainz.cryptoid.info/explorer/tx.raw.dws?coin=ltc&id=$tx_id&fmt.js"));
				$massive = json_decode($massive, true);
				
				for($i =0; $i != count($massive['vout']); $i++)
					if($massive['vout'][$i]['value'] >= "$price")
						if($massive['vout'][$i]['scriptPubKey']['addresses'][0] == "$ltc")
							return true;
		} else if($crypto == "dash") {
			$massive = implode('', file("https://chainz.cryptoid.info/explorer/tx.raw.dws?coin=dash&id=$tx_id&fmt.js"));
				$massive = json_decode($massive, true);
				
				for($i =0; $i != count($massive['vout']); $i++)
					if($massive['vout'][$i]['value'] >= "$price")
						if($massive['vout'][$i]['scriptPubKey']['addresses'][0] == "$dash")
							return true;
		} else {
			die;
		}
		
		return false;
	}

	function cryptoID($name) {
		$massive = implode('', file("https://api.coinmarketcap.com/v2/listings/"));
		$massive = json_decode($massive, true);
		
		for($i =0; $i != count($massive['data']); $i++)
			if($massive['data'][$i]['symbol'] == "$name")
				return $massive['data'][$i]['id'];
	}

	function retPrice($crypto, $fiat) {
		$c_id = cryptoID($crypto);
		
		$massive = implode('', file("https://api.coinmarketcap.com/v2/ticker/$c_id/?convert=$fiat"));
		$massive = json_decode($massive, true);
		
		return $massive['data']['quotes']["$fiat"]['price']+$massive['data']['quotes']["$fiat"]['price']*5/100; // Add 5% to buy price	
	}
	
	function Conventer($fiat,$crypto,$fiat_type) {
		return 1*$fiat/retPrice($crypto,$fiat_type);
	}
	
	function SQL_Query($tp, $sql) {
		include 'settings.php';
		
		$conn = new mysqli($host, $user, $pass, "simplecryptoshop");
		$result = $conn->query($sql);
		if($tp == "full") {
			$row = $result->fetch_assoc();
			$conn->close();
			
			return $row;
		} else {
			$conn->close();
			
			return $result;
		}
	}
	
	function buyCheck() {
		if(isset($_POST["id"]) and is_numeric($_POST["id"]) and isset($_POST["crypto"]) and $_POST["crypto"] == "ethereum" or $_POST["crypto"] == "bitcoin" or $_POST["crypto"] == "monero" or $_POST["crypto"] == "litecoin" or $_POST["crypto"] == "dash") {}
		else {
			header("Location: /");
			die();
		}
		
		if (SQL_Query("full", "SELECT COUNT(*) FROM `Items` WHERE `id` =".$_POST["id"])['COUNT(*)']  == 0) {
			header("Location: /");
			die();
		}
	}
	
	function retCN($name) {
		switch($name) {
		case "ethereum":
			return "ETH";
			break;
			
		case "bitcoin":
			return "BTC";
			break;
			
		case "litecoin":
			return "LTC";
			break;
			
		case "monero":
			return "XMR";
			break;
			
		case "dash":
			return "DASH";
			break;
	}
	}

?>
