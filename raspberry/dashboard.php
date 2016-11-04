<?php
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
$acerContent = glob('/servers/acer/*');

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
          <?php if(empty($acerContent)): ?>
            <h1 class="alert error">ACER n'est pas montée</h1>
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
            <button class="btn btn-default doAction" type="button" data-do="radio">
              <em class="glyphicon"></em> INTERNET
            </button>
            <button class="btn btn-default doAction" type="button" data-do="bluetooth">
              <em class="glyphicon"></em> Set Bluetooth
            </button>
            <button class="btn btn-default doAction" type="button" data-do="analogic">
              <em class="glyphicon"></em> Cable
            </button>
            <button class="btn btn-default doAction" type="button" data-do="inter">
              <em class="glyphicon"></em> France Inter
            </button>
            <button class="btn btn-default doAction" type="button" data-do="fip">
              <em class="glyphicon"></em> FIP
            </button>
            <button class="btn btn-default doAction" type="button" data-do="setFavorite" data-param="01">
              <em class="glyphicon"></em> FAV1
            </button>
            <button class="btn btn-default doAction" type="button" data-do="setFavorite" data-param="02">
              <em class="glyphicon"></em> FAV2
            </button>
            <button class="btn btn-default doAction" type="button" data-do="setFavorite" data-param="03">
              <em class="glyphicon"></em> FAV3
            </button>
            <button class="btn btn-default doAction" type="button" data-do="comptine">
              <em class="glyphicon"></em> Comptine
            </button>
            <button class="btn btn-default doAction" type="button" data-do="croc">
              <em class="glyphicon"></em> Crocodile
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
        document.location='<?php echo basename(__FILE__); ?>?action='+jQuery(this).attr('data-do')+'&param='+jQuery(this).attr('data-param');
        return false;
    });
});
</script>
    
    
    </body>
</html>