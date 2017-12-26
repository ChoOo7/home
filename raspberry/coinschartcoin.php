<?php
require_once(__DIR__.'/coinmon.class.php');
$cm = new Coinmon();
$infos = $cm->getCalculatedValues();
$devices = array();
$totalSeries = array();
$last = array();
$lastTotal = 0.0;

$charts = array();

/*
$charts[] = array(
    'name'=>'last 30min',
    'minInterval'=>5,
    'minTimestamp'=>time()-1800
);

$charts[] = array(
    'name'=>'last 1H',
    'minInterval'=>5,
    'minTimestamp'=>time()-3600*1
);
*/
$charts[] = array(
    'name'=>'last 6H',
    'minInterval'=>10,
    'minTimestamp'=>time()-3600*6
);

$charts[] = array(
    'name'=>'last 24H',
    'minInterval'=>60,
    'minTimestamp'=>time()-3600*24
);

$charts[] = array(
    'name'=>'last week',
    'minInterval'=>60*60,
    'minTimestamp'=>time()-3600*24*7
);

$charts[] = array(
    'name'=>'last month',
    'minInterval'=>60*60*2,
    'minTimestamp'=>time()-3600*24*30
);

$charts[] = array(
    'name'=>'all',
    'minInterval'=>60*60*2,
    'minTimestamp'=>time()-3600*24*365*12
);

foreach($charts as $chartIndex=>$chartDef) {
  $devices = array();
  $minTimestamp = $chartDef['minTimestamp'];
  $minInterval = $chartDef['minInterval'];

  $lastTimestamp = null;
  $firstValue = null;

  foreach ($infos as $timestamp => $values) {
    $isFull = true;
    $total = 0.0;

    if($timestamp < $minTimestamp)
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
      if (false && $info["valueInEuro"] == 0) {
        $isFull = false;
        continue;
      }
      $devices[$device][$timestamp] = $info["valueInEuro"];
    }
  }

  $newDevices = array();
  foreach($devices as $device=>$values) {
    $newDevices[$device] = array();

    $isFirst = true;
    $refValue = null;

    foreach ($values as $timestamp => $value) {
      if ($isFirst) {
        $refValue = $value;
        //$value = 100;
        $isFirst = false;
      }
      $newValue = (100 * $value) / $refValue;
      $newDevices[$device][$timestamp] = $newValue;
    }
  }

  $charts[$chartIndex]['devices'] = $newDevices;
}

?><html>
<head>
  <title>Coin control center</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script   src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <script src="./Chart.js"></script>
  <script src="https://npmcdn.com/Chart.Zoom.js@latest/Chart.Zoom.min.js"></script>

</head>
<body>


<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <br />
      <?php foreach($charts as $chartIndex=>$chartInfo): ?>
        <?php
        $devices = $chartInfo['devices'];
        $name = $chartInfo['name'];

        ?>
        <div class="row">
          <h2><?php echo $name; ?></h2>
          <div class="col-md-2">&nbsp;</div>
          <div class="col-md-8">
            <canvas id="myChart<?php echo $chartIndex; ?>" style="width: 80%;height: 400px;"></canvas>
          </div>
          <div class="col-md-2">&nbsp;</div>
        </div>
        <script>
        function getRandomColor() {
          var letters = '0123456789ABCDEF';
          var color = '#';
          for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
          }
          return color;
        }

  dataSet = [];
  <?php $first = true; ?>
  <?php foreach($devices as $deviceName=>$values): ?>
  color = getRandomColor();
  theSet = {
              label: '<?php echo $deviceName; ?>',
              showLine: true,
              backgroundColor: color,
              borderColor: color,
              fill: false,
              data: <?php
          $toDisplay = array();
          foreach($values as $timestamp=>$value){
            $toDisplay[] = array('x'=>$timestamp, 'y'=>$value);
          }

          echo json_encode($toDisplay);
          ?>,
              borderWidth: 1
          };
          dataSet.push(theSet);

  <?php endforeach; ?>
  ctx = document.getElementById("myChart<?php echo $chartIndex; ?>").getContext('2d');
  myChart = new Chart(ctx, {
      type: 'scatter',
      data: {
          datasets: dataSet
      },
      options: {
          tooltips: {
              callbacks: {
                  label: function(tooltipItem, data) {
                      var currencyLabel = dataSet[tooltipItem.datasetIndex].label;
                      var dt = new Date(tooltipItem.xLabel * 1000);
                      var dateString = dt.getDate()+ "/" + (dt.getMonth() + 1)+'/'+dt.getFullYear()+' '+dt.getHours()+':'+dt.getMinutes() ;
                      return currencyLabel + ' : ' + tooltipItem.yLabel+' % on '+dateString;
                  }
              }
          },
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero:false,
                      callback: function(value, index, values) {
                          return value+' %';
                      }

                  }
              }],

              xAxes: [{
                  ticks: {
                      beginAtZero:false,
                      callback: function(value, index, values) {
                          var dt = new Date(value * 1000);
                          var dateString = dt.getDate()+ "/" + (dt.getMonth() + 1)+'/'+dt.getFullYear()+' '+dt.getHours()+':'+dt.getMinutes() ;
                          return dateString;
                      }

                  }
              }],

          },
          pan: {
              enabled: true,
              mode: 'xy'
          },
          zoom: {
              enabled: true,
              mode: 'xy',
              limits: {
                  max: 10,
                  min: 0.5
              }
          }
      }
  });
  </script>
<?php endforeach; ?>

    </div>
  </div>
</div>
</body>
</html>