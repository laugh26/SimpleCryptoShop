<?php
	error_reporting(E_ALL ^ E_WARNING);

	include 'db.php';

	function Find_Payment($tx_id, $crypto, $price) {
		$wallets = DataBase('SELECT * FROM `wallets`');
		
		try {
			if($crypto == "XMR") {
				$var = file("https://xmrchain.net/myoutputs/$tx_id/".$wallets['XMR'].'/'.$wallets['XMRV']);

				for($i=160; $i != sizeof($var); $i++)
					if(strstr($var[$i], $price))
						$buyed = true;
			} else if($crypto == "ETH") {
				$massive = implode('', file("https://api.ethplorer.io/getTxInfo/$tx_id?apiKey=freekey"));
				$massive = json_decode($massive, true);
				
				if($massive['to'] == $wallets[$crypto])
					if($massive['value'] >= $price)
						return true;
			} else if($crypto == "BTC") {
				$massive = implode('', file("https://blockchain.com/rawtx/$tx_id"));
				$massive = json_decode($massive, true);
				
				foreach($massive['out'] as $out)
					if ($out['addr'] == $wallets[$crypto] && $out['value'] >= $price*10**8)
						return true;
			} else if($crypto == "LTC") {
				$massive = implode('', file("https://chainz.cryptoid.info/explorer/tx.raw.dws?coin=ltc&id=$tx_id&fmt.js"));
				$massive = json_decode($massive, true);
				
				foreach($massive['vout'] as $out)
					if($out['scriptPubKey']['addresses'][0] == $wallets[$crypto] && $out['value'] >= $price)
						return true;
			} else if($crypto == "DASH") {
				$massive = implode('', file("https://chainz.cryptoid.info/explorer/tx.raw.dws?coin=dash&id=$tx_id&fmt.js"));
				$massive = json_decode($massive, true);
				
				foreach($massive['vout'] as $out)
					if($out['scriptPubKey']['addresses'][0] == $wallets[$crypto] && $out['value'] >= $price)
						return true;
			}

			return false;
		} catch (Exception $e) {
			return false;
		}
	}

	function cryptoID($names) {
		$ids = [];
		$massive = implode('', file("https://api.coinmarketcap.com/v2/listings/"));
		$massive = json_decode($massive, true);

		foreach($massive['data'] as $out)
			if (in_array($out['symbol'], $names))
				array_push($ids, $out['id']);

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
		return 1 * (float)$fiat / retPrice($crypto, $fiat_type)[0];
	}

	function get_item($id) {
		$new = '';
		$items = DataBase('SELECT `content`, `quantity` FROM `items` WHERE `id` = '.$id);
		$count = $items['quantity'] - 1;
		$items = explode(PHP_EOL, $items['content']);
		$item = $items[0];
		unset($items[0]);

		foreach($items as $itm) {
			$new .= $itm.'\n';
		}

		$new = substr($new, 0, strlen($new)-2);

		UpdateDB('items', ['content', 'quantity'], [escpe_val($new), $count]);
		return $item;
	}

	function back_item($id, $item) {
		$items = DataBase('SELECT `content`, `quantity` FROM `items` WHERE `id` = '.$id);
		$count = $items['quantity'] + 1;
		UpdateDB('items', ['content', 'quantity'], [escpe_val($items['content']."\n".$item), $count]);
	}

?>
