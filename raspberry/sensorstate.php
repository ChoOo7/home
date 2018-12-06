<?php
require_once(__DIR__.'/config/constants.php');



function launchInBackground($command)
{
  $output = "/tmp/log";
  @chmod($output, 0777);
  $command = "nohup ".$command ." > ".$output." 2>&1 &";
  //$cmd = 'nohup '.$cmd.' > /tmp/output &';
  system($command);
}

$command = "php /var/home/raspberry/zigate/lamp/refreshState.php ".IKEA_LAMP01_ADDR." ".IKEA_LAMP01_CLUSTER;
launchInBackground($command);

sleep(2);//On attend la MAJ des données par la zigate

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$return = array();
$dataDirectory = __DIR__.'/data/';
foreach(glob($dataDirectory.'/*') as $filepath)
{
  $name = basename($filepath);
  $value = file_get_contents($filepath);

  $name = str_replace(IKEA_LAMP01_ADDR.'-', 'ikeaLamp1_', $name);

  $return[$name] = $value;
}

echo json_encode($return);
