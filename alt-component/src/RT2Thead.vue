<template>
  <thead>
    <tr>
      <td v-if="hasHideColumn">
        <button class="btn btn-default btn-xs" @click="toggleChild">
          <i v-if="!showChild" class="fa fa-fw fa-chevron-up"></i>
          <i v-if="showChild" class="fa fa-fw fa-chevron-down"></i>
        </button>
      </td>
      <th
        v-for="(column,key) in visibleColumns"
        :key="'col_'+key"
        is="rt2-column"
        v-bind="column.$data"
        @order="$emit('order',$event)"
        @draw="$emit('draw')"
        @check-all="$emit('check-all',[column,$event])"
        ref="column"
      ></th>
    </tr>

    <tr v-if="isSearchable">
      <td v-if="hasHideColumn"></td>
      <td
        is="alt-column-search"
        v-for="(column,key) in visibleColumns"
        :key="'search_'+key"
        v-bind="column.$data"
        @search="$emit('search',$event)"
      ></td>
    </tr>
  </thead>
</template>
<script>
import AltColumnSearch from "./ColumnSearch";
import Rt2Column from "./RT2Column";
export default {
  name: "rt2-thead",
  props: {
    columns: Array
  },
  components: {
    Rt2Column,
    AltColumnSearch
  },
  data() {
    return {
      showChild: false
    };
  },
  computed: {
    hasHideColumn() {
      return this.columns.some(o => o.hide);
    },
    visibleColumns() {
      return this.columns.filter(column => {
        return column.isDisplay();
      });
    },
    isSearchable() {
      return this.columns.some(o => o.searchable);
    }
  },
  methods: {
    toggleChild() {
      this.showChild = !this.showChild;
      this.$emit("toggle-child", this.showChild);
    }
  }
};
</script>

