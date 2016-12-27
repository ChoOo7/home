#!/usr/bin/env php 
<?php

$optionDir = __DIR__.'/config/';

$bandwidthFilePath = $optionDir.'bandwidthNow';

file_put_contents($bandwidthFilePath, $argv[1]."\n");
@chmod($bandwidthFilePath, 0777);
