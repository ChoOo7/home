<?php

require(__DIR__."/include.php");


$payload = '{"function": "actions_move_temperature", "args": ["b89c", 11, 7400]}';
$mqtt->publish("zigate/command", $payload, 0);

$mqtt->close();