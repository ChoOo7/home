<?php
require_once(__DIR__.'/config/constants.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$return = array();
$dataDirectory = __DIR__.'/data/';
foreach(glob($dataDirectory.'/*') as $filepath)
{
  $name = basename($filepath);
  $value = file_get_contents($filepath);

  $name = str_replace(IKEA_LAMP01_ADDR.'-', 'ikeaLamp1_', $name);

  $return[$name] = $value;
}

echo json_encode($return);
