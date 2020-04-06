<?php

function bersihkan($a) {
  $b = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');
  $c = str_replace($b, '', $a);
  $d = strtolower(str_replace(' ', '-', $c));
  return $d;
}

function upload($namaKandidat, $file){

  $extensionList = array("jpg", "jpeg", "png");
  $pecah = explode(".", $_FILES['fotoKandidat']['name']);
  $ekstensi = @$pecah[1];
  $nama = bersihkan($namaKandidat);
  $rand = rand(1111,9999);
  $uploadName = $rand."-".$nama.'.'.$ekstensi;

  if(!empty($_FILES['fotoKandidat']['name'])){
    if (in_array($ekstensi, $extensionList)){  
      //direktori gambar
      $directoryUpload = '../'.$GLOBALS['dirFotoKandidat'];
      $fileUpload = $directoryUpload . $uploadName;
      $imageType = $_FILES["fotoKandidat"]["type"];

      //Simpan gambar dalam ukuran sebenarnya
      if(move_uploaded_file($_FILES["fotoKandidat"]["tmp_name"], $fileUpload)){

        //identitas file asli
        switch($imageType) {
          case "image/gif":
            $im_src=imagecreatefromgif($fileUpload);
            break;
            case "image/pjpeg":
          case "image/jpeg":
          case "image/jpg":
            $im_src=imagecreatefromjpeg($fileUpload);
            break;
            case "image/png":
          case "image/x-png":
            $im_src=imagecreatefrompng($fileUpload);
            break;
        }

        $src_width = imageSX($im_src);
        $src_height = imageSY($im_src);

        //Simpan dalam versi besar 400 pixel
        //Set ukuran gambar hasil perubahan 

        $dst_width = 300;
        $dst_height = 400;

        //proses perubahan ukuran
        $im = imagecreatetruecolor($dst_width,$dst_height);
        imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

        //Simpan gambar
        switch($imageType) {
          case "image/gif":
              imagegif($im,$directoryUpload.$uploadName);
            break;
            case "image/pjpeg":
          case "image/jpeg":
          case "image/jpg":
              imagejpeg($im,$directoryUpload.$uploadName);
            break;
            case "image/png":
          case "image/x-png":
              imagepng($im,$directoryUpload.$uploadName);
            break;
        }

        //Hapus gambar di memori komputer
        imagedestroy($im_src);
        imagedestroy($im);
        
        $arr['success'] = true;
        $arr['foto'] = $uploadName;
        $arr['msg'] = 'Berhasil Mengupload';
      }else{
        
        //Gagal Upload
        $arr['success'] = false;
        $arr['msg'] = 'Gagal mengupload';
      }
    }else{

      $arr['success'] = false;
      $arr['msg'] = 'Ekstensi file tidak didukung';
    }//Akhir Upload 
    
  }else{
    $arr['success'] = false;
    $arr['msg'] = 'Kamu belum memilih foto';
  }
  
  return $arr;
}// Akhir Fungsi Upload

function uploadFile($dir,$file){
  $jumlah = count($file['name']);
  for($i=0; $i<$jumlah;$i++){
    $dir = rtrim($dir,'/');
    $fileUp = $file['tmp_name'][$i];
    $namaFile = $dir.'/'.str_replace(' ', '-',$file['name'][$i]);
    if(move_uploaded_file($fileUp, $namaFile)){
      $success[] = 1;
      $imgURL[] .= $namaFile;
    }else{
      $success[] = 0;
      $imgURL[] .= '';
    }
  }
  if(array_sum($success)==0){
    $arr['success'] = false;
    $arr['warning'] = 'danger';
    $arr['msg'] = 'Terjadi Kesalahan, tidak dapat mengupload';
  }else if(array_sum($success)!=$jumlah){
    $arr['success'] = true;
    $arr['warning'] = 'warning';
    $arr['msg'] = 'Terdapat Beberapa file yang tidak terupload';
    $arr['imgURL'] = $imgURL;
  }else{
    $arr['success'] = true;
    $arr['warning'] = 'success';
    $arr['msg'] = 'Berhasil mengupload dengan Sempurna';
    $arr['imgURL'] = $imgURL;
  }
  
  return $arr;
}
function DateToIndo($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia
  // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
  $BulanIndo = array("Januari", "Februari", "Maret","April", "Mei", "Juni","Juli", "Agustus", "September", "Oktober", "November", "Desember");
  $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
  $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
  $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
  $jam = substr($date, 11, 5);
  $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun." pukul " .$jam ." WIB";
  return($result);
}

function randomName($n){
  $char = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $str = '';
  for($i=0; $i<$n; $i++){
    $karakter = rand(0, strlen($char)-1);
    $str .= $char[$karakter];
  }
  return $str;
}

function enkripsi($metode, $str){
  $key = 'Agus';
  $iv = 'Tinus';

  $method = 'AES-256-CBC';
  $hased_key = hash('sha256', $key);
  $hased_iv = substr(hash('sha256', $iv), 0, 16);

  if($metode == 'encrypt'){
    $output = base64_encode(openssl_encrypt($str, $method, $hased_key, 0, $hased_iv));
  }elseif($metode == 'decrypt'){
    $output = openssl_decrypt(base64_decode($str), $method, $hased_key, 0, $hased_iv);
  }else{
    $output = false;
  }
  return $output;
}

function fSize($file){
  $a = array('B','KB','MB','GB');
  $pos = 0;
  $size = filesize($file);

  while($size>=1024){
    $size /= 1024;
    $pos++;
  }
  return round($size,2).''.$a[$pos];
}//Akhir fungsi FileSize


function getBrowser($type=null){
  $ua = $_SERVER['HTTP_USER_AGENT'];
  $userAgent = strtolower($ua);

  //Cek Platform
  $platformCek = array('/linux/i','/macintosh|mac os x/i', '/windows|win32/i');
  $platform = array('Linux', 'Mac Os', 'Windows');
  for($i=0;$i<count($platformCek);$i++){
    $cek = $platformCek[$i];
    if(preg_match($cek, $userAgent)){
    $OS = $platform[$i];
    }
  }

  // Cek Versi Operating System
  $pola = '#([/'.$OS.'/i])+(?<versiOS>[; 0-9.|a-zA-Z.-]*)#';
  preg_match_all($pola, $ua, $find);
  $pecahVersi = explode('; ',$find['versiOS'][2]);
  $versiOS = $pecahVersi[0];
  $tipe = $pecahVersi[1];
  $build = $pecahVersi[2];
  if(!preg_match('/android/i', $userAgent)){
    $versiOS = explode(' ', trim(ltrim($versiOS,';')));
    $vOS = $OS.' '.$versiOS[1];
  }else{
    $vOS = explode(' ',$build)[0] .' '.$versiOS.' '.$tipe;
  }

  // Cek Browser
  $cekBrowser = array('/msie/i', '/opera/i','/firefox/i', '/safari/i', '/chrome/i', '/netscape/i','/edge/i');
  $bName = array('Internet Explorer', 'Opera', 'Mozilla Firefox', 'Safari', 'Chrome', 'Netscape','Edge');
  $ub = array('MSIE', 'Opera', 'Firefox', 'Safari', 'Chrome', 'Netscape','Edge');
  for($i=0;$i<count($cekBrowser);$i++){
    $cek = $cekBrowser[$i];
    if(preg_match($cek, $userAgent)){
      $browserName = $bName[$i];
      $browser = $ub[$i];
    }
  }

  //Cek Versi Browser
  $versi = '';
  // $a = array('Version', $browser, 'other');
  $pola = '#(?<browser>[/'.$browser.'/i])[/]+(?<version>[0-9.|a-zA-Z.]*)#';
  if(preg_match_all($pola, $userAgent, $cocok)){
    $i = count($cocok['browser']);
    if($i != 1){
      if(strripos($userAgent,$ua)>strripos($userAgent,$browser)){
        $versi = $cocok['version'][1];
      }else{
        if(!empty($cocok['version'][2])){
          $versi = $cocok['version'][2];
        }else{
          $versi = $cocok['version'][0];
        }
      }
    }else{
      $versi = $cocok['version'][0];
    }
  }
  if(!empty($versi)){
    $pecah = explode('.',$versi);
    $versiBrowser = $pecah[0].'.'.$pecah[1];
  }else{
    $versiBrowser ='';
  }

  if($type=='platform'){
    return $vOS;
  }elseif($type=='browser'){
    return $browserName.' '.$versiBrowser;
  }else{
    return $vOS.' '.$browserName.' '.$versiBrowser;
  }
}
?>