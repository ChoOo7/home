<?php
require_once(__DIR__.'/coinmon.class.php');
$cm = new Coinmon();
$infos = $cm->getCalculatedValues();
$devices = array('TOTAL'=>array());
$totalSeries = array();
$last = array();
$lastTotal = 0.0;
$lastTimestamp = null;

require_once(__DIR__.DIRECTORY_SEPARATOR.'coins.config.php');


$actualValues = array();

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


arsort($actualValues);


$widthRatio = 10/$maxActualValues;
$_keys = array_keys($infos);
$lastTimestamp = end($_keys)+3600;

arsort($actualValues, SORT_NUMERIC);


foreach($charts as $chartIndex=>$chartDef) {
  $devices = array('TOTAL'=>array());

  foreach($actualValues as $device=>$uselessTotal)
  {
    $devices[$device] = array();
  }

  $minTimestamp = $chartDef['minTimestamp'];
  $minInterval = $chartDef['minInterval'];

  $lastTimestamp = null;
  $firstTotal = null;
  $lastTotal = null;

  $isLastEntry = false;
  $counter = 0;

  $sumForAvg = 0.0;
  $nbForAvg = 0;

  foreach ($infos as $timestamp => $values) {
    $counter++;
    $isFull = true;
    $total = 0.0;

    $isLastEntry = $counter == count($infos);

    if($timestamp < $minTimestamp && ! $isLastEntry)
    {
      continue;
    }
    if($lastTimestamp != null && ($timestamp - $lastTimestamp) < $minInterval && ! $isLastEntry)
    {
      continue;
    }
    $lastTimestamp = $timestamp;

    foreach ($values as $device => $info) {
      if (!array_key_exists($device, $devices)) {
        $devices[$device] = array();
      }
      if (false && $info["valueOfMyCoins"] == 0) {
        $isFull = false;
        continue;
      }
      if($info["valueOfMyCoins"] >= 5) {
        $devices[$device][$timestamp] = $info["valueOfMyCoins"];
      }
      $total += $info["valueOfMyCoins"];
    }
    if ($isFull) {
      $devices['TOTAL'][$timestamp] = $total;
      $last = $values;
      if($firstTotal === null)
      {
        $firstTotal = $total;
      }
      $lastTotal = $total;
    }

  }
  $devices = array_filter($devices);


  $normalDelta = 0;
  foreach($refundsAndWithdrawal as $actionTimestamp => $theLocalVariation)
  {
    if($actionTimestamp >= $minTimestamp)
    {
      $normalDelta += $theLocalVariation;
    }
  }

  $variation = ($lastTotal - $firstTotal);
  $normalVariation = ($variation - $normalDelta);
  $variationPct = ($normalVariation) / $firstTotal;
  $charts[$chartIndex]['devices'] = $devices;
  $charts[$chartIndex]['variation'] = $variation;
  $charts[$chartIndex]['normalVariation'] = $normalVariation;
  $charts[$chartIndex]['variationPct'] = $variationPct;






  $devices = array();
  $minTimestamp = $chartDef['minTimestamp'];
  $minInterval = $chartDef['minInterval'];

  $lastTimestamp = null;
  $firstValue = null;


  $isLastEntry = false;
  $counter = 0;
  foreach ($infos as $timestamp => $values) {
    $counter++;
    $isFull = true;
    $total = 0.0;

    $isLastEntry = $counter == count($infos);

    if($timestamp < $minTimestamp && ! $isLastEntry)
    {
      continue;
    }
    if($lastTimestamp != null && ($timestamp - $lastTimestamp) < $minInterval)
    {
      continue;
    }
    $lastTimestamp = $timestamp;

    foreach ($values as $device => $info) {
      if (!array_key_exists($device, $devices)) {
        $devices[$device] = array();
      }
      $devices[$device][$timestamp] = $info["valueInEuro"];
    }
  }

  //set order
  $newDevices = array();
  foreach($actualValues as $device=>$total)
  {
    if($total < 5)
    {
      continue;
    }
    $newDevices[$device] = array();
  }

  foreach($devices as $device=>$values) {
    $newDevices[$device] = array();
    $newDevices[$device]['values'] = array();



    $isFirst = true;
    $refValue = null;

    if( ! array_key_exists($device, $actualValues) || $actualValues[$device] < 5)
    {
      continue;
    }
    $newDevices[$device]['total'] = $actualValues[$device];
    $newDevices[$device]['widthRatio'] = max(1, round($widthRatio * $actualValues[$device]));

    foreach ($values as $timestamp => $value) {
      if ($isFirst) {
        $refValue = $value;
        //$value = 100;
        $isFirst = false;
      }
      //$newValue = 100 - ((100 * $value) / $refValue);
      $newValue = 100 * ($value - $refValue) / $refValue;
      $newDevices[$device]['values'][$timestamp] = $newValue;
    }
  }

  $charts[$chartIndex]['devicesVariation'] = $newDevices;





}

$actualValues = array();

foreach ($infos as $timestamp => $values) {
  $isFull = true;
  $total = 0.0;
  $actualValues = array();
  foreach ($values as $device => $info) {
    if($info["valueOfMyCoins"]) {
      $total += $info["valueOfMyCoins"];
      $actualValues[$device] = $info["valueOfMyCoins"];
    }
  }
}

$_keys = array_keys($infos);
$lastTimestamp = end($_keys)+3600;

arsort($actualValues, SORT_NUMERIC);
?><html>
<head>
  <meta http-equiv="refresh" content="100">
  <title>Coin control center - <?php echo $total; ?>€</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script   src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <script src="./Chart.js"></script>
  <script src="./md5.min.js"></script>
  <script src="./coins.js"></script>
  <script src="https://npmcdn.com/Chart.Zoom.js@latest/Chart.Zoom.min.js"></script>

</head>
<body>


<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <h1>Total : <?php echo round($total, 2); ?>€ on <?php echo date('H:i', $lastTimestamp); ?></h1>
      <?php foreach($actualValues as $crypto=>$value): ?>
        <?php if($value >= 3): ?>
          <?php
          echo ' ( '.$crypto.' : '.round($value).' € ) ';
          ?>
        <?php endif; ?>
      <?php endforeach; ?>

      <br />
      <?php foreach($charts as $chartIndex=>$chartInfo): ?>
        <?php
        $devices = $chartInfo['devices'];
        $devicesVariation = $chartInfo['devicesVariation'];

        $name = $chartInfo['name'];
        $variation = $chartInfo['variation'];
        $normalVariation = $chartInfo['normalVariation'];
        $variationPct = $chartInfo['variationPct'];


        ?>
        <div class="row">
          <h2><?php echo $name; ?> | <?php echo round($variationPct*100, 2); ?>% | <?php echo round($normalVariation, 2); ?>€<?php if($normalVariation != $variation): ?> | <?php echo round($variation, 2); ?>€<?php endif; ?> </h2>
          <div class="col-md-1">&nbsp;</div>
          <div class="col-md-5">
            <?php foreach($devices as $deviceName=>$values): ?>
            <?php if(empty($values)){continue;}?>
            <?php
            $last = end($values);

            $valuesVar = $devicesVariation[$deviceName]['values'];
            $lastVar = end($valuesVar);
            echo "<p>".$deviceName.' : '.round($last).' ( '.round($lastVar). ' %) </p>';
            ?>
            <?php endforeach; ?>
          </div>
          <div class="col-md-5">

          </div>
          <div class="col-md-1">&nbsp;</div>
        </div>
      <?php endforeach; ?>


    </div>
  </div>
</div>
</body>
</html>