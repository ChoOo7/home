<?php
require(__DIR__."/include.php");
$addr = $argv[1];

$payload = '{"function": "get_device_from_addr", "args": ["'.$addr.'"]}';
$mqtt->publish("zigate/command", $payload, 0);

$mqtt->close();