<?php
class Kodi
{

  protected $debug = false;

  public function getIp()
  {
    return "192.168.0.121:8080";
  }

  protected function sendCommand($url, $cmd = null, $async = true)
  {
    $fullUrl = 'http://'.$this->getIp().''.$url;
    $data = $cmd ? '--data-binary '.escapeshellarg($cmd) : '';
    
    $cmd = "curl -H 'Content-Type: application/json' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'X-Requested-With: XMLHttpRequest'  ".$data.' '.escapeshellarg($fullUrl).' 2>&1';
    if($async)
    {
      $cmd = ''.$cmd.' &';
    }
    if($this->debug)
    {
      echo "\n".$cmd;
    }
    $return = null;
    ob_start();
    exec($cmd, $return);
    ob_clean();
    ob_end_flush();
    if(is_array($return))
    {
      if($this->debug)
      {
        echo implode("\n", $return);
      }
      return json_decode(implode("\n", $return), true);
    }
    return $return;
  }

  public function clearPlaylist()
  {
    $cmd = '{"jsonrpc":"2.0","method":"Playlist.Clear","id":1,"params":[0]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }

  public function addArtistToPlaylist($artistId)
  {
    $cmd = '{"jsonrpc":"2.0","method":"Playlist.Add","id":1,"params":[0,{"artistid":'.$artistId.'}]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }

  public function playPlaylist()
  {
    $cmd = '{"jsonrpc":"2.0","method":"Player.Open","id":1,"params":[{"playlistid":0,"position":0}]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }
  
  public function setShuffle()
  {
    $cmd = '{"jsonrpc":"2.0","method":"Player.SetShuffle","id":1,"params":[0,"toggle"]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }

}
class MyDenon
{
  protected $debug = false;
  
  public function getIp()
  {
    return "192.168.0.120";
  }
  
  protected function sendCommand($url, $cmd = null, $async = true)
  {
    $fullUrl = 'http://'.$this->getIp().''.$url;
    $data = $cmd ? '--data-binary '.escapeshellarg($cmd) : '';
    $cmd = 'curl -s -H \'Content-Type: text/xml; charset="utf-8"\' '.$data.' '.escapeshellarg($fullUrl).' 2>&1';
    if($async)
    {
      $cmd = ''.$cmd.' &';
    }
    if($this->debug)
    {
      echo "\n".$cmd;
    }
    $return = null;
    ob_start();
    exec($cmd, $return);
    ob_clean();
    ob_end_flush();
    if(is_array($return))
    {
      if($this->debug)
      {
        echo implode("\n", $return);
      }
      return simplexml_load_string(implode("\n", $return));
    }
    return $return;
  }


  public function isPowerOff()
  {
    $state = $this->getState();
    $isOff = ((string)$state->Power->value) == 'STANDBY';

    return $isOff;
  }
  
  public function powerOnAndWaitForRead()
  {
    $isOff = $this->isPowerOff();
    if($isOff)
    {
      $this->powerOn();
      
      $lastVol = $this->getVolumeLevel();
      $volumeChanged = false;
      while( ! $volumeChanged)
      {
        $this->volumeUp(false);
        sleep(1);
        $vol = $this->getVolumeLevel();        
        $volumeChanged = ($vol != $lastVol);
        //Pour empecher que d'un coup le volume soit elevé
        if($lastVol <= 5)
        {
          $this->volumeSet(6);
        }else{
          $this->volumeSet(1);
        }
      }
    }
    $this->volumeSet(13);
  }
  
  public function getState()
  {
    $url = '/goform/formMainZone_MainZoneXml.xml';
    $result = $this->sendCommand($url, null, false);
    return $result;
  }

  public function powerOn()
  {
    $cmd = "cmd0=PutSystem_OnStandby/ON&ZoneName=MainZone";
    $url = "/MainZone/index.put.asp";
    $this->sendCommand($url, $cmd);
  }

  public function powerOff()
  {
    $cmd = "cmd0=PutSystem_OnStandby/STANDBY&ZoneName=MainZone";
    $url = "/MainZone/index.put.asp";
    $this->sendCommand($url, $cmd);
  }

  public function volumeUp($async = true)
  {
    $cmd = 'cmd0=PutMasterVolumeBtn/>&ZoneName=MainZone';
    $url = "/MainZone/index.put.asp";
    $this->sendCommand($url, $cmd, $async);
  }

  public function volumeDown($async = true)
  {
    $cmd = 'cmd0=PutMasterVolumeBtn/<&ZoneName=MainZone';
    $url = "/MainZone/index.put.asp";
    $this->sendCommand($url, $cmd, $async);
  }

  public function volumeSet($volume = 15)
  {
    $volume = -80+$volume;
    $cmd = 'cmd0=PutMasterVolumeSet/'.$volume.'&ZoneName=MainZone';
    $url = "/MainZone/index.put.asp";
    $this->sendCommand($url, $cmd);
  }

  public function getVolumeLevel()
  {

    $cmd = '<?xml version="1.0" encoding="utf-8"?>
<tx>
 <cmd id="1">GetAllZonePowerStatus</cmd>
 <cmd id="1">GetVolumeLevel</cmd>
 <cmd id="1">GetMuteStatus</cmd>
 <cmd id="1">GetSourceStatus</cmd>
</tx>
';   $cmd = '<?xml version="1.0" encoding="utf-8"?>
<tx>
 <cmd id="1">GetVolumeLevel</cmd>
</tx>
';
    $url = "/goform/AppCommand.xml";
    $result = $this->sendCommand($url, $cmd);
    return ((string)$result->cmd->dispvalue);
  }



  public function setRadio()
  {
    $cmd = 'cmd0=PutZone_InputFunction/IRADIO&ZoneName=MainZone';
    $url = "/MainZone/index.put.asp";
    $this->sendCommand($url, $cmd);
  }
  
  public function setFavorite($favNumber="01")
  {
    $cmd = "";
    $url = "/goform/formiPhoneAppFavorite_Call.xml?".$favNumber;
    $this->sendCommand($url, $cmd);
  }

  public function setBluetooth()
  {
    $cmd = 'cmd0=PutZone_InputFunction/BLUETOOTH&ZoneName=MainZone';
    $url = "/MainZone/index.put.asp";
    $this->sendCommand($url, $cmd);
  }

  public function setAnalogIn()
  {
    $cmd = 'cmd0=PutZone_InputFunction/ANALOGIN&ZoneName=MainZone';
    $url = "/MainZone/index.put.asp";
    $this->sendCommand($url, $cmd);
  }

  public function setDigitIn()
  {
    $cmd = 'cmd0=PutZone_InputFunction/DIGITALIN1&ZoneName=MainZone';
    $url = "/MainZone/index.put.asp";
    $this->sendCommand($url, $cmd);
  }  
}

$d = new MyDenon();
$k = new Kodi();

$cmd = $argv[1];
$param = @$argv[2];
switch($cmd)
{
  case "+":
  case "up":
  case "volumeUp":
    $d->volumeUp();
    break;
  case "++":
    $d->volumeUp();
    $d->volumeUp();
    break;
  case "+++":
    $d->volumeUp();
    $d->volumeUp();
    $d->volumeUp();
    break;
  case "-":
  case "do":
  case "dw":
  case "down":
  case "volumeDown":
    $d->volumeDown();
    break;
  case "--":
    $d->volumeDown();
    $d->volumeDown();
    break;
  case "---":
    $d->volumeDown();
    $d->volumeDown();
    $d->volumeDown();
    break;
  case "on":
    $d->powerOnAndWaitForRead();
    break;
  case "off":
    $d->powerOff();
    break;
  case "radio":
    $d->powerOnAndWaitForRead();
    $d->setRadio();
    $d->setVolume(15);
    break;
  case "favorite":
  case "fav":
    $d->powerOnAndWaitForRead();
    $d->setFavorite($param);
    break;
  case "vol":
  case "volume":
  case "getVolume":
    echo "\n".$d->getBluetooth()."\n";
    break;
  case "st":
  case "state":
  case "status":
  case "getState":
  case "getStatus":
    $st = $d->getState();
    echo "\n".$st->asXML()."\n";
    break;
  case "blue":
  case "bluetooth":
    $d->powerOnAndWaitForRead();
    $d->setBluetooth();
    break;
  case "analogic":
  case "ana":
  case "an":
    $d->powerOnAndWaitForRead();
    $d->setAnalogIn();
    break;
  case "digital":
  case "digit":
  case "dig":
  case "di":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    break;
  case "a":
  case "artist":
  case "art":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $k->clearPlaylist();
    $k->addArtistToPlaylist($param);
    $k->playPlaylist();
    break;
  case "cpt":
  case "comptine":
  case "comptines":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(2);
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(20);
    break;
}
/*
//$d->powerOff();
//sleep(10);
$d->getState();
$d->powerOnAndWaitForRead();

$d->volumeSet(20);
sleep(1);
$vol = $d->getVolumeLevel();
var_dump($vol);
sleep(1);
/*
$d->getState();
$vol = $d->getVolumeLevel();
var_dump($vol);
$d->volumeUp(false);
sleep(1);
$vol = $d->getVolumeLevel();
var_dump($vol);
*/
echo "\n";


//curl 'http://192.168.0.121:8080/jsonrpc' -H 'Pragma: no-cache' -H 'Origin: http://192.168.0.121:8080' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4,it;q=0.2' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36' -H 'Content-Type: application/json' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'Cache-Control: no-cache' -H 'X-Requested-With: XMLHttpRequest' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.121:8080/' -H 'DNT: 1' --data-binary '{"jsonrpc":"2.0","method":"Playlist.Clear","id":1,"params":[0]}' --compressed
//curl 'http://192.168.0.121:8080/jsonrpc' -H 'Pragma: no-cache' -H 'Origin: http://192.168.0.121:8080' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4,it;q=0.2' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36' -H 'Content-Type: application/json' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'Cache-Control: no-cache' -H 'X-Requested-With: XMLHttpRequest' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.121:8080/' -H 'DNT: 1' --data-binary '{"jsonrpc":"2.0","method":"Playlist.Add","id":1,"params":[0,{"artistid":2}]}' --compressed
//curl 'http://192.168.0.121:8080/jsonrpc' -H 'Pragma: no-cache' -H 'Origin: http://192.168.0.121:8080' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4,it;q=0.2' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36' -H 'Content-Type: application/json' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'Cache-Control: no-cache' -H 'X-Requested-With: XMLHttpRequest' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.121:8080/' -H 'DNT: 1' --data-binary '{"jsonrpc":"2.0","method":"Player.Open","id":1,"params":[{"playlistid":0,"position":0}]}' --compressed
