<?php
require_once(__DIR__.'/denon.class.php');
require_once(__DIR__.'/kodi.class.php');


$action=$_GET['action'];
$param=$_GET['param'];

function ampliSetInputSource()
{
  
}
if($action)
{
  header('Location: '.basename(__FILE__));
  
  $cmd = 'nohup php '.__DIR__.'/homecmd.php '.escapeshellarg($action).' '.escapeshellarg($param).' &';
  echo "\n".$cmd."\n";
  passthru($cmd);
  
  exit;
}


$d = new MyDenon();
$state = $d->getState();
$denonState = $state->Power->value.' - '.$state->InputFuncSelect->value. ' - Vol: '.$d->getVolumeLevel();


$lastLogs = array();
exec("tail -n 10 /var/home/raspberry/preloadLog", $lastLogs);

foreach($lastLogs as $k=>$line)
{
    $tmp = explode("\r", $line);
    $last = $tmp[count($tmp)-1];
    $lastLogs[$k]=$last;
    //var_dump($last);
}
//die();

$speedConfigDir="/var/home/raspberry/config/";
$actualSpeed = file_get_contents($speedConfigDir."bandwidth");
if(file_exists($speedConfigDir."bandwidthNow"))
{
    $actualSpeed = file_get_contents($speedConfigDir."bandwidthNow");
}
$actualSpeed = trim($actualSpeed);


$downloadedContent = glob('/media/data/downloaded/*');
$chooo7Content = glob('/servers/chooo7/*');
$anthoContent = glob('/servers/antho/*');
$anthoSambaContent = glob('/servers/antho/home/simon/samba/*');

$simulateCommand = 'rsync -n --timeout=115 --partial --inplace --append --recursive --bwlimit=2000 -vP /servers/chooo7/var/downloaded/ /media/data/downloaded';



require_once(__DIR__.'/coinmon.class.php');
$cm = new Coinmon();
$infos = $cm->getCalculatedValues();

$total = 0;
$maxActualValues = 0;
foreach ($infos as $timestamp => $values) {
  $isFull = true;
  $total = 0.0;
  $actualValues = array();
  foreach ($values as $device => $info) {
    if($info["valueOfMyCoins"]) {
      $total += $info["valueOfMyCoins"];
      $actualValues[$device] = $info["valueOfMyCoins"];
      $maxActualValues = max($maxActualValues, $info["valueOfMyCoins"]);
    }
  }
}
/*
 http://www.audioproducts.com.au/downloadcenter/products/Denon/CEOLPICCOLOBK/Manuals/DRAN5_RCDN8_PROTOCOL_V.1.0.0.pdf
 */

?>
<html>
    <head>
    <title>Home control center</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
      <script   src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    </head>
    <body>
    
    
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <?php if(empty($anthoContent)): ?>
            <h1 class="alert error">ANTHO n'est pas montée</h1>
            <hr />
          <?php endif; ?>

          <?php if(empty($anthoSambaContent)): ?>
            <h1 class="alert error">ANTHO SAMBA n'est pas montée</h1>
            <hr />
          <?php endif; ?>
          <?php if(empty($chooo7Content)): ?>
            <h1 class="alert error">ChoOo7 n'est pas montée</h1>
            <hr />
          <?php endif; ?>
          <h1><?php echo round($total); ?></h1>
          <h2>Ampli : <?php echo $denonState; ?></h2>

          <div class="btn-group">
            <button class="btn btn-default doAction" type="button" data-do="powerOn">
              <em class="glyphicon"></em> POWER ON
            </button>
            <button class="btn btn-default doAction" type="button" data-do="powerOff">
              <em class="glyphicon"></em> POWER OFF
            </button>
          </div>
        </div>
      </div>
      <br />
      <div class="row">
        <div class="col-md-12">
          <div class="btn-group">
            <button class="btn btn-default doAction" type="button" data-do="volumeUp">
              <em class="glyphicon"></em> Volume +
            </button>
            <button class="btn btn-default doAction" type="button" data-do="volumeDown">
              <em class="glyphicon"></em> Volume -
            </button>
          </div>
        </div>
      </div>
      <br />
      <div class="row">
        <div class="col-md-12">
          <div class="btn-group">
            <button class="btn btn-default doAction" type="button" data-do="bluetooth">
              <em class="glyphicon"></em> Bluetooth
            </button>
            <button class="btn btn-default doAction" type="button" data-do="analogic">
              <em class="glyphicon"></em> Cable
            </button>
            <button class="btn btn-default doAction" type="button" data-do="digit">
              <em class="glyphicon"></em> DIGIT
            </button>
            <button class="btn btn-default doAction" type="button" data-do="inter">
              <em class="glyphicon"></em> France Inter
            </button>
            <button class="btn btn-default doAction" type="button" data-do="fip">
              <em class="glyphicon"></em> FIP
            </button>
            <button class="btn btn-default doAction" type="button" data-do="meuh">
              <em class="glyphicon"></em> MEUH
            </button>
            <button class="btn btn-default doAction" type="button" data-do="rtu">
              <em class="glyphicon"></em> RTU
            </button>
            <button class="btn btn-default doAction" type="button" data-do="chantefrance">
              <em class="glyphicon"></em> CHANTEFRANCE
            </button>
          </div>
        </div>
      </div>
      <br />
      <div class="row">
        <div class="col-md-12">
          <div class="btn-group">
            <button class="btn btn-default doAction" type="button" data-do="tata">
              <em class="glyphicon"></em> Tata Marthe
            </button>
            <button class="btn btn-default doAction" type="button" data-do="ours">
              <em class="glyphicon"></em> Ours
            </button>
            <button class="btn btn-default doAction" type="button" data-do="comptine">
              <em class="glyphicon"></em> Comptine
            </button>
            <button class="btn btn-default doAction" type="button" data-do="croc">
              <em class="glyphicon"></em> Crocodile
            </button>
            <button class="btn btn-default doAction" type="button" data-do="fourmis">
              <em class="glyphicon"></em> Fourmis
            </button>
            <button class="btn btn-default doAction" type="button" data-do="alphabet">
              <em class="glyphicon"></em> Alphabet
            </button>
            <button class="btn btn-default doAction" type="button" data-do="dodoenfant">
              <em class="glyphicon"></em> Dodo
            </button>
            <button class="btn btn-default doAction" type="button" data-do="cerf">
              <em class="glyphicon"></em> Cerf
            </button>
            <button class="btn btn-default doAction" type="button" data-do="michel">
              <em class="glyphicon"></em> Michel
            </button>
            <button class="btn btn-default doAction" type="button" data-do="gipsy">
              <em class="glyphicon"></em> Gipsy
            </button>
          </div>
          <br />


          <div class="btn-group">
            <button class="btn btn-default doAction" type="button" data-do="ouioui">
              <em class="glyphicon"></em> Oui oui
            </button>
            <button class="btn btn-default doAction" type="button" data-do="castor">
              <em class="glyphicon"></em> Pere castor
            </button>
            <button class="btn btn-default doAction" type="button" data-do="trotro">
              <em class="glyphicon"></em> TroTro
            </button>
            <button class="btn btn-default doAction" type="button" data-do="tchoupi">
              <em class="glyphicon"></em> Tchoupi
            </button>

            <button class="btn btn-default doAction" type="button" data-do="sam">
              <em class="glyphicon"></em> Sam le pompier
            </button>

            <button class="btn btn-default doAction" type="button" data-do="peppa">
              <em class="glyphicon"></em> Peppa Pig
            </button>

            <button class="btn btn-default doAction" type="button" data-do="poule">
              <em class="glyphicon"></em> poule
            </button>
          </div>

          <br />

          <div class="btn-group">
            <button class="btn btn-default doAction" type="button" data-do="clean">
              <em class="glyphicon"></em> Clean
            </button>
            <button class="btn btn-default doAction" type="button" data-do="scan">
              <em class="glyphicon"></em> Scan
            </button>
          </div>

          <h2>Actuel download preload speed : <?php echo $actualSpeed; ?></h2>

          <div class="btn-group">
            <button class="btn btn-default doAction" type="button" data-do="downloadSpeedSlow">
              <em class="glyphicon"></em> Slow speed
            </button>
            <button class="btn btn-default doAction" type="button" data-do="downloadSpeedNormal">
              <em class="glyphicon"></em> Normal speed
            </button>
            <button class="btn btn-default doAction" type="button" data-do="downloadSpeedHigh">
              <em class="glyphicon"></em> High speed
            </button>
          </div>


          <h2>
            Logs
          </h2>
          <p>
            <?php echo implode("\n<br />", $lastLogs); ?>
          </p>
          
        </div>
      </div>
    </div>
<script type="text/javascript">

    function reloadPage()
    {
      document.location = document.location;
    }
    jQuery(function(){
      jQuery('.doAction').click(function() {
          jQuery.ajax('<?php echo basename(__FILE__); ?>?action='+jQuery(this).attr('data-do')+'&param='+jQuery(this).attr('data-param'), {
            success: function() {
             document.location = document.location;
            }
          });
          return false;
      });
      setTimeout('reloadPage();', 10000);
  });
</script>
    
    
    </body>
</html>
