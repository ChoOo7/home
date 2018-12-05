<?php
class Broadlink
{

  public function setBroadlinkState($deviceIndex, $state)
  {
    $cmd = "php ".__DIR__."/broadlink/setState.php ".escapeshellarg($deviceIndex)." ".escapeshellarg($state);
    passthru($cmd);
  }

}
