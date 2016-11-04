<?php
$action=$_GET['action'];

function ampliSetInputSource()
{
  
}

switch($action)
{
  case "downloadSpeedSlow":
      $downloadSpeed = 200;
      $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
      var_dump($command);
      exec($command);
      break;
  case "downloadSpeedHigh":
      $downloadSpeed = 50000;
      $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
      exec($command);
      break;
  case "downloadSpeedNormal":
    $downloadSpeed = 2000;
    $command = 'php /var/home/raspberry/preloadResetCurrentSpeed.php';
    exec($command);
    break;

  case "powerOn":
    $command = 'curl -i --data '.escapeshellarg('cmd0=PutSystem_OnStandby/ON&ZoneName=MainZone').' http://192.168.0.120/MainZone/index.put.asp';
    exec($command);
    $command = 'curl -i --data '.escapeshellarg('cmd0=PutZone_OnOff/ON&ZoneName=MainZone').' http://192.168.0.120/MainZone/index.put.asp';
    exec($command);
    break;
  
  case "powerOff":
    $command = 'curl -i --data '.escapeshellarg('cmd0=PutSystem_OnStandby/STANDBY&ZoneName=MainZone').' http://192.168.0.120/MainZone/index.put.asp';
    exec($command);
    break;
  
  case "volumeUp":
    $command = 'curl -i --data '.escapeshellarg('cmd0=PutMasterVolumeBtn\>&ZoneName=MainZone').' http://192.168.0.120/MainZone/index.put.asp';
    exec($command);
    break;

  case "volumeDown":
    $command = 'curl -i --data '.escapeshellarg('cmd0=PutMasterVolumeBtn\<&ZoneName=MainZone').' http://192.168.0.120/MainZone/index.put.asp';
    exec($command);
    break;
  case "volumeSet":
    $command = 'curl -i --data '.escapeshellarg('cmd0=PutMasterVolumeSet/10&ZoneName=MainZone').' http://192.168.0.120/MainZone/index.put.asp';
    exec($command);
    break;
  
  case "setInternet":
  case "setRadio":
    $command = 'curl -i --data '.escapeshellarg('cmd0=PutZone_InputFunction/IRADIO&ZoneName=MainZone').' http://192.168.0.120/MainZone/index.put.asp';
    exec($command);
    break;
    
  
  case "setFavorite1":
    $command = 'curl -i http://192.168.0.120/goform/formiPhoneAppFavorite_Call.xml?01';
    exec($command);
    break;
  case "setFavorite2":
    $command = 'curl -i http://192.168.0.120/goform/formiPhoneAppFavorite_Call.xml?02';
    exec($command);
    break;
  case "setFavorite3":
    $command = 'curl -i http://192.168.0.120/goform/formiPhoneAppFavorite_Call.xml?03';
    exec($command);
    break;

  case "setBluetooth":
    $command = 'curl -i --data '.escapeshellarg('cmd0=PutZone_InputFunction/BLUETOOTH&ZoneName=MainZone').' http://192.168.0.120/MainZone/index.put.asp';
    exec($command);
    break;

  case "setCable":
    $command = 'curl -i --data '.escapeshellarg('cmd0=PutZone_InputFunction/ANALOGIN&ZoneName=MainZone').' http://192.168.0.120/MainZone/index.put.asp';
    exec($command);
    break;  
    
//http://www.openremote.org/display/docs/OpenRemote+2.0+How+To+-+Denon+HTTP+Control
    
}

$stateXmlString = file_get_contents("http://192.168.0.120/goform/formMainZone_MainZoneXml.xml");
$stateXml = simplexml_load_string($stateXmlString);
$powerState = (string)$stateXml->Power->value;
$currentInput = (string)$stateXml->InputFuncSelect->value;


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

$simulateCommand = 'rsync -n --timeout=115 --partial --inplace --append --recursive --bwlimit=2000 -vP /servers/chooo7/var/downloaded/ /media/data/downloaded';

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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script   src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
    </head>
    <body>
    
    
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <?php if(empty($redboxContent)): ?>
            <h1 class="alert error">La REDBOX n'est pas montée</h1>
            <hr />
          <?php endif; ?>
          <?php if(empty($chooo7Content)): ?>
            <h1 class="alert error">ChoOo7 n'est pas montée</h1>
            <hr />
          <?php endif; ?>
          
          <h2>Ampli : <?php echo $powerState; ?> - <?php echo $currentInput; ?></h2>

          <div class="btn-group">
            <button class="btn btn-default doAction" type="button" data-do="powerOn">
              <em class="glyphicon"></em> POWER ON
            </button>
            <button class="btn btn-default doAction" type="button" data-do="powerOff">
              <em class="glyphicon"></em> POWER OFF
            </button>
            <button class="btn btn-default doAction" type="button" data-do="volumeUp">
              <em class="glyphicon"></em> Volume +
            </button>
            <button class="btn btn-default doAction" type="button" data-do="volumeDown">
              <em class="glyphicon"></em> Volume -
            </button>
            <button class="btn btn-default doAction" type="button" data-do="setRadio">
              <em class="glyphicon"></em> INTERNET
            </button>
            <button class="btn btn-default doAction" type="button" data-do="setBluetooth">
              <em class="glyphicon"></em> Set Bluetooth
            </button>
            <button class="btn btn-default doAction" type="button" data-do="setCable">
              <em class="glyphicon"></em> Cable
            </button>
            <button class="btn btn-default doAction" type="button" data-do="setFavorite1">
              <em class="glyphicon"></em> FAV1
            </button>
            <button class="btn btn-default doAction" type="button" data-do="setFavorite2">
              <em class="glyphicon"></em> FAV2
            </button>
            <button class="btn btn-default doAction" type="button" data-do="setFavorite3">
              <em class="glyphicon"></em> FAV3
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
jQuery(function(){
    jQuery('.doAction').click(function() {
        document.location='<?php echo basename(__FILE__); ?>?action='+jQuery(this).attr('data-do');
        return false;
    });
});
</script>
    
    
    </body>
</html>