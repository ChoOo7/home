<?php
class Raspberry
{

  protected $debug = false;
  protected $i = 1;


  public function vlcToogle($url)
  {
    $command = 'ps aux | grep vlc | grep -v grep';
    $output = array();
    exec($command, $output);
    var_dump($output);
    if(count($output) == 0)
    {
      echo "\nStarting\n";
      $command = 'cvlc --http-password test:test --http-host=0.0.0.0 -A alsa,none --alsa-audio-device default  -I http --http-port 43822 '.escapeshellarg($url);
      $outputFile = "./vlcOutput.log";
      $command = "nohup ".$command ." > ".$outputFile." 2>&1 &";
    }else{
      echo "\nKilling\n";
      $command = 'killall vlc';
    }
    passthru($command);
  }
}
