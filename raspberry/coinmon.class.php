<?php
class Coinmon
{
  public function getCryptoValueInEuro($crypto, $tryLeft = 5)
  {
    static $_cache = array();
    $_key = $crypto;
    if(array_key_exists($_key, $_cache))
    {
      return $_cache[$_key];
    }
    if($crypto == "ZEUR")
    {
      return 1;
    }
    if($crypto == "XBT")
    {
      $crypto = "BTC";
    }
    if($crypto == "IOTA")
    {
      $crypto = "MIOTA";
    }
    ob_start();
    $cmd = 'coinmon -c eur -f '.escapeshellarg($crypto).' | grep '.escapeshellarg($crypto);
    $output = array();
    exec($cmd, $output);
    ob_get_contents();
    ob_end_clean();

    $output = implode("\n", $output);
    $tmp = explode('│', $output);

    $price = trim($tmp[2]);
    //var_dump('before', $price);
    $price = preg_replace('![^0-9.,]!is', '', $price);

    //var_dump('replaced', $price);
    /*
    $price = preg_match('!([0-9.,]*)!is', $price, $matches);
    var_dump('matches', $matches);
    */
    $price = (float) $price;
    //var_dump($price);
    if($price == 0.0 && $tryLeft > 0)
    {
      var_dump($tmp);
      var_dump($cmd);
      var_dump($output);
      return $this->getCryptoValueInEuro($crypto, $tryLeft-1);
    }

    $_cache[$_key] = $price;
    return $price;
  }

  public function saveCalculatedValues($values)
  {
    $fileName = __DIR__.DIRECTORY_SEPARATOR.'coinsInfo.json';
    $cnt = $this->getCalculatedValues();
    $cnt[time()]=$values;
    if(count($cnt) < 5)
    {
      var_dump($cnt);
      var_dump($values);
      echo('not enought data, strange !!!');
      throw new Exception('not enought data, strange !!!');
    }
    file_put_contents($fileName, json_encode($cnt, JSON_PRETTY_PRINT));
  }


  public function decreaseFileContent()
  {
    $fileName = __DIR__.DIRECTORY_SEPARATOR.'coinsInfo.json';
    $cnt = $this->getCalculatedValues();

    $now = time();

    $oneHour = 3600;
    $oneDay = $oneHour*24;

    $lastDay = $now - 3600*24;
    $last3days = $now - 3600*24*3;
    $lastWeek = $now - 3600*24*7;
    $lastMonth = $now - 3600*24*30;
    $lastYear = $now - 3600*24*30;

    ksort($cnt, SORT_NUMERIC);
    $lastTime = null;
    foreach($cnt as $time=>$values)
    {
      $shouldKeep = false;
      if($lastTime === null)
      {
        $shouldKeep = true;
      }
      if( ! $shouldKeep) {
        $delta = $time - $lastTime;
        if ($time < $lastYear)//il y a plus d'un an
        {
          $shouldKeep = $delta >= $oneDay;
        } elseif ($time < $lastMonth)//entre un mois et un an
        {
          $shouldKeep = $delta >= ($oneHour * 12);
        } elseif ($time < $lastWeek)//entre une semaine et un mois
        {
          $shouldKeep = $delta >= ($oneHour * 6);
        } elseif ($time < $last3days) {
          $shouldKeep = $delta >= ($oneHour);
        } elseif ($time < $lastDay)//entre 1 et 3 jours
        {
          $shouldKeep = $delta >= (60 * 30);//30 minutes
        } else {
          $shouldKeep = true;//today : on garde tout
        }
      }
      if( ! $shouldKeep)
      {
        unset($cnt[$time]);
      }else{
        $lastTime = $time;
      }
    }

    file_put_contents($fileName, json_encode($cnt, JSON_PRETTY_PRINT));
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
