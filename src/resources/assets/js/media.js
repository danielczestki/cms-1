import Vue from "vue";

/**
 * Init the media dialog
 */
if (document.getElementById("media")) {
  
  new Vue({
    el: "#media",
    data: {
      parentVue: null
    },
    components: {
      mediafocus: require("./components/Mediafocus/Mediafocus")
    },
    ready() {
      this.parentVue = parent.vueApp;
    }
  });
  
}