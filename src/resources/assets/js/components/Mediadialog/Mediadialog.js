module.exports = {
  
  props: ["media_allowed", "media_deleted", "open", "src"],
  template: require("./Mediadialog.html"),
  computed: {
    _src() {
      return this.open ? (this.src + "?allowed=" + this.media_allowed + "&deleted=" + this.media_deleted + "&tiny=" + (this.media_deleted ? false : true)) : "about:blank";
    }
  },
  methods: {
    // show/hide the dialog
    toggle() {
      this.open = ! this.open;
    }
  }
  
}