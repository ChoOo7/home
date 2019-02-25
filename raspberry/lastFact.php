<?php

class LastFact
{

  public static $TEl_SO_CONNECTED = 'tel_so_connect';
  public static $TEl_SIM_CONNECTED = 'tel_sim_connect';

  public static $TEL_SIM_POS_HOME = 'tel_sim_pos_home';
  public static $TEL_SO_POS_HOME = 'tel_so_pos_home';
  
  public static $PRESENCE_DETECTOR = 'presence_detector';

  public static $actionTimeout = 600;


  public $factName;
  public function LastFact($factName)
  {
    $this->factName = $factName;
  }

  public static function  getInstance($factName) {
    return new self($factName);
  }

  public function setLastConnectionDate($date = null)
  {
    if($date == null)
    {
      $date = time();
    }

    $data = $this->getData();
    $lastAction = $data['last_action'];
    if( ! $lastAction || (strtotime($lastAction) + self::$actionTimeout) < time())
    {
      $data['action_start'] = date('Y-m-d H:i:s', $date);
    }
    $data['last_action'] = date('Y-m-d H:i:s', $date);

    file_put_contents($this->getFileName(), json_encode($data));
    chmod($this->getFileName(), 0777);
  }

  public function getFileName()
  {
    return __DIR__.'/'.'data'.'/'.$this->factName;
  }

  public function getData()
  {
    $data = json_decode(file_get_contents($this->getFileName()), true);
    if( ! is_array($data))
    {
      $data = array(
        'last_action'=>null,
        'action_start'=>null
      );
    }
    return $data;
  }


  public static function IsIpOnLan($ip)
  {

    $cmd = '(arp -d '.$ip.' || echo 1 ) && ping -c 1 '.$ip.' && sleep 3 && cat /proc/net/arp | grep '.$ip;
    exec($cmd, $output);
    var_dump($output);
    foreach($output as $line)
    {
      if(stripos($line, $ip) !== false && stripos($line, "eth0") !== false)
      {
        return true;
      }
    }
    return false;
  }
}