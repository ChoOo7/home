<?php

require_once(__DIR__.'/coinmon.class.php');

$cm = new Coinmon();

$cm->decreaseFileContent();

var_dump("ok");
?>
