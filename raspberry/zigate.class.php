<?php
class ZiGate
{
  
  public function launchCommand($device, $deviceCmd, $retry = 3)
  {
    $cmd ='timeout 2 python3 '.__DIR__.'/zigate/'.$device.'/'.$deviceCmd.'.py';
    var_dump($cmd);
    exec($cmd);
    if($retry > 0)
    {
      $this->launchCommand($device, $deviceCmd, $retry - 1);
    }
  }  
}
