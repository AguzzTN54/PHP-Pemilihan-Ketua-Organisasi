<?php

class User{
  public $tblUser;
  public $tampil;
  public $jumlahUser;

  function __construct(){
    $tabel = $GLOBALS['prefix'].'pemilih';
    $this->tblUser = $tabel;
    $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabel` WHERE `level`=2 ORDER BY `idPemilih` ASC");
    $count = mysqli_num_rows($query);
    while($data[] = mysqli_fetch_assoc($query));

    $this->tampil =  $data;
    $this->jumlahUser = $count;

  }//akhir Construct

  function cek($username, $type=null){
    $tabel = $this->tblUser;

    if($type=='id'){
      $query = mysqli_query($GLOBALS['db'], "SELECT `username` FROM `$tabel` WHERE `idPemilih`='$username' and `level`=2");
    }else{
      $query = mysqli_query($GLOBALS['db'], "SELECT `username` FROM `$tabel` WHERE `username`='$username' and `level`=2");
    }
    if(mysqli_num_rows($query)>0){
      return true;
    }else{
      return false;
    }
  }

  function show($id, $kolom){
    $tabel = $this->tblUser;
    $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabel` WHERE `idPemilih`='$id' and `level`=2");
    
    if(mysqli_num_rows($query)>0){
      $data = mysqli_fetch_assoc($query)[$kolom];
      return $data;
    }else{
      return 'Data Tidak Ditemukan';
    }
  }
  function set($args){
    $tabel = $this->tblUser;
    $aksi = $args['aksi'];
    $idUser = $args['idPemilih'];
    $nama = $args['namaPemilih'];
    $username = $args['userName'];
    $pass = $args['password'];

    if($aksi == 'hapus'){
      $query = mysqli_query($GLOBALS['db'], "DELETE FROM `$tabel` WHERE `idPemilih`='$idUser'");
      if(mysqli_affected_rows($GLOBALS['db'])){
        $arr['success'] = true;
        $arr['msg'] = 'Berhasil Menghapus '.$nama .' dari daftar pemilih tetap';
      }else{
        $arr['success'] = false;
        $arr['msg'] = $nama.' Gagal dihapus dari daftar pemilih';
      }
    // Akhir Aksi Hapus
    }elseif($aksi =='tambah'){
      if($this->cek($username)){
        $arr['success'] = false;
        $arr['msg'] ='Username sudah dipakai, gunakan username lain !';
      }else{
        if(!empty($username)){
          if(!empty($pass)){
            $password = enkripsi('encrypt',$pass);
            $query = mysqli_query($GLOBALS['db'], "INSERT INTO `$tabel`(`namaPemilih`, `username`, `password`, `level`) VALUES('$nama','$username','$password',2)");
            if($query){
              $arr['success'] = true;
              $arr['msg'] = 'Berhasil Menambahkan pemilih baru';
            }else{
              $arr['success'] = false;
              $arr['msg'] = 'Gagal menambahkan pemilih baru';
            }
          }else{
            $arr['success'] = false;
            $arr['msg'] = 'Password masih kosong, tidak dapat menambahkan Pemilih';
          }
        }else{
          $arr['success'] = false;
          $arr['msg'] = 'Username tidak boleh kosong';
        }
      }//Akhir Cek username
    //Akhir Aksi Tambah
    
    }elseif($aksi=='edit'){
      if(!empty($username)){
        if(!empty($pass)){
          $password = enkripsi('encrypt',$pass);
        }else{
          $password = $this->show($idUser, 'password');
        } 
        $query = mysqli_query($GLOBALS['db'], "UPDATE `$tabel` SET `namaPemilih`='$nama', `username`='$username', `password`='$password' WHERE `idPemilih`='$idUser'");
        
        if($query){
          $arr['success'] = true;
          $arr['msg'] = 'Berhasil mengupdate data Pemilih';
        }else{
          $arr['success'] = false;
          $arr['msg'] = 'Terjadi kesalahan, gagal update data Pemilih';
        }
      }else{
        $arr['success'] = false;
        $arr['msg'] = 'Username tidak boleh kosong';
      }

      //Akhir Aksi Edit
    }else{
      $arr['success'] = false;
      $arr['msg'] = 'Tidak ada aksi dijalankan';
      
    }
    return $arr;
  }
}//Akhir Class User



?>