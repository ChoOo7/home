<?php

$install = in_array('install',$argv);

function myCopy($src, $dest)
{

  if(file_exists('/etc/hosts')) {
    $cmd = 'cp ' . $src . ' '.$dest;
    echo "\n" . $cmd . "\n";
    passthru($cmd);
  }else {
    //windows
    $cmd = 'copy ' . $src . ' '.$dest;
    echo "\n" . $cmd . "\n";
    passthru($cmd);
  }
}


$files = array();

if( ! $install) {
  $files['package.json'] = 'package.json';
}
$files['webpack.config.js'] = 'webpack.config.js';


foreach($files as $from=>$to) {
  //bash
  myCopy($from, 'src'.DIRECTORY_SEPARATOR.$from);

  $cmd = 'docker-compose exec home mv src/'.$from.' ./'.$to;
  echo "\n".$cmd."\n";
  passthru($cmd);
}


//reverse order
$files = array();
if($install) {
  $files['package.json'] = 'package.json';
}
$files['yarn.lock'] = 'yarn.lock';
$files['package-lock.json'] = 'package-lock.json';

foreach($files as $from=>$to) {


  $cmd = 'docker-compose exec home cp  ./'.$to.' src/'.$from;
  echo "\n".$cmd."\n";
  passthru($cmd);

  myCopy('src'.DIRECTORY_SEPARATOR.$from, $from);

  @unlink('src'.DIRECTORY_SEPARATOR.$from);

}

