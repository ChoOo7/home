<?php
require(__DIR__."/include.php");
$payload = '{"function": "get_devices_list", "args": []}';
$mqtt->publish("zigate/command", $payload, 0);


$mqtt->close();