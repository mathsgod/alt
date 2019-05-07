<template>
  <tbody>
    <template v-for="(d,index) in data">
      <tr
        v-on:click="onClickRow(d)"
        v-bind:class="getRowClass(d)"
        :style="getStyle(d)"
        :key="'row'+index"
      >
        <td v-if="hasHideColumn">
          <button
            class="btn btn-default btn-xs"
            v-on:click="toggleRowChild(index)"
            v-on:mouseenter="mouseEnterRow(index)"
            v-on:mouseleave="mouseLeaveRow(index)"
          >
            <i v-if="!showIndex[index]" class="fa fa-fw fa-chevron-up"></i>
            <i v-if="showIndex[index]" class="fa fa-fw fa-chevron-down"></i>
          </button>
        </td>
        <td
          ref="cell"
          is="rt2-cell"
          v-bind:index="index"
          :data="d"
          v-bind:column="column"
          v-for="(column,key) in visibleColumns"
          :key="'col'+key"
          :storage="storage"
          v-on:data-deleted="$emit('data-deleted')"
          v-on:toggle-sub-row="toggleSubRow(index,$event)"
          v-on:click="onClickCell(column,index)"
          v-bind:edit-mode="isEditMode(column,index)"
          v-on:update-data="updateData(index,d,column,$event)"
        ></td>
      </tr>
      <tr class="child" v-show="showChild(index)" :key="'child'+index">
        <td v-bind:colspan="showColumnCount">
          <ul>
            <li v-for="(column,key) in hideColumns" :key="'hide_col'+key">
              <b v-html="column.title"></b>&nbsp;&nbsp;
              <span v-if="column.cell(d).type=='html'" v-html="column.getContent(d)"></span>
              <span v-if="column.cell(d).type=='text'" v-text="column.getContent(d)"></span>
            </li>
          </ul>
        </td>
      </tr>

      <tr v-show="subRow[index]" :key="'subrow'+index">
        <td v-html="subRowContent[index]" :colspan="visibleColumns.length"></td>
      </tr>
    </template>
  </tbody>
</template>

<script>
export default {
  name: "rt2-tbody",
  props: {
    data: Array,
    columns: Array,
    selectable: Boolean,
    storage: Object
  },
  data() {
    return {
      subRow: [],
      subRowContent: [],
      subRowColumn: null,
      hoverChild: [],
      showIndex: [],
      editMode: false,
      editColumn: null,
      editIndex: null,
      selectedData: []
    };
  },
  computed: {
    hideColumns() {
      return this.columns.filter(column => {
        return column.hide;
      });
    },
    visibleColumns() {
      return this.columns.filter(column => {
        return column.isDisplay();
      });
    },
    hasHideColumn() {
      return this.columns.some(o => o.hide);
    },
    showColumnCount() {
      return (
        this.columns.filter(c => {
          return !c.hide;
        }).length + 1
      );
    }
  },
  mounted() {
    this.$parent.$on("reset-local-storage", () => {
      this.$emit("reset-local-storage");
    });
  },
  methods: {
    checkAll(column, value) {
      var cells = this.$refs.cell.filter(cell => {
        return cell.column == column;
      });
      cells.forEach(cell => {
        cell.setCheckbox(value);
      });
    },
    toggleSubRow(index, content) {
      if (this.subRow[index]) {
        this.subRow[index] = false;
      } else {
        this.subRow[index] = true;
        Vue.http
          .get(content.url, {
            params: content.params
          })
          .then(resp => {
            this.subRowContent[index] = resp.body;
            this.$forceUpdate();
          });
      }
      this.$forceUpdate();
    },
    getStyle(d) {
      if (d.__row__.style) {
        return d.__row__.style;
      }
    },
    getRowClass(d) {
      var c = [];
      if (this.isSelected(d)) {
        c.push("selected");
      }
      if (d.__row__) {
        c = c.concat(d.__row__.class);
      }

      return c;
    },
    isSelected(d) {
      return this.selectedData.indexOf(d) >= 0;
    },
    onClickRow(d) {
      if (!this.selectable) return;
      if (this.isSelected(d)) {
        var index = this.selectedData.indexOf(d);
        this.selectedData.splice(index, 1);
      } else {
        this.selectedData.push(d);
      }
    },
    isEditMode: function(column, index) {
      if (!this.editMode) return false;
      if (this.editColumn == column && this.editIndex == index) {
        return true;
      }
      return false;
    },
    onClickCell: function(column, index) {
      if (!column.editable) return false;
      this.editMode = true;
      this.editColumn = column;
      this.editIndex = index;
    },
    showAllChild() {
      this.data.forEach((o, i) => {
        this.showIndex[i] = true;
      });
      this.$forceUpdate();
    },
    hideAllChild() {
      this.data.forEach((o, i) => {
        this.showIndex[i] = false;
      });
      this.$forceUpdate();
    },
    toggleRowChild(index) {
      this.showIndex[index] = !this.showIndex[index];
      this.$forceUpdate();
    },
    showChild(index) {
      if (this.hoverChild[index]) return true;
      return this.showIndex[index];
    },
    mouseLeaveRow(index) {
      this.hoverChild[index] = false;
      this.$forceUpdate();
    },
    mouseEnterRow(index) {
      this.hoverChild[index] = true;
      this.$forceUpdate();
    },
    updateData(index, r, column, value) {
      if (!column.editable) return;
      this.editMode = false;

      if (column.editType == "text") {
        if (column.getValue(r) != value) {
          r[column.data] = value;
          this.$emit("update-data", r._key, column.data, value);
        }
        return;
      }

      if (column.editType == "select") {
        if (r[column.data].value != value) {
          r[column.data].value = value;
          r[column.data].content = column.editData[value].label;
          this.$emit("update-data", r._key, column.data, value);
        }
      }
      /* else {
                    if (r[column.field] != value) {
                        r[column.field] = value;
                        this.$emit("update-data", r._key, column.field, value);
                    }
                }*/
    }
  }
};
</script>
