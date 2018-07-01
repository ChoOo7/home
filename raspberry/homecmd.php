<?php

require_once(__DIR__.'/denon.class.php');
require_once(__DIR__.'/kodi.class.php');
require_once(__DIR__.'/smartphone.class.php');
require_once(__DIR__.'/raspberry.class.php');

$d = new MyDenon();
$k = new Kodi();

$cmd = $argv[1];
$param = @$argv[2];
switch($cmd)
{

  case "downloadSpeedSlow":
  case "slow":
    $downloadSpeed = 200;
    $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
    passthru($command);
    break;

  case "downloadSpeedHigh":
  case "quick":
    $downloadSpeed = 50000;
    $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
    passthru($command);
    break;


  case "downloadSpeedNormal":
  case "normal":
    $downloadSpeed = 2000;
    $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
    passthru($command);
    break;


  case "tooglefranceintercreatived80":
  case "cuisineinter":
    $url ="http://direct.franceinter.fr/live/franceinter-midfi.mp3";
    $r = new Raspberry();
    $r->vlcToogle($url);
    break;


  case "tooglefipcreatived80":
  case "cuisinefip":
    $url = "http://direct.fipradio.fr/live/fip-webradio1.mp3";
    $r = new Raspberry();
    $r->vlcToogle($url);
    break;


  case "toogleradioperfectord80":
  case "cuisineradioperfecto":
  case "cuisineperfecto":
  case "cuisinerock":

    $url = "http://radioperfecto.net-radio.fr/perfecto.mp3";
    $r = new Raspberry();
    $r->vlcToogle($url);
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
    $k->stop();
    $d->powerOff();
    break;
  
  case "stp":
  case "stop":
    $k->stop();
    break;
  case "radio":
  case "tuner":
  $k->stop();
    $d->powerOnAndWaitForRead();
    $d->setRadio();
    $d->setVolume(15);
    break;
  case "favorite":
  case "setFavorite":
  case "fav":
    $k->stop();
    $d->powerOnAndWaitForRead();
    $d->setFavorite($param);
    break;
  case "fi":
  case "france":
  case "inter":
  case "franceinter":
    $k->stop();
    $d->powerOnAndWaitForRead();
    $d->setFavorite("01");
    break;
  case "fip":
    $k->stop();
    $d->powerOnAndWaitForRead(array($d, 'setFavorite'), array("01"));
    $d->setFavorite("02");
    break;
  case "rtu":
  case "tru":
    $k->stop();
    $d->powerOnAndWaitForRead();
    $d->setFavorite("03");
    break;

  case "chantefrance":
    $k->stop();
    $d->powerOnAndWaitForRead(array($d, 'setFavorite'), array("04"));
    $d->setFavorite("04");
    break;

  case "meuh":
  case "meuf":
  case "mheu":
  case "meu":
  case "me":
    $d->powerOnAndWaitForRead();
    $d->setFavorite("14");
    $k->stop();
    break;

  case "radioperfecto":
  case "perfecto":
  case "rock":
    $d->powerOnAndWaitForRead();
    $d->setFavorite("6");
    $k->stop();
    break;
  
  case "vol":
  case "volume":
  case "getVolume":
    echo "\n".$d->getVolumeLevel()."\n";
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
    $k->stop();
    $d->powerOnAndWaitForRead();
    $d->setBluetooth();
    break;
  case "analogic":
  case "ana":
  case "an":
    $k->stop();
    $d->powerOnAndWaitForRead();
    $d->setAnalogIn();
    break;
  case "digital":
  case "digit":
  case "dig":
  case "di":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
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

  case "tro":
  case "trotro":
  case "troTro":
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(822);
    $k->setShuffle();
    $k->playPlaylist();

    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(822);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    $d->volumeSet(15);
    break;

  case "henri":
  case "des":
  case "henrides":
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(828);
    $k->setShuffle();
    $k->playPlaylist();

    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(828);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    $d->volumeSet(15);
    break;


  case "tata":
  case "marthe":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle(false);
    $k->addArtistToPlaylist(820);
    $k->setShuffle(false);
    $k->playPlaylist();
    $k->setShuffle(false);
    $d->volumeSet(15);
    break;

  case "oui":
  case "ouioui":
  case "ouiOui":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(821);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    $d->volumeSet(15);
    break;


  case "sam":
  case "samlepompier":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(825);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    $d->volumeSet(15);
    break;

  case "tchoupi":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(826);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    $d->volumeSet(15);
    break;

  case "peppa":
  case "peppapig":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(824);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    $d->volumeSet(15);
    break;

  case "poule":
  case "pouleRousse":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(823);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    $d->volumeSet(15);
    break;
  
  case "castor":
  case "pereCastor":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(827);
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

    $artistId = 2;
    $songId = null;
    $songs = $k->getSongsOfArtist($artistId);
    foreach($songs as $song)
    {
      if(stripos($song['label'], 'croco') !== false)
      {
        $songId = $song['songid'];
        break;
      }
    }


    $k->addSongToPlaylist($songId);
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(15);
    break;


  case "fourmis":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();

    $artistId = 2;
    $songId = null;
    $songs = $k->getSongsOfArtist($artistId);
    foreach($songs as $song)
    {
      if(stripos($song['label'], 'fourmis') !== false)
      {
        $songId = $song['songid'];
        break;
      }
    }


    $k->addSongToPlaylist($songId);
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(15);
    break;


  case "ours":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();

    $artistId = 816;
    $songId = null;
    $songs = $k->getSongsOfArtist($artistId);
    foreach($songs as $song)
    {
      $k->addSongToPlaylist($songId);
    }


    
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(15);
    break;

  case "alphabet":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();

    $artistId = 2;
    $songId = null;
    $songs = $k->getSongsOfArtist($artistId);
    foreach($songs as $song)
    {
      if(stripos($song['label'], 'alphabet') !== false)
      {
        $songId = $song['songid'];
        break;
      }
    }

    $k->addSongToPlaylist($songId);
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(15);
    break;

  case "dodoenfant":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();

    $artistId = 2;
    $songId = null;
    $songs = $k->getSongsOfArtist($artistId);
    foreach($songs as $song)
    {
      if(stripos($song['label'], 'dodo') !== false && stripos($song['label'], 'enfant') !== false)
      {
        $songId = $song['songid'];
        break;
      }
    }

    $k->addSongToPlaylist($songId);
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(15);
    break;


  case "cerf":
  case "cerfs":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();

    $artistId = 2;
    $songId = null;
    $songs = $k->getSongsOfArtist($artistId);
    foreach($songs as $song)
    {
      if(stripos($song['label'], 'cerf') !== false)
      {
        $songId = $song['songid'];
        break;
      }
    }

    $k->addSongToPlaylist($songId);
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(15);
    break;

  case "michel":
  case "michele":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();

    $artistId = 2;
    $songId = null;
    $songs = $k->getSongsOfArtist($artistId);
    foreach($songs as $song)
    {
      if(stripos($song['label'], 'Michel') !== false)
      {
        $songId = $song['songid'];
        break;
      }
    }

    $k->addSongToPlaylist($songId);
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(15);
    break;

  case "gipsy":
  case "gypsi":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    $k->clearPlaylist();
    $k->setShuffle();

    $artistId = 2;
    $songId = null;
    $songs = $k->getSongsOfArtist($artistId);
    foreach($songs as $song)
    {
      if(stripos($song['label'], 'gipsy') !== false)
      {
        $songId = $song['songid'];
        break;
      }
    }

    $k->addSongToPlaylist($songId);
    $k->setShuffle();
    $k->playPlaylist();
    $d->volumeSet(15);
    break;


  case "shuffle":
  case "random":
    $k->setShuffle();
    break;

  case "noshuffle":
  case "norandom":
    $k->setShuffle(false);
    break;


  case "clean":
    $k->clean();
    break;
  case "scan":
    $k->scan();
    break;





  case "ventiloGet":
  case "fanGet":
    $s = new Smartphone();
    $state = $s->getFanState();
    echo $state ? "On" : "Off";
    break;

  case "ventiloOn":
  case "ventilo":
  case "fanOn":
  case "fan":
    $s = new Smartphone();
    $s->setFanState(true);
    break;

  case "ventiloOff":
  case "fanOff":
    $s = new Smartphone();
    $s->setFanState(false);
    break;

  case "ventiloSwitch":
  case "ventiloToogle":
  case "fanOff":
  case "fanToogle":
    $s = new Smartphone();
    $s->toogleFanState();
    break;


}

echo "\n";

