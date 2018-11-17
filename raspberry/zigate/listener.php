<?php
require(__DIR__."/broker.php");
$server = "127.0.0.1";     // change if necessary
$port = 1883;                     // change if necessary
$username = "";                   // set your username
$password = "";                   // set your password


/*
$client_id = "phpMQTT-subscriber.".__FILE__.'-'.uniqid(); // make sure this is unique for connecting to sever - you could use uniqid()
$mqtt = new \Bluerhinos\phpMQTT($server, $port, $client_id);
if(!$mqtt->connect(true, NULL, $username, $password)) {
  exit(1);
}



//client.publish('zigate/command', payload)

*/

function procmsg($topic, $msg){
  //echo "Msg Recieved: " . date("r") . "\n";
  //echo "Topic: {$topic}\n\n";
  //echo "\t$msg\n\n";
  global $mqtt;
  if($topic == 'zigate/command/result')
  {
    var_dump($topic);
    var_dump($msg);
    var_dump("______");
  }else{

    var_dump($topic);
    var_dump($msg);
    var_dump("XXXX");
  }

  if(strpos($topic, '0cba') !== false)
  {
    $decoded = json_decode($msg, true);

    echo "\nSwitch 1\t";
    if($decoded['name'] == "onoff") {
      if($decoded['data'] == false) {
        $cmd = 'php /var/home/raspberry/homecmd.php btn1-1';
        var_dump($cmd);
        exec($cmd, $output);
      }
    }
    if($decoded['name'] == "multiclick") {
      $value = $decoded['value'];
      if($value > 0) {
        $cmd = 'php /var/home/raspberry/homecmd.php btn1-' . $value;
        var_dump($cmd);
        exec($cmd, $output);
      }
    }

  }elseif($topic == 'zigate/attribute_changed/62ce/01/0006/0000')
  {

    $decoded = json_decode($msg, true);
    if($decoded['data'] == false) {
      echo "\nSwitch 2\t";
      $cmd = 'php /var/home/raspberry/homecmd.php dashchangeledstate';
      exec($cmd, $output);
    }
  }elseif($topic == 'zigate/attribute_changed/be02/01/0400/0000') {
    echo "\nCapteur 1\t";

    $decoded = json_decode($msg, true);
    $luminosity = $decoded['value'];

    echo "\tPresence detectée\t";

    $cmd = 'php /var/home/raspberry/homecmd.php ledStripe2Toogle';
    exec($cmd, $output);
  }else{
    //var_dump($topic);

  }
}

/*

$topics = array();
$topics['zigate/attribute_changed/fd1d/01/0006/0000'] = array("qos" => 0, "function" => "procmsg");
$mqtt->subscribe($topics, 0);


$client_id = "phpMQTT-subscriber.".__FILE__.'-'.uniqid(); // make sure this is unique for connecting to sever - you could use uniqid()
$mqtt2 = new \Bluerhinos\phpMQTT($server, $port, $client_id);
if(!$mqtt2->connect(true, NULL, $username, $password)) {
  exit(1);
}
$topics = array();
$topics['zigate/attribute_changed/62ce/01/0006/0000'] = array("qos" => 0, "function" => "procmsg");
$mqtt2->subscribe($topics, 0);



$client_id = "phpMQTT-subscriber.".__FILE__.'-'.uniqid(); // make sure this is unique for connecting to sever - you could use uniqid()
$mqtt3 = new \Bluerhinos\phpMQTT($server, $port, $client_id);
if(!$mqtt3->connect(true, NULL, $username, $password)) {
  exit(1);
}
$topics = array();
$topics['zigate/attribute_changed/be02/01/0400/0000'] = array("qos" => 0, "function" => "procmsg");
$mqtt3->subscribe($topics, 0);

while(true)
{
  //echo "avant proc";
  $mqtt->proc(true);
  $mqtt2->proc(true);
  $mqtt3->proc(true);
  //usleep(10000);
  //echo "apres proc";
}

$mqtt->close();
*/

define('BROKER', 'localhost');
define('PORT', 1883);
define('CLIENT_ID', "pubclient_" + getmypid());

$client = new Mosquitto\Client(CLIENT_ID);
$client->onConnect('connect');
$client->onDisconnect('disconnect');
$client->onSubscribe('subscribe');
$client->onMessage('message');
$client->connect(BROKER, PORT, 60);
$client->subscribe('#', 1); // Subscribe to all messages

$client->loopForever();

function connect($r) {
  echo "Received response code {$r}\n";
}

function subscribe() {
  echo "Subscribed to a topic\n";
}

function message($message) {
  //printf("Got a message on topic %s with payload:\n%s\n", $message->topic, $message->payload);
  procmsg($message->topic, $message->payload);
}

function disconnect() {
  echo "Disconnected cleanly\n";
}