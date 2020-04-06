var delay2 = 4,
  kS,
  gkS,
  v_img;

function resetModal() {
  $("#modal")
    .delay(50)
    .fadeOut("slow", function () {
      $(".modal-dialog-centered").addClass("modal-dialog");
      $(".modal-dialog-centered").removeClass(
        "row proses-kumpulkan-suara justify-content-center"
      );
    });
}

var x, pch, iki;

function kSuara(txt) {
  x += "#AGZ#" + txt;
  pch = x.split('#AGZ#');
  iki = pch[1];

  kS = window.setTimeout(kSuara, 1000);
  delay2--;
  if (delay2 < 1) {
    $("#_1647533943456").removeClass("surat-loading");
    $("#_1647533943456").addClass("surat-masuk");
    $(".loading-suara").addClass("kotak-masuk");
    $(".teks").removeClass("show");
    $(".radio-coblos").removeClass("coblos");
    $(".wkt").addClass("fade");
    $(".kumpul").fadeOut("slow", function () {
      $(this).remove();
    });

    if (delay2 < -2) {
      $(".teks").html("<h2> Berhasil</h2>");
      $(".teks").addClass("show");
      $(".form-kandidat").fadeOut("slow", function () {
        $(this).remove();
        $(".output-coblos").html(
          ' <div class="col-md-5 bg-white pl-4 pr-4 pt-4 pb-4"><p class="check"><i class="far fa-check-circle"></i></p> <p><h4>SELESAI</h4> Terimakasih telah memberikan suara dalam pemilu ini, Kamu bisa keluar bilik sekarang :D</p> <p style="font-size:smaller">Logout otomatis dalam <span class="durasi-logout">10</span> detik</p> <a href="./?act=logout" class="btn btn-success w-100 p-1"> KELUAR </a> </div>'
        );
      });
      if (delay2 < -4) {
        $("#modal").modal("hide");
        $(".tiga").addClass("active");
        $(".login-alert").html(alert("success", iki));
        resetModal();
        durasiLogOut();
        window.clearTimeout(kS);
        delay2 = 4;
      }
    }
  }
}

function gkSuara(txt) {
  x += "#AGZ#" + txt;
  pch = x.split('#AGZ#');
  iki = pch[1];

  gkS = window.setTimeout(gkSuara, 1000);
  delay2--;
  if (delay2 < 3) {
    $(".kumpul").removeClass("disabled");
    $(".kumpul").removeAttr("disabled");
    $("input").removeAttr("disabled");
    $(".radio-coblos").removeAttr("disabled");
    $("#modal").modal("hide");
    $(".login-alert").html(alert("danger", iki));
    resetModal();
    dr = window.setTimeout(durasi, 1000);
    window.clearTimeout(gkS);
    delay2 = 4;
  }
}


// Visi
$(".visi , .misi").on("click", function (e) {
  e.preventDefault();
  var ms = $(this).html(),
    nama = $(this)
    .parents(".vm")
    .siblings(".nama-kandidat")
    .html(),
    id = $(this)
    .parents(".vm")
    .siblings(".coblos-button")
    .children(".r-coblos")
    .val();
  $(".modal-dialog").html(modal);
  $(".modal-title").html(ms + " " + nama);
  // $('.modal-body').html(id);
  $(".modal-body").load("?act=test&data=" + ms + "&idK=" + id, function (
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
  $(".modal-footer").html(
    '<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'
  );
  $("#modal").modal("show");
});

// Misi

// Pilih Kandidat
$(".coblos").on("click", function () {
  if ($(this).hasClass("coblos")) {
    $(".coblos").html('Coblos <i class="fa fa-edit"></i>');
    $(".kandidat-item").removeClass("active");
    $(this)
      .parents(".kandidat-item")
      .addClass("active");
    $(this).html('Tercoblos <i class="fa fa-check"></i>');
    $(".kumpul").removeClass("disabled");
    $(".kumpul").removeAttr("disabled");
  }
});

// Lihat gambar full
$(".v-img").on("click", function () {
  v_img = $(this).attr("href");
  teks =
    '<div class="animasi zoomIn"><img src="' +
    v_img +
    '" alt="full view">' +
    close +
    "</div>";
  $(".modal-dialog").addClass("modal-xl full");
  $(".modal-dialog").html(teks);
});
$(".modal").on("hide.bs.modal", function () {
  $(".animasi").removeClass("zoomIn");
  $(".animasi").addClass("zoomOut");
  $("#modal")
    .delay(1000)
    .fadeOut("slow", function () {
      $(".modal-dialog").removeClass("modal-xl full");
      $(".modal-dialog").html("");
    });
});

// Kirim Suara
$(".form-kandidat").on("submit", function (e) {
  idK = $(this).serialize();
  var nmKndt = $('.active').children('.nama-kandidat').html();
  window.clearTimeout(dr);
  e.preventDefault();

  $(".kumpul").addClass("disabled");
  $(".kumpul").attr("disabled", "disabled");
  $("input").attr("disabled", "disabled");
  $(".radio-coblos").attr("disabled", "disabled");
  $("#modal").modal("show");
  $(".modal-dialog-centered").removeClass("modal-dialog");
  $(".modal-dialog-centered").addClass(
    "row proses-kumpulkan-suara justify-content-center"
  );
  $(".modal-dialog-centered").html(
    '<div class="bg-white animasi loading-suara zoomIn col-sm-6"><div class="teks fade show animasi zoomIn"><h5>' + nmKndt + '</h5><span> mengirim ke kotak suara</span></div><div class="svg-suara"></div></div>'
  );
  $(".svg-suara").load("images/kotak-suara.svg");

  $.ajax({
    url: "index.php?act=app",
    data: idK,
    type: "post",
    dataType: "json",
    success: function (data) {
      if (data.success) {
        kSuara(data.msg);
        //akhir sukses mengirim
      } else {
        gkSuara(data.msg);
      } //Akhir gagal Kirim Suara
    },
    error: function () {
      teks = "<strong>Gagal Mengirim!</strong> Terjadi Kesalahan, silakan hubungi Operator jika kamu tidak bisa mengatasi masalah ini!";
      gkSuara(teks);
    } // Akhir error
  }); //akhir Ajax
});