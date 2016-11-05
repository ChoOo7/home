<?php

require_once(__DIR__.'/denon.class.php');
require_once(__DIR__.'/kodi.class.php');

$d = new MyDenon();
$k = new Kodi();

$cmd = $argv[1];
$param = @$argv[2];
switch($cmd)
{

  case "downloadSpeedSlow":
    $downloadSpeed = 200;
    $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
    exec($command);
    break;

  case "downloadSpeedHigh":
    $downloadSpeed = 50000;
    $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
    exec($command);
    break;


  case "downloadSpeedNormal":
    $downloadSpeed = 2000;
    $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
    exec($command);
    break;
  
  case "+":
  case "up":
  case "volumeUp":
    $d->volumeUp();
    break;
  case "++":
    $d->volumeUp();
    $d->volumeUp();
    break;
  case "+++":
    $d->volumeUp();
    $d->volumeUp();
    $d->volumeUp();
    break;
  case "-":
  case "do":
  case "dw":
  case "down":
  case "volumeDown":
    $d->volumeDown();
    break;
  case "--":
    $d->volumeDown();
    $d->volumeDown();
    break;
  case "---":
    $d->volumeDown();
    $d->volumeDown();
    $d->volumeDown();
    break;
  case "on":
  case "powerOn":
    $d->powerOnAndWaitForRead();
    break;
  case "off":
  case "of":
  case "powerOff":
    $d->powerOff();
    break;
  
  case "stp":
  case "stop":
    $k->stop();
    break;
  case "radio":
  case "tuner":
    $d->powerOnAndWaitForRead();
    $d->setRadio();
    $d->setVolume(15);
    break;
  case "favorite":
  case "setFavorite":
  case "fav":
    $d->powerOnAndWaitForRead();
    $d->setFavorite($param);
    break;
  case "fi":
  case "france":
  case "inter":
  case "franceinter":
    $d->powerOnAndWaitForRead();
    $d->setFavorite("01");
    break;
  case "fip":
    $d->powerOnAndWaitForRead();
    $d->setFavorite("02");
    break;
  case "vol":
  case "volume":
  case "getVolume":
    echo "\n".$d->getBluetooth()."\n";
    break;
  case "st":
  case "state":
  case "status":
  case "getState":
  case "getStatus":
    $st = $d->getState();
    echo "\n".$st->asXML()."\n";
    break;
  case "blue":
  case "bluetooth":
    $d->powerOnAndWaitForRead();
    $d->setBluetooth();
    break;
  case "analogic":
  case "ana":
  case "an":
    $d->powerOnAndWaitForRead();
    $d->setAnalogIn();
    break;
  case "digital":
  case "digit":
  case "dig":
  case "di":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    break;
  case "a":
  case "artist":
  case "art":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $k->clearPlaylist();
    $k->addArtistToPlaylist($param);
    $k->playPlaylist();
    break;
    
  case "c":
  case "cc":
  case "clr":
  case "clear":
  case "clearPlaylist":
    $k->clearPlaylist();    
    break;
    
  case "cpt":
  case "comptine":
  case "comptines":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);    
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(2);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    $d->volumeSet(15);
    break;
    
  case "croc":
  case "crocodile":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addSongToPlaylist(5033);
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(15);
    break;
    
    
  case "shuffle":
  case "random":
    $k->setShuffle();
    break;

}

echo "\n";

