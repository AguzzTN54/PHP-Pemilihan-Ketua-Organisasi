function tambahAksi(target = null, aksi = null, id = null, nama = null) {
  if (nama == null) {
    nama = "";
  } else {
    nama = '"' + nama + '"';
  }
  if (target == null || aksi == null) {
    if ($("#spiner").length) {
      $("#spiner").fadeOut("slow", function () {
        $(this).remove();
        $("#modal").modal("hide");
      });
    }
    notif("danger", "Tidak dapat memuat");
  } else {
    if (id == null) {
      if (aksi == "tambah" || aksi == "generate") {
        updateAksi = true;
        aksiURI = aksi;
      } else {
        if ($("#spiner").length) {
          $("#spiner").fadeOut("slow", function () {
            $(this).remove();
            $("#modal").modal("hide");
          });
        }
        notif("danger", "Tidak ada data yang sesuai");
        updateAksi = false;
      }
    } else {
      updateAksi = true;
      aksiURI = aksi + "/" + id;
    }

    $(".modal-title").html(
      '<span style="text-transform:capitalize">' +
        aksi +
        " " +
        target +
        " " +
        nama +
        "</span>"
    );
    if (updateAksi) {
      $(".updateAksi").load("app/" + target + "/" + aksiURI, function (
        responseTxt,
        statusTxt,
        xhr
      ) {
        if (loadMsg(statusTxt, xhr)) {
          if ($("#spiner").length) {
            $("#spiner")
              .delay(500)
              .fadeOut("slow", function () {
                $(this).remove();
              });
          }
        }
      });
      $(".form-update").attr("data-aksi", aksiURI);
    }
  }
  return;
}

function hapusAksi(target, id, nama = null) {
  if (nama == null) {
    nama = "";
  } else {
    nama = '"' + nama + '"';
  }
  if (id == "terpilih") {
    var hapusClick = "hapusFix('" + target + "','" + id + "')";
  } else {
    var hapusClick = "hapusFix('" + target + "'," + id + ")";
  }
  var button =
    '<button class="btn btn-primary" data-dismiss="modal">Batal</button> <button class="btn btn-danger hapus-fix" onclick="' +
    hapusClick +
    '">Hapus</button>';
  $(".modal-title").html("Yakin Menghapus " + nama + " ?");
  $(".modal-body").html(
    "Yakin menghapus data <b>" +
      nama +
      '</b> ? <br/> data yang dihapus tidak dapat dikembalikan !</div><div class="modal-footer">' +
      button
  );
  return;
}

function hapusFix(target = null, id = null) {
  if (target != null || id != null) {
    $(".hapus-fix").attr("disabled", "disabled");
    $(".hapus-fix").html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Hapus'
    );
    var url, data;
    if (id == "terpilih") {
      url = "app/" + target;
      data = $("#userList").serialize();
    } else {
      url = "app/" + target + "/hapus/" + id;
      data = "";
    }

    $.ajax({
      url: url,
      dataType: "json",
      data: data,
      type: "POST",
      success: function (data) {
        var laler;
        if (data.success) {
          laler = "success";
          notif("success", data.msg);
          $("#item-preload").fadeIn("slow", function () {
            $(this).show();
            $(".app").load("app/" + target, function () {
              if ($("#item-preload").length) {
                $("#item-preload")
                  .delay(500)
                  .fadeOut("slow", function () {
                    $(this).hide();
                    $("#modal").modal("hide");
                  });
              }
            });
          });
        } else {
          notif("danger", data.msg);
          laler = "danger";
          $(".hapus-fix").removeAttr("disabled");
          $(".hapus-fix").html("Hapus");
        }
        notifikasi =
          '<div class="alert alert-' + laler + '">' + data.msg + "</div>";
        $(".form-update").html(notifikasi);
      },
    });
  }
}

$(document).ready(function () {
  $(".btn-mdl").on("click", function (e) {
    var idForm = $(this).attr("idForm"),
      formID;
    if (idForm) {
      formID = idForm;
    } else {
      formID = "form-update";
    }

    e.preventDefault();
    $(".modal-dialog").html(modal);
    $(".modal-body").html(
      '<form action="" method="post" id="' +
        formID +
        '" class="form-update" enctype="multipart/form-data" style="min-height:200px; position:relative"> <div id="spiner" class="d-flex justify-content-center bg-white" style="align-items:center; height:100%; position:absolute; top:0;left:0;width:100%;z-index:100"><div class="spinner-border text-primary" role="status"> <span class="sr-only">Loading...</span> </div></div> <div  class="updateAksi"></div></form>'
    );
    $("#modal").modal("show");
  });

  $(".tambahAksi").on("click", function () {
    let target = $(this).attr("target-name"),
      aksi = $(this).attr("target-aksi"),
      id = $(this).attr("target-id"),
      nama = $(this).attr("target-init");

    if (aksi == "hapus") {
      return hapusAksi(target, id, nama);
    } else {
      return tambahAksi(target, aksi, id, nama);
    }
  });

  $(".lihat").on("click", function () {
    var idKandidat = $(this).parents(".kandidat-item").attr("data-id"),
      namaKandidat = $(this).parents(".do").siblings("h4").html();
    $(".modal-title").html("Perolehan Suara " + namaKandidat);
    $(".updateAksi").load("app/hasil/kandidat/" + idKandidat, function (
      responseTxt,
      statusTxt,
      xhr
    ) {
      if (loadMsg(statusTxt, xhr)) {
        if ($("#spiner").length) {
          $("#spiner")
            .delay(1000)
            .fadeOut("slow", function () {
              $(this).remove();
            });
        }
      }
    });
  });
});
