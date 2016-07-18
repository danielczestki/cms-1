
module.exports = {
  
  props: ["csrf", "editUrl", "deleteUrl", "focalUrl", "previewUrl", "id", "icon", "type"],
  template: require("./Mediathumb.html"),
  data() {
    return {
      deleting: false,
      deleted: false
    }
  },
  methods: {
    click() {
      console.log("clicked");
    },
    delete() {
      if (! confirm("Are you sure you want to permanently delete this media record?")) return false;
      this.deleting = true;
      this.$http.delete(this.deleteUrl, {body: {_token: this.csrf}}).then((response) => {
        let result = response.json();
        if (result.success) {
          // all good
          this.deleted = true;
        } else {
          // just chuck back a alert for now
          alert(result.message);
          this.deleting = false;
        }
      });
    }
  }
  
}