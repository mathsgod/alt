<template>
  <tbody>
    <template v-for="(d,index) in data">
      <tr
        v-on:click="onClickRow(d)"
        v-bind:class="getRowClass(d)"
        :style="getStyle(d)"
        :key="index"
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
          v-for="(column,key) in visibleColumns"
          :key="key"
          v-on:click="onClickCell(column,index)"
          v-bind:style="column.cell(d).style"
        >
          <template v-if="isEditMode(column,index)">
            <template v-if="column.editType=='text'">
              <input
                type="text"
                class="form-control input-sm"
                v-bind:value="column.getValue(d)"
                v-on:blur="updateData(index,d,column,$event.target.value)"
              >
            </template>
            <template v-else-if="column.editType=='select'">
              <select
                class="formControl"
                v-on:blur="updateData(index,d,column,$event.target.value)"
              >
                <option
                  v-for="(opt,opt_key) in column.editData"
                  :key="opt_key"
                  v-bind:value="opt.value"
                  v-text="opt.label"
                  v-bind:selected="opt.value==column.getValue(d).value"
                ></option>
              </select>
            </template>
            <template v-else-if="column.editType=='date'">
              <input
                type="text"
                class="form-control input-sm"
                v-bind:value="column.getValue(d)"
                v-on:blur="updateData(index,d,column,$event.target.value)"
              >
            </template>
          </template>
          <template v-else>
            <div
              v-if="column.cell(d).type=='html'"
              v-html="column.getContent(d)"
              v-bind:style="column.cell(d).divStyle"
            ></div>
            <div
              v-if="column.cell(d).type=='text'"
              v-text="column.getContent(d)"
              v-bind:style="column.cell(d).divStyle"
            ></div>
            <input type="checkbox" v-if="column.type=='checkbox'" is="icheck">
            <input type="checkbox" v-if="column.type=='deletes'">
            <button
              class="btn btn-xs btn-danger"
              v-else-if="column.cell(d).type=='delete'"
              v-on:click="deleteRow(d[column.data].content)"
            >
              <i class="fa fa-fw fa-times"></i>
            </button>
            <button
              class="btn btn-xs btn-default"
              v-else-if="column.type=='sub-row'"
              v-on:click="toggleSubRow(index,r)"
            >
              <i v-if="subRow[index]" class="fa fa-fw fa-minus"></i>
              <i v-if="!subRow[index]" class="fa fa-fw fa-plus"></i>
            </button>
            <a v-else-if="column.type=='link'" v-bind:href="column.href" v-html="column.content"></a>
          </template>
        </td>
      </tr>
      <tr class="child" v-show="showChild(index)" :key="index">
        <td v-bind:colspan="showColumnCount">
          <ul>
            <li v-for="(column,key) in hideColumns" :key="key">
              <b v-html="column.title"></b>&nbsp;&nbsp;
              <span v-if="column.cell(d).type=='html'" v-html="column.getContent(d)"></span>
              <span v-if="column.cell(d).type=='text'" v-text="column.getContent(d)"></span>
            </li>
          </ul>
        </td>
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
    selectable: Boolean
  },
  data() {
    return {
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
  methods: {
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
    deleteRow(uri) {
      if (confirm("Are your sure?")) {
        this.$http.delete(uri).then(resp => {
          this.$emit("data-deleted");
        });
      }
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
    onClickCell: function(column, index, r) {
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
