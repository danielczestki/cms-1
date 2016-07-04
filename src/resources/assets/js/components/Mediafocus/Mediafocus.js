
module.exports = {
  
  props: ["image", "section"],
  template: require("./Mediafocus.html"),
  methods: {
    select(section) {
      this.section = section
    }
  }
  
}