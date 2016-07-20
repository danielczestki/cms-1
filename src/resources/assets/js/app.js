import Vue from "vue";
import VueResource from "vue-resource";
Vue.use(VueResource);

// Global components (likely form elements)
Vue.component("alert", require("./components/Alert/Alert"));
Vue.component("fileupload", require("./components/Form/Fileupload/Fileupload"));
Vue.component("mediaselect", require("./components/Form/Mediaselect/Mediaselect"));
Vue.component("mediathumb", require("./components/Mediathumb/Mediathumb"));

/**
 * Init the app (cms)
 */
if (document.getElementById("app")) {
  
  window.vueApp = new Vue({
    el: "#app",
    data: {
      nav_clicked: false, // changed only if a toggle button is clicked to lock the hover method
      nav_timeout: null, // the timeout event on hovering to show/hide the nav automatically
      nav_open: false, // is the nav open or not
      media_open: false, // is the media dialog open or not?
      media_focus: null,
    },
    components: {
      mediadialog: require("./components/Mediadialog/Mediadialog") // media dialog popup (the iframe basically)
    },
    methods: {
      // Reveal/hide the primary nav on clicking a toggle button
      primary_click() {
        this.nav_open = ! this.nav_open;
        this.nav_clicked = ! this.nav_clicked;
      },
      // Reveal/hide the primary nav on hover (after a timeout)
      primary_hover(state) {
        clearTimeout(this.nav_timeout);
        if (this.nav_clicked) return false;
        if (state == "out") {
          this.nav_open = false;
          return true;
        }
        this.nav_timeout = setTimeout(() => {
          this.nav_open = true;
        }, 1000)
      },
      media_click(state = null) {
        this.media_open = state ? state : !this.media_open;
      }
    }
  });

}