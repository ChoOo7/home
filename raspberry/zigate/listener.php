<?php
require(__DIR__."/broker.php");
$server = "127.0.0.1";     // change if necessary
$port = 1883;                     // change if necessary
$username = "";                   // set your username
$password = "";                   // set your password
$client_id = "phpMQTT-subscriber.".__FILE__.'-'.uniqid(); // make sure this is unique for connecting to sever - you could use uniqid()
$mqtt = new \Bluerhinos\phpMQTT($server, $port, $client_id);
if(!$mqtt->connect(true, NULL, $username, $password)) {
exit(1);
}



//client.publish('zigate/command', payload)



function procmsg($topic, $msg){
  //echo "Msg Recieved: " . date("r") . "\n";
  //echo "Topic: {$topic}\n\n";
  //echo "\t$msg\n\n";
  global $mqtt;
  if($topic == 'zigate/attribute_changed/fd1d/01/0006/0000')
  {
    $decoded = json_decode($msg, true);
    if($decoded['data'] == false)
    {
      echo "CLICKED";
      $payload = '{"function": "action_onoff", "args": ["ee65", 3, 2]}';
      $mqtt->publish("zigate/command", $payload, 0);
    }
  }
}




$topics['zigate/attribute_changed/fd1d/01/0006/0000'] = array("qos" => 0, "function" => "procmsg");
$mqtt->subscribe($topics, 0);

while(true)
{
  //echo "avant proc";
  $mqtt->proc(true);
  //usleep(10000);
  //echo "apres proc";
}

$mqtt->close();