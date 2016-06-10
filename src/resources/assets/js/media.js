import Vue from "vue";

/**
 * Init the media dialog
 */
if (document.getElementById("media")) {
  
  new Vue({
    el: "#media",
    ready() {
      console.log("sdsd");
    }
  });
  
}