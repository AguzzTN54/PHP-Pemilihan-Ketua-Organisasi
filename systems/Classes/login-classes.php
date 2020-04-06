<?php

class Login{
  public $level;
  public $tblPemilih;
  public $tblSuara;

  function __construct($username){
    $tabel = $GLOBALS['tblUser'];
    $this->tblSuara = $GLOBALS['tblSuara'];
    $this->tblPemilih = $tabel;
    $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabel` WHERE `username`='$username'");
    
    if(mysqli_num_rows($query)>0){
      $data= mysqli_fetch_assoc($query)['level'];
      $this->level = $data;
    }
  }

  function masuk($username, $password){
    $tabel = $this->tblPemilih;
    if($this->level==1){
      $pass = md5($password);
    }else{
      $pass = enkripsi('encrypt',$password);
    }
    $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabel` WHERE `username`='$username' and `password`='$pass'");

    if(mysqli_num_rows($query)>0){
      $data = mysqli_fetch_assoc($query);
      $idUser = $data['idPemilih'];
      if($data['level']==1){
        $_SESSION['admlogin'] = true;
        $_SESSION['idAdmin'] = $idUser;
        $_SESSION['adminName'] = $username;
      }else{
        $_SESSION['login'] = true;
        $_SESSION['idUser'] = $idUser;
        $_SESSION['username'] = $username;
      }

      $attempt = ($data['loginAttempt'] + 1);
      $update = mysqli_query($GLOBALS['db'], "UPDATE `$tabel` SET `loginAttempt`='$attempt' WHERE `idPemilih`='$idUser'");
      return true;
    }else{
      return false;
    }
  }// Akhir Fungsi Masuk()

  function cekLevel(){
    if($this->level==1){
      // header('location:'.$GLOBALS['alamat'].$GLOBALS['adminDir']);
      $json['success'] = true;
      $json['msg'] = '<strong>Login Admin Sukses!</strong> Kamu berhasil login sebagai Admin. Kamu bisa konfigurasi situs ini sekarang.
      <script>window.location.replace("'.$GLOBALS['alamat'].$GLOBALS['adminDir'].'")</script>';
      
    }else{
      // header('location:'.$GLOBALS['alamat']);
      $json['success'] = true;
      $json['msg'] = '<strong>Login Sukses!</strong> Kamu bisa berikan suaramu Sekarang :D';
    }
    header('Content-Type: application/json');
    $json['level'] = $this->level;
    echo json_encode($json);
      
  } //akhir Fungsi ceklevel


  function cekPilihan($dataToShow=null){
    $tabel = $this->tblSuara;
    $pemilih = $_SESSION['idUser'];

    if(!empty($pemilih)){
      $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabel` WHERE `idPemilih`='$pemilih'");
      if(mysqli_num_rows($query)>0){
        $data = mysqli_fetch_assoc($query);
        $idKandidat = $data['idKandidat'];
        $time = $data['waktu'];
       
        // Return Ketika Data dditemukan
        if($dataToShow=='kandidat'){
          return $idKandidat;
        }elseif($dataToShow=='waktu'){
          return $time;
        }else{
          return true;
        }
      }else{
        return false; // Return belum memilih
      }
    }else{
      return 'Kamu Belum Login'; // ID Pemilih Kosong
    }
  }//Akhir fungsi cekPilihan
}//Akhir Class Login


?>