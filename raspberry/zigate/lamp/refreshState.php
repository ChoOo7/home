<?php

require(__DIR__."/include.php");
$addr = $argv[1];
$cluster = $argv[2];


//onoff
$payload = '{"function": "read_attribute_request", "args": ["'.$addr.'", '.$cluster.', 6, 0]}';
$mqtt->publish("zigate/command", $payload, 0);


//level
$payload = '{"function": "read_attribute_request", "args": ["'.$addr.'", '.$cluster.', 8, 0]}';
$mqtt->publish("zigate/command", $payload, 0);


//couleur
$payload = '{"function": "read_attribute_request", "args": ["'.$addr.'", '.$cluster.', 768, 7]}';
$mqtt->publish("zigate/command", $payload, 0);


$mqtt->close();
