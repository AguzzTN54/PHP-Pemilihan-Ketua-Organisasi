<?php

$GLOBALS['page'] = $page;
$GLOBALS['halaman'] = array('dashboard','hasil', 'kandidat', 'pemilih-tetap', 'settings');
$GLOBALS['judul'] = Array("Dashboard", "Hasil Perolehan Suara", "Kandidat", "Pemilih Tetap", "Pengaturan");

if(!empty($page)){
  $GLOBALS['title'] = $GLOBALS['judul'][array_search($page, $GLOBALS['halaman'])].' || Admin Panel';
}else{
  $GLOBALS['title'] = 'Admin Panel Pemilu Online';
}

function endas($dashboard=false){
  if($dashboard==true){
    $logged = '<script>logged = true;</script>';
    $navbar = '<nav class="navbar navbar-expand-lg navbar-light wow fadeInDown" style="visibility: visible; animation-name: fadeInDown;"><div class="container"><a class="navbar-brand" href="#">ADMIN PANEL</a><a class="nav-item nav-link text-center active">Status : <span class="text-success"> Sedang Pemilu</span></a><a class="nav-item nav-link" href="#"> Admin <i class="fa fa-user"></i></a></div></nav>';
    $icon = array('home','box-open', 'user-tie', 'users', 'cogs');
    
    $menu ='';
    for($i=0;$i<count($GLOBALS['halaman']);$i++){
      if(strtolower($GLOBALS['page'])==$GLOBALS['halaman'][$i]){
        $aktif = ' active';
      }else{
        $aktif ='';
      }
      $menu .= '<a data-href="'.$GLOBALS['halaman'][$i].'" data-title="'.$GLOBALS['judul'][$i].'" onclick="pushState(\'state'.$i.'\');" id="state'.$i.'" class="menu-item wow fadeInUp'.$aktif.'" data-wow-delay="0.'.$i.'s">  <i class="fa fa-'.$icon[$i].'"></i> <span>'.$GLOBALS['halaman'][$i].'</span> </a>' ;
    }
    $sidebar = '<div id="login" class="bg-bar col-md-3 sidebar">
    '.$menu.'
    </div>';

    $worksheet = '<div class="worksheet col-11 col-md-9 ws-load">
    <div id="item-preload" class="wow fadeIn">
      <div class="item-preload">
        <i class="fa fa-fan"></i>
        <span>Menyiapkan Aplikasi</span>
      </div>
    </div>
    <div class="notifikasi">
      <div role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-delay="10000" data-autohide="true" data-animation="true">
      </div>
    </div>
    
    <div class="app">';

  }else{
    $navbar ='';
    $sidebar = '';
    $worksheet ='';
  }

  $header = '<!DOCTYPE html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/loading.css" />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/animate.css" />
    <link rel="stylesheet" href="./assets/datatables.min.css" />
    <link rel="stylesheet" href="./assets/style.css" />
    <script src="../assets/js/libs/jquery.min.js"></script>
    '.$logged.'
    <title>'.$GLOBALS['title'].'</title>
  </head>

  <body>
    <!-- Preloader -->
    <div id="preloader">
      <div class="load">
        <div class="load-animation main-load">
          <div class="anime">
            <div class="a-box"></div>
            <div class="a-box"></div>
            <div class="a-box"></div>
            <div class="a-box"></div>
            <div class="a-box"></div>
          </div>
        </div>
        <span>Menyiapkan Aplikasi </span>
      </div>
    </div>
    <!-- Akhir Preloader -->

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="IkiModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" style="transition:all .3s;"></div>
    </div>

    <!-- login -->
    <div class="header">'.$navbar.'</div>
    <div class="row konten">';

  return $header . $sidebar . $worksheet;
}

function sikil($dashboard=false){
  if($dashboard==true){
    $worksheet = '</div>
    <div class="madeby">
      <p> Made with <i class="fa fa-heart"></i> by <a href="http://instagarm.com/aguzztn54" target="_blank" rel="noopener noreferrer"><b>Agus</b> </a> <br>
      Lulusan 2018 </p>
    </div>';
  }else{
    $worksheet= '';
  }

  $footer = ' </div>
  <!-- akhir login -->

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="../assets/js/libs/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/libs/easing.js"></script>
  <script src="../assets/js/libs/waypoints.min.js"></script>
  <script src="../assets/js/libs/counterup.min.js"></script>
  <script src="../assets/js/libs/wow.js"></script>
  <script src="./assets/js/datatables.min.js"></script>
  <script src="./assets/js/main.js"></script>


  <script>
    const inputs = document.querySelectorAll(".input");

    function addcl() {
      let parent = this.parentNode.parentNode;
      parent.classList.add("focus");
    }

    function remcl() {
      let parent = this.parentNode.parentNode;
      if (this.value == "") {
        parent.classList.remove("focus");
      }
    }

    inputs.forEach(input => {
      input.addEventListener("focus", addcl);
      input.addEventListener("blur", remcl);
    });
  </script>
  </body>

  </html>';

    return $worksheet.$footer;
  }
?>