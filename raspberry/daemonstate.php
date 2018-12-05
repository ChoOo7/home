<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$return = array();

function checkProcess($processName)
{
  $execOutput = array();
  exec("ps aux | grep ".escapeshellarg($processName).' | grep -v grep', $execOutput);
  return ! empty($execOutput);
}


$return['zigate_listener'] = checkProcess("zigate/listener.php");
if( ! $return['zigate_listener'])
{
  $cmd = "nohup php ".__DIR__.'/homecmd.php restartZigateListener > /dev/null &';
  exec($cmd);
}
$return['zigate_broker'] = checkProcess("zigate.mqtt_broker");
if( ! $return['zigate_broker'])
{
  $cmd = "nohup php ".__DIR__.'/homecmd.php restartZigateBroker > /dev/null &';
  exec($cmd);
}

$return['broadlinkbroker'] = checkProcess("broadlinkbroker");
if( ! $return['broadlinkbroker'])
{
  $cmd = "nohup php ".__DIR__.'/homecmd.php restartBroadlinkBroker > /dev/null &';
  exec($cmd);
}

$execOutput = array();
exec("timeout 1 mpc | grep volume", $execOutput);
$return['mpc'] = ! empty($execOutput);

$execOutput = array();
exec("timeout 1  mpc --port=6601 | grep volume", $execOutput);
$return['mmpc'] = ! empty($execOutput);


$logFile = "/var/log/zilistener";
exec("tail -n 150 ".escapeshellarg($logFile), $output);
$return['zigateLog'] = implode("<br />", $output);

echo json_encode($return);
