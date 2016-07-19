module.exports = {
  
  props: {
    media_click: {required: true},
    name: {reuqired: true},
    label: { required: true },
    existing: {
      coerce: function (val) {
        return JSON.parse(val)
      }
    }
  },
  
  template: require("./Mediaselect.html"),
  
  partials: {
    image: require("./image.html"),
    video: require("./video.html"),
    document: require("./document.html"),
    embed: require("./embed.html")
  },
  
  methods: {
    pick() {
      this.media_click();
    },
    remove(index) {
      let media = this.existing[index];
      media.removed = true;
      return true;
    }
  }
  
}