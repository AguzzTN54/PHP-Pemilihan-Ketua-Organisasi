<?php

class Online{
  public $path;
  public $tampil;
  public $show;

  function __construct(){
    $path = 'systems/.deviceOnline';
    if(!file_exists($path)){
      $path = '../systems/.deviceOnline';
    }
    $tampil = unserialize(file_get_contents($path));
    $reverse = array_reverse($tampil);

    $this->path = $path;
    $this->tampil = $tampil;
    $this->show = $reverse;
  }

  function cek($aksi=null){
    $cek = array_filter($this->tampil, function($var){
      $sid = ltrim(trim(explode('; ',$_SERVER['HTTP_COOKIE'])[0]),'PHPSESSID=');
      return ($var['sid']==$sid);
    });
    // $cek = array_search($browser, array_column($this->tampil,'device'));
    $id = array_keys($cek);
    
    if(count($id)>0){
      if($aksi=='id'){
        return $id[0];
      }else{
        return true;
      }
    }else{
      return false;
    }
  }

  function update($do=null){
    $path = $this->path;
    $platform = getBrowser();
    $arrayFinal ='';
    $sid = ltrim(trim(explode('; ',$_SERVER['HTTP_COOKIE'])[0]),'PHPSESSID=');
    $ip = $_SERVER['REMOTE_ADDR'];
    if(@$_SESSION['login']){
      $login = 1;
    }else{
      $login = 0;
    }

    $template = array(
        'login' => $login,
        'sid' => $sid,
        'device' => $platform,
        'IP_ADDR' => $ip,
        'last_activity' => time(),
    );

    if($do=='edit'){
      $tampil = unserialize(file_get_contents($path));
    
      if($this->cek()){
        $index = $this->cek('id');
        $arr = array(
          $index => $template,
        );
        
        $arrayFinal = array_replace($tampil, $arr);
      }

      // Akhir Edit Array
    }elseif($do=='hapus'){
    
      $tampil = unserialize(file_get_contents($path));
      $filtered = array_filter($tampil, function($var){
        //memilih  User yang tidak refresh halaman selama 5 menit
        return ($var['last_activity'] < (time()-300));
      });
      if(count($filtered) > 0){
        $index = array_keys($filtered);
        foreach($index as $del){
          $d = array($del => 'xy');
          $arrayFinal = array_diff_key($tampil, $d);
        }
      }
      // Akhir Hapus Array
    }else{
      //Tambah Data ke dalam Array
      $tampil = unserialize(file_get_contents($path));
      if(!$this->cek()){
        $arr = array($template);
        if(!empty($tampil)){
          $arrayFinal = array_merge($tampil, $arr);
        }else{
          $arrayFinal = $arr;
        }
      }// Akhir cek ketiadaan nilai pada array
    }

    if($arrayFinal!=''){
      $isi = serialize($arrayFinal);
      $buka = fopen( $path, 'w' );
      $tulis = fwrite($buka, $isi);
      fclose($buka);
      $this->tampil = $arrayFinal;
      $this->show = array_reverse($arrayFinal);
    }

    if(@$tulis){
      return true;
    }else{
      return false;
    }
  }//Akhir Fungsi cetak
}
?>