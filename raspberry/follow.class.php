<?php

class FollowMe
{
  public static function getInstance($token)
  {
    return new self($token);
  }
  protected $token;
  public function __construct($token)
  {
    $this->token = $token;
  }

  public function getRemoteInformation()
  {
    $url = 'https://www.followmee.com/getpos.aspx?token='.urlencode($this->token);
    $cnt = file_get_contents($url);
    $cnt = json_decode($cnt, true);
    $cnt = $cnt[0];

    $date = $cnt['Dated'];
    $lat = $cnt['Lat'];
    $lng = $cnt['Lng'];


    $date = strtotime($date);
    $lng = (float)$lng;
    $lat = (float)$lat;

    $return = array('date'=>$date, 'lat'=>$lat, 'lng'=>$lng);
    return $return;
  }

  public function isLocationInSector($infos, $targetLat, $targetLng, $precision)
  {
    $latOk = abs($infos['lat'] - $targetLat) < $precision;
    $lngOk = abs($infos['lng'] - $targetLng) < $precision;

    return $latOk && $lngOk;
  }

}