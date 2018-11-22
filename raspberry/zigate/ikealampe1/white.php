<?php
require(__DIR__."/include.php");

$payload = '{"function": "actions_move_temperature", "args": ["7d56", 1, 7400]}';
$mqtt->publish("zigate/command", $payload, 0);

$mqtt->close();