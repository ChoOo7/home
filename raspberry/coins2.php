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

  $isOk = false;
  $tryLeft = 10;
  do {
    $tryLeft--;
    $res = $kraken->QueryPrivate('Balance');
    if(array_key_exists("result", $res) &&  ! empty($res['result']))
    {
      $isOk = true;
      foreach($res['result'] as $name=>$value)
      {
        $name = preg_replace('!^X([a-zA-Z0-9]+)$!', '$1', $name);
        //$newName = str_replace('XX', 'X', $name);
        if($name == "XBT") {
          $name = "BTC";
        }
        if( ! array_key_exists($name, $coins))
        {
          $coins[$name] = 0.0;
        }
        $coins[$name] += $value;
      }
    }
  } while($tryLeft > 0 && ! $isOk);
  if( ! $isOk)
  {
    exit(1);
  }
}


if(array_key_exists("binance", $config) && $config["binance"]["key"]) {
  $key = $config["binance"]["key"];
  $secret = $config["binance"]["secret"];

  $api = new Binance\API($key, $secret);
  $ticker = $api->prices();
  $balances = $api->balances($ticker);
  foreach($balances as $name=>$info)
  {

    $av = $info['available'];
    if($av == 0.0)
    {
      continue;
    }
    if($name == "XBT") {
      $name = "BTC";
    }


    if( ! array_key_exists($name, $coinsPrice))
    {
      $coinsPrice[$name] = $info['btcTotal'] / $av;
    }

    if( ! array_key_exists($name, $coins))
    {
      $coins[$name] = 0.0;
    }
    //var_dump($info);die();
    $coins[$name] += $av;
  }
}

if( ! empty($coinsPrice))
{
  $btcPrice = $cm->getCryptoValueInEuro("BTC");
  foreach($coinsPrice as $name=>$btcValue)
  {
    $coinsPriceEur[$name] = $btcValue * $btcPrice;
  }
}

$output = array();

$totalValue = 0.0;
foreach($coins as $cryptoName=>$coinNumber)
{
  $valueInEuro = 0;

  $valueInEuro = array_key_exists($cryptoName, $coinsPriceEur) ? $coinsPriceEur[$cryptoName] : 0;
  if($valueInEuro == 0)
  {
    $valueInEuro = $cm->getCryptoValueInEuro($cryptoName);
  }
  if($valueInEuro == 0)
  {
    continue;
  }

  $valueOfMyCoins = $coinNumber * $valueInEuro;
  $output[$cryptoName] = array(
      'number'=>$coinNumber,
      'valueInEuro'=>$valueInEuro,
      'valueOfMyCoins'=>$valueOfMyCoins,
  );
  $totalValue += $valueOfMyCoins;
}

$cm->saveCalculatedValues($output);

var_dump($output);
var_dump($totalValue);
?>
