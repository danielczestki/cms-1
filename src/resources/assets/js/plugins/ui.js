/**
 * Plain old jQuery plugin calls and some other basic jQuery
 * and JS DOM hooks for the UI
 */

import $ from "jquery";
import slimScroll from "../vendor/jquery-slimscroll.js";
import Pikaday from "pikaday-time";

(function(){
  
  /**
   * Init the tinymce editors. We don't use a Vuejs component here as we
   * want to utlise the form model binding without any config, this way
   * this work automatically.
   */
  tinymce.init({
    selector: ".Form__wysiwyg",
    statusbar: false,
    menubar: "edit insert table view tools",
    toolbar: "styleselect | bold italic | link | alignleft aligncenter alignright | bullist numlist | indent outdent | image | removeformat",
    content_css: "/vendor/cms/css/wysiwyg.css",
    plugins: "link autolink image code paste searchreplace anchor charmap table imagetools hr contextmenu visualchars visualblocks",
    paste_data_images: true,
    paste_word_valid_elements: "b,strong,i,em,h1,h2,a",
    paste_webkit_styles: "color font-size",
    paste_retain_style_properties: "color font-size",
    contextmenu: "cut copy paste | bold italic | link image inserttable"
  });
  
  // Insert contet at the current cursor position ("f-body" is the ID of the textarea)
  // tinymce.get("f-body").execCommand("mceInsertContent", false, "INSERT ME HERE");
  
  
  
  /**
   * Hook up a pikaday time plugin to all the date fields
   */
  Array.prototype.forEach.call(document.getElementsByClassName("Form__input--date"), (el) => {
    new Pikaday({
      field: el,
      showTime: Boolean(el.getAttribute("data-time")),
      incrementMinuteBy: 5
    });
  });
  
  /**
   * Hook up the slimScroll jQuery plugin to the primary nav.
   * This add a slimscroll to the nav if it goes below the
   * viewport height, as the primary is fixed
   */
  $(".Primary__overflow").slimScroll({
    height: "100%",
    size: "5px",
    opacity: 1
  });
  
})();