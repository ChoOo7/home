<?php
require_once(__DIR__.'/config/constants.php');
require_once(__DIR__.'/lastFact.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$return = array();

foreach(array(LastFact::$PRESENCE_DETECTOR, LastFact::$TEl_SIM_CONNECTED, LastFact::$TEl_SO_CONNECTED, LastFact::$TEL_SIM_POS_HOME) as $type)
{
  $t = LastFact::getInstance($type);
  $subdata = $t->getData();
  foreach($subdata as $k=>$v)
  {
    if(stripos('timestamp', $k) ===false)
    {
      $subdata[$k.'_timestamp'] = strtotime($v);
    }
  }
  $return[$type] = $subdata;
}

echo json_encode($return);
