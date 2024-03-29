<template>
  <div class="box" :class="{'collapsed-box':collapsed}">
    <div class="overlay" v-if="loading">
      <i class="fa fa-spin fa-sync-alt"></i>
    </div>
    <slot></slot>
  </div>
</template>
<script>
export default {
  name: "box",
  data: function() {
    return {
      loading: false,
      isPinned: true
    };
  },
  props: {
    collapsible: Boolean,
    collapsed: Boolean,
    closeable: Boolean,
    pinable: Boolean,
    acl: Boolean,
    aclGroup: Array,
    dataUri: String,
    dataAclUri: String,
    dataUrl: String
  },
  computed: {
    header() {
      return this.$children.filter(o => {
        return o.$vnode.componentOptions.tag == "box-header";
      });
    },
    body() {
      return this.$children.filter(o => {
        return o.$vnode.componentOptions.tag == "box-body";
      });
    },
    footer() {
      return this.$children.filter(o => {
        return o.$vnode.componentOptions.tag == "box-footer";
      });
    }
  },
  mounted() {
    this.header.forEach(h => {
      h.collapsible = this.collapsible;
      h.closeable = this.closeable;
      h.collapsed = this.collapsed;
      h.dataUrl = this.dataUrl;
      h.pinable = this.pinable;

      h.acl = this.acl;
      h.aclGroup = this.aclGroup;
    });

    this.header.forEach(h => {
      h.$on("pinned", isPinned => {
        this.isPinned = isPinned;
      });

      h.$on("closed", () => {
        this.$emit("closed");
        this.$el.parentNode.removeChild(this.$el);
      });

      h.$on("collapsed", collapsed => {
        this.$emit("collapsed", collapsed);

        this.body.forEach(e => {
          if (collapsed) {
            $(e.$el).slideUp(500);
          } else {
            $(e.$el).slideDown(500);
          }
        });
        this.footer.forEach(e => {
          if (collapsed) {
            $(e.$el).slideUp(500);
          } else {
            $(e.$el).slideDown(500);
          }
        });

        if (!this.dataUri) {
          console.log("data-url not found");
          return;
        }
        var data = {};
        data.type = "box";
        data.layout = {
          collapsed: collapsed
        };
        data.uri = this.dataUri;

        this.$http.post("UI/save", data);
      });

      h.$on("acl", acl => {
        if (!this.dataAclUri) {
          console.log("data-acl-uri not found");
          return;
        }
        var data = acl;
        data.path = this.dataAclUri;
        this.$http.post("ACL/box", data);
      });
    });

    if (this.dataUrl) {
      this.reload();
    }
  },
  methods: {
    reload() {
      if (!this.dataUri) {
        console.log("data-uri not found");
        return;
      }
      this.showLoading();
      this.$http.get(this.dataUrl).then(resp => {
        this.hideLoading();
        this.body[0].setContent(resp.body);
      });
    },
    showLoading() {
      this.loading = true;
    },
    hideLoading() {
      this.loading = false;
    },
    pin() {
      this.header.forEach(h => h.pin());
    },
    unpin() {
      this.header.forEach(h => h.unpin());
    }
  }
};
</script>
