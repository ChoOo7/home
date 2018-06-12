<?php
class MyDenon
{
  protected $debug = false;
  
  public function getIp()
  {
    return "192.168.0.202";
  }
  
  protected function sendCommand($url, $cmd = null, $async = true)
  {
    $fullUrl = 'http://'.$this->getIp().''.$url;
    $data = $cmd ? '--data-binary '.escapeshellarg($cmd) : '';
    $cmd = 'timeout 3 curl -s -H \'Content-Type: text/xml; charset="utf-8"\' '.$data.' '.escapeshellarg($fullUrl).' 2>&1';
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
  
  public function powerOnAndWaitForRead($callback = null, $callbackParams = array())
  {
    $isOff = $this->isPowerOff();
    if($isOff)
    {
      $this->powerOn();
      
      $lastVol = $this->getVolumeLevel();
      $volumeChanged = false;
      $counter = 0;
      while( ! $volumeChanged && $counter < 100)
      {
        $counter++;
        $this->volumeUp(false);
        sleep(1);
        $vol = $this->getVolumeLevel();        
        $volumeChanged = ($vol != $lastVol);
        //Pour empecher que d'un coup le volume soit elevé
        if($lastVol <= 5)
        {
          $this->volumeSet(6);
        }else{
          $this->volumeSet(10);
        }
        if($callback && $counter == 3)
        {
          call_user_func_array($callback, $callbackParams);
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
    $this->setRadio();
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
