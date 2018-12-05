<?php
class Ifttt
{


  public function sendWebhookEvent($event)
  {
    $secret = file_get_content('./config/ifttt');
    $url = 'https://maker.ifttt.com/trigger/broadlinkOff/with/key/'.$secret;
    file_get_contents($url);
  }

  public function broadlinkOn()
  {
    $this->sendWebhookEvent('broadlinkOn');
  }
  public function broadlinkOff()
  {
    $this->sendWebhookEvent('broadlinkOff');
  }
}
