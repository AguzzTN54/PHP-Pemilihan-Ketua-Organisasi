<?php

$user = new User;

if($do == 'hapus'){
  if($user->cek($id, 'id')){
    $submit = $user->set(array(
      'aksi' => 'hapus',
      'idPemilih' => $id,
      'namaPemilih' => $user->show($id, 'namaPemilih'),
    ));

  }else{
    $submit['success'] = false;
    $submit['msg'] = 'Tidak dapat Menghapus';
  }

  echo json_encode($submit);

}elseif($do == 'tambah' || $do == 'edit'){
  if($do == 'edit'){
    if($user->cek($id, 'id')){
      $editNama = $user->show($id,'namaPemilih');
      $editUsername = $user->show($id,'username');
      $editPass = $user->show($id,'password');
      if(empty($editNama)){
        $anonim = ' <div class="col"><i class="text-primary"> Anonim</i></div>';
      }
      $aksi = 'edit';
      $aksiURI = 'edit/'.$id;
    }else{
      header('location:'.$adminHome.'pemilih-tetap');
    }
  }else{
    $aksi = 'tambah';
    $aksiURI = $aksi;
  }

  if($_POST['set']){
    header('Content-Type: application/json');

    $namaPemilih = $_POST['namaPemilih'];
    $userName = $_POST['userName'];
    
    if($_POST['passPemilih1'] == $_POST['passPemilih2']){
      $userPass = $_POST['passPemilih1'];
      $submit = $user->set(array(
        'aksi' => $aksi,
        'idPemilih' => $id,
        'namaPemilih' => $namaPemilih,
        'userName' => $userName,
        'password' => $userPass,
      ));
    }else{
      $submit['success'] = false;
      $submit['msg'] = 'Password Tidak sama !';
    }

    echo json_encode($submit);
  
  }else{
?>

<div class="form-group">
  <label for="namaPemilih"><b>Nama Pemilih Tetap </b><i class="text-primary">bisa dikosongi (Anonim)</i></label>
  <div class="row">
    <div class="col">
      <input class="form-control" id="namaPemilih" type="text" name="namaPemilih" placeholder="Nama"
        value="<?php echo $editNama?>">
    </div>
    <?php echo $anonim?>
  </div>
</div>
<div class="form-group">
  <label for="username"><b>Username</b></label>
  <input class="form-control" id="username" type="text" name="userName" placeholder="username"
    value="<?php echo $editUsername?>" required>
</div>
<div class="form-group">
  <label for="password1"><b>Password</b></label>
  <div class="row">
    <div class="col-sm-6">
      <input class="form-control" type="password" id="password1" name="passPemilih1" placeholder="Password" required>
    </div>
    <div class="col-sm-6">
      <input class="form-control" type="password" id="password2" name="passPemilih2" placeholder="Retype Password"
        required>
    </div>
  </div>
</div>

</div>
<div class="modal-footer">
  <input type="hidden" name="set" value="set">
  <button class="btn btn-primary" data-dismiss="modal">Batal</button>
  <button type="submit" class="btn btn-success btn-update-u" target-aksi="<?=$aksiURI?>"><i class="fa fa-check"></i>
    Update</button>

  <script src="assets/js/upload.js"></script>

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
        $msg .= '<div class="p-3 alert-success"> Berhasil menambahkan Pemilih <b>'.$randomName.'</b> </div>';
      }else{
        $scsAdd[] = 0;
        $gglAdd[] = 1;
        $msg .= '<div class="p-3 alert-danger">Gagal menambahkan Pemilih <b>'.$randomName.'</b> "'.$submit['msg'].'" </div>';
      }
    }//akhir generate user otomatis

    if(array_sum($scsAdd) >0){
      $arr['success'] = true;
      $arr['msg'] = 'Berhasil menambahkan '.array_sum($scsAdd) .' pemilih tetap !';
    }else{
      $arr['success'] = false;
      $arr['msg'] = 'Gagal Menambahkan pemilih tetap !';
    }
    $arr['msgs'] = $msg;
    $arr['berhasil'] =  '<p class="p-3 m-0 text-success">Berhasil Ditambahkan => <b>'. array_sum($scsAdd).'</b></p>';
    $arr['gagal']  = '<p class="p-3 m-0 text-danger"> Gagal Ditambahkan => <b>'.array_sum($gglAdd).'</b></p>';

    header('Content-Type: Application/json');
    echo json_encode($arr);

  }else{
?>
  <div class="form-group">
    <label for="targetJumlah"><b>Jumlah Pemilih Tetap yang diinginkan</b></label>
    <input type="number" id="targetJumlah" class="form-control" name="jumlahUser" required
      placeholder="Jumlah Pemilih Tetap">
  </div>
</div>
<div class="modal-footer">
  <input type="hidden" name="generate" value="generate">
  <button class="btn btn-primary" data-dismiss="modal">Batal</button>
  <button type="submit" class="btn btn-success btn-update-u" target-aksi="generate"><i class="fa fa-check"></i>
    Generate</button>

  <script src="assets/js/upload.js"></script>

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

  if(@isset($_POST['hapusTerpilih'])){
    
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
      if(array_sum($scsHapus)>0){
        $arr['success'] = true;
      }else{
        $arr['success'] = false;
      }
      $arr['msg'] = 'Berhasil mengahapus <b>'. array_sum($scsHapus) .'</b> Data';

    }else{
      $arr['success'] = false;
      $arr['msg'] = 'tidak ada data yang dihapus';
    }// Akhir Mass Hapus
    header('Content-Type:Application/json');
    echo json_encode($arr);
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
        <td>
          <a href="'.$adminHome.'pemilih-tetap/edit/'.$idUser.'" class="btn btn-primary btn-mdl tambahAksi" idForm="form-update-u" target-name="pemilih-tetap" target-aksi="edit" target-id="'.$idUser.'" target-init="'.$userName.'"> <i class="fa fa-edit"></i> Edit </a>
          <a href="'.$adminHome.'pemilih-tetap/hapus/'.$idUser.'" class="btn btn-danger btn-mdl tambahAksi" idForm="form-update-u" target-name="pemilih-tetap" target-aksi="hapus" target-id="'.$idUser.'" target-init="'.$userName.'"> <i class="fa fa-trash-alt"></i> Hapus</a>
        </td>
      </tr>';
    }

    echo '</form>';
?>

  <form action="" method="post" id="userList">
    <input type="hidden" name="hapusTerpilih">
    <div class="wow fadeInUp" data-wow-duration="1.7s" data-wow-delay="1.2s">
      <div class="d-flex align-items-center" style="justify-content:space-between;">
        <h3 class="sub-title" style="font-weight:bold;">Daftar Pemilih Tetap <button class="btn ml-2 reset"
            disabled="disabled">Reset</button></h3>
      </div>
      <div class="d-flex" style="justify-content:space-between">
        <h5 class="sub-title">Total : <strong> <?=$user->jumlahUser?> </strong></h5>
        <a href="<?=$adminHome?>app/pemilih-tetap/export" class="btn btn-outline-dark" style="margin:0 30px 0 10px">
          <i class="fa fa-file-excel"></i> Export XLS
        </a>
      </div>

      <button class="btn-mdl btn btn-outline-danger tambahAksi" idForm="form-update-u" target-name="pemilih-tetap"
        target-aksi="hapus" target-id="terpilih" target-init="Terpilih"><i class="fa fa-trash-alt"></i> Hapus
        Terpilih</button>
      <button class="btn-mdl btn btn-outline-dark tambahAksi" idForm="form-update-u" target-name="pemilih-tetap"
        target-aksi="tambah"><i class="fa fa-plus"></i> Tambah Pemilih</button>
      <button class="btn-mdl btn btn-outline-dark tambahAksi" idForm="form-update-u" target-name="pemilih-tetap"
        target-aksi="generate"><i class="fa fa-plus"></i> Generate
        Pemilih</button>
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

  <script src="assets/js/kandidat.js"></script>
  <script>
  $(document).ready(function() {
    $('#dataTables').DataTable();

  });
  </script>
  <?php
  }
}

?>