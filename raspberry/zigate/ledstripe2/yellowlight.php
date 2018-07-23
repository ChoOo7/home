<?php

require(__DIR__."/include.php");


$payload = '{"function": "actions_move_hue_hex", "args": ["ee65", 3, "#FFFF99"]}';
$mqtt->publish("zigate/command", $payload, 0);

$payload = '{"function": "action_move_level_onoff", "args": ["ee65", 3, 1, 10]}';
$mqtt->publish("zigate/command", $payload, 0);


$mqtt->close();