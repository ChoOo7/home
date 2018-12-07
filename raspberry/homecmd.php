<?php
header('Access-Control-Allow-Origin: *');


while(ob_get_level())
{
  ob_end_flush();
}
require_once(__DIR__.'/config/constants.php');
require_once(__DIR__.'/denon.class.php');
require_once(__DIR__.'/kodi.class.php');
require_once(__DIR__.'/ifttt.class.php');
require_once(__DIR__.'/smartphone.class.php');
require_once(__DIR__.'/raspberry.class.php');
require_once(__DIR__.'/zigate.class.php');
require_once(__DIR__.'/mpd.class.php');
require_once(__DIR__.'/broadlink.class.php');



$dataDirectory = __DIR__.'/data/';

$clicWaitTime = 1.5;

$d = new MyDenon();
$k = new Kodi();
$z = new Zigate();
$mpd = new Mpd();
$if = new Ifttt();
$b = new Broadlink();

$cmd = null;
$param = null;
$param2 = null;
if(isset($_GET['action']))
{
  $cmd = $_GET['action'];
  $param = $_GET['param'];
  $param2 = $_GET['param2'];
}else {
  $cmd = $argv[1];
  $param = @$argv[2];
  $param2 = @$argv[3];
}

function homeCmd($cmd, $param)
{
  $cmd = "php ".__FILE__." ".escapeshellarg($cmd)." ".escapeshellarg($param);
  passthru($cmd);
}

function launchInBackground($command)
{
  $output = "/tmp/log";
  @chmod($output, 0777);
  $command = "nohup ".$command ." > ".$output." 2>&1 &";
  //$cmd = 'nohup '.$cmd.' > /tmp/output &';
  system($command);
}

function detectClicNumber($btnName)
{
  global $clicWaitTime;
  $filePath = "/tmp/".$btnName;
  if( ! file_exists($filePath))
  {
    file_put_contents($filePath, "1");
    sleep($clicWaitTime);
    $nbClic = file_get_contents($filePath);

    $cmd = "php ".__DIR__."/homecmd.php ".$btnName.'-'.$nbClic;
    passthru($cmd);

    unlink($filePath);
  }else{
    $nbClic = file_get_contents($filePath);
    $nbClic++;
    file_put_contents($filePath, $nbClic);
  }
}

switch($cmd)
{
  case "btn1":case "btn2":
    detectClicNumber($cmd);
    break;

  case "btn1-1":
    $cmd = "php ".__DIR__."/homecmd.php castor";
    echo "\n".$cmd."\n";
    passthru($cmd);
    break;

  case "btn1-2":
    $cmd = "php ".__DIR__."/homecmd.php trotro";
    echo "\n".$cmd."\n";
    passthru($cmd);
    break;

  case "btn1-3":
    $cmd = "php ".__DIR__."/homecmd.php peppa";
    echo "\n".$cmd."\n";
    passthru($cmd);
    break;

  case "btn1-4":
    $cmd = "php ".__DIR__."/homecmd.php off";
    echo "\n".$cmd."\n";
    passthru($cmd);
    break;

  case "btn2-1":
    echo "2-1";
    break;

  case "btn2-2":
    echo "2-2";
    break;

  case 'ikeaLampSetIntensity':
    $z->setLampToIntensity(IKEA_LAMP01_ADDR, IKEA_LAMP01_CLUSTER, $param);
    break;

  case 'ikeaLamp1On':
    $command = 'php /var/home/raspberry/zigate/ikealampe1/on.php';
    launchInBackground($command);
    break;
  case 'ikeaLamp1Off':
    $command = 'php /var/home/raspberry/zigate/ikealampe1/off.php';
    launchInBackground($command);
    break;
  case 'ikeaLamp1Toogle':
    $command = 'php /var/home/raspberry/zigate/ikealampe1/toogle.php';
    launchInBackground($command);
    break;
  case 'ikeaLamp1High':
    $z->setLampToIntensity(IKEA_LAMP01_ADDR, IKEA_LAMP01_CLUSTER, 100);
    break;
  case 'ikeaLamp1Medium':
    $z->setLampToIntensity(IKEA_LAMP01_ADDR, IKEA_LAMP01_CLUSTER, 50);
    break;
  case 'ikeaLamp1MediumLow':
    $z->setLampToIntensity(IKEA_LAMP01_ADDR, IKEA_LAMP01_CLUSTER, 25);
    break;
  case 'ikeaLamp1Low':
    $z->setLampToIntensity(IKEA_LAMP01_ADDR, IKEA_LAMP01_CLUSTER, 2);
    break;


  case 'broadlinkOn':
    $if->broadlinkOn();
    break;
  case 'broadlinkOff':
    $if->broadlinkOff();
    break;

  case 'movementDetected':
    $command = 'php /var/home/raspberry/homecmd.php ikeaLamp1On';
    launchInBackground($command);
    break;

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

  case 'setTemperature':
  case 'setHumidity':
  case 'setPressure':
  case 'setLuminosity':
    $paramName = strtolower(str_replace('set', '', $cmd));
    $data = $param;
    file_put_contents($dataDirectory.'/'.$paramName, $data);
    break;

  case 'storeLampState':
    $paramName = strtolower(str_replace('set', '', $cmd));
    $lampAddr = $param;
    $dataJsonString = $param2;
    $data = json_decode($dataJsonString, true);
    foreach($data as $k=>$v) {
      file_put_contents($dataDirectory . '/' . $lampAddr.'-'.$k, $v);
    }
    break;


  case "restartZigateListener":
    $command = 'sudo service zigatelistener restart';
    passthru($command);
    break;


  case "restartZigateBroker":
    $command = 'sudo service zigate_broker restart';
    passthru($command);
    break;
  case "restartMopidy":
    $command = 'sudo service mopidy restart';
    passthru($command);
    break;
  
  case "restartMpd":
    $command = 'sudo service mpd restart';
    passthru($command);
    break;

  case "restartBroadlinkBroker":
    $command = 'sudo service broadlinkbroker restart';
    passthru($command);
    break;

  case "chauffageOn":
    $b->setBroadlinkState(0, true);
    break;
  case "chauffageOff":
    $b->setBroadlinkState(0, false);
    break;


  case "tooglefranceintercreatived80":
  case "cuisineinter":

    if( ! $mpd->isPlayingInter())
    {

      $mpd->isPlayingInter(true);

      $mpd->clearPlaylist();
      $mpd->addRemoteM3u8("http://direct.franceinter.fr/live/franceinter-midfi.mp3");
      $mpd->setShuffle(false);
      $mpd->play();
      $mpd->setMpcSourceMopidy();

      $mpd->cuisineOn();
      $mpd->denonOff();

      $d->powerOff();

      $d->volumeSet(15);
    }else{
      $mpd->isPlayingInter(false);

      $mpd->clearPlaylist();
      $mpd->stop();
      $mpd->cuisineOff();
    }
    break;


  case "tooglefipcreatived80":
  case "cuisinefip":

    $mpd->clearPlaylist();
    $mpd->addRemoteM3u8("http://direct.fipradio.fr/live/fip-webradio1.mp3");
    $mpd->setShuffle(false);
    $mpd->play();
    $mpd->setMpcSourceMopidy();

    $mpd->cuisineOn();
    $mpd->denonOff();

    $d->powerOff();

    $d->volumeSet(15);
    /*
    $url = "http://direct.fipradio.fr/live/fip-webradio1.mp3";
    $r = new Raspberry();
    $r->vlcToogle($url);
    */
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

  $d->powerOnAndWaitForRead();
  $d->setDigitIn();
  $d->volumeSet(15);
  /*
  $k->clearPlaylist();
  $k->setShuffle();
  $k->addArtistToPlaylist(827);
  $k->setShuffle();
  $k->playPlaylist();
  $k->setShuffle();
  */

  $mpd->clearPlaylist();
  $mpd->addArtistToPlaylist("troTro");
  $mpd->setShuffle(true);
  $mpd->play();
  $mpd->setMpcSourceMopidy();

  $mpd->cuisineOff();
  $mpd->denonOn();

  $d->volumeSet(15);
    break;

  case "henri":
  case "des":
  case "henrides":
  case "henrisdes":
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(828);
    $k->setShuffle();
    $k->playPlaylist();

    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    /*
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(828);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    $d->volumeSet(15);
    */

    $mpd->clearPlaylist();
    $mpd->addArtistToPlaylist("henrisDes");
    $mpd->setShuffle(true);
    $mpd->play();
    $mpd->setMpcSourceMopidy();

    $mpd->cuisineOff();
    $mpd->denonOn();

    $d->volumeSet(15);
    break;


  case "tata":
  case "marthe":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    /*
    $k->clearPlaylist();
    $k->setShuffle(false);
    $k->addArtistToPlaylist(820);
    $k->setShuffle(false);
    $k->playPlaylist();
    $k->setShuffle(false);
    $d->volumeSet(15);
    */

  $mpd->clearPlaylist();
  $mpd->addArtistToPlaylist("Tata Marthe");
  $mpd->setShuffle(false);
  $mpd->play();
  $mpd->setMpcSourceMopidy();

  $mpd->cuisineOff();
  $mpd->denonOn();

  $d->volumeSet(13);
    break;

  case "oui":
  case "ouioui":
  case "ouiOui":
    homeCmd("playArtist", "ouiOui");
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

  case "restartPi":
    exec('sudo reboot');
    break;

  case "pepa":
  case "peppa":
  case "peppapig":
    homeCmd("playArtist", "PeppaPig");
    break;
  case "yak":
  case "yakari":
  case "yakary":
    homeCmd("playArtist", "Yakari");
    break;

  case "poule":
  case "pouleRousse":
    homeCmd("playArtist", "pouleRousse");
    break;

  case "castor":
  case "pereCastor":
    homeCmd("playArtist", "pereCastor");
    break;
  case "belleBete":
  case "laBelleEtLaBete":
    homeCmd("playArtist", "La belle et la bete");
    break;


  case "mpcLsArtist":
    $res = $mpd->lsArtistToPlaylist($param);
    var_dump($res);
    break;

  case "cuisineOn":
    $mpd->cuisineOn();
    break;
  case "cuisineOff":
    $mpd->cuisineOff();
    break;

  case "salonOn":
  case "denonOn":
    $mpd->denonOn();
    break;
  case "denonOff":
  case "salonOff":
    $mpd->denonOff();
    $d->powerOff();
    break;

  case "playArtist":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);

    $mpd->clearPlaylist();
    $mpd->addArtistToPlaylist($param);
    $mpd->setShuffle(true);
    $mpd->play();

    $mpd->denonOn();

    $d->volumeSet(15);
    break;

  case "camille":
    homeCmd("playArtist", "Camille");
    break;

  case "floyd":
    homeCmd("playArtist", "Pink Floyd");
    break;

  case "teteRaides":
  case "tetesRaides":
  case "tetes":
    homeCmd("playArtist", "Tetes Raides");
    break;

  case "hang":
  case "hangMassive":
    homeCmd("playArtist", "Hang Massive");
    break;


  case "vargas":
    $d->powerOnAndWaitForRead();
    $d->setDigitIn();
    $d->volumeSet(15);
    /*
    $k->clearPlaylist();
    $k->setShuffle();
    $k->addArtistToPlaylist(827);
    $k->setShuffle();
    $k->playPlaylist();
    $k->setShuffle();
    */

    $mpd->clearPlaylist();
    $mpd->addSpotifyArtist("Chavela vargas");
    $mpd->setShuffle(true);
    $mpd->play();

    //$mpd->cuisineOn();
    $mpd->denonOn();
    

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


  case "dashchangeledstate":

    $filename = __DIR__.'dashchangeledstate.log';
    $state = (int)file_get_contents($filename);
    $state++;
    file_put_contents($filename, $state);
    $cmd = null;
    switch($state % 6)
    {
      case 0:
        $cmd = 'php '.__FILE__.' ledStripe2white';
        passthru($cmd);
        break;
      case 1:
        $cmd = 'php '.__FILE__.' ledStripe2Yellowlight';
        passthru($cmd);
        break;
      case 2:
        $cmd = 'php '.__FILE__.' ledStripe2Blue';
        passthru($cmd);
        break;
      case 3:
        $cmd = 'php '.__FILE__.' ledStripe2Red';
        passthru($cmd);
        break;
      case 4:
        $cmd = 'php '.__FILE__.' ledStripe2Green';
        passthru($cmd);
        break;
      case 5:
        $cmd = 'php '.__FILE__.' ledStripe2WhiteLight';
        passthru($cmd);
        break;
    }
    var_dump($cmd);

    break;




  case "ledStripe2Toogle":
    $device = 'ledstripe2';
    $deviceCmd = 'toogle';
    $retry = 0;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe2On":
    $device = 'ledstripe2';
    $deviceCmd = 'on';
    $retry = 3;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe2Off":
    $device = 'ledstripe2';
    $deviceCmd = 'off';
    $retry = 3;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;
  case "ledStripe2white":
    $device = 'ledstripe2';
    $deviceCmd = 'white';
    $retry = 0;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;
  case "ledStripe2Yellowlight":
    $device = 'ledstripe2';
    $deviceCmd = 'yellowlight';
    $retry = 0;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;
  case "ledStripe2WhiteLight":
    $device = 'ledstripe2';
    $deviceCmd = 'whitelight';
    $retry = 0;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;


  case "ledStripe2Blue":
    $device = 'ledstripe2';
    $deviceCmd = 'blue';
    $retry = 0;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe2Red":
    $device = 'ledstripe2';
    $deviceCmd = 'red';
    $retry = 0;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe2Green":
    $device = 'ledstripe2';
    $deviceCmd = 'green';
    $retry = 0;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe1Toogle":
    $device = 'ledstripe1';
    $deviceCmd = 'toogle';
    $retry = 0;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe1On":
    $device = 'ledstripe1';
    $deviceCmd = 'on';
    $retry = 3;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe1Off":
    $device = 'ledstripe1';
    $deviceCmd = 'off';
    $retry = 3;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe1White":
    $device = 'ledstripe1';
    $deviceCmd = 'white';
    $retry = 3;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe1Yellow":
    $device = 'ledstripe1';
    $deviceCmd = 'yellow';
    $retry = 3;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe1Low":
    $device = 'ledstripe1';
    $deviceCmd = 'low';
    $retry = 3;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;

  case "ledStripe1High":
    $device = 'ledstripe1';
    $deviceCmd = 'high';
    $retry = 3;
    $z->launchCommand($device, $deviceCmd, $retry);
    break;
  default:
    echo"\nUnknow command. Available commands : ";
    $cnt = file(__FILE__);
    foreach($cnt as $line)
    {
      $line = trim($line);
      if(strpos($line, 'case "') !== false && strpos($line, 'str_replace') === false)
      {
        $line = str_replace('case "', "", $line);
        $line = str_replace('":', "", $line);
        $line = trim($line);
        echo "\n - ".$line;
      }
    }
  echo "\n";

}

echo "\n";

