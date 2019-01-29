<style>
table.rt {
  margin-bottom: 0px;
}

table.rt .ui-resizable-e {
  right: 0px;
}

table.rt tbody input {
  padding-left: 1px;
  padding-right: 1px;
  height: 22px;
}

table.rt .unselectable {
  user-select: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
}

table.rt td,
table.rt th {
  white-space: nowrap;
  padding: 1px 2px 1px 2px !important;
}

table.rt thead .date.input-group {
  width: 1px;
  float: left;
}

table.rt thead select {
  padding: 0px 1px;
  height: 25px;
}

table.rt thead input.search {
  padding-left: 1px;
  padding-right: 1px;
  height: 25px !important;
}

table.rt thead input.date {
  width: 80px;
  float: left;
  padding-left: 1px;
  padding-right: 1px;
  height: 25px !important;
}

table.rt thead .search-clear-btn {
  height: 25px !important;
  padding: 5px 5px !important;
}

table.rt thead .select2-selection {
  padding-left: 1px;
  padding-right: 1px;
  height: 25px;
}

table.rt thead .select2-selection__rendered {
  padding-left: 2px !important;
  margin-top: -9px !important;
}

table.rt .select2-selection__arrow {
  top: -3px !important;
}

table.rt th.sortable {
  cursor: pointer;
  background: url(./assets/images/sort_both.png) no-repeat center right;
}

table.rt th.sorting_desc {
  background: url(./assets/images/sort_desc.png) no-repeat center right;
}

table.rt th.sorting_asc {
  background: url(./assets/images/sort_asc.png) no-repeat center right;
}

table.rt tbody tr.child {
  white-space: normal;
  word-break: break-all;
}

table.rt tbody tr.child ul {
  display: inline-block;
  list-style-type: none;
  margin: 0;
  padding: 0;
}

table.rt tbody > tr.selected,
table.dataTable tbody > tr > .selected {
  background-color: #08c;
}
table.rt tbody tr.selected,
table.dataTable tbody th.selected,
table.dataTable tbody td.selected {
  color: white;
}
</style>

<template>
    <alt-box ref="box">
        <alt-box-body class="no-padding" :class="{'table-responsive':!tableResponsive}">
            <div style="position: relative; clear: both;">

                <div :style="tableContainerStyle">
                    <rt-table class="rt table-bordered" 
                        :responsive="tableResponsive" 
                        :sort-field="sortField" 
                        :sort-dir="sortDir" 
                        :cell-url="cellUrl"
                        :selectable="selectable"
                        :page-number="page" 
                        :page-size="page_size" v-on:resized="resized" :data-url="source" ref="table" v-on:refreshed="refreshed"
                        v-on:loading="$refs.box.showLoading()" v-on:loaded="$refs.box.hideLoading()">
                        <slot></slot>
                    </rt-table>
                </div>

                <div style="position:absolute; top:0; left:0; overflow:hidden;" v-if="hasFixedColumn">

                    <table class="table table-hover table-condensed rt table-bordered" style="background-color: white">
                        <thead>
                            <tr>
                                <th :data-width="c.getWidth()" :style="{width:c.getWidth()+'px'}" class="unselectable sorting_desc" v-for="(c,index) in fixedColumns()" :key="index">{{c.field}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(d,index) in getData()" :key="index">
                                <td :style="{width:c.getWidth()+'px', height:c.getHeight()+'px'}" v-for="(c,key) in fixedColumns()" :key="key">
                                    <span v-html="c.getDataValue(d)" />
                                    <span>{{c.$el.offsetWidth}}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </alt-box-body>

        <alt-box-footer>
            <div is="rt-pagination" :page="page" :page-count="page_count" v-on:first-page="$refs.table.firstPage()" v-on:last-page="$refs.table.lastPage()"
                v-on:prev-page="$refs.table.prevPage()" v-on:next-page="$refs.table.nextPage()" v-on:change-page="$refs.table.gotoPage($event)"></div>

            <div class="pull-left btn-group">
                <button @click="$refs.table.refresh()" class="btn btn-default btn-sm" type="button" title="重新載入" data-toggle="tooltip">
                    <i class="fa fa-sync-alt"></i>
                </button>
            </div>

            <div class="pull-left dropup">
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    <span class="icon glyphicon glyphicon-th-list"></span>
                </button>
                <ul class="dropdown-menu" ref="column_menu">
                    <!-- li v-for="(col,key) in columns" v-if="col.title" :key="key">
                        <a href="#" class="small" data-value="option1" tabIndex="-1" @click.prevent="col.toggleVisible()">
                            <input type="checkbox" v-model="col.isVisible" />&nbsp;{{col.title}}</a>
                    </li -->
                    <li v-for="(col,key) in columnSequence" v-if="getColumn(col).title" :key="key" :data-field="col">
                        <a href="#" class="small" data-value="option1" tabIndex="-1" @click.prevent="getColumn(col).toggleVisible()">
                            <input type="checkbox" v-model="getColumn(col).isVisible" />&nbsp;{{getColumn(col).title}}</a>
                    </li>
                </ul>
            </div>

            <div class="pull-left">
                <select class="form-control input-sm" style="width:70px" @change="changePageSize()" v-model="page_size">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </div>

            <div class="pull-left btn-group">
                <button @click="toggleResponsive" :class="{active:tableResponsive}" class="btn btn-default btn-sm" type="button" title="Responsive" data-toggle="tooltip">
                    <i class="fa fa-tv"></i>
                </button>

                <button @click="resetLocaStorage" class="btn btn-default btn-sm" type="button" title="clear cache" data-toggle="tooltip">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>

            <div class="pull-left dropdown" v-if="showExport()">
                <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    Export
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li v-if="exportXlsx">
                        <a href="#" @click.prevent="exportFile('xlsx')">XLSX</a>
                    </li>
                    <li v-if="exportCsv">
                        <a href="#" @click.prevent="exportFile('csv')">CSV</a>
                    </li>
                </ul>
            </div>



            <div class="pull-left btn-group" v-if="$slots.buttons">
                <slot name="buttons"></slot>
            </div>

            <rt-info class="pull-right" :total="total" :from="from" :to="to"></rt-info>
        </alt-box-footer>
    </alt-box>
</template>

<script>
export default {
  name: "alt-rt",
  props: {
    responsive: {
      type: Boolean,
      default: false
    },
    exportXlsx: Boolean,
    exportCsv: Boolean,
    cellUrl: {
      type: String
    },
    source: {
      type: String,
      require: true
    },
    sortDir: String,
    sortField: String,
    pageSize: {
      type: Number,
      default: 25
    },
    pageNumber: {
      type: Number,
      default: 1
    },
    selectable: Boolean
  },
  created: function() {},
  mounted: function() {
    this.total = this.$refs.table.total;
    this.from = 1;
    this.to = 1;
    this.hasFixedColumn = this.$refs.table.hasFixedColumn();
    this.columns = this.$refs.table.columns;

    this.$refs.table.$on("resize", function() {
      this.$forceUpdate();
    });
    //window.addEventListener('resize', this.resize);

    this.columns.forEach(o => {
      this.columnSequence.push(o.field);
    });

    //console.log(this.columnSequence);
    new Sortable(this.$refs.column_menu, {
      /*    group: 'widget',
                /*onAdd: this.onAdd,*/
      onRemove: this.onRemoveColumnSequence,
      /*onEnd: this.onEnd,
                onStart: this.onStart,*/
      onUpdate: this.onUpdateColumnSequence
    });
  },
  computed: {},
  data() {
    var storage = $.localStorage.get(this.source) || {};

    var data = {
      columns: [],
      hasFixedColumn: false,
      tableResponsive: this.responsive,
      from: 0,
      to: 0,
      total: 0,
      page_size: this.pageSize,
      page_count: 0,
      page: this.pageNumber,
      columnSequence: []
    };

    if (typeof storage.responsive !== "undefined") {
      data.tableResponsive = storage.responsive;
    }

    if (typeof storage.page_size !== "undefined") {
      data.page_size = parseInt(storage.page_size);
    }

    return data;
  },
  methods: {
    onRemoveColumnSequence: function(evt) {
      this.columnSequence.splice(evt.oldIndex, 1);
    },
    onUpdateColumnSequence(evt) {
      var field = evt.item.dataset.field;
      this.columnSequence.splice(evt.oldIndex, 1);
      this.columnSequence.splice(evt.nexIndex, 0, field);
    },
    getColumn(field) {
      return this.columns.find(c => {
        return c.field == field;
      });
    },
    resetLocaStorage() {
      $.localStorage.remove(this.source);
      this.$refs.table.reset();
    },
    showExport() {
      return this.exportXlsx || this.exportCsv;
    },
    exportFile(type) {
      return this.$refs.table.exportFile(type);
    },
    tableContainerStyle() {
      var style = {};
      if (this.responsive) {
        style.overflow = "auto";
      }
      return style;
    },
    resized() {
      this.$forceUpdate();
    },
    resize() {
      this.$forceUpdate();
    },
    fixedColumns() {
      if (!this.$refs.table.hasFixedColumn()) {
        return [];
      }

      var cs = this.$refs.table.columns.filter(function(o) {
        return o.fixed;
      });
      return cs;
    },
    getData() {
      return this.$refs.table.data;
    },
    toggleResponsive() {
      this.tableResponsive = !this.tableResponsive;

      //save
      var storage = $.localStorage.get(this.source) || {};
      storage.responsive = this.tableResponsive;
      $.localStorage.set(this.source, storage);
    },
    refreshed: function() {
      var table = this.$refs.table;
      this.total = table.getTotal();
      this.from = table.getFrom();
      this.to = table.getTo();
      this.page = table.getPage();
      this.page_count = table.getPageCount();
      this.$forceUpdate();
    },
    changePageSize() {
      this.$refs.table.setPageSize(this.page_size);

      //save
      var storage = $.localStorage.get(this.source) || {};
      storage.page_size = this.page_size;
      $.localStorage.set(this.source, storage);
    }
  }
};
</script>
