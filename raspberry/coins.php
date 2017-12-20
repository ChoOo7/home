<?php
require_once(__DIR__.'/coinmon.class.php');


$coins = array(
    'CDT'=>199.8,
    'XRP'=>56.97108000,
    'KMD'=>4.55544000,
    'ETH'=>0.08138297,
    'LTC'=>0.14985000,
    'BTC'=>0.00317487

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
