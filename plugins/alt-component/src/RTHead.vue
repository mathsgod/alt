<template>
<thead>
    <tr>
        <th v-if="hasHideColumn" class="width:29px;max-width:29px">
            <button class="btn btn-default btn-xs" v-on:click="toggleChild">
                <i v-if="!showChild" class="fa fa-fw fa-chevron-up"></i>
                <i v-if="showChild" class="fa fa-fw fa-chevron-down"></i>
            </button>
        </th>
        <slot></slot>
    </tr>
    <tr v-if="hasSearchColumn">
        <td v-if="hasHideColumn">
        </td>
        <td v-for="(column,key) in columns" v-bind:key="key" v-if="column.display()">
            <template v-if="column.searchable">
                <template v-if="column.searchType=='text'">
                    <div>
                        <input v-model="search[column.field]" class="form-control input-sm search" type="text" @keyup.enter="doSearch()" />
                    </div>
                </template>
                <template v-if="column.searchType=='date'">
                    <div class="date input-group input-group-sm">
                        <input class="form-control date" placeholder="from" v-bind:data-field="column.field" data-search="from"/>
                        <span class="input-group-btn">
                            <button type="button" class="search-clear-btn btn btn-default" v-on:click="clearSearchDate(column,'from')">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button>
                        </span>
                    </div>

                    <div class="date input-group input-group-sm">
                        <input class="form-control date" placeholder="to" v-bind:data-field="column.field" data-search="to"/>
                        <span class="input-group-btn">
                            <button type="button" class="search-clear-btn btn btn-default" v-on:click="clearSearchDate(column,'to')">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button>
                        </span>
                    </div>
                </template>
                <template v-if="column.searchType=='select'">
                    <select class="form-control" v-model="search[column.field]" v-on:change="doSearch">
                        <option></option>
                        <option v-for="(opt,i) in column.searchOption" v-text="opt.label" v-bind:value="opt.value" :key="i"></option>
                    </select>
                </template>
                <template v-if="column.searchType=='multiselect'">
                    <select multiple class="form-control" v-on:change="doSearch" search-type="multiselect" v-bind:data-field="column.field">
                        <option v-for="(opt,i) in column.searchOption" v-text="opt.label" v-bind:value="opt.value" :key="i"></option>
                    </select>
                </template>
                <template v-if="column.searchType=='select2'">
                    <select class="form-control" v-model="search[column.field]" v-bind:data-field="column.field" search-type="select2">
                        <option></option>
                        <option v-for="(opt,i) in column.searchOption" v-text="opt.label" v-bind:value="opt.value" :key="i"></option>
                    </select>
                </template>
                <template v-if="column.searchType=='range'">
                    <div class="input-group input-group-sm">
                        <input class="search form-control" v-model="search[column.field].from" placeholder="from" v-bind:data-field="column.field" data-search="from" @keyup.enter="doSearch()"/>
                        <span class="input-group-btn">
                            <button type="button" class="search-clear-btn btn btn-default" v-on:click="clearSearchDate(column,'from')">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button>
                        </span>
                    </div>
                    <div class="input-group input-group-sm">
                        <input class="search form-control" v-model="search[column.field].to" placeholder="to" v-bind:data-field="column.field" data-search="to" @keyup.enter="doSearch()"/>
                        <span class="input-group-btn">
                            <button type="button" class="search-clear-btn btn btn-default" v-on:click="clearSearchDate(column,'to')">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button>
                        </span>
                    </div>

                </template>                    
            </template>
        </td>
    </tr>
</thead>
</template>
<script>
export default {
  name: "rt-head",
  props: {
    hideIndex: Number
  },
  data() {
    return {
      showChild: false,
      search: {},
      columns: []
    };
  },
  mounted: function() {
    this.columns = this.$slots.default
      .filter(o => {
        if (o.componentOptions == undefined) return false;
        return o.componentOptions.tag == "rt-column";
      })
      .map(o => {
        return o.componentInstance;
      });

    this.columns.forEach(column => {
      column.$on("toggle-visible", function(a) {
        this.$parent.$emit("toggle-visible", {
          name: this.field,
          visible: a
        });
      });
    });

    this.$on("sort", a => {
      this.columns.forEach(column => {
        if (column.field != a.field) {
          column.dir = "";
        }
      });
    });

    this.columns.forEach(c => {
      if (c.searchType == "multiselect") {
        this.search[c.field] = [];
      } else if (c.searchType == "date") {
        this.search[c.field] = {
          from: "",
          to: ""
        };
      } else if (c.searchType == "range") {
        this.search[c.field] = {
          from: "",
          to: ""
        };
      }
    });

    this.$nextTick(function() {
      $(() => {
        $(".rt [search-type='multiselect']").each((i, o) => {
          $(o).multiselect({
            buttonClass: "btn btn-default btn-xs",
            enableFiltering: true
          });
          $(o).on("change", () => {
            var field = $(o).attr("data-field");
            this.search[field] = $(o).val();
            this.doSearch();
          });
        });

        $(".rt [search-type='select2']").each((i, o) => {
          $(o).select2();
          $(o).on("change", () => {
            var field = $(o).attr("data-field");
            this.search[field] = $(o).val();
            this.doSearch();
          });
        });
        $(".rt input.date").each((i, o) => {
          $(o).daterangepicker({
            singleDatePicker: true,
            opens: "center",
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            locale: {
              format: "YYYY-MM-DD"
            }
          });

          $(o).on("apply.daterangepicker", (ev, picker) => {
            var o = picker.element.get(0);
            $(o).val(picker.startDate.format("YYYY-MM-DD"));

            var field = $(o).attr("data-field");
            if ($(o).attr("data-search") == "from") {
              this.search[field].from = picker.startDate.format("YYYY-MM-DD");
            } else {
              this.search[field].to = picker.startDate.format("YYYY-MM-DD");
            }

            this.$emit("search", this.search);
          });
        });
      });
    });
  },
  computed: {
    hasSearchColumn() {
      return this.columns.some(function(c) {
        return c.searchable;
      });
    },
    hasHideColumn() {
      return this.columns.some(function(c) {
        return c.hide;
      });
    }
  },
  methods: {
    toggleChild() {
      this.showChild = !this.showChild;
      this.$emit("toggle-child", this.showChild);
    },
    clearSearchDate(column, v) {
      $("[data-field='" + column.field + "'][data-search='" + v + "']").val("");

      this.search[column.field][v] = "";
      this.doSearch();
    },
    doSearch: function() {
      this.$emit("search", this.search);
    }
  }
};
</script>
