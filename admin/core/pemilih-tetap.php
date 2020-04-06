<?php

$user = new User;

if($do == 'hapus'){
  if($user->cek($id, 'id')){
    $submit = $user->set(array(
      'aksi' => 'hapus',
      'idPemilih' => $id,
      'namaPemilih' => $user->show($id, 'namaPemilih'),
    ));
    echo $submit['msg'];

  }else{
    header('location:'.$adminHome.'pemilih-tetap');
  }

}elseif($do == 'tambah' || $do == 'edit'){
  if($do == 'edit'){
    if($user->cek($id, 'id')){
      $editNama = $user->show($id,'namaPemilih');
      $editUsername = $user->show($id,'username');
      $editPass = $user->show($id,'password');
      if(empty($editNama)){
        $anonim = 'Anonim';
      }
      $aksi = 'edit';
    }else{
      header('location:'.$adminHome.'pemilih-tetap');
    }
  }else{
    $aksi = 'tambah';
  }

  if($_POST['set']){
    $namaPemilih = $_POST['namaPemilih'];
    $userName = $_POST['userName'];
    $userPass = $_POST['passPemilih'];
  
    $submit = $user->set(array(
      'aksi' => $aksi,
      'idPemilih' => $id,
      'namaPemilih' => $namaPemilih,
      'userName' => $userName,
      'password' => $userPass,
    ));

    echo $submit['msg'];
  
  }else{
?>

<form action="" method="post">
  <input type="text" name="namaPemilih" placeholder="Nama" value="<?php echo $editNama?>"> <?php echo $anonim?><br>
  <input type="text" name="userName" placeholder="username" value="<?php echo $editUsername?>"> <br>
  <input type="password" name="passPemilih" placeholder="Password"><br>
  <input type="submit" value="Kirim" name="set">
</form>

<?php
  }
 //Akhir Tambah atau edit user

}elseif($do=='generate'){
  if($gen=$_POST['generate']){
    $jumlah = $_POST['jumlahUser'];
    
    for($i=0; $i<$jumlah; $i++){
      $randomName = randomName(4);
      $submit = $user->set(array(
        'aksi' => 'tambah',
        'userName' => $randomName,
        'password' => randomName(3),
      ));
      if($submit['success']){
        $scsAdd[] = 1;
        $gglAdd[] = 0;
        echo 'Berhasil menambahkan Pemilih <b>'.$randomName.'</b> <br>';
      }else{
        $scsAdd[] = 0;
        $gglAdd[] = 1;
        echo 'Gagal menambahkan Pemilih <b>'.$randomName.'</b> "'.$submit['msg'].'" <br>';
      }
    }//akhir generate user otomatis

    echo '<br>Berhasil ditambahkan => '.array_sum($scsAdd);
    echo '<br> Gagal ditambahkan => '.array_sum($gglAdd);
  }else{
?>
<form action="" method="post">
  <input type="number" name="jumlahUser"><br>
  <input type="submit" value="Generate" name="generate">
</form>

<?php
  }
  //Akhir Generate user otomatis
}elseif($do=='export'){
  if($user->jumlahUser>0){
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Daftar Pemilih Tetap.xls"');
    echo'<table>
    <thead>
    <tr>
      <td colspan="3"> Jumlah Pemilih Tetap => <b>'.$user->jumlahUser.'</b></td>
    </tr>
    <tr><td></td></tr>
    <tr>
      <td>No</td>
      <td>USERNAME</td>
      <td>PASSWORD</td>
    </tr>
  </thead>
  <tbody>';

    for($i=0; $i<$user->jumlahUser; $i++){
      echo '<tr>
      <td>'.($i+1).'</td>
      <td>Username : <b> '.$user->tampil[$i]['username'].'</b></td>
      <td>Password : <b> '.enkripsi('decrypt',$user->tampil[$i]['password']).'</b></td>
      </tr>';
    }

  echo '</tbody>
  </table>';
  }
}else{

  if(@$_POST['hapusTerpilih']){
    
    $idTerpilih = @$_POST['idPemilih'];
    if(count($idTerpilih)>0){
      
      for($i=0; $i<count($idTerpilih); $i++){
        $submit = $user->set(array(
          'aksi' => 'hapus',
          'idPemilih' => $idTerpilih[$i],
        ));
        if($submit['success']){
          $scsHapus[] = 1;
          $gglHapus[] = 0;
        }else{
          $scsHapus[] = 0;
          $gglHapus[] = 1;
        }
      }//Akhir Hapus
      echo 'berhasil mengahpus '. array_sum($scsHapus);

    }else{
      echo 'tidak ada yang dihapus';
    }// Akhir Mass Hapus
  }else{
    
    // echo '<form action"" method="post"><input type="submit" name="hapusTerpilih" value="Hapus Terpilih"><br>
    //       <a href="'.$adminHome.'pemilih-tetap/export/"> Export .XLS </a>';

    $tableField = '';
    for($i=0; $i<$user->jumlahUser; $i++){
      $idUser = $user->tampil[$i]['idPemilih'];
      $userName = $user->tampil[$i]['username'];
      $userPass = enkripsi('decrypt',$user->tampil[$i]['password']);
      $name = $user->tampil[$i]['namaPemilih'];
      $loginAttempt = $user->tampil[$i]['loginAttempt'];
      if(empty($name)){
        $nama = 'Anonim';
      }else{
        $nama = $name;
      }
      
      // echo '<br><br><input type="checkbox" value="'.$idUser.'" name="idPemilih[]">'.$idUser.'/'.$nama .'/'.$userName.'/'.$userPass.' <a href="'.$adminHome.'pemilih-tetap/edit/'.$idUser.'"> Edit </a> || <a href="'.$adminHome.'pemilih-tetap/hapus/'.$idUser.'"> Hapus</a>';

      $tableField .= '<tr>
        <td> <input type="checkbox" class="check" value="'.$idUser.'" name="idPemilih[]">
          '.($i+1).' </td>
        <td> '.$nama .' </td>
        <td> '.$userName.' </td>
        <td> '.$loginAttempt.' </td>
        <td> <a href="'.$adminHome.'pemilih-tetap/edit/'.$idUser.'" class="btn btn-primary"> <i class="fa fa-edit"></i> Hapus </a> <a href="'.$adminHome.'pemilih-tetap/hapus/'.$idUser.'" class="btn btn-danger"> <i class="fa fa-trash-alt"></i> Hapus</a> </td>
      </tr>';
    }

    echo '</form>';
?>

<form action="" method="post">
  <div class="wow fadeInUp" data-wow-duration="1.7s" data-wow-delay="1.2s">
    <div class="d-flex align-items-center" style="justify-content:space-between;">
      <h3 class="sub-title" style="font-weight:bold;">Daftar Pemilih Tetap <button class="btn ml-2 reset"
          disabled="disabled">Reset</button></h3>
    </div>
    <div class="d-flex" style="justify-content:space-between">
      <h5 class="sub-title">Total : <strong> <?=$user->jumlahUser?> </strong></h5>
      <a href="<?=$adminHome?>pemilih-tetap/export" class="btn btn-outline-dark" style="margin:0 30px 0 10px">
        <i class="fa fa-file-excel"></i> Export XLS
      </a>
    </div>

    <button class="btn-mdl btn btn-outline-danger"><i class="fa fa-trash-alt"></i> Hapus Terpilih</button>
    <button class="btn-mdl btn btn-outline-dark"><i class="fa fa-plus"></i> Tambah Pemilih</button>
    <button class="btn-mdl btn btn-outline-dark"><i class="fa fa-plus"></i> Generate Pemilih</button>
  </div>

  <div class="bg-white shadow rounded p-3 mt-3 wow fadeInUp" data-wow-duration="1.7s" data-wow-delay="1.4s">
    <table id="dataTables" class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Username</th>
          <th>Jumlah Login</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?=$tableField?>
      </tbody>
    </table>
  </div>
</form>

<script>
$(document).ready(function() {
  $('#dataTables').DataTable();

});
</script>
<?php
  }
}

?>