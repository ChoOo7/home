<?php
class Kodi
{

  protected $debug = false;
  protected $i = 1;

  public function getIp()
  {
    return "192.168.0.121:8080";
  }

  protected function sendCommand($url, $cmd = null, $async = true)
  {
    $fullUrl = 'http://'.$this->getIp().''.$url;
    $data = $cmd ? '--data-binary '.escapeshellarg($cmd) : '';
    
    $cmd = "curl -H 'Content-Type: application/json' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'X-Requested-With: XMLHttpRequest'  ".$data.' '.escapeshellarg($fullUrl).' 2>&1';
    if($async)
    {
      $cmd = ''.$cmd.' &';
    }
    if($this->debug)
    {
      echo "\n".$cmd;
    }
    $return = null;
    ob_start();
    exec($cmd, $return);
    ob_clean();
    ob_end_flush();
    if(is_array($return))
    {
      if($this->debug)
      {
        echo implode("\n", $return);
      }
      return json_decode(implode("\n", $return), true);
    }
    return $return;
  }

  public function clearPlaylist()
  {
    $cmd = '{"jsonrpc":"2.0","method":"Playlist.Clear","id":'.($this->i++).',"params":[0]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }

  public function addArtistToPlaylist($artistId)
  {
    $cmd = '{"jsonrpc":"2.0","method":"Playlist.Add","id":'.($this->i++).',"params":[0,{"artistid":'.$artistId.'}]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }

  public function addSongToPlaylist($songId)
  {
    $cmd = '{"jsonrpc":"2.0","method":"Playlist.Add","id":'.($this->i++).',"params":[0,{"songid":'.$songId.'}]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }


  public function playPlaylist()
  {
    $cmd = '{"jsonrpc":"2.0","method":"Player.Open","id":'.($this->i++).',"params":[{"playlistid":0,"position":0}]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }
  
  public function setShuffle()
  {
    $cmd = '{"jsonrpc":"2.0","method":"Player.SetShuffle","id":'.($this->i++).',"params":[0,true]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }
  
  public function stop()
  {
    $cmd = '{"jsonrpc":"2.0","method":"Player.Stop","id":'.($this->i++).',"params":[0]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }
}