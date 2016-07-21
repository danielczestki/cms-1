module.exports = {
  
  props: ["media_allowed", "open", "src"],
  template: require("./Mediadialog.html"),
  computed: {
    _src() {
      return this.open ? this.src + "?allowed=" + this.media_allowed : "about:blank";
    }
  },
  methods: {
    // show/hide the dialog
    toggle() {
      this.open = ! this.open;
    }
  }
  
}