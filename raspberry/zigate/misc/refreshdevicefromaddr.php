<?php
require(__DIR__."/include.php");
$addr = $argv[1];

$payload = '{"function": "refresh_device", "args": ["'.$addr.'"]}';
$mqtt->publish("zigate/command", $payload, 0);

$mqtt->close();