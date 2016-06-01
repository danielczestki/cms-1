import Vue from "vue";
import "./plugins/ui";

/**
 * Init the app
 */
new Vue({
  
  el: "#app",
  
  data: {
    nav_clicked: false, // changed only if a toggle button is clicked to lock the hover method
    nav_timeout: null, // the timeout event on hovering to show/hide the nav automatically
    nav_open: false // is the nav open or not
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
    }
  }
  
});