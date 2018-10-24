<template>
    <th class="unselectable"
        v-if="display()"
        v-bind:class="{
            sortable:sortable,
            sorting_desc:(dir=='desc'),
            sorting_asc:(dir=='asc')
        }" v-on:click='sort' v-bind:style="style">
        <div v-bind:title="title" style="overflow:hidden" v-html="title"></div>
        <div ref="resizer"></div>
        <template v-if="type=='deletes'">
            <button class='btn btn-xs btn-danger' v-on:click="$emit('deletes')"><i class='fa fa-fw fa-times'></i></button>
        </template>
    </th>
</template>
<script>
export default {
  name: "rt-column",
  props: {
    type: String,
    field: String,
    title: String,
    sortable: Boolean,
    sortDir: String,
    searchable: Boolean,
    searchMultiple: Boolean,
    searchType: {
      type: String,
      default: "text"
    },
    searchOption: {
      type: Array
    },
    editable: Boolean,
    editType: String,
    editData: {
      type: Array
    },
    width: String,
    maxWidth: String,
    resizable: Boolean,
    fixed: Boolean,
    hidden: Boolean,
    align: String,
    wrap: Boolean
  },
  data: function() {
    return {
      isVisible: !this.hidden,
      hide: false,
      index: -1,
      hideIndex: -1,
      dir: this.sortDir
    };
  },
  mounted() {
    var that = this;
    if (this.resizable) {
      this.$nextTick(() => {
        $(() => {
          $(this.$el).resizable({
            handles: "e",
            minWidth: 28,
            resize(event, ui) {
              $(that.$refs.resizer).width(ui.size.width);
            }
          });
        });
      });
    }
  },
  computed: {
    style() {
      var style = {};
      style.width = this.width;
      style.maxWidth = this.maxWidth;

      return style;
    }
  },
  updated() {},
  methods: {
    cell(r) {
      var cell = {
        type: "text"
      };

      if (r[this.field] == null) {
        return cell;
      }

      if (typeof r[this.field] == "string") {
        cell.content = r[this.field];
      } else {
        for (var i in r[this.field]) {
          cell[i] = r[this.field][i];
        }
      }

      if (this.wrap) {
        Vue.set(cell, "style", {
          "word-wrap": "break-word",
          "white-space": "pre-wrap"
        });
      }
      return cell;
    },
    display() {
      return this.isVisible && !this.hide;
    },
    getWidth() {
      return this.$el.offsetWidth;
    },
    getHeight() {
      return this.$el.offsetHeight;
    },
    getDataValue(data) {
      var v = data[this.field];
      if (typeof v === "string") {
        return v;
      }
      return v.content;
    },
    sort() {
      if (!this.sortable) return false;
      if (this.dir == "" || this.dir == "asc") {
        this.dir = "desc";
      } else {
        this.dir = "asc";
      }

      this.$parent.$emit("sort", {
        field: this.field,
        dir: this.dir
      });
    },
    toggleVisible() {
      this.isVisible = !this.isVisible;
      this.$emit("toggle-visible", this.isVisible);
    }
  }
};
</script>
