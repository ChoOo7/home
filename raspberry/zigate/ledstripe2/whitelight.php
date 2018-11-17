<?php
require(__DIR__."/include.php");
/*
$payload = '{"function": "actions_move_hue_hex", "args": ["ee65", 3, "#FFFFFF", 3]}';
$mqtt->publish("zigate/command", $payload, 0);

$payload = '{"function": "action_move_level_onoff", "args": ["ee65", 3, 1, 10, 3]}';
$mqtt->publish("zigate/command", $payload, 0);
*/
/*
$payload = '{"function": "actions_move_hue_hex", "args": ["ee65", 3, "#FFFFFF"]}';
$mqtt->publish("zigate/command", $payload, 0);

$payload = '{"function": "action_move_level_onoff", "args": ["ee65", 3, 1, 100]}';
$mqtt->publish("zigate/command", $payload, 0);
*/
$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 360, 0]}';
$mqtt->publish("zigate/command", $payload, 0);


$payload = '{"function": "action_move_level_onoff", "args": ["ee65", 3, 1, 100]}';
$mqtt->publish("zigate/command", $payload, 0);

/*
$payload = '{"function": "actions_move_temperature", "args": ["ee65", 3, 3000]}';
$mqtt->publish("zigate/command", $payload, 0);
*/

$mqtt->close();