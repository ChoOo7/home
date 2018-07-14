<?php
class Smartphone
{
  protected $debug = false;

  public function getIp()
  {
    //lenovo
    return "192.168.0.205";

    //ZOPO
    return "192.168.0.208";
  }

  public function getBroadlinkApiEndpoind()
  {
    return $this->getIp().":9192";
  }

  public function getFanMac()
  {
    return "34:ea:34:f4:c6:92";
  }

  public function getFanState($tryLeft = 3)
  {
    $url = "http://".$this->getBroadlinkApiEndpoind().'/'.$this->getFanMac().'/socketState/';
    $output = array();
    $cmd = "timeout 4 curl -s ".escapeshellarg($url);
    exec($cmd, $output);
    if(count($output) != 1)
    {
      if($tryLeft > 1) {
        return $this->getFanState($tryLeft - 1);
      }else{
        //echo "ERROR";
        return false;
      }
    }
    return $output[0] == '"1"';
    /*
    var_dump($output);
    $cnt = file_get_contents($url);
    var_dump($cnt);
    return $cnt == '"1"';
    */
  }

  public function toogleFanState()
  {
    $state = $this->getFanState();

    $this->setFanState( ! $state);
  }

  public function setFanState($up = "1")
  {
    $state = ($up !== false && $up !== "0" && $up !== "false") ? "1" : "0";
    $url = "http://".$this->getBroadlinkApiEndpoind().'/'.$this->getFanMac().'/socketState/'.$state.'/';
    $cnt = file_get_contents($url);
    var_dump($cnt);
  }
}
