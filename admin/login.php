<?php


if($_POST['admUsername']){
  $masuk = $admlogin->masuk(mysqli_real_escape_string($db,$username), mysqli_real_escape_string($db,$password));
  if($masuk){
    header('Content-Type: application/json');
    if($admlogin->level == 1){
      $json['success'] = true;
      $json['msg'] = '<strong> Login Berhasil!</strong> Kamu dapat Mengatur aplikasi sekarang!';
      
    }else{
      unset($_SESSION['idUser']);
      unset($_SESSION['login']);
      unset($_SESSION['username']);
    
      $json['success'] = false;
      $json['msg'] = '<strong> Login Gagal!</strong> Anda tidak memiliki hak untuk mengakases halaman admin !';
    }
  }else{
    $json['success'] = false;
    $json['msg'] = '<strong> Login Gagal!</strong> Pastikan Username dan password benar !';
  }
  echo json_encode($json);

}else{
  echo endas();
?>

<div class="notifikasi login-container">
  <div role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-delay="10000" data-autohide="true"
    data-animation="true">
  </div>
</div>

<div id="login" class="bg-bar col-md-12">
  <div id="row" class="row temp justify-content-center w-100">
    <div id="login" class="col-10 col-sm-6 col-md-4 bg-white pl-4 pr-4 pt-3 pb-4 login">
      <h3 class="text-center title">ADMIN PANEL</h3>
      <hr />

      <form action="" class="proses" method="POST">
        <div class="input-div one">
          <div class="i">
            <i class="fas fa-user"></i>
          </div>
          <div class="div">
            <h5>Username</h5>
            <input type="text" class="input username" name="admUsername" required />
          </div>
        </div>
        <div class="input-div pass">
          <div class="i">
            <i class="fas fa-lock"></i>
          </div>
          <div class="div">
            <h5>Password</h5>
            <input type="password" class="input password" name="admPassword" required />
          </div>
          <i class="fa fa-eye-slash show-password"></i>
        </div>
        <input type="submit" class="btn btn1 tombolProses mt-5" value="Login Admin" />
        <div class="load-proccess d-none"></div>
      </form>
      <div class="result"></div>
    </div>
  </div>
  <div class="login-container madeby text-white">
    <p>
      Made with <i class="fa fa-heart"></i> by
      <a href="http://instagarm.com/aguzztn54" target="_blank" rel="noopener noreferrer" class="text-white"><b>Agus</b>
      </a>
      <br />Lulusan 2018
    </p>
  </div>
</div>
<!-- Kontent -->

<div class="worksheet">
</div>

<?php
echo sikil();
}

?>