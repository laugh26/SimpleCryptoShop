<?php

	function Find_Payment($tx_id, $crypto, $price) {
		include 'settings.php';
		
		try {
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
				return;
			}
		} catch (Exception $e) {
			return;
		}
	}

	function cryptoID($names) {
		$ids = [];
		$massive = implode('', file("https://api.coinmarketcap.com/v2/listings/"));
		$massive = json_decode($massive, true);
		
		for ($i=0; $i != count($massive['data']); $i++)
			if (in_array($massive['data'][$i]['symbol'], $names))
				array_push($ids, $massive['data'][$i]['id']);

		return $ids;
	}

	function retPrice($cryptos, $fiat) {
		$ids = cryptoID($cryptos);
		$prices = [];

		foreach ($ids as $id) {
			$massive = implode('', file("https://api.coinmarketcap.com/v2/ticker/$id/?convert=$fiat"));
			$massive = json_decode($massive, true)['data']['quotes']["$fiat"]['price'];
			array_push($prices, round($massive-$massive*5/100, 2));
		}

		return $prices;
	}
	
	function Conventer($fiat, $crypto, $fiat_type) {
		return 1 * $fiat / retPrice($crypto, $fiat_type);
	}

?>
