<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="assets/css/loading.css">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="assets/css/style.css" />
  <title><?php echo ucwords($judulPemilu);?></title>
</head>

<body class="tempat-login">
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
    <div class="modal-dialog modal-dialog-centered" role="document">

    </div>
  </div>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container d-flex">
      <div class="navbar-brand">
        <div class="brandTop" logo-url="<?=str_replace('../','',implode(',',$logos))?>">
        </div>
      </div>
      <div class="nav-item nav-link judul<?php echo $textAlgn?>">
        <h3> <?php echo strtoupper($judulPemilu);?></h3>
      </div>
      <div class="nav-kanan" durasi-nyoblos="<?php echo $menit?>:<?php echo $detik;?>">
        <div class="collapse navbar-collapse">
        </div>
      </div>
    </div>
  </nav>
  <!-- Akhir navBar -->

  <?php 

  $step = '
  <div id="log">
  <div class="login-alert"><div class="alert alert-success alert-dismissible fade wow bounceInLeft" role="alert" data-wow-delay="0s" data-wow-duration="1s" style="visibility: visible; animation-duration: 1s; animation-delay: 0s; animation-name: bounceInLeft;"></div></div>

  <div id="row" class="row justify-content-center">
    <div id="login" class="bg-white pl-4 pr-4 pt-3 pb-4 login col-md-12 logged-in">
      <h3 class="text-center title">PEMILU ONLINE</h3>
      <hr>
      <ul class="steps">
        <li class="satu active"><i>1</i> Login</li>
        <li class="dua active"><i>2</i> Coblos</li>
        <li class="tiga"><i>3</i> Selesai</li>
      </ul>
    </div>
  </div>
</div>';

?>