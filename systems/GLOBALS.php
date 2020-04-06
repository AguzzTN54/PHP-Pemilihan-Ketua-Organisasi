<?php


$db = mysqli_connect($host, $user, $password, $database) or die('<br/><br/>Koneksi gagal <b>"'.mysqli_connect_error($db).'"</b>, Cek konfigurasi pada config.php dan MySQL anda !');

$path = 'systems/.settings';
if(!file_exists($path)){
  $path = '../systems/.settings';
}

$GLOBALS['db'] = $db;
$GLOBALS['time'] = date("Y-m-d")." ".date("H:i");
$GLOBALS['tblUser'] = $prefix.'pemilih';
$GLOBALS['tblKandidat'] = $prefix.'kandidat';
$GLOBALS['tblSuara'] = $prefix.'suara';

$set = unserialize(file_get_contents($path));

$GLOBALS['alamat'] = $set['alamat']; //Url Path aplikasi E-Voting
$GLOBALS['adminDir'] = $set['alamat']; //Folder Admin panel
$GLOBALS['dirFotoKandidat'] = $set['dirFotoKandidat'];

//Settings
$pemilu = $set['pemilu']['start'];
$waktuMulai = $set['pemilu']['waktu']['mulai'];
$waktuSelesai = $set['pemilu']['waktu']['selesai'];
$judulPemilu = $set['judulPemilu'];
$menit = $set['durasi']['menit'];
$detik = $set['durasi']['detik'];
$logos = $set['logo'];
$alamat = $set['alamat'];
$adminDir = $set['adminDir'];
$dirFotoKandidat = $set['dirFotoKandidat'];

?>