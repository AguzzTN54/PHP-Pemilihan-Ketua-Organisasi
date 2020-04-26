<?php

$kandidat = new Kandidat;


if($do == 'hapus'){
  header('Content-Type: application/json');
  if($kandidat->cek($id)){
    $submit = $kandidat->set(array(
      'aksi' => 'hapus',
      'idKandidat' => $id,
    ));
    
  }else{
    $submit['success'] = false;
    $submit['msg'] = 'Tidak dapat menerima request';
  }
  echo json_encode($submit);

}elseif($do == 'tambah' || $do == 'edit'){
  if($do=='edit'){
    if($kandidat->cek($id)){
      $editNama = $kandidat->show($id, 'namaKandidat');
      $editJurusan = $kandidat->show($id, 'jurusan');
      $editVisi = $kandidat->show($id, 'visi');
      $editMisi = $kandidat->show($id, 'misi');
      $editFoto = $kandidat->show($id, 'foto');
      $aksi = 'edit';

    }else{
      header('location:'.$adminHome.'kandidat');
    }
  }else{
    $aksi = 'tambah';
  }

  if(@$_POST['tambah']){
    header('Content-Type: application/json');
    
    $namaKandidat = $_POST['namaKandidat'];
    $jurusan = $_POST['jurusan'];
    $visi = $_POST['visi'];
    $misi = $_POST['misi'];

    if($do == 'edit'){
      if(empty($_FILES['fotoKandidat']['name'])){
        $upload['success'] = true;
        $upload['foto'] = $editFoto;
        $upload['msg'] = 'Tidak Mengubah Foto';
      }else{
        $upload = upload($namaKandidat, $_FILES);
      }
    }else{
      $upload = upload($namaKandidat, $_FILES);
    }

    if($upload['success']){
      $submit = $kandidat->set(array(
        'aksi' => $aksi,
        'idKandidat' => $id,
        'namaKandidat' => $namaKandidat,
        'jurusan' => $jurusan,
        'visi' => $visi,
        'misi' => $misi,
        'foto' => $upload['foto'],
      ));

      echo json_encode($submit);
    }else{

      echo json_encode($upload);
    } 
  }else{
    if($editFoto){
      $inputFile = 'style="display:none"';
      $pic = '<div class="mr-1"><img class="file-upload-image" src="../'.$dirFotoKandidat.$editFoto.'" alt="'.$editFoto.'" /></div>';
      $button ='<button type="button" onclick="removeUpload()" class="remove-image btn btn-danger">Ganti</button>';
    }else{

    }
?>
<div class="form-group">
  <label for="formFile"><b>Foto Kandidat</b></label>
  <div class="uploadFile" <?=$inputFile?>>
    <input type="file" id="formFile" class="pilihFile" name="fotoKandidat" accept="image/*" onchange="readURL(this)" />
    <div id="preview-image"><i class="fa fa-plus fa-2x"></i></div>
  </div>
  <div class="fileBaru d-flex" style="flex-direction:column;width:100px;">
    <div class="file-upload-content d-flex">
      <?=$pic;?>
    </div>
    <?=$button;?>
  </div>

</div>
<div class="row">
  <div class="form-group col-6">
    <label for="formName"><b>Nama Kandidat</b></label>
    <input type="text" id="formName" class="form-control" name="namaKandidat" placeholder="Nama kandidat"
      value="<?php echo $editNama;?>" required>
  </div>
  <div class="form-group col-6">
    <label for="formKelas"><b>Kelas</b></label>
    <input type="text" id="formKelas" class="form-control" name="jurusan" placeholder="Kelas"
      value="<?php echo $editJurusan;?>">
  </div>
</div>
<div class="form-group">
  <label for="formVisi"><b>Visi</b></label>
  <textarea name="visi" class="form-control" id="formVisi" cols="30" rows="5"><?php echo $editVisi;?></textarea>
</div>
<div class="form-group">
  <label for="formMisi"><b>Misi</b></label>
  <textarea name="misi" class="form-control" id="formMisi" cols="30" rows="5"><?php echo $editMisi;?></textarea>
</div>
</div>
<div class="modal-footer">
  <input type="hidden" name="tambah" value="tambah">
  <button class="btn btn-primary" data-dismiss="modal">Batal</button>
  <button type="submit" class="btn btn-success btn-update-k"><i class="fa fa-check"></i> Update</button>

  <script src="assets/js/upload.js"></script>
  <script>
  $(document).ready(function() {
    $('#dataTables').DataTable();

  });
  </script>

  <?php
  }
}else{
  $listKandidat = '';
  for ($i=0; $i < $kandidat->jumlah; $i++) {
    $idKandidat = $kandidat->tampil[$i]['idKandidat'];
    $namaKandidat = $kandidat->tampil[$i]['namaKandidat'];
    $jurusan = $kandidat->tampil[$i]['jurusan'];
    $foto = $kandidat->tampil[$i]['foto'];
    $visi = $kandidat->tampil[$i]['visi'];
    if($pemilu){
      $sKan='';
      $delKan = '';
    }else{
      $sKan = '<a class="btn btn-success btn-mdl lihat" href="'.$adminHome.'hasil/kandidat/'.$idKandidat.'"><i class="fa fa-eye"></i> Suara</a>';
      $delKan = '<a class="btn btn-danger btn-mdl tambahAksi" target-name="kandidat" target-aksi="hapus" target-id="'.$idKandidat.'" target-init="'.$namaKandidat.'" href="kandidat/hapus/'.$idKandidat.'"><i class="fa fa-trash-alt"></i> Hapus</a>';
    }

    $listKandidat .= '<div class="col-sm-6 mt-3">
      <div data-id="'.$idKandidat.'" class="rounded kandidat-item overflow-hidden row shadow wow fadeInUp" data-wow-duration="1.7s"
        data-wow-delay="1.'.($i+2).'s">
        <div class="col-lg-4 pl-0 foto-kandidat">
          <img src="../'.$dirFotoKandidat.$foto.'" alt="Kandidat ke '.($i+1).'">
        </div>
        <div class="info col-lg-8 p-3 position-relative">
          <h4>'.$namaKandidat.'</h4>
          <strong>Kelas : <span class="text-utama">'.$jurusan.'</span></strong>
          <div class="visi mt-2">
            <p>'.substr($visi,0,120).'</p>
          </div>
          <div class="no-kandidat">'.($i+1).'</div>
          <div class="do mt-3">
            '.$sKan.'
            <a class="btn btn-primary btn-mdl tambahAksi" target-name="kandidat" target-aksi="edit" target-id="'.$idKandidat.'" target-init="'.$namaKandidat.'" href="kandidat/edit/'.$idKandidat.'"><i class="fa fa-edit"></i> edit</a>
            '.$delKan.'
          </div>
        </div>
      </div>
    </div>';
  }
?>

  <h3 class="sub-title" style="font-weight:bold;justify-content: unset;">
    List Kandidat
    <button class="btn ml-2 reset btn-mdl" <?=$disabled?>>Reset</button>
  </h3>
  <button class="btn-mdl btn btn-outline-dark tambahAksi" target-name="kandidat" target-aksi="tambah" <?=$disabled?>><i
      class="fa fa-plus"></i> Tambah Kandidat</button>

  <div class="row">
    <?=$listKandidat;?>
  </div>

  <script src="./assets/js/kandidat.js"></script>

  <?php
}

?>