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
$command = "rsync --timeout=115 --partial --inplace --append --recursive --bwlimit=".$bandwidth." -vP  -e 'ssh -p 11122' /mnt/chooo7/var/downloaded/ /home/osmc/downloaded";
$command = 'timeout 175 '.$command;

echo "\n".date('d/m/Y H:i:s');
echo "\n".$command;
passthru($command);
