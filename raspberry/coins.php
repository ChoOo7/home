<?php
require_once(__DIR__.'/coinmon.class.php');


$coins = array(
    'CDT'=>49.80000000,
    'XRP'=>56.97108000,
    'KMD'=>5.00044000,
    'ETH'=>0.00083027,
    'LTC'=>0.17982000,
    'XMR'=>0.07992000,
    'BTC'=>0.00392 + 0.00524844

);

$cm = new Coinmon();

$output = array();

$totalValue = 0.0;
foreach($coins as $cryptoName=>$coinNumber)
{
  $valueInEuro = 0;

  $valueInEuro = $cm->getCryptoValueInEuro($cryptoName);
  if($valueInEuro == 0)
  {
    $valueInEuro = $cm->getCryptoValueInEuro($cryptoName);
  }
  $valueInEuro = $cm->getCryptoValueInEuro($cryptoName);
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
