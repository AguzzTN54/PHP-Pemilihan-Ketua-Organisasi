$(document).ready(function () {

  $(".nowNum").counterUp({
    delay: 1,
    time: 200
  });

  $(".tugel-pilu").on("click", function (e) {
    e.preventDefault();
    var data;
    if ($(this).hasClass("stop-pemilu")) {
      data = 0
    } else {
      data = 1
    }

    $.ajax({
      url: 'app/settings',
      type: 'POST',
      dataType: 'json',
      data: 'simpan&statusPemilu=' + data,
      success: function (data) {
        if (data.success) {
          if ($('.tugel-pilu').hasClass("stop-pemilu")) {
            $('.tugel-pilu').html('<i class="fa fa-toggle-off"></i> Start');
            $('.tugel-pilu').addClass("start-pemilu");
            $('.tugel-pilu').removeClass("stop-pemilu");
            $(".pemilu-on").html("<span>Pemilu Masih Berhenti</span>");
            $(".pemilu-on").addClass("pemilu-off");
            $(".pemilu-on").removeClass("pemilu-on");
            $('.pemilu-statusInfo').removeClass('text-success');
            $('.pemilu-statusInfo').addClass('text-danger');
            $('.pemilu-statusInfo').html('Pemilu Berhenti');
          } else {
            $('.tugel-pilu').html('<i class="fa fa-toggle-on"></i> Stop');
            $('.tugel-pilu').addClass("stop-pemilu");
            $('.tugel-pilu').removeClass("start-pemilu");
            $(".pemilu-off").html("<span>Pemilu Sedang Berlangsung</span>");
            $(".pemilu-off").addClass("pemilu-on");
            $(".pemilu-off").removeClass("pemilu-off");
            $('.pemilu-statusInfo').removeClass('text-danger');
            $('.pemilu-statusInfo').addClass('text-success');
            $('.pemilu-statusInfo').html('Sedang Pemilu');
          }

          notif("success", 'Success !');
        } else {

          notif("danger", 'Terjadi Kesalahan');
        }

      }
    })
  });
});