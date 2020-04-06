<?php
$kandidat = new Kandidat();
$data = strtolower(@$_GET['data']);

if($data){
  $id = $_GET['idK'];
  if(!empty($id)){
   if($data == 'visi'){
    echo $kandidat->show($id,'visi');
   }elseif($data == 'misi'){
    echo $kandidat->show($id,'misi');
   }else{
     echo 'Ngawur Lu!!';
   }
  }else{
    echo 'Nothing to show';
  }
  
}elseif($_POST['idKandidat']){
  header('Content-Type: application/json');
    echo $kandidat->coblos();
  
}else{

  // print($login->cekPilihan());
  if($login->cekPilihan()){
    $idTerpilih = $login->cekPilihan('kandidat');
    $coblos = '';
    $disabled = 'disabled';
    $txtCbls = '---';
    $tombol ='';
    echo '<div class="alert alert-primary text-center info-pilihan wow slideInDown"><div class="container"><strong>Kamu sudah mencoblos sejak '.DateToIndo($login->cekPilihan('waktu')).'</strong></div></div>';
  }else{
    $coblos = 'coblos';
    $disabled = '';
    $txtCbls = 'Coblos <i class="fa fa-edit"></i>';
    $tombol = '<div class="kumpulkan-suara fade show">
    <button class="btn kumpul disabled" type="submit" disabled> Kirim Ke Kotak Suara <i class="fa fa-cube"></i></button>
  </div>';
  }
?>


<form action="" class="form-kandidat">
  <?php

//Deklarasi  Kandidat
for ($i=0; $i < $kandidat->jumlah; $i++) {
  $idKandidat = $kandidat->tampil[$i]['idKandidat'];
  $namaKandidat = $kandidat->tampil[$i]['namaKandidat'];
  $jurusan = $kandidat->tampil[$i]['jurusan'];
  $visi = $kandidat->tampil[$i]['visi'];
  $misi = $kandidat->tampil[$i]['misi'];
  $foto = $kandidat->tampil[$i]['foto'];
  if($idKandidat == $idTerpilih){
    $active = ' active';
    $checked = 'checked="checked"';
    $txtCoblos = 'Tercoblos <i class="fa fa-check"></i>';
  }else{
    $active = '';
    $checked = '';
    $txtCoblos = $txtCbls;
  }
?>

  <div class="kandidat-item wow bounceInUp<?php echo $active;?>" data-wow-delay="0s">
    <h3 class="nomor-kandidat">
      Kandidat ke <?php echo ($i+1); ?>
    </h3>
    <div class="foto-kandidat">
      <img src="<?php echo $GLOBALS['dirFotoKandidat'].$foto;?>" alt="<?php echo $namaKandidat;?>">
      <div class="dinas">
        <?php echo $jurusan ?>
      </div>
      <div class="lihat-gambar">
        <a class="v-img" href="<?php echo $GLOBALS['dirFotoKandidat'].$foto;?>" data-toggle="modal"
          data-target="#modal">
          <i class="fa fa-eye v-img"></i><br>Lihat Foto
        </a>
      </div>
    </div>
    <h4 class="nama-kandidat">
      <?php echo $namaKandidat;?>
    </h4>
    <div class="vm">
      <button class="btn btn-primary visi">Visi</button>
      <button class="btn btn-success misi">Misi</button>
    </div>
    <div class="coblos-button">
      <input type="radio" class="r-coblos" name="idKandidat" id="kandidat-<?php echo $idKandidat ?>"
        value="<?php echo $idKandidat ?>" <?php echo $checked.$disabled;?>>
      <label class="<?php echo $coblos; ?> radio-coblos"
        for="kandidat-<?php echo $idKandidat ?>"><?php echo $txtCoblos;?></label>
    </div>
  </div>

  <?php
}//Akhir Deklarasi Kandidat

echo $tombol;
?>

</form>
<div class="row justify-content-center output-coblos">
</div>

<?php
echo $scKandidat;

}// Akhir Jika terjadi post Idkandidat
?>