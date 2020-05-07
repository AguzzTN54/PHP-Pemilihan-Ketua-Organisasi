<?php

$core = './core';

if($pemilu){
  $disabled = ' disabled="disabled"';
}else{
  $disabled = '';
}
if(substr($_GET['uri'],-1) == '/'){
  header('location:'.$adminHome.$page);
  
}elseif(file_exists($core.'/'.$page.'.php')){
  include $core.'/'.$page.'.php';
  
}else{
  // Dashboard
  $suara = new Suara;

  $suaraSah = $suara->jumlahSah;
  $SuaraMasuk = $suara->dataSuara('sudahMasuk');
  $golput = $suara->dataSuara('golput');
  $sisaSuara = $suara->dataSuara('sisaSuara');
  
  $waktuAwal = new DateTime($waktuMulai);
  if($pemilu){
    $status = 'on';
    $toggle = 'stop';
    $textStatus = 'Pemilu Sedang Berlangsung';
    $waktuAkhir = new DateTime($GLOBALS['time']);
  }else{
    $status = 'off';
    $toggle = 'start';
    $textStatus = 'Pemilu Berhenti';
    $waktuAkhir = new DateTime($waktuSelesai);
  }
  $intervalHari = $waktuAwal->diff($waktuAkhir)->d;
  $intervalJam = $waktuAwal->diff($waktuAkhir)->h;
  $intervalMenit = $waktuAwal->diff($waktuAkhir)->i;
  if($intervalHari==0){
    if($intervalJam==0){
      $intervalWaktu = $intervalMenit.' Menit';
    }else{
      $intervalWaktu = $intervalJam .' Jam '.$intervalMenit.' Menit';
    }
  }else{
    $intervalWaktu = $intervalHari.' Hari '.$intervalJam.' Jam '.$intervalMenit.' Menit';
  }

  $onlineCount = count($online->tampil);
  if($onlineCount>0){
    $onlineItem = '';
    foreach($online->tampil as $device){
      if($device['login']){
        $onStatus = 'Sedang Mencoblos';
        $log[] = 1;
      }else{
        $onStatus = 'Online';
        $log[] = 0;
      }

      $d = strtolower($device['device']);
      if(preg_match('/windows/i',$d)){
        $os = 'windows';
        $icn =  'fab fa-windows';
      }else if(preg_match('/android/i',$d)){
        $od = 'android';
        $icn =  'fab fa-android';
      } else if(preg_match('/linux/i',$d)){
        $os = 'linux';
        $icn =  'fab fa-linux';
      }else{
        $od = 'windows';
        $icn = 'fa fa-question-circle';
      }

      $sID = substr($device['sid'],0,10);
      $onlineItem .= '<div id="'.$sID.'" class="on-item d-flex align-items-center">
        <span class="device-name">
          <i class="'.$icn.' on-icon"></i>
          '.$device['device'].'
        </span>
        <span class="on-status">'.$onStatus.'</span>
        <div class="d-flex device-id">
          <span class="sid">ID : '.$sID.'</span>
          <span class="device-ip">IP : '.$device['IP_ADDR'].'</span>
        </div>
      </div>';

      $info[] = array(
        'status' => $onStatus,
        'os' => $os,
        'browser' => $device['device'],
        'sid' => $sID,
        'ip' => $device['IP_ADDR']
      );
    }
    $coblosCount = array_sum($log);
  }else{
    $coblosCount = 0;
    $onlineItem = '<div id="noOnline" class="on-item d-flex justify-content-center mt-3 align-items-center text-center">
    <span class="device-name"> Tidak Ada Device Aktif</span>
    </div>';
  }


  if(isset($_GET['refresh'])){
    $json['statusPemilu'] = $pemilu;
    $json['durasi'] = $intervalWaktu;
    $json['suara'] = array(
      'totalMasuk' => $SuaraMasuk,
      'suaraSah' => $suaraSah,
      'golput' => $golput,
      'belumMasuk' => $sisaSuara
    );
    $json['bilikAktif'] = array(
      'online' => $onlineCount,
      'mencoblos' => $coblosCount,
      'info' => $info
    );
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    echo json_encode($json);
    flush();
    sleep(30);

  }elseif($page!='dashboard'){
    header('location:'.$adminHome.'dashboard');
  }else{

  ?>

<h3 class="sub-title mb-3 wow fadeInUp" style="font-weight:bold;" data-wow-duration="1.7s" data-wow-delay="1s">dashboard
</h3>
<div class="row mr-2 wow fadeInUp" data-wow-duration="1.7s" data-wow-delay="1.1s">
  <div class="col-sm-8">
    <div class="position-relative on-off">
      <div class="btn-pilu pemilu-<?=$status;?>"> <span> <?=$textStatus?></span></div>
      <button class="btn tugel-pilu btn-pilu <?=$toggle;?>-pemilu"><i class="fa fa-toggle-<?=$status;?>"></i>
        <?=strtoupper($toggle);?></button>
    </div>
  </div>

  <div class="col-sm-4">
    <div class="waktu-ditempuh bg-white shadow p-3 rounded">
      <span class="info-title"> Pemilu Aktif Selama</span>
      <span class="info-body durasiPemilu"> <?=$intervalWaktu;?></span>
    </div>
  </div>
</div>


<div class="row mr-2 mt-4 wow fadeInUp" data-wow-duration="1.7s" data-wow-delay="1.2s">
  <div class="col-lg-8 col-sm-7 mb-2 pb-0">
    <div class="informasi-suara shadow bg-white rounded p-3" style="height:100%;">
      <h3 class="sub-title pr-3 pl-3">Informasi Suara <button class="btn reset" <?=$disabled?>>Reset</button>
      </h3>
      <div class="divider"></div>
      <div class="hasil row align-items-center justify-content-center text-center">
        <div class="hasil-item col-6 col-lg-3">
          <h4 class="info-body hitung" id="suaraSah">
            <span class="nowNum"> <?=$suaraSah;?></span>
          </h4>
          <span class="info-title">Suara Sah</span>
        </div>

        <div class="hasil-item col-6 col-lg-3">
          <h4 class="info-body hitung" id="totalMasuk">
            <span class="nowNum"> <?=$SuaraMasuk;?></span>
          </h4>
          <span class="info-title">Total Login</span>
        </div>

        <div class="hasil-item col-6 col-lg-3">
          <h4 class="info-body hitung" id="golput">
            <span class="nowNum"> <?=$golput;?></span>
          </h4>
          <span class="info-title">Tidak Memilih</span>
        </div>

        <div class="hasil-item col-6 col-lg-3">
          <h4 class="info-body hitung" id="belumMasuk">
            <span class="nowNum"> <?=$sisaSuara;?></span>
          </h4>
          <span class="info-title">Belum Login</span>
        </div>
      </div>
      <button onclick="pushState('state1')" data-href="hasil" data-title="Hasil Perolehan Suara" id="state1"
        class="btn btn1 ds" <?=$disabled?>>Cek Detail Perolehan
        Suara</button>

      <div class="aksi d-flex align-items-center">
        <a href="#" class="aksi-item rounded">
          <i class="fa fa-plus"></i>
          <span class="info-title"> Tambah Kandidat</span>
        </a>
        <a href="#" class="aksi-item rounded">
          <i class="fa fa-plus"></i>
          <span class="info-title"> Tambah Pemilih</span>
        </a>
        <a href="#" class="aksi-item rounded">
          <i class="fa fa-cog"></i>
          <span class="info-title"> Setting Pemilu</span>
        </a>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-sm-5">
    <div class="bg-white rounded shadow overflow-hidden">
      <h5 class="sub-title bg-utama justify-content-center text-white p-3"> Bilik Aktif</h5>
      <div class="online-info m-2">
        <div class="ml-3 mr-3 mb-1">
          BILIK ONLINE : <strong><span class="onlineCount"><?=$onlineCount;?></span></strong><br>
          SEDANG MENCOBLOS : <strong><span class="coblosCount"><?=$coblosCount?></span></strong>
        </div>
        <div class="divider"></div>
        <div class="scroll" style="height:230px;">
          <?=$onlineItem;?>
        </div>
      </div>
    </div>
  </div>

</div>
<script src="./assets/js/dashboard.js"></script>

<?php
  }
}

?>