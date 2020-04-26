function readURL(input) {
  if (input.files && input.files[0]) {

    for (let i = 0; i < input.files.length; i++) {
      let reader = new FileReader();

      reader.onload = function (e) {
        var img = '<div class="mr-1"><img class="file-upload-image" src="' + e.target.result + '" alt="' + e.target.result + '" id="img' + i + '" /></div>';
        $('.uploadFile').hide();
        $('.file-upload-content').append(img);
        $('.file-upload-content').show();
      };

      reader.readAsDataURL(input.files[i]);
    }
    $('.fileBaru').append('<button type="button" onclick="removeUpload()" class="remove-image btn btn-danger">Ganti</button>')

  } else {
    removeUpload();
  }
}

function removeUpload(hapus = null) {
  if (hapus != null) {
    $('#' + hapus).remove();
  } else {
    $('.uploadFile').removeClass('image-dropping');
    $('.pilihFile').val('');
    $('.pilihFile').replaceWith($('.pilihFile').clone());
    $('.fileBaru').html('<div class="file-upload-content d-flex"></div>');
    $('.uploadFile').show();
  }
}


(function () {

  $('.uploadFile').bind('dragover', function () {
    $(this).addClass('image-dropping');
  });
  $('.uploadFile').bind('dragleave', function () {
    $(this).removeClass('image-dropping');
  });


  $('.app-setting').on('submit', function (e) {
    e.preventDefault();
    $('.simpan').attr('disabled', 'disabled');
    $('.simpan').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Simpan');

    $.ajax({
      url: "app/settings",
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      dataType: "json",
      success: function (data) {
        if (data.success) {
          notif('success', 'Berhasil menyimpan Perubahan');
          $("#item-preload").fadeIn("slow", function () {
            $(this).show();
            $('.app').load('app/settings', function () {
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
          notif('danger', 'Terjadi Kesalahan');
          $('.simpan').removeAttr('disabled');
          $('.simpan').html('<i class="fa fa-check"></i> Simpan');
        }
      }
    })
  })

  $("#form-update").on("submit", function (e) {
    e.preventDefault();
    $('.btn-update-k').attr('disabled', 'disabled');
    $('.btn-update-k').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Update');
    var aksi = $(this).attr("data-aksi");

    $.ajax({
      url: "app/kandidat/" + aksi,
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      dataType: "json",
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
          $('.btn-update-k').removeAttr('disabled');
          $('.btn-update-k').html('<i class="fa fa-check"></i> Update');
        }
        notifikasi = '<div class="alert alert-' + laler + ' d-flex justify-content-center align-items-center" style="height:100px">' + data.msg + '</div>'
        $("#form-update").html(notifikasi);
      }
    });

  });

  $('#form-update-u').on('submit', function (e) {
    e.preventDefault();
    $('.form-update').removeAttr('id');
    $('.btn-update-u').attr('disabled', 'disabled');
    $('.btn-update-u').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Update');
    var aksi = $('.btn-update-u').attr('target-aksi');

    $.ajax({
      url: 'app/pemilih-tetap/' + aksi,
      dataType: 'json',
      data: new FormData(this),
      type: 'POST',
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        if (data.success) {
          notif('success', data.msg);
          $("#item-preload").fadeIn("slow", function () {
            $(this).show();
            $('.app').load('app/pemilih-tetap', function () {
              if ($("#item-preload").length) {
                $("#item-preload")
                  .delay(1000)
                  .fadeOut("slow", function () {
                    $(this).hide();
                    if (aksi == 'generate') {
                      var out = data.msgs + data.berhasil + data.gagal;
                      $('.updateAksi').html(out);
                    } else {
                      $('#modal').modal('hide');
                    }
                  });
              }
            })
          });
        } else {
          notif('danger', data.msg);
          $('.btn-update-u').removeAttr('disabled');
          $('.btn-update-u').html('<i class="fa fa-check"></i> Simpan');
        }
      }
    })
  })

})();