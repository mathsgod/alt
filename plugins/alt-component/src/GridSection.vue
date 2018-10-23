<template>
    <section :style="getStyle()">
        <slot ref="s"></slot>
    </section>
</template>
<script>
export default {
  computed: {
    box() {
      if (!this.$slots.default) return [];
      return this.$children.filter(o => {
        return o.$vnode.componentOptions.tag == "alt-box";
      });
    }
  },
  data() {
    return {
      isDragDrop: false
    };
  },
  mounted() {
    this.$children.forEach(c => {
      c.$on("pinned", v => {
        this.$emit("pinned", v);
      });
    });
  },
  methods: {
    getStyle() {
      if (this.isDragDrop) {
        return { "min-height": "100px" };
      } else {
        return { "min-height": "0px" };
      }
    },
    isPinned() {
      //no children
      if (this.box.length == 0) return true;
      return this.box.every(b => {
        return b.isPinned;
      });
    },
    startSort() {
      this.isDragDrop = true;
      this.box.forEach(b => b.unpin());
      $(this.$el)
        .sortable({
          placeholder: "sort-highlight",
          connectWith: ".connectedSortable",
          handle: ".box-header, .nav-tabs",
          forcePlaceholderSize: true,
          zIndex: 999999
        })
        .on("sortstop", (event, ui) => {
          this.$emit("sortstop");
        });
    },
    endSort() {
      this.isDragDrop = false;
      this.box.forEach(b => b.pin());
      $(this.$el).sortable("destroy");
    }
  }
};
</script>
