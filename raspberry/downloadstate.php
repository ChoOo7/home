<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$return = array();



$lastLogs = array();
exec("tail -n 10 /var/home/raspberry/preloadLog", $lastLogs);

foreach($lastLogs as $k=>$line)
{
  $tmp = explode("\r", $line);
  $last = $tmp[count($tmp)-1];
  $lastLogs[$k]=$last;
  //var_dump($last);
}
$return['logs'] = $lastLogs;

$speedConfigDir="/var/home/raspberry/config/";
$actualSpeed = file_get_contents($speedConfigDir."bandwidth");
if(file_exists($speedConfigDir."bandwidthNow"))
{
  $actualSpeed = file_get_contents($speedConfigDir."bandwidthNow");
}
$actualSpeed = trim($actualSpeed);

$return['speed'] = $actualSpeed;

echo json_encode($return);
