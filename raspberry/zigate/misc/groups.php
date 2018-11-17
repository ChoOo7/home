<?php
require(__DIR__."/include.php");

$groupAddr = "0b71";

echo "\nadd group to light 1";
$payload = '{"function": "add_group", "args": ["b89c", 11, "'.$groupAddr.'"]}';
$mqtt->publish("zigate/command", $payload, 0);

echo "\nadd group to light 2";
$payload = '{"function": "add_group", "args": ["ee65", 3, "'.$groupAddr.'"]}';
$mqtt->publish("zigate/command", $payload, 0);

echo "\nadd group to switch 1";
$payload = '{"function": "add_group", "args": ["fd1d", 1, "'.$groupAddr.'"]}';
$mqtt->publish("zigate/command", $payload, 0);

echo "\nview grouips";
$payload = '{"function": "get_group_membership", "args": ["b89c", 11]}';
$mqtt->publish("zigate/command", $payload, 0);

echo "\nview grouips";
$payload = '{"function": "get_group_membership", "args": ["fd1d", 1]}';
$mqtt->publish("zigate/command", $payload, 0);

echo "\nview grouips";
$payload = '{"function": "get_group_membership", "args": ["ee65", 3]}';
$mqtt->publish("zigate/command", $payload, 0);


echo "\nfin\n";
$mqtt->close();