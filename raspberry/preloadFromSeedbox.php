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

//$command = "timeout 170 rsync --timeout=115 --partial --inplace --append --recursive --bwlimit=".$bandwidth." -vP --no-o  /servers/chooo7/var/downloaded/ /media/data/downloaded";

$command = " cd /servers/chooo7/var/downloaded/ && find ./ -mtime -5 -type f -print0 | timeout 170 rsync --timeout=115 --partial --inplace --append --recursive --bwlimit=".$bandwidth." -vP --no-o --files-from=- --from0 /servers/chooo7/var/downloaded/ /media/data/downloaded";

echo "\n".date('d/m/Y H:i:s');
echo "\n".$command."\n";
passthru('chmod ugo+r -R /media/data/downloaded/');
passthru($command);
passthru('chmod ugo+r -R /media/data/downloaded/');

