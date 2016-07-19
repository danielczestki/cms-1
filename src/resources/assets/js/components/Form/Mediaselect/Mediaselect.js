module.exports = {
  
  props: {
    label: { required: true },
    existing: {
      coerce: function (val) {
        return JSON.parse(val)
      }
    }
  },
  
  template: require("./Mediaselect.html"),
  
  methods: {
    select() {
      console.log("pressed select media...");
    }
  },
  
  ready() {
    console.log(this.existing);
  }
  
}