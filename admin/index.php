<?php

error_reporting(0);
date_default_timezone_set('Asia/Jakarta');
session_start();

try{

  if(!file_exists('../systems/.deviceOnline')){
    throw new Exception('<b> File .deviceOnline tidak ada !! </b>, Silahkan buat file baru dengan nama ".deviceOnline" di folder http://HostKamu/systems/');
    
  }elseif(!file_exists('../systems/.settings')){
    throw new Exception('<b> File .settings tidak ada !! </b>, Silahkan buat file baru dengan nama ".settings" di folder http://HostKamu/systems/');
    
  }

  require_once '../systems/config.php';
  require_once '../systems/GLOBALS.php';
  require_once '../systems/functions.php';
  require_once '../systems/Classes/Login-classes.php';
  require_once '../systems/Classes/Kandidat-classes.php';
  require_once '../systems/Classes/Suara-classes.php';
  require_once '../systems/Classes/User-classes.php';
  require_once '../systems/Classes/Online-classes.php';
  
  $online = new Online;
  $online->update('hapus');

  $uri = explode('/',strtolower($_GET['uri']));
  if(@$uri[0]=='app'){
    $act = 'app';
    $page = @$uri[1];
    $do = @$uri[2];
    $id = @$uri[3];
  }else{
    $page = @$uri[0];
    $do = @$uri[1];
    $id = @$uri[2];
    $act = @$_REQUEST['act'];
  }

  $id = mysqli_escape_string($db, $id);
  
  $username = @$_POST['admUsername'];
  $password = @$_POST['admPassword'];
  

  $adminHome = $GLOBALS['alamat'].$GLOBALS['adminDir'];

  if($username){
    $admlogin = new Login($username);
  }elseif($_SESSION['adminName']){
    $admlogin = new Login($_SESSION['adminName']);
  }


  require_once './header.php';
  if($act == 'logout'){
    unset($_SESSION['admlogin']);
    unset($_SESSION['idAdmin']);
    unset($_SESSION['adminName']);
    header('location:'.$adminHome);

  }elseif($act=='app'){
    if($_SESSION['admlogin']){
      include 'app.php';
    }else{
      header('location:'.$adminHome);
    }

  }else{

    if($_SESSION['admlogin']){
      if(empty($page)){
        header('location:'.$adminHome.'dashboard');
      
      }else {
        echo endas(true);
        include 'app.php';
        echo sikil(true);
      }
    }else{
      include './login.php';
    }
  }

}catch(Exception $e){
  die($e->getMessage());
}
?>