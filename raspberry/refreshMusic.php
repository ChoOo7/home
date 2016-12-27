<?php

$action = $argv[1];
$dir = '/media/data/Music/';
$dirs = glob($dir.'*');
foreach($dirs as $_dir)
{
  $cmd = null;
  $subDir = str_replace($dir, '', $_dir);
  if($action == "add")
  {
    $cmd = "mv ".escapeshellarg($_dir)." ".escapeshellarg($dir."_".$subDir);
  }elseif($action = "del")
  {
    $newDir = $dir.trim($subDir, '_');
    $cmd = "mv ".escapeshellarg($_dir)." ".escapeshellarg($newDir);
  }
  if($cmd)
  {
    echo "\n".$cmd."\n";
    //passthru($cmd);
  }
}
echo "\nFIN\n";