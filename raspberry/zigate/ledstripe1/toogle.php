<?php
require(__DIR__."/include.php");

$payload = '{"function": "action_onoff", "args": ["b89c", 11, 2]}';
$mqtt->publish("zigate/command", $payload, 0);


$mqtt->close();