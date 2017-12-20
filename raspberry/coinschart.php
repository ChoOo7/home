<?php
require_once(__DIR__.'/coinmon.class.php');
$cm = new Coinmon();
$infos = $cm->getCalculatedValues();
$devices = array('TOTAL'=>array());
$totalSeries = array();
foreach($infos as $timestamp=>$values)
{
  $isFull = true;
  $total = 0.0;
  foreach($values as $device=>$info)
  {
    if( ! array_key_exists($device, $devices))
    {
      $devices[$device] = array();
    }
    if($info["valueOfMyCoins"] == 0)
    {
      $isFull = false;
      continue;
    }
    $devices[$device][$timestamp] = $info["valueOfMyCoins"];
    $total += $info["valueOfMyCoins"];
  }
  if($isFull)
  {
    $devices['TOTAL'][$timestamp] = $total;

  }
}
?><html>
<head>
  <title>Home control center - <?php echo $total; ?>€</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script   src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  <script src="./Chart.js"></script>

</head>
<body>


<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <h1>Total : <?php echo $total; ?>€</h1>

      <canvas id="myChart" style="width: 100%;height: 400px;"></canvas>
      <script>
      function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
          color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
      }

var dataSet = [];
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
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'scatter',
    data: {
        datasets: dataSet
    },
    options: {
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var dt = new Date(tooltipItem.xLabel * 1000);
                    var dateString = dt.getDate()+ "/" + (dt.getMonth() + 1)+'/'+dt.getFullYear()+' '+dt.getHours()+':'+dt.getMinutes() ;
                    return Math.round(tooltipItem.yLabel)+' € on '+dateString;
                }
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:false,
                    callback: function(value, index, values) {
                        return Math.round(value)+' €';
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

        }
    }
});
</script>


    </div>
  </div>
</div>
</body>
</html>