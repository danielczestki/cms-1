module.exports = {
  
  props: {
    media_focus: {required: true},
    media_click: {required: true},
    name: {required: true},
    label: { required: true },
    limit: {
      required: true,
      default: 0 },
    allowed: {
      coerce: function (val) {
        if (! val) return ["image", "video", "document", "embed"];
        return JSON.parse(val)
      }
    },
    existing: {
      coerce: function (val) {
        return JSON.parse(val)
      }
    }
  },
  
  template: require("./Mediaselect.html"),
  
  computed: {
    count() {
      let count = 0;
      this.existing.forEach((data) => {
         if (! data.removed) count++;
      });
      return count;
    },
    disabled() {
      return this.limit == 0 ? false : (this.count >= this.limit);
    }
  },
  
  partials: {
    image: require("./image.html"),
    video: require("./video.html"),
    document: require("./document.html"),
    embed: require("./embed.html")
  },
  
  methods: {
    pick() {
      if (this.disabled) return false;
      this.media_focus = this.name;
      this.media_click(true, this.allowed);
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
  }  
}