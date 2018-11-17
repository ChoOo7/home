<?php

$sendToProd = in_array('prod', $argv);
$noMin = in_array('nomin', $argv);
$noRefresh = in_array('norefresh', $argv) || in_array('noRefresh', $argv);


if(false &&  ! $noRefresh) {
  $cmd = 'php refreshDockerFiles.php';
  echo "\n" . $cmd . "\n";
  passthru($cmd);
}


$packageJson = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'package.json'), true);
$versionNumber = $packageJson['version'];

$distDir = __DIR__.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR;
$targetDir = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
/*

$webTestPictureLibDir = __DIR__.DIRECTORY_SEPARATOR.'web-test'.DIRECTORY_SEPARATOR.'picturelib'.DIRECTORY_SEPARATOR;
$packageDir = __DIR__.DIRECTORY_SEPARATOR.'package'.DIRECTORY_SEPARATOR;
$thisPackageDir = $packageDir.'wedia-standard'.DIRECTORY_SEPARATOR.$versionNumber.DIRECTORY_SEPARATOR;
*/



$cmd = 'docker-compose exec home npm run build';
echo "\n".$cmd."\n";
passthru($cmd);

$files = glob($distDir.'/*');
foreach($files as $file) {
  copy($file, $targetDir . DIRECTORY_SEPARATOR . basename($file));
}
