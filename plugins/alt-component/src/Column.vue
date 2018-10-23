<template>
    <th v-on:click="click" class="unselectable" v-bind:style="getStyle()"
    v-bind:class="{
        sortable:orderable,
        sorting_desc:(orderDir=='desc'),
        sorting_asc:(orderDir=='asc')
    }">
        <input v-if="type=='checkbox'" type="checkbox" is="icheck" v-on:change="checkboxChange"/>
        <div v-else v-text="title"></div>
    </th>
</template>
<script>
export default {
  name: "alt-column",
  props: {
    name: String,
    data: String,
    title: String,
    orderable: Boolean,
    orderDir: String,
    isVisible: {
      type: Boolean,
      default: true
    },
    width: String,
    type: String
  },
  data() {
    return {
      hide: false,
      local: {
        orderDir: this.orderDir
      }
    };
  },
  methods: {
    checkboxChange(e) {
      console.log(e.target.checked);
    },
    click() {
      if (!this.orderable) return;
      if (this.local.orderDir == "desc") {
        this.order("asc");
      } else {
        this.order("desc");
      }
      this.draw();
    },
    search(search) {
      this.local.search = search;
      return this;
    },
    order(dir) {
      if (!this.orderable) return this;
      this.local.orderDir = dir;
      this.$emit("order", [this.name, dir]);
      return this;
    },
    draw() {
      this.$emit("draw");
      return this;
    },
    getStyle() {
      let style = {};
      if (this.width) {
        style["min-width"] = this.width;
        style.width = this.width;
      }
      return style;
    }
  }
};
</script>
