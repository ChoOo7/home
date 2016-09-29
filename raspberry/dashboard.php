<?php
$action=$_GET['action'];
switch($action)
{
    case "downloadSpeedSlow":
        $downloadSpeed = 200;
        $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
        var_dump($command);
        exec($command);
        break;
    case "downloadSpeedHigh":
        $downloadSpeed = 20000;
        $command = 'php /var/home/raspberry/preloadSetSpeed.php '.escapeshellarg($downloadSpeed);
        exec($command);
        break;
    case "downloadSpeedNormal":
        $downloadSpeed = 200;
        $command = 'php /var/home/raspberry/preloadResetCurrentSpeed.php';
        exec($command);
        break;
}

$lastLogs = array();
exec("tail -n 20 /tmp/preloadLog", $lastLogs);

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

$simulateCommand = 'rsync -n --timeout=115 --partial --inplace --append --recursive --bwlimit=2000 -vP /servers/chooo7/var/downloaded/ /servers/redbox/downloaded';


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
			 <h2>Actuel download preload speed : <?php echo $actualSpeed; ?></h2>
			<div class="btn-group">
				<button class="btn btn-default doACtion" type="button" data-do="downloadSpeedSlow">
					<em class="glyphicon"></em> Slow speed
				</button> 
				<button class="btn btn-default doACtion" type="button" data-do="downloadSpeedNormal">
					<em class="glyphicon"></em> Normal speed
				</button> 
				<button class="btn btn-default doACtion" type="button" data-do="downloadSpeedHigh">
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