<?php

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
    'minInterval'=>60*30,
    'minTimestamp'=>time()-3600*24
);

$charts[] = array(
    'name'=>'last week',
    'minInterval'=>3600*3,
    'minTimestamp'=>time()-3600*24*7
);

$charts[] = array(
    'name'=>'last month',
    'minInterval'=>3600*12,
    'minTimestamp'=>time()-3600*24*30
);

$charts[] = array(
    'name'=>'all',
    'minInterval'=>3600*24,
    'minTimestamp'=>time()-3600*24*365*12
);


$refundsAndWithdrawal = array();

$refundsAndWithdrawal[strtotime('2017-12-27 19:00:00')] = "100";

