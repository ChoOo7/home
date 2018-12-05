<?php

require(__DIR__."/include.php");
$deviceIndex = $argv[1];
$deviceState = $argv[2];


$payload = '{"deviceIndex": '.$deviceIndex.', "deviceState": '.$deviceState.'}';

$mqtt->publish("broadlink/command", $payload, 0);
var_dump("broadlink/command");
var_dump($payload);
$mqtt->close();