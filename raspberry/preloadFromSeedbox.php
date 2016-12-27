#!/usr/bin/env php 
<?php



$optionDir = __DIR__.'/config/';

$bandwidth = trim(file_get_contents($optionDir.'bandwidth'));
$bandwidthNowFilePath = $optionDir.'bandwidthNow';
if(file_exists($bandwidthNowFilePath))
{
    $bandwidth = trim(file_get_contents($bandwidthNowFilePath));
}
//--backup --partial --inplace 

$command = "timeout 170 rsync --timeout=115 --partial --inplace --append --recursive --bwlimit=".$bandwidth." -vP --no-o  /servers/chooo7/var/downloaded/ /media/data/downloaded";

echo "\n".date('d/m/Y H:i:s');
echo "\n".$command;
passthru($command);
