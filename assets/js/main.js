var animasi =
  '<div class="load-animation load-content"> <div class="anime"> <div class="a-box"></div> <div class="a-box"></div> <div class="a-box"></div> <div class="a-box"></div><div class="a-box"></div></div></div>',
  close =
  '<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>',
  modal = '<div class="modal-content animasi zoomIn"><div class="modal-header"><h5 class="modal-title"></h5>' + close + '</div><div class="modal-body"></div><div class="modal-footer"></div></div>',
  durasiNyoblos = $('.nav-kanan').attr('durasi-nyoblos'),
  pecah = durasiNyoblos.split(':'),
  menit = pecah[0],
  detik = pecah[1],
  dr,
  logTime = 10,
  lg,
  d,
  bL,
  gL,
  hA,
  dl,
  delayed,
  delay = 2,
  hideDelay = 2, //timer
  teks,
  ll,
  logos;

function alert(type, txt) {
  teks =
    '<div class="alert alert-' +
    type +
    ' alert-dismissible fade show wow bounceInLeft" role="alert" data-wow-delay="0s" data-wow-duration="1s">' +
    txt +
    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
  return teks;
}

function hideAlert() {
  hA = window.setTimeout(hideAlert, 1000);
  hideDelay--;
  if (hideDelay < -10) {
    $(".alert").removeClass("show");
    window.clearTimeout(hA);
    hideDelay = 2;
  }
}

function durasiLogOut() {
  lg = window.setTimeout(durasiLogOut, 1000);
  logTime--;
  if (logTime < 0) {
    window.clearTimeout(lg);
    logTime = 0;
    window.location.replace('./?act=logout');
  }
  $('.durasi-logout').html(logTime);
}

function durasi() {
  dr = window.setTimeout(durasi, 1000);
  detik--;
  if (detik < 0) {
    menit = menit - 1;
    detik = 59;
    if (menit < 0) {
      menit = 0;
      detik = 0;
      window.clearTimeout(dr);
      $('.modal-dialog').html(modal);
      $('.modal-title').html('WAKTU HABIS');
      $('.modal-body').html('<p style="font-size:larger">Kamu Terlalu lama menentukan pilihan, silakan login kembali ya :D Jangan Golput !!</p>');
      $('.modal-footer').html('<p>Logout otomatis dalam <span class="durasi-logout"></span> detik</p><button type="button" onclick="window.location.replace(\'./?act=logout\')" class="btn btn-primary">OK</button>');
      $('#modal').modal('show');
      durasiLogOut();
    }
  }
  $('.waktu').html(menit + ':' + detik);
}

function showNavbar(time) {
  logos = $(".brandTop")
    .attr("logo-url")
    .split(",");
  logos.forEach(function (logo) {
    ll += '<img src="' + logo + '" alt="Logo">';
  });
  var y =
    '<div class="logo wow fadeIn">' + ll.replace(undefined, "") + "</div>";
  var navKanan =
    '<div class="collapse navbar-collapse wow fadeIn"> <div class="navbar-nav ml-auto"> <a class="nav-item nav-link active wkt"> Waktu Anda <span class="waktu">' + time + '</span> </a> <a id="logout" class="nav-item nav-link active" href="#" onclick="logOut()"><i class="fa fa-power-off"></i></a> </div> </div>';

  $(".brandTop").html(y);
  $('.judul').addClass('text-center');
  $('.nav-kanan').addClass('d-unset');
  $(".nav-kanan").html(navKanan);
}

function logOut() {
  // e.preventDefault();
  $('.modal-dialog').html(modal);
  $('.modal-title').html('Yakin Keluar?');
  $('.modal-body').html('<p style="font-size:larger">Kamu akan keluar dari bilik sekarang sebelum waktu berakhir, yakin akan keluar ?</p>');
  $('.modal-footer').html('<button type="button" class="btn btn-primary" data-dismiss="modal">Batal</button> <button type="button" onclick="window.location.replace(\'./?act=logout\')" class="btn btn-danger">Log Out</button>')
  $('#modal').modal('show');
}

function berhasilLogin(txt, delayed) {
  bL = window.setTimeout(berhasilLogin, 1000);
  delay--;
  if (delayed) {
    dl = 0;
  } else {
    dl = 2;
  }
  $(".login-alert").html(txt);
  if (delay < dl) {
    var waktu = $('.nav-kanan').attr('durasi-nyoblos');
    showNavbar(waktu);
    $("#log").removeClass("container");
    $("#login").removeClass("col-md-5");
    $("#login").addClass("col-md-12");
    $("#login").addClass("logged-in");
    $(".dua").addClass("active");
    $(".kandidat-list").addClass(" kandidat");
    $(".ngarep").fadeOut("slow", function () {
      $(this).remove();
    });
    window.clearTimeout(bL);
    delay = 2;

    $(".kandidat-list").load("index.php?act=app", function (
      // $(".kandidat-list").load("kandidat.html", function (
      responseTxt,
      statusTxt,
      xhr
    ) {
      if (statusTxt == "error") {
        var err =
          '<div class="gagal-menampilkan-kandidat"> <span>' +
          xhr.status +
          "</span><span>" +
          xhr.statusText +
          "</span></div>";
        $(this).html(err);
      }
    });
  }
} //akhir fumgsi berhasil Login

function gagalLogin(txt) {
  $(".password").val("");
  $(".load-proccess")
    .delay(500)
    .fadeOut("slow", function () {
      $(this).hide();
      $(".tombolProses").removeAttr("disabled");
      $(this).removeClass("d-flex");
      $(this).addClass("d-none");
    });
  $(".login-alert").html(txt);
}

(function ($) {
  "use strict";

  // Cek Status sudah login atau belum
  $(window).on("load", function () {
    if ($("#preloader").length) {
      $("#preloader")
        .delay(1000)
        .fadeOut("slow", function () {
          $(this).remove();
        });
    }
    // $.ajax({
    //   url: "json.json",
    //   data: "check",
    //   type: "post",
    //   dataType: "json",
    //   success: function (data) {
    //     if (data.status == "masuk") {
    //       if ($("#preloader").length) {
    //         $("#preloader")
    //           .delay(4000)
    //           .fadeOut("slow", function () {
    //             $(this).remove();
    //           });
    //       }
    //       berhasilLogin("", false);
    //       durasi();
    //     } else {
    //       if ($("#preloader").length) {
    //         $("#preloader")
    //           .delay(1000)
    //           .fadeOut("slow", function () {
    //             $(this).remove();
    //           });
    //       }
    //     }
    //   },
    //   error: function () {
    //     if ($("#preloader").length) {
    //       $("#preloader")
    //         .delay(1000)
    //         .fadeOut("slow", function () {
    //           $(this).remove();
    //         });
    //     }
    //     teks = alert(
    //       "danger",
    //       "Terdapat Masalah pada server, mungkin beberapa fungsi tidak berjalan"
    //     );
    //     gagalLogin(teks);
    //   } // Akhir error
    // });
  }); // Akhir Cek Login

  //cek data client
  $(".proses").on("submit", function () {
    var dataForm = 'username=' + $('.username').val() + '&userPassword=' + $('.password').val();

    $(".load-proccess").show();
    $(".load-proccess").removeClass("d-none");
    $(".load-proccess").html(animasi);
    $(".load-proccess").addClass("d-flex");
    $(".tombolProses").attr("disabled", "disabled");

    $.ajax({
      url: 'index.php',
      data: dataForm,
      type: "POST",
      dataType: "json",
      success: function (data) {
        if (data.success) {
          teks = alert("success", data.msg);
          if (data.level == 2) {
            berhasilLogin(teks, true);
            hideAlert();
            durasi();
          } else {
            $('.login-alert').html(teks);
          }
        } else {
          teks = alert("danger", data.msg);
          gagalLogin(teks);
          hideAlert();
        }
      }, //akhir proses
      error: function (xhr) {
        teks = alert(
          "danger",
          "<strong> Gagal menghubungkan ke Server!</strong> Mungkin terjadi kesalahan dari server atau device anda!"
        );
        gagalLogin(teks);
        hideAlert();
      } // Akhir error
    }); //akhir Ajax
    return false;
  });



  $(".show-password").on("click", function () {
    if ($(this).hasClass("fa-eye")) {
      $(".password").attr("type", "password");
      $(this).removeClass("fa-eye");
      $(this).addClass("fa-eye-slash");
    } else {
      $(".password").attr("type", "text");
      $(this).removeClass("fa-eye-slash");
      $(this).addClass("fa-eye");
    }
  });




  // Initiate the wowjs animation library
  new WOW().init();


})(jQuery);