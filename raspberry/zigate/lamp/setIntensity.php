<?php

require(__DIR__."/include.php");
$addr = $argv[1];
$cluster = $argv[2];
$intensity = $argv[3];


$payload = '{"function": "action_move_level_onoff", "args": ["'.$addr.'", '.$cluster.', 1, '.$intensity.']}';
//var_dump($payload);
$mqtt->publish("zigate/command", $payload, 0);
$mqtt->close();