<?php

if(isset($_POST['simpan'])){
  $postpemilu = $_POST['statusPemilu'];
  $postJudul = $_POST['judulPemilu'];
  $postMenit = $_POST['menit'];
  $postDetik = $_POST['detik'];
  $postLogos = $_POST['logos'];
  $postLogo = $_FILES['logo'];
  $postAlamat = $_POST['alamat'];
  $postAdminDir = $_POST['adminDir'];
  $posDirFotoKandidat = $_POST['dirFotoKandidat'];

  if($postLogo['name'][0]){
    $upLogo = uploadFile('../images',$postLogo);
    if($upLogo['success']){
      if($postLogos[0]){
        $logone = array_merge($upLogo['imgURL'],$postLogos);
      }else{
        $logone = $upLogo['imgURL'];
      }
    }else{
      $logone = $postLogos;
    }
  }else{
    if($postLogos[0]){
      $logone = $postLogos;
    }else{
      $logone = $logos;
    }
  }

  if($postpemilu==1){
    if($pemilu==0){
      if(empty($waktuSelesai)){
        $waktuMulai = $GLOBALS['time'];
      }
    }
  }else{
    if($pemilu==1){
      if(!empty($waktuMulai)){
        $waktuSelesai = $GLOBALS['time'];
      }
    }
  }
  
  if(!isset($postAlamat)){
    if(empty($postMenit) && empty($postDetik)){
        $menit = $menit;
        $detik = $detik;

      }else{
        $menit = $postMenit;
        $detik = $postDetik;
      }
      $pemilu = $postpemilu;
  }
  
  if(!empty($postJudul)){$judulPemilu = $postJudul;}
  if(!empty($postLogos)){}
  if(!empty($postAlamat)){$alamat = $postAlamat;}
  if(!empty($postAdminDir)){$adminDir = $postAdminDir;}
  if(!empty($posDirFotoKandidat)){$dirFotoKandidat = $posDirFotoKandidat;}


  $ubahSet = array(
    'pemilu' => array(
      'start' => $pemilu,
      'waktu' => array(
        'mulai' => $waktuMulai,
        'selesai' => $waktuSelesai,
      )
    ),
    'judulPemilu' => $judulPemilu,
    'durasi' => array(
      'menit' => $menit,
      'detik' => $detik,
    ),
    'logo' => $logone,
    'alamat' => $alamat,
    'adminDir' => $adminDir,
    'dirFotoKandidat' => $dirFotoKandidat,

  );


  $isi = serialize($ubahSet);
  $buka = fopen( $path, 'w' );
  $tulis = fwrite($buka, $isi);
  fclose($buka);

  if($tulis){
    $arr['success'] = true;
  }else{
    $arr['success'] = false;
  }
  header('Content-Type: Application/json');
  echo json_encode($arr);
}else{

  // print_r($set);
  $onOff = array('OFF','ON');
  for($i=0;$i<2;$i++){
    if($i==$pemilu){
      $check = '" checked="checked';
      $aktif = ' active';
    }else{
      $check = '';
      $aktif = '';
    }
    @$radio .= '<div class="checkOnOff'.$aktif.'">
      <input type="radio" class="form-check-input" name="statusPemilu" id="check'.($i+1).'" value="'.$i.$check.'">
      <label for="check'.($i+1).'">'.$onOff[$i].'</label>
    </div>';
  }

  if(!empty($logos[0])){
    $a=0;
    foreach($logos as $log){
      $a++;
      @$logo .= '<div id="logo'.$a.'" class="mr-2" style="width:100px;">
        <img src="'.$log.'" width="100px">
        <input type="hidden" name="logos[]" value="'.$log.'">
        <button type="button" onclick="removeUpload(\'logo'.$a.'\')" class="remove-image btn btn-danger">Hapus</button>
      </div>';
    }
  }
?>

<div class="d-flex align-items-center mb-2 wow fadeInUp" style="justify-content:space-between;" data-wow-duration="1.7s"
  data-wow-delay="1s">
  <h3 class="sub-title" style="font-weight:bold;">Settings</h3>
</div>

<div class="shadow bg-white rounded overflow-hidden p-0 wow fadeInUp" style="justify-content:space-between;"
  data-wow-duration="1.7s" data-wow-delay="1s">
  <div class="list-group list-group-horizontal" id="list-tab" role="tablist">
    <a class="list-group-item list-group-item-action active" data-toggle="list" href="#general-setting" role="tab"
      aria-controls="general">APPLICATION</a>
    <a class="list-group-item list-group-item-action" data-toggle="list" href="#application-setting" role="tab"
      aria-controls="application">SITE SETTING</a>
  </div>


  <div class="tab-content mb-5 mt-3 mr-3 ml-3" id="nav-tabContent">
    <div class="tab-pane fade show active" id="general-setting" role="tabpanel">
      <form class="app-setting" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="statusPemilu"><b>Status Pemilu</b></label>
          <div class="form-check">
            <?php echo $radio;?>
          </div>
        </div>
        <div class="form-group">
          <label for="judulPemilu"><strong>Judul Pemilu</strong></label>
          <input type="text" class="form-control" id="judulPemilu" name="judulPemilu" placeholder="Judul Pemilu"
            value="<?php echo $judulPemilu?>">
        </div>
        <div class="form-group">
          <label for="durasi"><strong>Durasi Nyoblos</strong></label>
          <div class="form-inline">
            <div class="form-group">
              <label for="menit">Menit</label>
              <input type="number" id="menit" class="form-control menit" name="menit" value="<?php echo $menit;?>"
                placeholder="Menit">
            </div>
            <span style="margin:5px;font-size:xx-large">:</span>
            <div class="form-group">
              <label for="detik">Detik</label>
              <input id="detik" type="number" class="form-control detik" name="detik" placeholder="Detik"
                value="<?php echo $detik;?>">
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="Logo"><strong> LOGO</strong></label>
          <div class="d-flex">
            <?php echo $logo;?>
            <div class="uploadFile">
              <input type="file" class="pilihFile" name="logo[]" multiple="multiple" accept="image/*"
                onchange="readURL(this)" />
              <div id="preview-image"><i class="fa fa-plus fa-2x"></i></div>
            </div>
            <div class="fileBaru d-flex" style="flex-direction:column">
              <div class="file-upload-content d-flex">
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" name="simpan">
        <button type="submit" class="simpan btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
      </form>

      <div class="mt-5">
        <h4>Pengaturan Pabrik</h4>
        <i class="d-block">* Hapus Semua data termasuk data Kandidat, Pemilih Tetap dan Pengaturan</i>
        <button class="btn btn-danger">Reset Aplikasi</button>
      </div>
    </div>

    <div class="tab-pane fade" id="application-setting" role="tabpanel">
      <form class="app-setting" action="" method="post">
        <div class="form-group">
          <label for="alamat"><strong>Alamat Situs</strong></label>
          <div class="row">
            <div class="col-sm-6">
              <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat;?>"
                placeholder="Alamat Situs">
            </div>
            <div class="col-sm-6">
              <i class="text-primary">Awali dengan http dan akhiri dengan tanda "/ "</i>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="adminDir"> <strong>Directory Admin Panek </strong></label>
          <div class="row">
            <div class="col-sm-6">
              <input type="text" class="form-control" id="adminDir" name="adminDir" value="<?php echo $adminDir;?>"
                placeholder="Admin Directory">
            </div>
            <div class="col-sm-6">
              <i class="text-primary">akhiri dengan tanda " / "</i>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="dirFotoKandidar"><strong> Directory Foto Kandidat</strong></label>
          <div class="row">
            <div class="col-sm-6">
              <input type="text" class="form-control" name="dirFotoKandidat" value="<?php echo $dirFotoKandidat;?>"
                placeholder="Directory Foto Kandidat">
            </div>
            <div class="col-sm-6">
              <i class="text-primary">akhiri dengan tanda " / "</i>
            </div>
          </div>
        </div>
        <input type="hidden" name="simpan">
        <button class="simpan btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
      </form>
    </div>
  </div>
</div>
<script src="assets/js/upload.js"></script>
<?php  
}//Akhir General Setting

?>