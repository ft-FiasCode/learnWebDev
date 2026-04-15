$(document).ready(function () {

  $(document).ready(function () {
    $("p").click(function () {
      $(this).hide(2000);
    });

    $("#btnhide").click(function () {
      $("p").show(2000);
    });

    var styled = false;
    $("#btnShow").click(function () {
      if (!styled) {
        $("p").css("background-color", "lightgreen");
        styled = true;
      } else {
        $("p").css("background-color", "#f0f0f0");
        styled = false;
      }
    });
  });

});