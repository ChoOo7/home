<?php
class ZiGate
{
  
  public function launchCommand($device, $deviceCmd, $retry = 3)
  {
    $cmd = null;
    if(file_exists(__DIR__.'/zigate/'.$device.'/'.$deviceCmd.'.php'))
    {
      $retry = 0;
      $cmd ='php '.__DIR__.'/zigate/'.$device.'/'.$deviceCmd.'.php';
    }else {
      $cmd = 'timeout 2 python3 ' . __DIR__ . '/zigate/' . $device . '/' . $deviceCmd . '.py';
    }
    var_dump($cmd);
    exec($cmd);
    if($retry > 0)
    {
      $this->launchCommand($device, $deviceCmd, $retry - 1);
    }
  }  
}
