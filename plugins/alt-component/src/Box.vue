<template>
    <div class="box" :class="{'collapsed-box':collapsed}">

        <div class="overlay" v-if="loading">
            <i class="fa fa-spin fa-sync-alt"></i>
        </div>

        <slot></slot>
    </div>
</template>

<script>
/* eslint-disable */
export default {
  name: "alt-box",
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
    dataUrl: String,
    dataUri: String
  },
  computed: {
    header() {
      return this.$children.filter(o => {
        return o.$vnode.componentOptions.tag == "alt-box-header";
      });
    },
    body() {
      return this.$slots.default
        .filter(o => {
          if (o.componentOptions == undefined) return false;
          return o.componentOptions.tag == "alt-box-body";
        })
        .map(o => {
          return o.componentInstance;
        });
    },
    footer() {
      return this.$slots.default
        .filter(o => {
          if (o.componentOptions == undefined) return false;
          return o.componentOptions.tag == "alt-box-footer";
        })
        .map(o => {
          return o.componentInstance;
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
    });

    this.header.forEach(h => {
      h.$on("pinned", isPinned => {
        this.isPinned = isPinned;
      });

      h.$on("collapsed", collapsed => {
        var data = {};
        data.type = "box";
        data.layout = {
          collapsed: collapsed
        };
        data.uri = this.dataUri;

        this.$http.post("UI/save", data);

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
      });

      h.$on("acl", acl => {
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

