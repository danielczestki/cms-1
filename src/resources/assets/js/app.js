import $ from "jquery";
import slimScroll from "./vendor/jquery-slimscroll.js";

$(".Primary__overflow").slimScroll({
  height: "100%",
  size: "5px",
  opacity: 1
});


// TEMP
$(".Nav--toggle").on("click", function(ev) {
  ev.preventDefault();
  if ($("body").hasClass("Nav--closed")) {
    $("body").removeClass("Nav--closed");
    $("body").addClass("Nav--open");
  } else {
    $("body").removeClass("Nav--open");
    $("body").addClass("Nav--closed");
  }
})

/* TODO: ORGANISE WHEN WE GET IN TO THE JS */