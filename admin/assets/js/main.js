var animasi =
  '<div class="load-animation load-content"> <div class="anime"> <div class="a-box"></div> <div class="a-box"></div> <div class="a-box"></div> <div class="a-box"></div><div class="a-box"></div></div></div>',
  itemPreload =
  '<div id="item-preload" class="wow fadeIn"> <div class="item-preload"> <i class="fa fa-fan"></i> <span>Menyiapkan Aplikasi</span> </div></div>',
  close =
  '<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>',
  modal =
  '<div class="modal-content animate zoomIn" style"animation-duration:0.3s"><div class="modal-header"><h5 class="modal-title"></h5>' +
  close +
  '</div><div class="modal-body"></div></div>',
  navbar =
  '<nav class="navbar navbar-expand-lg navbar-light wow fadeInDown"><div class="container"><a class="navbar-brand" href="#">ADMIN PANEL</a><a class="nav-item nav-link text-center active">Status : <span class="text-success pemilu-statusInfo"> Sedang Pemilu</span></a><a class="nav-item nav-link" href="#"> Admin <i class="fa fa-user"></i></a></div></nav>',
  notifikasi =
  '<div class="notifikasi"> <div role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-delay="10000" data-autohide="true" data-animation="true"> </div> </div><div class="app"></div>',
  madeBy =
  '<div class="madeby"> <p> Made with <i class="fa fa-heart"></i> by <a href="http://instagarm.com/aguzztn54" target="_blank" rel="noopener noreferrer"><b>Agus</b> </a> <br />Lulusan 2018 </p> </div>',
  menu = Array("dashboard", "hasil", "kandidat", "pemilih-tetap", "settings"),
  icon = Array("home", "box-open", "user-tie", "users", "cogs"),
  judul = Array("Dashboard", "Hasil Perolehan Suara", "Kandidat", "Pemilih Tetap", "Pengaturan"),
  item = "",
  alert,
  msg,
  aktif,
  menuTitle,
  benar = true,
  logged;

for (let i = 0; i < menu.length; i++) {
  if (menu[i] == "dashboard") {
    aktif = " active";
  } else {
    aktif = "";
  }
  menuTitle = menu[i].replace("-", " ");
  item += '<a data-href="' + menu[i] + '" data-title="' + judul[i] + '" onclick="pushState(\'state' + [i] + '\');" id="state' + [i] + '" class="menu-item' + aktif + ' wow  fadeInUp" data-wow-delay="0.' + [i * 2] + 's">  ' + '<i class="fa fa-' + icon[i] + '"></i> <span>' + menuTitle + "</span> </a>";
}

function notif(tipe, pesan, autohide = true) {
  alert =
    '<div class="alert alert-' +
    tipe +
    ' wow slideInDown"> <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"> <span aria-hidden="true">&times;</span> </button><div class="container">' +
    pesan +
    "</div> </div>";
  if (autohide == false) {
    $(".toast").attr("data-autohide", "false");
  } else {
    $(".toast").attr("data-autohide", "true");
  }
  $(".toast").html(alert);
  $(".toast").toast("show");
}

function loadMsg(status = null, xhr = null) {
  if (status == "error") {
    setTimeout(function () {
      $('#modal').modal('hide');
      clearTimeout();
    }, 300);

    notif("danger", 'Gagal memuat, <b>"<span>' + xhr.status + "</span> <span>" + xhr.statusText + '</span>"</b>', false);
    return false;
  } else {
    return true;
  }
}

function loadDashboard() {
  $(".sidebar")
    .delay(1500)
    .fadeIn("slow", function () {
      $(this).html(item);
    });
  $(".header")
    .delay(1500)
    .fadeIn("slow", function () {
      $(this).html(navbar);
    });

  $(".app").load("app/dashboard", function (responseTxt, statusTxt, xhr) {
    if (loadMsg(statusTxt, xhr)) {
      window.history.replaceState({
        id: 'state0',
        hal: './dashboard'
      }, "title", 'dashboard');
      document.title = 'Dashboard || Admin Panel';
    }

    if ($("#item-preload").length) {
      $("#item-preload")
        .delay(1000)
        .fadeOut("slow", function () {
          $(this).hide();
        });
    }
  });
}

function berhasilLogin(msg) {
  notif("success", msg);
  $(".tombolProses")
    .delay(2000)
    .fadeOut("slow", function () {
      $(".worksheet").addClass("col-11 col-md-9 ws-load");
      $(".login-container").fadeOut("slow", function () {
        $(this).remove();
      });
      $(".temp").fadeOut("slow", function () {
        $(this).remove();
        $(".bg-bar").removeClass("col-md-12");
        $(".bg-bar").addClass("col-md-3 sidebar");
        $(".worksheet").html(itemPreload + notifikasi + madeBy);
        loadDashboard();
      });
    });
}

function gagalLogin(msg) {
  $(".load-proccess").fadeOut("slow", function () {
    $(this).hide();
    $(this).addClass("d-none");
    $(this).removeClass("d-flex");
    notif("danger", msg);
  });
  $(".tombolProses").removeAttr("disabled");
  $(".tombolProses").removeClass("disabled");
}

function pushState(elementID) {
  var a = $('#' + elementID),
    page = a.attr('data-href'),
    title = a.attr('data-title') + ' || Admin Panel';
  $(".toast").toast('hide');
  $("#item-preload").fadeIn("slow", function () {
    $(this).show();
    $('.app').load('app/' + page, function (responseTxt, statusTxt, xhr) {
      if (loadMsg(statusTxt, xhr)) {
        $('.menu-item').removeClass('active');
        a.addClass('active');
        window.history.pushState({
          id: elementID,
          hal: page
        }, title, page);
        document.title = title;
      }
      if ($("#item-preload").length) {
        $("#item-preload")
          .delay(1000)
          .fadeOut("slow", function () {
            $(this).hide();
            console.clear();
          });
      }
    });
  });
}


// RealTime Dashboard
var devices;

function refreshData(target) {
  var output = "",
    showSid = '';
  sid = "";
  $.ajax({
    url: target,
    dataType: "json",
    success: function (data) {
      $(".hitung").each(function () {
        var id = $(this).attr("id"),
          now = $(this)
          .children(".nowNum")
          .html(),
          baru = data.suara[id];

        if (data.suara[id] != now) {
          $(this).html(
            '<span class="newNum">' +
            baru +
            '</span> <span class="nowNum">' +
            now +
            "</span>"
          );
          setTimeout(function () {
            $("#" + id)
              .children(".nowNum")
              .addClass("oldNum");
            $("#" + id)
              .children(".nowNum")
              .removeClass("nowNum");
            $("#" + id)
              .children(".newNum")
              .addClass("nowNum");
            $("#" + id)
              .children(".newNum")
              .removeClass("newNum");
          }, 100);
        }
      });

      $('.durasiPemilu').html(data.durasi);
      $('.onlineCount').html(data.bilikAktif.online);
      $('.coblosCount').html(data.bilikAktif.mencoblos);

      devices = data.bilikAktif.info;
      if (devices == null) {
        var on = $('.on-item');
        if (on.attr('id') != 'noOnline') {
          on.fadeOut('fast', function () {
            on.addClass('h0');
            setTimeout(function () {
              on.remove();
            }, 600);
          });
        }
      } else {
        $('.on-item').each(function () {
          var b, atr = $(this).attr('id'),
            status = $(this).children('.on-status').html(),
            ss = devices.filter(function (x) {
              return (atr == x.sid);
            });

          if (ss[0] != undefined) {
            b = ss[0].status
          }
          if (atr == undefined || ss.length == 0 || b != status) {
            $(this).fadeOut('fast', function () {
              $(this).addClass('h0');
              setTimeout(function () {
                $('#' + atr).remove();
              }, 600);
            });
          } else {
            showSid += atr + '/';

          }
        });
      } //Akhir Hapus User Offline


      var sh = showSid.split('/');
      if (devices == null) {
        if ($('.on-item').attr('id') != 'noOnline') {
          output = '<div id="noOnline" class="on-item d-flex justify-content-center mt-3 align-items-center text-center h0"> <span class="device-name"> Tidak Ada Device Aktif</span> </div>';
        }
      } else {
        devices.forEach(device => {
          if ($.inArray(device.sid, sh) < 0) {
            output +=
              '<div id="' + device.sid + '" class="on-item d-flex align-items-center h0"> <span class="device-name"> <i class="fab fa-' +
              device.os +
              ' on-icon"></i> ' +
              device.browser +
              ' </span> <span class="on-status">' +
              device.status +
              '</span> <div class="d-flex device-id"> <span class="sid">ID : ' +
              device.sid +
              '</span> <span class="device-ip">IP : ' +
              device.ip +
              "</span> </div> </div>";
          }
        });
      }

      setTimeout(function () {
        var tidakBerubah = $('.on-item').first();
        if (tidakBerubah.html() == undefined) {
          $('.scroll').html(output);
        } else {
          tidakBerubah.before(output);
        }
        $('.on-item').fadeIn('slow');
        $('.on-item').removeClass('h0');
      }, 1500)

      // console.clear();
    } //Akhir Respon Sukses
  });
}

// Akhir Realtime Dashboard


(function ($) {
  "use strict";

  if (!!window.EventSource) {
    const streamData = new EventSource('app/dashboard?refresh')
    streamData.onopen = function (event) {
      refreshData(streamData.url)
    }
  }


  window.onpopstate = function (e) {
    $('.toast').toast('hide');
    if (e.state.id) {
      $("#item-preload").fadeIn("slow", function () {
        $(this).show();
        $('.app').load('app/' + e.state.hal, function (responseTxt, statusTxt, xhr) {
          if (loadMsg(statusTxt, xhr)) {
            $('.menu-item').removeClass('active');
            $('#' + e.state.id).addClass('active');
          }

          if ($("#item-preload").length) {
            $("#item-preload")
              .delay(1000)
              .fadeOut("slow", function () {
                $(this).hide();
                console.clear();
              });
          }
        });
      });
    }
  }

  // Cek Status sudah login atau belum
  $(window).on("load", function () {
    if ($("#preloader").length) {
      $(".toast").toast("show");
      $("#preloader")
        .delay(1000)
        .fadeOut("slow", function () {
          $(this).remove();
        });
    }
    if (logged) {
      if ($("#item-preload").length) {
        $("#item-preload")
          .delay(1000)
          .fadeOut("slow", function () {
            $(this).hide();
          });
      }
    }
  }); // Akhir preloader


  // MODAL
  $('.modal').on('hide.bs.modal', function () {
    $('.modal-content').removeClass('zoomIn');
    $('.modal-content').addClass('zoomOut');
  });

  //
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

  // Proses Login
  $(".proses").on("submit", function (e) {
    var dataForm = 'admUsername=' + $('.username').val() + '&admPassword=' + $('.password').val();
    e.preventDefault();
    $(".tombolProses").attr("disabled", "disabled");
    $(".tombolProses").addClass("disabled");
    $(".load-proccess").html(animasi);
    $(".load-proccess").fadeIn("slow", function () {
      $(this).removeClass("d-none");
      $(this).addClass("d-flex");
    });

    $.ajax({
      url: '',
      data: dataForm,
      dataType: 'json',
      type: 'post',
      success: function (data) {
        if (data.success) {
          berhasilLogin(data.msg);
        } else {
          gagalLogin(data.msg);
        }
      }

    })
    // if (benar) {
    //   msg = "Kamu masuk admin bos ya";
    //   // berhasilLogin(msg);
    // } else {
    //   msg = "Terjado kesalahan bosque bgst";
    //   gagalLogin(msg);
    // }
  });

  $(".toast").on("hide.bs.toast, hidden.bs.toast", function () {
    $(this).removeClass("fade hide");
    $(this).html("");
  });

  // Initiate the wowjs animation library
  new WOW().init();
})(jQuery);