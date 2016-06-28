module.exports = {
  
  props: ["type"],
  template: require("./Alert.html"),
  data() {
    return {
      visible: true
    }
  },
  methods: {
    // Close the alert
    close() {
      this.visible = false
    }
  },
  ready() {
    setTimeout(() => {
      this.visible = false;
    }, 7000);
  }
  
}