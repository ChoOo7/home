<?php
require(__DIR__."/include.php");
$payload = '{"function": "actions_move_hue_hex", "args": ["ee65", 3, "#0000FF"]}';
$mqtt->publish("zigate/command", $payload, 0);


$mqtt->close();