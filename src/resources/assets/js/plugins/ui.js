/**
 * Plain old jQuery plugin calls and some other basic jQuery
 * and JS DOM hooks for the UI
 */

import $ from "jquery";
import slimScroll from "../vendor/jquery-slimscroll.js";
import Pikaday from "pikaday-time";

(function(){
  
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