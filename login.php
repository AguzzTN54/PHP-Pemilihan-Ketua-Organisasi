<?php

if($_POST['username']){
  $masuk = $login->masuk(mysqli_real_escape_string($db,$username), mysqli_real_escape_string($db,$password));
  if($masuk){
    $login->cekLevel();
  }else{
    header('Content-Type: application/json');
    $json['success'] = false;
    $json['msg'] = '<strong> Login Gagal!</strong> Pastikan Username dan password benar !';
    echo json_encode($json);
  }
}else{
  include './header.php';
  
?>
<!-- login -->
<div id="log" class="container">
  <div class="login-alert"></div>

  <div id="row" class="row justify-content-center">
    <div id="login" class="col-md-5 bg-white pl-4 pr-4 pt-3 pb-4 login">
      <h3 class="text-center title">PEMILU ONLINE</h3>
      <hr>
      <ul class="steps">
        <li class="satu active"><i>1</i> Login</li>
        <li class="dua"><i>2</i> Coblos</li>
        <li class="tiga"><i>3</i> Selesai</li>
      </ul>

      <div class="ngarep">
        <form action="" class="proses" method="POST">
          <div class="input-div one">
            <div class="i">
              <i class="fas fa-user"></i>
            </div>
            <div class="div">
              <h5>Username</h5>
              <input type="text" name="username" class="input username" required>
            </div>
          </div>
          <div class="input-div pass">
            <div class="i">
              <i class="fas fa-lock"></i>
            </div>
            <div class="div">
              <h5>Password</h5>
              <input type="password" name="userPassword" class="input password" required>
            </div>
            <i class="fa fa-eye-slash show-password"></i>
          </div>
          <input type="submit" class="btn btn1 tombolProses" value="Menuju Bilik Suara">
          <div class="load-proccess d-none"></div>
        </form>
        <div class="result"></div>
        <div class="logo mt-4">
          <img src="images/smak.png" alt="Sekolah">
          <img src="images/MPK.png" alt="mpk">
          <img src="images/EOS.png" alt="osis">
          <img src="images/osis.jpg" alt="osis">
        </div>
      </div>
    </div>
  </div>
</div>
<!-- akhir login -->

<!-- Kandidat -->
<div class="container kandidat-list">

</div>
<!-- Akhir kandidat -->



<?php
include './footer.php';
}
?>