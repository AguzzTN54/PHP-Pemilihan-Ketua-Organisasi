<?php

class Suara{
  public $tampil;
  public $jumlahSah;
  public $tblUser;
  public $tblKandidat;
  public $tblSuara;
  
  public function __construct(){
    $tabel = $GLOBALS['tblSuara'];
    $this->tblUser = $GLOBALS['tblUser'];
    $this->tblKandidat = $GLOBALS['tblKandidat'];
    $this->tblSuara = $tabel;
    
    $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabel` ORDER BY `idSuara` ASC");
    $jumlah = mysqli_num_rows($query);

    if($jumlah>0){
      while($data[] = mysqli_fetch_assoc($query));
      $this->tampil = $data;
      $this->jumlahSah = $jumlah;
      return true;
    }else{
      $this->jumlahSah = 0;
      return false;
    }
  }// Akhir Constructor

  function kandidat($id,$aksi=null){
    $tabel = $this->tblSuara;
    $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabel` WHERE `idKandidat`='$id'");
    $count = mysqli_num_rows($query);

    if($aksi=='hitung'){
      return $count;
    }else{

      if($count>0){
        while($data[] = mysqli_fetch_assoc($query));
        return $data;
      }else{
        return 'Belum memiliki Suara';
      }
    }
  }//Akhir Fungsi kandidat() untuk mendapatkan jumlah suara masing masing kandidat

  function cekPerolehan($type=null){
    $tabelKandidat = $this->tblKandidat;
    $tabelSuara = $this->tblSuara;

    $kandidat = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabelKandidat`");
    while($data=mysqli_fetch_assoc($kandidat)){
      $id = $data['idKandidat'];
      $suara = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabelSuara` WHERE `idKandidat`='$id'");
      $num[] = mysqli_num_rows($suara);
    }

    if($type=='tertinggi'){
      return max($num);
    }elseif($type=='terendah'){
      return min($num);
    }else{
      return 'Tidak Ditemukan';
    }
  }//Akhir Cek perolehan suara tertinggi terendah

  function dataSuara($jenis=null){
    $tabelUser = $this->tblUser;
    $tabelSuara = $this->tblSuara;
    
    if($jenis=='golput' || $jenis=='sudahMasuk'){
      $cekLogin = mysqli_query($GLOBALS['db'], "SELECT `idPemilih` FROM `$tabelUser` WHERE `loginAttempt`>0 and `level`=2");

      if($jenis=='golput'){
        while($data=mysqli_fetch_assoc($cekLogin)){
          $idpemilih = $data['idPemilih'];
          $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabelSuara` WHERE `idPemilih`='$idpemilih'");
          if(mysqli_num_rows($query)==0){
            $num[] = 1;
          }else{
            $num[] = 0;
          }
        }
        return array_sum($num); //Menjumlahkan semua suara
      }else{
        return mysqli_num_rows($cekLogin);
      }

    }else{
      if($jenis == 'sisaSuara'){
        $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabelUser` WHERE `loginAttempt`=0 and `level`=2");
      }else{
        $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabelUser` WHERE `level`=2");
      }
      return mysqli_num_rows($query);
    }
  }

}

?>