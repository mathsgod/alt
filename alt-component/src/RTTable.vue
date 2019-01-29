<template>
    <table class="table table-hover table-condensed">
        <thead is="rt-head" 
            v-on:toggle-child="toggleChild" 
            v-on:toggle-visible="toggleVisible" 
            v-on:sort="sortByColumn" 
            v-on:search="search" 
            v-bind:hide-index="hideIndex"><slot></slot></thead>
        <tbody ref="body" is="rt-body" 
            v-bind:columns="columns" 
            v-bind:data="data" 
            v-on:update-data="updateData" 
            v-on:data-deleted="refresh"></tbody>
    </table>
</template>
<script>
export default {
  name: "rt-table",
  props: {
    dataUrl: {
      type: String
    },
    cellUrl: {
      type: String
    },
    responsive: {
      type: Boolean,
      default: false
    },
    sortField: String,
    sortDir: String,
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
  watch: {
    page: function() {
      console.log("page changed:" + this.page);
    },
    responsive() {
      this.resize();
    }
  },
  data: function() {
    var storage = $.localStorage.get(this.dataUrl);

    var data = {
      draw: 0,
      page: 1,
      data: [],
      columns: [],
      page_size: this.pageSize,
      total: 0,
      sort: this.sortField,
      sort_dir: this.sortDir,
      searchData: {},
      hideIndex: -1
    };

    if (storage) {
      if (storage.page) {
        data.page = storage.page;
      }

      if (storage.order.column) {
        data.sort = storage.order.column;
      }
      if (storage.order.dir) {
        data.sort_dir = storage.order.dir;
      }
    }

    return data;
  },
  mounted: function() {
    //get the columns
    var cs = this.$slots.default.filter(function(o) {
      if (o.componentOptions == undefined) return;
      return o.componentOptions.tag == "rt-column";
    });

    this.columns = cs.map(function(c) {
      return c.componentInstance;
    });

    this.columns.forEach((c, i) => {
      c.index = i;
    });

    this.refresh();

    this.columns.forEach(o => {
      if (o.field == this.sort) {
        o.dir = this.sort_dir;
      } else {
        o.dir = null;
      }
    });

    window.addEventListener("resize", this.resize);
  },
  computed: {},
  methods: {
    toggleVisible(data) {
      data.RT2 = 1;
      data.uri = this.dataUrl;
      this.$http.post("UI/save", data).then(resp => {
        console.log(resp);
      });
    },
    hasFixedColumn() {
      return this.columns.some(c => {
        return c.fixed;
      });
    },
    toggleChild(value) {
      this.$refs.body.toggleChild(value);
    },
    updateData(key, field, value) {
      if (!this.cellUrl) {
        console.log("cell-url not found");
        return;
      }
      this.$http
        .post(this.cellUrl, {
          _pk: key,
          name: field,
          value: value
        })
        .then(resp => {
          console.log("done");
        });
    },
    search(data) {
      this.searchData = data;
      this.page = 1;
      this.refresh();
    },
    sortByColumn: function(a) {
      this.sortBy(a.field, a.dir);
    },
    getPage() {
      return this.page;
    },
    getTo() {
      var i = this.page_size * this.page;

      return Math.min(i, this.total);
    },
    getFrom() {
      return this.page_size * (this.page - 1) + 1;
    },
    getPageCount() {
      return Math.ceil(this.total / this.page_size);
    },
    getTotal: function() {
      return this.total;
    },
    setPageSize: function(p) {
      this.page_size = p;
      this.page = 1;
      this.refresh();
    },
    doSomething: function() {
      console.log("do something");
    },
    firstPage: function() {
      this.page = 1;
      this.refresh();
    },
    gotoPage: function(page) {
      this.page = parseInt(page);
      this.refresh();
    },
    prevPage: function() {
      this.page--;
      this.refresh();
    },
    nextPage: function() {
      this.page++;
      this.refresh();
    },
    lastPage: function() {
      this.page = this.getPageCount();
      this.refresh();
    },
    sortBy: function(name, dir) {
      this.sort = name;
      if (dir == undefined) {
        this.sort_dir = "asc";
      } else {
        this.sort_dir = dir;
      }

      this.refresh();
    },
    exportFile(type) {
      this.draw++;
      this.$http
        .get(this.dataUrl, {
          params: {
            draw: this.draw,
            page: this.page,
            column: this.columns.map(function(s) {
              return s.field;
            }),
            order: [
              {
                column: this.sort,
                dir: this.sort_dir
              }
            ],
            search: this.searchData,
            type: type
          },
          responseType: "arraybuffer"
        })
        .then(function(response) {
          console.log(response);
          var headers = response.headers;
          var blob = new Blob([response.data], {
            type: headers["content-type"]
          });
          var link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          if (type == "xlsx") {
            link.download = "export.xlsx";
          } else if (type == "csv") {
            link.download = "export.csv";
          }

          link.click();
        });
    },
    reset() {
      this.page = 1;
      this.page_size = 25;

      this.columns.forEach(o => {
        o.dir = null;
      });

      this.searchData = {};

      this.sort = null;
      this.sort_dir = null;
      this.refresh();
    },
    refresh: function() {
      var storage = $.localStorage.get(this.dataUrl) || {};
      storage.page = this.page;
      storage.order = {
        column: this.sort,
        dir: this.sort_dir
      };
      $.localStorage.set(this.dataUrl, storage);

      this.$emit("loading");

      this.draw++;
      this.$http
        .get(this.dataUrl, {
          params: {
            dataUrl: this.dataUrl,
            draw: this.draw,
            page: this.page,
            length: this.page_size,
            column: this.columns.map(function(s) {
              return s.field;
            }),
            order: [
              {
                column: this.sort,
                dir: this.sort_dir
              }
            ],
            search: this.searchData
          }
        })
        .then(function(r) {
          if (r.data.draw < this.draw) {
            return;
          }
          this.data = r.data.data;
          this.total = r.data.total;
          this.$emit("loaded");
          this.$emit("refreshed");
          this.resize();
        });
    },
    resize() {
      //show all column
      this.columns.forEach(c => {
        c.hide = false;
      });

      this.$nextTick(function() {
        this.$emit("resized");
      });

      if (!this.responsive) return;

      this.$nextTick(function() {
        //get parent width
        var parentWidth = this.$el.parentElement.offsetWidth;
        //console.log("parent width", parentWidth);
        var total = 0; //show
        var hide_index = -1;
        this.columns.forEach((c, i) => {
          if (hide_index >= 0) return;
          if (total + 29 > parentWidth) {
            hide_index = i;
            return;
          }

          var w = c.$el.offsetWidth;
          total += w;
          if (total > parentWidth) {
            hide_index = i;
          }
        });
        if (hide_index >= 0) {
          this.columns.forEach((c, i) => {
            if (i >= hide_index) {
              c.hide = true;
            }
          });
        }

        this.$emit("resized");
      });
    }
  }
};
</script>
