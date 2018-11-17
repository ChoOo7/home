<?php
require(__DIR__."/include.php");

$payload = '{"function": "action_move_level_onoff", "args": ["ee65", 3, 1, 100]}';
$mqtt->publish("zigate/command", $payload, 0);



$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 360, 0]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(5);

//bleu
$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 180, 100]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(1);
//blanc
$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 360, 0]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(1);

//bleu
$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 180, 100]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(1);

//blanc
$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 360, 0]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(5);
/*
$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 180, 100]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(1);

$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 360, 0]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(1);

/*
$payload = '{"function": "actions_move_hue_hex", "args": ["ee65", 3, "#FFFFFF"]}';
$mqtt->publish("zigate/command", $payload, 0);

sleep(1);

$payload = '{"function": "actions_move_hue_hex", "args": ["ee65", 3, "#FFFFFF"]}';
$mqtt->publish("zigate/command", $payload, 0);

sleep(1);
*/
$payload = '{"function": "action_move_level_onoff", "args": ["ee65", 3, 1, 100]}';
$mqtt->publish("zigate/command", $payload, 0);

sleep(1);

$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 0, 10]}';
$mqtt->publish("zigate/command", $payload, 0);

sleep(1);

$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 0, 100]}';
$mqtt->publish("zigate/command", $payload, 0);

sleep(1);

$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 180, 10]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(1);

$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 180, 100]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(1);

$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 360, 0]}';
$mqtt->publish("zigate/command", $payload, 0);
sleep(1);

$payload = '{"function": "actions_move_hue_saturation", "args": ["ee65", 3, 360, 100]}';
$mqtt->publish("zigate/command", $payload, 0);



$mqtt->close();

$mqtt->close();