
module.exports = {
  
  props: {
    parentVue: { required: true },
    mediadata: {
      required: true,
      coerce: function (val) {
        return JSON.parse(val)
      }
    },
    csrf: { required: true },
    editUrl: {},
    deleteUrl: {},
    focalUrl: {},
    previewUrl: {},
    id: {},
    icon: {},
    type: {}
  },
  template: require("./Mediathumb.html"),
  data() {
    return {
      deleting: false,
      //deleted: true
      deleted: false
    }
  },
  computed: {
    focused() {
      return this.parentVue.$data.media_focus;
    },
    media() {
      return this.parentVue.$refs[this.focused];
    }
  },
  watch: {
    parentVue(val, oldVal) {
      if (val) this.update();
    }
  },
  methods: {
    select() {
      this.media.add(this.mediadata);
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
    },
    update() {
      let currentIds = this.media.ids();
      //if (currentIds.indexOf(parseInt(this.id)) == -1) this.deleted = false;
    }
  }
  
}