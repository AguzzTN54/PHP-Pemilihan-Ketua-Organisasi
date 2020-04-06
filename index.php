<?php

error_reporting(0);
date_default_timezone_set('Asia/Jakarta');
session_start();

require_once 'systems/config.php';
require_once 'systems/GLOBALS.php';
require_once 'systems/functions.php';
require_once 'systems/Classes/Login-classes.php';
require_once 'systems/Classes/Kandidat-classes.php';
require_once 'systems/Classes/Online-classes.php';
$online = new Online;

$username = $_POST['username'];
$password = $_POST['userPassword'];
$act = $_REQUEST['act'];

if($username){
  $login = new Login($username);
}elseif($_SESSION['username']){
  $login = new Login($_SESSION['username']);
}

if($act == 'logout'){
  unset($_SESSION['idUser']);
  unset($_SESSION['login']);
  unset($_SESSION['username']);
  header('location:./');

}elseif($act=='app'){
  if($_SESSION['login']){
    $scKandidat = '<script class="sc-kandidat" src="assets/js/kandidat.js"></script>';
    include 'app.php';

  }else{
    header('location: ./');
  }

}elseif($act=='test'){
  include 'app.php';
  
}else{
  
  if($_SESSION['login']){ // jika user telah login
    $script .= '<script src="assets/js/kandidat.js"></script>
    <script>
    showNavbar();
    durasi();
    </script>';
    $textAlgn = ' text-center';

    include 'header.php';
    echo $step;
    echo '<div class="container kandidat-list kandidat">';
    include 'app.php';
    echo '</div>';
    include 'footer.php';
  }else{
    include './login.php';
  }
}

$online->update();
$online->update('edit');
$online->update('hapus');
?>