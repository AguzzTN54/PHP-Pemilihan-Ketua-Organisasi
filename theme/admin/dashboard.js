$(document).ready(function () {

  $(".nowNum").counterUp({
    delay: 1,
    time: 500
  });

  $(".tugel-pilu").on("click", function (e) {
    e.preventDefault();
    if ($(this).hasClass("stop-pemilu")) {
      $(this).html('<i class="fa fa-toggle-off"></i> Start');
      $(this).addClass("start-pemilu");
      $(this).removeClass("stop-pemilu");
      $(".pemilu-on").html("<span>Pemilu Masih Berhenti</span>");
      $(".pemilu-on").addClass("pemilu-off");
      $(".pemilu-on").removeClass("pemilu-on");
    } else {
      $(this).html('<i class="fa fa-toggle-on"></i> Stop');
      $(this).addClass("stop-pemilu");
      $(this).removeClass("start-pemilu");
      $(".pemilu-off").html("<span>Pemilu Sedang Berlangsung</span>");
      $(".pemilu-off").addClass("pemilu-on");
      $(".pemilu-off").removeClass("pemilu-off");
    }
    notif("success", "hore");
  });
});