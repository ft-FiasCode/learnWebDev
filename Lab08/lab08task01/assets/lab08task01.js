$(document).ready(function () {

function setTheme(bg, text) {
  $("body").css({
    "background-color": bg,
    "color": text
  });
}

$("#btnDark").click(function () {
  setTheme("black", "white");
});

$("#btnLight").click(function () {
  setTheme("white", "black");
});

$("#btnBlue").click(function () {
  setTheme("lightblue", "darkblue");
});


});