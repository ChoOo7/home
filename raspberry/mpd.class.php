<?php
class Mpd
{

  protected $debug = false;
  protected $i = 1;

  public function sendCommand($cmd)
  {
    $output = array();
    //var_dump($cmd);
    exec($cmd, $output);
    return $output;
    //var_dump($output);
  }

  public function clearPlaylist()
  {
    $cmd = $this->mopidy().' clear';
    $this->sendCommand($cmd);
  }

  public function mpc()
  {
    return 'mpc';
  }

  public function mopidy()
  {
    return 'mpc --port=6601';
  }

  public function lsArtistToPlaylist($artistDirectory)
  {
    $artistDirectory = "Local media/Artists/".$artistDirectory;
    //$cmd = 'mpc ls '.escapeshellarg($artistDirectory).' | mpc add';
    $cmd = $this->mopidy().' ls '.escapeshellarg($artistDirectory);
    return $this->sendCommand($cmd);
  }

  public function addArtistToPlaylist($artistDirectory)
  {
    $artistDirectory = "Local media/Artists/".$artistDirectory;
    //$cmd = 'mpc ls '.escapeshellarg($artistDirectory).' | mpc add';
    $cmd = $this->mopidy().' ls '.escapeshellarg($artistDirectory).' | '.$this->mopidy().' add';
    $this->sendCommand($cmd);
  }
  public function addUrlToPlaylist($artistDirectory)
  {
    $cmd = $this->mopidy().' add '.escapeshellarg($artistDirectory).'';
    $this->sendCommand($cmd);
  }
/*
  public function addSongToPlaylist($songId)
  {
    $cmd = '{"jsonrpc":"2.0","method":"Playlist.Add","id":'.($this->i++).',"params":[0,{"songid":'.$songId.'}]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }
  
  public function getSongsOfArtist($artistId)
  {
    $cmd = '{"jsonrpc":"2.0","method":"AudioLibrary.GetSongs","id":'.($this->i++).',"params":[["title","file","thumbnail","artist","artistid","album","albumid","lastplayed","track","year","duration"],{"start":0},{"method":"track","order":"ascending","ignorearticle":true},{"artistid":'.$artistId.'}]}';
    $url = "/jsonrpc";
    $return = $this->sendCommand($url, $cmd, false);
    return $return['result']['songs'];
  }
*/
/*
  public function playPlaylist()
  {
    $cmd = '{"jsonrpc":"2.0","method":"Player.Open","id":'.($this->i++).',"params":[{"playlistid":0,"position":0}]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }
*/
  /*
  public function clean()
  {
    $cmd = '{"jsonrpc":"2.0","method":"AudioLibrary.Scan","id":'.($this->i++).',"params":[]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }

  public function scan()
  {
    $cmd = '{"jsonrpc":"2.0","method":"AudioLibrary.Scan","id":'.($this->i++).',"params":[]}';
    $url = "/jsonrpc";
    $this->sendCommand($url, $cmd, false);
  }
  */

  public function setMpcSourceMopidy()
  {
    $cmd = $this->mpc().' clear && '.$this->mpc().' add "http://home.chooo7.com:8000/mopidy" && mpc play' ;
    $this->sendCommand($cmd);
  }
  
  public function setShuffle($shuffle = true)
  {
    $cmd = $this->mopidy().' random '.($shuffle ? 'on' : 'off');
    $this->sendCommand($cmd);
  }

  
  public function addSpotifyArtist($artistName)
  {
    https://open.spotify.com/artist/0gOsZcHl7H3ewXVIEnWFZX?si=pxkRC6lgQMWkST5Cc8riCQ
    $cmd = $this->mopidy().' search any '.escapeshellarg($artistName).' | grep spotify:artist | head -n 1 | '.$this->mopidy().' add';
    $this->sendCommand($cmd);
  }


  public function stop()
  {
    $cmd = $this->mopidy().' stop';
    $this->sendCommand($cmd);
  }

  public function play()
  {
    $cmd = $this->mopidy().' play';
    $this->sendCommand($cmd);

    $cmd = $this->mpc().' play';
    $this->sendCommand($cmd);
  }

  public function addRemoteM3u8($url)
  {
    $cmd = $this->mopidy().' add '.escapeshellarg($url);
    $this->sendCommand($cmd);
  }

  public function isPlayingInter($setting = null)
  {
    $file = '/tmp/inter';
    if($setting === null) {
      return file_exists($file);
    }else{
      if($setting)
      {
        file_put_contents($file, date('Y-d-m H:i:s'));
        @chmod($file, 0777);
      }else{
        @unlink($file);
      }
    }
  }


  public function pause()
  {
    $cmd = $this->mopidy().' pause';
    $this->sendCommand($cmd);
  }

  public function cuisineOn()
  {
    $cmd = 'echo "connect 00:02:3C:41:45:08" | bluetoothctl';
    $this->sendCommand($cmd);

    $cmd = $this->mpc().' enable 1';
    $this->sendCommand($cmd);
    $this->syncOutput();
  }

  public function cuisineOff()
  {
    $cmd = $this->mpc().' disable 1';
    $this->sendCommand($cmd);
  }

  public function denonOn()
  {
    $cmd = $this->mpc().' enable 2';
    $this->sendCommand($cmd);

    $this->syncOutput();
  }

  public function denonOff()
  {
    $cmd = $this->mpc().' disable 2';
    $this->sendCommand($cmd);
  }

  public function syncOutput()
  {
    $cmd = $this->mpc().' pause';
    $this->sendCommand($cmd);
    $cmd = $this->mpc().' play';
    $this->sendCommand($cmd);
  }

  public function addIcecastToMpd()
  {
    $cmd = $this->mpc().' clear && '.$this->mpc().' add "http://127.0.0.1:8000/mopidy" && '.$this->mpc().' play';
    $this->sendCommand($cmd);
  }

}
