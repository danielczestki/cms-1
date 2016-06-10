module.exports = {
  
  props: ["open", "src"],
  template: require("./Mediadialog.html"),
  computed: {
    _src() {
      return this.open ? this.src : "about:blank";
    }
  },
  methods: {
    // show/hide the dialog
    toggle() {
      this.open = ! this.open;
    }
  }
  
}