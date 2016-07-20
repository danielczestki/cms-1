module.exports = {
  
  props: {
    media_focus: {required: true},
    media_click: {required: true},
    name: {required: true},
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
      this.media_focus = this.name;
      this.media_click();
    },
    remove(index) {
      let media = this.existing[index];
      media.removed = true;
      return true;
    },
    add(data) {
      this.existing.push(data);
      this.media_click(false);
    },
    ids() {
      let result = [];
      this.existing.forEach((data) => {
        if (! data.removed) result.push(data.cms_medium_id)
      });
      return result;
    }
  },
  
  
  
  ready() {
    console.log(this.ids());
  }
  
}