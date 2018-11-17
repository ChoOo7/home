<?php

require(__DIR__."/include.php");


$payload = '{"function": "action_move_level_onoff", "args": ["b89c", 11, 1, 100]}';

$mqtt->publish("zigate/command", $payload, 0);
$mqtt->close();