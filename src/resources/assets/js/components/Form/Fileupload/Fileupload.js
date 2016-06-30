module.exports = {
  
  props: ["name", "mediatype"],
  template: require("./Fileupload.html"),
  data() {
    return {
      field: null,
      image: null,
      spinner: false
    }
  },
  computed: {
    placeholder() {
      return this.field ? this.field.replace(/^.*[\\\/]/, '') : "No file selected."
    },
    stylePreview() {
      if (! this.image) return {};
      return {
        "background-image": "url("+ this.image +")"
      }
    }
  },
  methods: {
    /**
     * Activated when the user clicks the browse button
     */
    browse() {
      this.$els.field.click();
    },
    preview(event) {
      this.spinner = true;
      this.image = null;
      let file = event.target.files[0];
      if (! file.type.match('image.*')) return false;
      var reader = new FileReader();
      reader.onload = ((f) => {
        return (e) => {
          this.image = e.target.result
          this.spinner = false;
        }
      })(file)
      reader.readAsDataURL(file);      
    },
    clear() {
      this.field = "";
      this.image = null;
    }
  }
  
}