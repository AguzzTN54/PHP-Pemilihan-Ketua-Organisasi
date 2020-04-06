<?php

class Kandidat{
  public $tampil;
  public $jumlah;
  public $tblKandidat;
  public $tblSuara;

  public function __construct(){
    $tabel = $GLOBALS['tblKandidat'];
    $this->tblSuara = $GLOBALS['tblSuara'];
    $this->tblKandidat = $tabel;
    $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabel` ORDER BY 'idKandidat' ASC");
    $jumlah = mysqli_num_rows($query);
    
    if($jumlah>0){
      while($data[] = mysqli_fetch_assoc($query));
      $this->tampil = $data;
      $this->jumlah = $jumlah;
      return true;
    }else{
      $this->jumlah = 0;
      return false;
    }
  }

  function cek($id){
    $tabel = $this->tblKandidat;
    $query = mysqli_query($GLOBALS['db'], "SELECT * FROM `$tabel` WHERE `idKandidat`='$id'");
    if(mysqli_num_rows($query)>0){
      return $query;
    }else{
      return false;
    }
  }

  function show($id,$kolom){
    if($this->cek($id)){
      $data = mysqli_fetch_assoc($this->cek($id))[$kolom];
    }else{
      $data = 'Data tidak Ditemukan';
    }
    return $data;
  }
  
  function coblos(){
    $tabel = $this->tblSuara;
    $idPemilih = $_SESSION['idUser'];
    $idKandidat = $_POST['idKandidat'];
    $waktu = $GLOBALS['time'];

    if(!empty($idPemilih)){
      $query = mysqli_query($GLOBALS['db'], "INSERT INTO `$tabel`(`idKandidat`, `idPemilih`, `waktu`) VALUES ('$idKandidat', '$idPemilih', '$waktu')");

      if(mysqli_affected_rows($GLOBALS['db'])>0){
        $json['success'] = true;
        $json['msg'] = '<strong>Berhasil Mencoblos!</strong> pilihanmu telah terkirim ke kotak suara, kamu bisa Logout sekarang!';
      }else{
        $json['success'] = false;
        $json['msg'] = '<strong>Gagal Mengirim!</strong> Terjadi Kesalahan, silakan hubungi Operator jika kamu tidak bisa mengatasi masalah ini!';
      }
    }else{
      $json['success'] = false;
      $json['msg'] = '<strongKamu Siapa?</strong> Kamu tidak terdaftar untuk melakukan pencoblosan!, hubungi Operator!';
    }
    return json_encode($json);
  }//Akhir Coblos


  //ADMIN PANEL

  function set($args){
    $tabel = $this->tblKandidat;
    $aksi = $args['aksi'];
    $nama = $args['namaKandidat'];
    $jurusan = $args['jurusan'];
    $visi = $args['visi'];
    $misi = $args['misi'];
    $foto = $args['foto'];
    $editID = $args['idKandidat'];

    if($aksi=='tambah'){
      if(!empty($nama)){
        $query = mysqli_query($GLOBALS['db'], "INSERT INTO `$tabel`(`namaKandidat`, `jurusan`, `visi`, `misi`, `foto`) VALUES ('$nama','$jurusan','$visi','$misi','$foto')");
        if(mysqli_affected_rows($GLOBALS['db'])){
          $arr['success'] = true;
          $arr['msg'] = 'berhasil menambahkan';
        }else{
          unlink('../'.$GLOBALS['dirFotoKandidat'].$foto);
          $arr['success'] = false;
          $arr['msg'] = 'Terjadi kesalahan saat input ke database';
        }
      }else{
        unlink('../'.$GLOBALS['dirFotoKandidat'].$foto);
        $arr['success'] = false;
        $arr['msg'] = 'nama tidak boleh kosong';
      }

    }elseif($aksi == 'edit'){
      $fotoLama = $this->show($editID, 'foto');
      $query = mysqli_query($GLOBALS['db'], "UPDATE `$tabel` SET `namaKandidat`='$nama',`jurusan`='$jurusan',`visi`='$visi',`misi`='$misi',`foto`='$foto' WHERE `idKandidat`='$editID'");

      if($query){
        if($foto != $fotoLama){
          unlink('../'.$GLOBALS['dirFotoKandidat'].$fotoLama);
        }
        $arr['success'] = true;
        $arr['msg'] = 'Berhasil Update Data Kandidat';
        
      }else{
        unlink('../'.$GLOBALS['dirFotoKandidat'].$foto);
        $arr['success'] = false;
        $arr['msg'] = 'Terjadi Kesalahan Saat mengupdate data';
      }

    }elseif($aksi == 'hapus'){
      $id = $args['idKandidat'];
      $hapusNama = $this->show($id, 'namaKandidat');
      $hapusFoto = $this->show($id, 'foto');
      
      if($this->cek($id)){
        $query = mysqli_query($GLOBALS['db'], "DELETE FROM `$tabel` WHERE `idKandidat`='$id'");
        if($query){
          unlink('../'.$GLOBALS['dirFotoKandidat'].$hapusFoto);
          $arr['success'] = true;
          $arr['msg'] = $hapusNama .' Berhasil dihapus dari daftar kandidat';
        }else{
          $arr['success'] = false;
          $arr['msg'] = 'Gagal menghapus '. $hapusNama .' dari daftar kandidat';
        }
      }else{
        $arr['success'] = false;
        $arr['msg'] = 'Data Kandidat Tidak ditemukan di database';
      }

    }else{
      unlink('../'.$GLOBALS['dirFotoKandidat'].$foto);
      $arr['success'] = false;
      $arr['msg'] = 'Tidak ada aksi yang dijalankan';
    }

    
    return $arr;
  }
}//Akhir Class Kandidat

?>