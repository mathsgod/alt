<template>
<tbody>
    <template v-for="(r,index) in data">
        <tr v-bind:style="getStyle(r)" v-on:click="rowClicked(r)" v-bind:class="{active:r._selected}">
            <td v-if="hasHideColumn">
                <button class="btn btn-default btn-xs" v-on:click="toggleRowChild(index)" v-on:mouseenter="mouseEnter(index)" v-on:mouseleave="mouseLeave(index)">
                    <i v-if="!showIndex[index]" class="fa fa-fw fa-chevron-up"></i>
                    <i v-if="showIndex[index]" class="fa fa-fw fa-chevron-down"></i>
                </button>
            </td>
            <td v-for="column in columns" v-on:click="cellClicked(column,index,r)" v-if="column.display()"
            v-bind:style="getCellStyle(column,index,r)" >
                <template v-if="isEditMode(column,index)" >
                    <template v-if="column.editType=='text'">
                        <input type="text" class="form-control input-sm" v-bind:value="getValue(r[column.field])" v-on:blur="updateData(index,r,column,$event.target.value)"/>
                    </template>
                    <template v-else-if="column.editType=='select'">
                        <select class="formControl" v-on:blur="updateData(index,r,column,$event.target.value)">
                            <option v-for="opt in column.editData" v-bind:value="opt.value" v-text="opt.label"
                            v-bind:selected="opt.value==r[column.field].value"></option>
                        </select>
                    </template>
                    <template v-else-if="column.editType=='date'">
                        <input type="text" class="form-control input-sm" v-bind:value="getValue(r[column.field])" v-on:blur="updateData(index,r,column,$event.target.value)"/>
                    </template>
                </template>
                <template v-else>
                    <input type="checkbox" v-if="column.cell(r).type=='deletes'"/>
                    <button class="btn btn-xs btn-danger" v-else-if="column.cell(r).type=='delete'" v-on:click="deleteRow(r[column.field].uri)"><i class="fa fa-fw fa-times"></i></button>
                    <button class="btn btn-xs btn-default" v-else-if="column.cell(r).type=='sub-row'" v-on:click="toggleSubRow(index,r)">
                        <i v-if="subRow[index]" class="fa fa-fw fa-minus"></i>
                        <i v-if="!subRow[index]" class="fa fa-fw fa-plus"></i>
                    </button>
                    <a v-else-if="column.cell(r).type=='link'" v-bind:href="column.cell(r).href" v-html="column.cell(r).content"></a>
                    <div v-else v-html="column.cell(r).content" v-bind:style="column.cell(r).style">
                    </div>
                </template>
            </td>
        </tr>
        <tr class="child" v-show="showChild(index)">
            <td v-bind:colspan="showColumnCount">
                <ul>
                    <li v-for="column in columns" v-if="column.hide">
                        <b v-html="column.title"></b>&nbsp;&nbsp;<span v-html="getValue(r[column.field])" />
                    </li>
                </ul>
            </td>
        </tr>    
        <tr v-if="subRow[index]">
            <td v-bind:colspan="showColumnCount" v-html="subRow[index]">
            </td>
        </tr>
    </template>
</tbody>
</template>
<script>
export default {
  name: "rt-body",
  props: {
    columns: Array,
    data: Array,
    showResponsive: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      editMode: false,
      editIndex: null,
      editField: null,
      showIndex: [],
      subRow: [],
      hoverChild: []
    };
  },
  computed: {
    hasHideColumn() {
      return this.columns.some(function(c) {
        return c.hide;
      });
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
    rowClicked(data) {
      if (!this.$parent.selectable) return;
      data._selected = !data._selected;
      this.$forceUpdate();
    },
    getCellStyle(col, index, data) {
      var style = {};
      if (col.align) {
        style["text-align"] = col.align;
      }
      return style;
    },
    mouseLeave(index) {
      this.hoverChild[index] = false;
      this.$forceUpdate();
      //                console.log("mouseleave",index);
    },
    mouseEnter(index) {
      this.hoverChild[index] = true;
      this.$forceUpdate();
      //                console.log("mouseenter",index);
    },
    showChild(index) {
      if (this.hoverChild[index]) return true;
      return this.showIndex[index];
    },
    toggleSubRow(index, r) {
      if (this.subRow[index]) {
        this.subRow[index] = null;
        this.$forceUpdate();
        return;
      }
      this.$http.get(r["[subhtml]"].url).then(resp => {
        this.subRow[index] = resp.body;
        this.$forceUpdate();
        this.$nextTick(function() {
          var div = $(this.subRow[index]);
          div.each(function(i, o) {
            if (o.tagName == "SCRIPT") {
              eval(o.innerHTML);
            }
          });
        });
      });
    },
    deleteRow(uri) {
      if (confirm("Are your sure?")) {
        this.$http.get(uri).then(resp => {
          this.$emit("data-deleted");
        });
      }
    },
    toggleChild(value) {
      this.data.forEach((r, i) => {
        this.showIndex[i] = value;
      });
      this.$forceUpdate();
    },
    toggleRowChild(index) {
      if (this.showIndex[index]) {
        this.showIndex[index] = false;
      } else {
        this.showIndex[index] = true;
      }
      this.$forceUpdate();
    },
    isShowChild(index) {
      return this.showIndex[index];
    },
    updateData: function(index, r, column, value) {
      this.editMode = false;

      if (column.editType == "select") {
        if (r[column.field].value != value) {
          this.data[index][column.field].value = value;
          this.data[index][column.field].content = column.editData[value].label;
          this.$emit("update-data", r._key, column.field, value);
        }
      } else {
        if (r[column.field] != value) {
          r[column.field] = value;
          this.$emit("update-data", r._key, column.field, value);
        }
      }
    },
    isEditMode: function(column, index) {
      if (!this.editMode) return false;
      if (this.editField == column.field && this.editIndex == index) {
        return true;
      }
      return false;
    },
    cellClicked: function(column, index, r) {
      if (!column.editable) return false;

      this.editMode = true;
      this.editField = column.field;
      this.editIndex = index;
    },
    getValue: function(v) {
      if (typeof v === "string") {
        return v;
      }
      return v.content;
    },
    getContent: function(v) {
      if (typeof v === "string") {
        return v;
      }
      return v.content;
    },
    getStyle: function(r) {
      if (r._row == undefined) return {};
      return r._row.style;
    }
  },
  mounted: function() {}
};
</script>
