<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$config = $_GET['config'];
$data = array();
switch($config)
{
  case "chooo7":
    $data = glob('/servers/chooo7/*');
    break;
  case "downloaded":
    $data = glob('/media/data/downloaded/*');
    break;
  case "antho":
    $data = glob('/servers/antho/home/simon/samba/*');
    break;
  default:
    throw new Exception("unknow data");
}
echo empty($data) ? json_encode(false) : json_encode(true);
