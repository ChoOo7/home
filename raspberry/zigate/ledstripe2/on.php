<?php
require(__DIR__."/include.php");

$payload = '{"function": "action_onoff", "args": ["ee65", 3, 1]}';
$mqtt->publish("zigate/command", $payload, 0);


$mqtt->close();