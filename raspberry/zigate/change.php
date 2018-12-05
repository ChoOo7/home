<?php
require(__DIR__ . "/../libs/broker.php");
$server = "127.0.0.1";     // change if necessary
$port = 1883;                     // change if necessary
$username = "";                   // set your username
$password = "";                   // set your password
$client_id = "phpMQTT-subscriber"; // make sure this is unique for connecting to sever - you could use uniqid()
$mqtt = new \Bluerhinos\phpMQTT($server, $port, $client_id);
if(!$mqtt->connect(true, NULL, $username, $password)) {
exit(1);
}

$payload = '{"function": "actions_move_hue_hex", "args": ["ee65", 3, "#FFFFFF"]}';
$mqtt->publish("zigate/command", $payload, 0);


$mqtt->close();