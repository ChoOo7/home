<?php
use Payward\KrakenAPI;

require_once(__DIR__.'/coinmon.class.php');


require_once(__DIR__.'/kraken.class.php');
require_once(__DIR__.'/binance.class.php');


$cm = new Coinmon();

$coins = array();
$coinsPrice = array();
$coinsPriceEur = array();

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'coins.config'), true);
if(array_key_exists("kraken", $config) && $config["kraken"]["key"]) {
  $key = $config["kraken"]["key"];
  $secret = $config["kraken"]["secret"];
  // set which platform to use (currently only beta is operational, live available soon)
  $beta = false;
  $url = $beta ? 'https://api.beta.kraken.com' : 'https://api.kraken.com';
  $sslverify = $beta ? false : true;
  $version = 0;
  $kraken = new KrakenAPI($key, $secret, $url, $version, $sslverify);


  /**
  Add a standard order: buy 2.12345678 BTCUSD @ limit $101.9901 with 2:1 leverage, with
  a follow up stop loss, take profit sell order: stop at -5% loss, take profit at
  +$10 price increase (signed stop/loss prices determined automatically using # notation):
   */
  $pair = "EOSXBT";
  $price = 0.0005453;
  $percentProfit = 0.25;
  $wantToBuyInEuro = 25;
  $btcPrice = $cm->getCryptoValueInEuro("BTC");

  $valueInBTC = $wantToBuyInEuro / $btcPrice;

  $valueInCurrency = $valueInBTC / $price;

  $res = $kraken->QueryPublic('Ticker', array('pair' => $pair));

  $actualPrice = $res['result'][$pair]['a'][0];

  $delta = abs(($actualPrice-$price) / $actualPrice);
  if( empty($delta) || $delta > 0.3 )
  {
    echo "\nDelta too hight. Delta : ".$delta.' - wanted price : '.$price.' - actualPrice : '.$actualPrice."\n";
    exit(1);
  }

  $increaseWanted = $percentProfit * $price;
/*
  $orderConfig = array(
      'pair' => $pair,
      'type' => 'buy',
      'ordertype' => 'limit',
      'price' => $price,
      'volume' => $valueInCurrency,
      'leverage' => '2:1',
      'close' => array(
          'ordertype' => 'stop-loss-profit-limit',
          'price' => '#5%',  // stop loss price (relative percentage delta)
          'price2' => '#'.$increaseWanted  // take profit price
      )
  );
*/

  $orderConfig = array(
      'pair' => $pair,
      'type' => 'buy',
      'ordertype' => 'limit',
      'price' => $price,
      'volume' => $valueInCurrency
  );
  var_dump($orderConfig);
  echo "\nSleeping 3\n";
  sleep(3);
  echo "\nQuering\n";
  $res = $kraken->QueryPrivate('AddOrder', $orderConfig);
  echo "\nResult\n";
  var_dump($res);
}