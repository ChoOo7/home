<?php
require(__DIR__ . "/../libs/broker.php");
require(__DIR__.'/../config/constants.php');
$server = "127.0.0.1";     // change if necessary
$port = 1883;                     // change if necessary
$username = "";                   // set your username
$password = "";                   // set your password


$startTime = time();
$ignoreStartDuration = 10;

/*
$client_id = "phpMQTT-subscriber.".__FILE__.'-'.uniqid(); // make sure this is unique for connecting to sever - you could use uniqid()
$mqtt = new \Bluerhinos\phpMQTT($server, $port, $client_id);
if(!$mqtt->connect(true, NULL, $username, $password)) {
  exit(1);
}



//client.publish('zigate/command', payload)

*/

function procmsg($topic, $msg){
  global $startTime, $ignoreStartDuration;

  if(time() - $ignoreStartDuration < $startTime)
  {
    echo "\nStarting, msg ignored\n";
    return ;
  }
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

  if(strpos($topic, '/'.AQARA_BTN_1_ADDR.'/') !== false)
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

  }elseif(strpos($topic, '/'.XIOMI_TEMPERATURE_ADDR.'/') !== false) {
    $decoded = json_decode($msg, true);
    
    switch(@$decoded['name'])
    {
      case "temperature":
        $temp = $decoded["value"];
        $cmd = 'php /var/home/raspberry/homecmd.php setTemperature '.escapeshellarg($temp);
        exec($cmd, $output);
        break;
      case "humidity":
        $temp = $decoded["value"];
        $cmd = 'php /var/home/raspberry/homecmd.php setHumidity '.escapeshellarg($temp);
        exec($cmd, $output);
        break;
      case "pressure":
        $temp = $decoded["value"];
        $cmd = 'php /var/home/raspberry/homecmd.php setPressure '.escapeshellarg($temp);
        exec($cmd, $output);
        break;
    }
  }
  elseif(strpos($topic, '/'.AQARA_BTN_2_ADDR.'/') !== false)
  {
    $decoded = json_decode($msg, true);

    echo "\nSwitch 2\t";
    if($decoded['name'] == "onoff") {
      if($decoded['data'] == false) {
        $cmd = 'php /var/home/raspberry/homecmd.php btn2-1';
        var_dump($cmd);
        exec($cmd, $output);
      }
    }
    if($decoded['name'] == "multiclick") {
      $value = $decoded['value'];
      if($value > 0) {
        $cmd = 'php /var/home/raspberry/homecmd.php btn2-' . $value;
        var_dump($cmd);
        exec($cmd, $output);
      }
    }
  }
  elseif(strpos($topic, '/'.IKEA_LAMP01_ADDR) !== false)
  {
    var_dump("Ikea lampe 1");
    $decoded = json_decode($msg, true);
    $lampState = array();
    //var_dump($decoded);
    if(array_key_exists("endpoints", $decoded))
    {
      $endpoints = $decoded["endpoints"];
      foreach($endpoints as $endpoint) {
        if (array_key_exists("clusters", $endpoint)) {
          $clusters = $endpoint["clusters"];
          foreach ($clusters as $cluster) {
            $attributes = $cluster["attributes"];
            foreach ($attributes as $attribute) {
              $name = $attribute["name"];
              $value = $attribute["value"];

              switch ($name) {
                case "onoff":
                  $lampState['onoff'] = $value;
                  break;
                case "current_level":
                  $lampState['current_level'] = $value;
                  break;
                case "colour_temperature":
                  $lampState['colour_temperature'] = $value;
                  break;
              }
            }
          }
        }
      }
    }

    if( ! empty($lampState)) {
      var_dump($lampState);
      $cmd = 'php /var/home/raspberry/homecmd.php storeLampState ' . IKEA_LAMP01_ADDR . ' ' . escapeshellarg(json_encode($lampState));
      passthru($cmd);
    }
    /*

string(1760) "{"endpoints": [{"out_clusters": [5, 25, 32, 4096], "endpoint": 1, "in_clusters": [0, 3, 4, 5, 6, 8, 768, 2821, 4096], "clusters": [{"attributes": [{"name": "zcl_version", "value": 1, "attribute": 0, "data": 1}, {"name": "application_version", "value": 17, "attribute": 1, "data": 17}, {"name": "stack_version", "value": 87, "attribute": 2, "data": 87}, {"name": "hardware_version", "value": 1, "attribute": 3, "data": 1}, {"name": "manufacturer", "value": "IKEA of Sweden", "attribute": 4, "data": "IKEA of Sweden"}, {"name": "type", "value": "TRADFRI bulb E27 WS opal 980lm", "attribute": 5, "data": "TRADFRI bulb E27 WS opal 980lm"}, {"name": "power_source", "value": 0, "attribute": 7, "data": 0}], "cluster": 0}, {"attributes": [{"name": "current_level", "value": 49, "attribute": 0, "data": 125}], "cluster": 8}, {"attributes": [{"name": "onoff", "value": true, "attribute": 0, "data": true}], "cluster": 6}, {"attributes": [{"name": "remaining_time", "value": 0, "attribute": 2, "data": 0}, {"name": "current_x", "value": 0.459869384765625, "attribute": 3, "data": 30138}, {"name": "current_y", "value": 0.4105987548828125, "attribute": 4, "data": 26909}, {"name": "colour_temperature", "value": 3003, "attribute": 7, "data": 333}, {"name": "colour_mode", "value": 2, "attribute": 8, "data": 2}, {"name": "capabilities", "value": "", "attribute": 16394, "data": ""}], "cluster": 768}], "profile": 49246, "device": 544}], "info": {"max_buffer": 82, "id": 50, "rssi": 113, "manufacturer": "117c", "descriptor_capability": "00000000", "addr": "7d56", "server_mask": 0, "mac_capability": "10001110", "max_rx": 82, "ieee": "90fd9ffffe8b41a6", "bit_field": "0100000000000001", "max_tx": 82, "power_type": 1, "last_seen": "2018-11-25 20:32:41"}, "addr": "7d56"}"
     */
  }
  elseif(stripos($topic, '/'.AQARA_PRESENCE_ADDR.'/') !== false){
    echo "\nCapteur présence 1\t";

    $decoded = json_decode($msg, true);
    switch(@$decoded['name'])
    {
      case 'luminosity':
        $temp = $value = $decoded['value'];
        $cmd = 'php /var/home/raspberry/homecmd.php setLuminosity '.escapeshellarg($temp);
        exec($cmd, $output);
        break;
      case 'presence':
        echo "\tPresence detectée\t";
        $temp = $value = $decoded['value'];
        $cmd = 'php /var/home/raspberry/homecmd.php movementDetected ';
        exec($cmd, $output);
        break;
    }


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