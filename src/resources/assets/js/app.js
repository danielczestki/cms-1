import Vue from "vue";
import "./plugins/ui";

/**
 * Global components
 */
//Vue.component("nav", require("./components/Nav/Nav"));

/**
 * Init the app
 */
new Vue({
  
  el: "#app",
  
  data: {
    nav_open: false
  }
  
});