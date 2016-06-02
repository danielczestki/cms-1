/**
 * Plain old jQuery plugin calls and some other basic jQuery
 * and JS DOM hooks for the UI
 */

import $ from "jquery";
import slimScroll from "../vendor/jquery-slimscroll.js";
import Popper from "popper.js";

(function(){
  
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