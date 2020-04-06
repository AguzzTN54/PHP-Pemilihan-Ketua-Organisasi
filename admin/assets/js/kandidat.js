$(".btn-mdl").on("click", function (e) {
  e.preventDefault();
  $('.modal-dialog').html(modal);
  $('.modal-body').html('<form action="" method="post" id="form-update" enctype="multipart/form-data" style="min-height:200px; position:relative"> <div id="spiner" class="d-flex justify-content-center bg-white" style="align-items:center; height:100%; position:absolute; top:0;left:0;width:100%;z-index:100"><div class="spinner-border text-primary" role="status"> <span class="sr-only">Loading...</span> </div></div> <div  class="updateKandidat"></div></form>')
  $("#modal").modal("show");
});

$('.tambah-k').on('click', function () {
  $('.modal-title').html('Tambah Kandidat');
  $('.updateKandidat').load('app/kandidat/tambah', function (responseTxt, statusTxt, xhr) {
    if (loadMsg(statusTxt, xhr)) {
      if ($("#spiner").length) {
        $("#spiner").delay(1000).fadeOut("slow", function () {
          $(this).remove();
        });
      }
    }
  });
  $('#form-update').attr('data-aksi', 'tambah');
});

$('.sunting').on('click', function () {
  var namaKandidat = $(this).parents('.do').siblings('h4').html(),
    idKandidat = $(this).parents('.kandidat-item').attr('data-id');
  $('.modal-title').html('Edit Data ' + namaKandidat);
  $('.updateKandidat').load('app/kandidat/edit/' + idKandidat, function (responseTxt, statusTxt, xhr) {
    if (loadMsg(statusTxt, xhr)) {
      if ($("#spiner").length) {
        $("#spiner").delay(1000).fadeOut("slow", function () {
          $(this).remove();
        });
      }
    }
  });
  $('#form-update').attr('data-aksi', 'edit/' + idKandidat);
});

$('.hapus').on('click', function () {
  var namaKandidat = $(this).parents('.do').siblings('h4').html(),
    idKandidat = $(this).parents('.kandidat-item').attr('data-id'),
    button = '<button class="btn btn-primary" data-dismiss="modal">Batal</button> <button class="btn btn-danger hapus-fix" onclick="hapusFix(\'' + idKandidat + '\')">Hapus</button>';
  $('.modal-title').html('Yakin Menghapus "' + namaKandidat + '" ?');
  $('.modal-body').html('Yakin menghapus kandidat <b>"' + namaKandidat + '"</b> ? <br/> data yang dihapus tidak dapat dikembalikan !</div><div class="modal-footer">' + button);
});

function hapusFix(id) {
  $('.hapus-fix').attr('disabled', 'disabled');
  $('.hapus-fix').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Hapus')
  $.ajax({
    url: 'app/kandidat/hapus/' + id,
    dataType: 'json',
    success: function (data) {
      var laler;
      if (data.success) {
        laler = 'success';
        $("#item-preload").fadeIn("slow", function () {
          $(this).show();
          $('.app').load('app/kandidat', function () {
            if ($("#item-preload").length) {
              $("#item-preload")
                .delay(1000)
                .fadeOut("slow", function () {
                  $(this).hide();
                  $('#modal').modal('hide');
                });
            }
          })
        });

      } else {
        laler = 'danger';
        $('.hapus-fix').removeAttr('disabled');
        $('.hapus-fix').html('Hapus');
      }
      notifikasi = '<div class="alert alert-' + laler + '">' + data.msg + '</div>'
      $("#form-update").html(notifikasi);
    }
  })
}

$('.lihat').on('click', function () {
  var idKandidat = $(this).parents('.kandidat-item').attr('data-id'),
    namaKandidat = $(this).parents('.do').siblings('h4').html()
  $('.modal-title').html('Perolehan Suara ' + namaKandidat)
  $('.updateKandidat').load('app/hasil/kandidat/' + idKandidat, function (responseTxt, statusTxt, xhr) {
    if (loadMsg(statusTxt, xhr)) {
      if ($("#spiner").length) {
        $("#spiner").delay(1000).fadeOut("slow", function () {
          $(this).remove();
        });
      }
    }
  });
})