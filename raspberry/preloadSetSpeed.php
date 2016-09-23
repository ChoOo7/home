#!/usr/bin/env php 
<?php

$optionDir = __DIR__.'/config/';

$bandwidthFilePath = $optionDir.'bandwidth';

file_put_contents($bandwidthFilePath, $argv[1]."\n");
