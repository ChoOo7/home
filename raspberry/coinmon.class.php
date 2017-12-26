<?php
class Coinmon
{
  public function getCryptoValueInEuro($crypto, $tryLeft = 5)
  {
    if($crypto == "ZEUR")
    {
      return 1;
    }
    if($crypto == "XBT")
    {
      $crypto = "BTC";
    }
    ob_start();
    $cmd = 'coinmon -c eur -f '.escapeshellarg($crypto).' | grep '.escapeshellarg($crypto);
    $output = array();
    exec($cmd, $output);
    ob_get_contents();
    ob_end_clean();

    $output = implode("\n", $output);
    $tmp = explode('│', $output);

    $price = (float)trim($tmp[2]);
    if($price == 0.0 && $tryLeft > 0)
    {
      var_dump($cmd);var_dump($output);
      return $this->getCryptoValueInEuro($crypto, $tryLeft-1);
    }

    return $price;
  }

  public function saveCalculatedValues($values)
  {
    $fileName = __DIR__.DIRECTORY_SEPARATOR.'coinsInfo.json';
    $cnt = $this->getCalculatedValues();
    $cnt[time()]=$values;
    file_put_contents($fileName, json_encode($cnt));
  }

  public function getCalculatedValues()
  {
    $fileName = __DIR__.DIRECTORY_SEPARATOR.'coinsInfo.json';
    $cnt = file_get_contents($fileName);
    $cnt = json_decode($cnt, true);
    if( ! is_array($cnt))
    {
      $cnt = array();
    }
    return $cnt;
  }

}
