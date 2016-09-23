#!/usr/bin/env php 
<?php
@mkdir("/tmp/torrents");
$torrents = array();

$base_url = 'http://www.cpasbien.cm';
$uri = [
	'films' => '/view_cat.php?categorie=films',
	//'series' => '/view_cat.php?categorie=series',
	//'musique' => '/view_cat.php?categorie=musique',
	//'ebook' => '/view_cat.php?categorie=ebook',
	//'logiciels' => '/view_cat.php?categorie=logiciels',
	//'jeux-pc' => '/view_cat.php?categorie=jeux-pc',
	//'jeux-consoles' => '/view_cat.php?categorie=jeux-consoles'
];

function _is_curl() {
    return  (in_array  ('curl', get_loaded_extensions()))?true:false;
}


$_POST['f'] = 'films';

$url = $base_url.$uri[$_POST['f']];
$p = file_get_contents($url);
preg_match_all('/<a href="(http:\/\/www.cpasbien.cm\/dl-torrent\/[^"]*)"[^>]*>[^>]*>([^<]+)/im', $p, $m);
$i=0;
$mh = curl_multi_init();
$ch = array();
$dd = array();
$i = 0;
while(isset($m[1][$i]) && $m[1][$i]) {
	$ch[$i] = curl_init();
	curl_setopt_array($ch[$i],
		Array(
			CURLOPT_URL => $m[1][$i],
			//CURLOPT_USERAGENT => 'GheopReader',
			CURLOPT_TIMEOUT => 5,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_ENCODING => 'UTF-8'
			)
		);
	curl_multi_add_handle($mh, $ch[$i]);
	$dd[$i] = $m[2][$i];
	$i++;
}

$running=null;

do {
	curl_multi_exec($mh,$running);
} while ($running > 0);

for($j=0;$j<$i;$j++) {
	preg_match('/<\/strong><\/p>(<p>.*<strong>.*<\/strong>.*<\/p>)?.*<p>(.*)<\/p>.*<b>.*<\/b>.*<a href="(.*\.torrent)"/smi', curl_multi_getcontent($ch[$j]), $z);
	if(isset($z[3]))
	{
            $torrent = $base_url.$z[3];
            $torrents[] = $torrent;
        }
}

error_reporting(E_ALL);

foreach($torrents as $torrent)
{
    $cnt = file_get_contents($torrent);
    file_put_contents("/tmp/torrents/".basename($torrent), $cnt);
}    
