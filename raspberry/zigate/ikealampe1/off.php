<?php
require(__DIR__."/include.php");

$payload = '{"function": "action_onoff", "args": ["7d56", 1, 0]}';
$mqtt->publish("zigate/command", $payload, 0);


$mqtt->close();