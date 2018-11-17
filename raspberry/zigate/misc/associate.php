<?php
require(__DIR__."/include.php");
$payload = '{"function": "permit_join", "args": []}';
$mqtt->publish("zigate/command", $payload, 0);


$mqtt->close();