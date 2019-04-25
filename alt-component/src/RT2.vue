<style>
table.rt > thead button.multiselect {
  height: 25px;
  padding: 0px;
  background-color: white;
}
</style>
<template>
  <div class="box no-border" is="alt-box" ref="box">
    <div class="box-body no-padding" v-if="buttons.length>0">
      <button
        v-for="(button,index) in buttons"
        :key="index"
        v-text="button.title"
        @click="onClickButton(button)"
        :class="button.class"
      ></button>
    </div>
    <div class="box-body no-padding" is="alt-box-body" :class="{'table-responsive':!responsive}">
      <table class="table table-hover table-condensed table-bordered rt" ref="table">
        <thead>
          <tr>
            <td v-if="hasHideColumn">
              <button class="btn btn-default btn-xs" v-on:click="toggleChild">
                <i v-if="!showChild" class="fa fa-fw fa-chevron-up"></i>
                <i v-if="showChild" class="fa fa-fw fa-chevron-down"></i>
              </button>
            </td>
            <th
              v-for="(column,key) in visibleColumns"
              :key="key"
              is="rt2-column"
              v-bind="column.$data"
              v-on:order="order"
              v-on:draw="draw"
              v-on:search="$emit('search',$event)"
              ref="column"
              v-on:check-all="checkAll(column,$event)"
            ></th>
          </tr>
          <tr v-if="isSearchable">
            <td v-if="hasHideColumn"></td>
            <td
              v-for="(column,key) in visibleColumns"
              :key="key"
              is="alt-column-search"
              v-bind="column.$data"
              v-on:search="search"
            ></td>
          </tr>
        </thead>
        <tbody
          is="rt2-tbody"
          :selectable="selectable"
          :data="data()"
          :columns="columns"
          ref="tbody"
          v-on:update-data="updateData"
          v-on:data-deleted="draw"
          :storage="storage"
        ></tbody>
      </table>
    </div>
    <div class="box-footer">
      <rt-pagination
        :page="page"
        :page-count="pageCount"
        v-on:change-page="gotoPage"
        v-on:first-page="firstPage"
        v-on:next-page="nextPage"
        v-on:prev-page="prevPage"
        v-on:last-page="lastPage"
      ></rt-pagination>

      <div class="pull-left btn-group">
        <button
          @click="draw"
          class="btn btn-default btn-sm"
          type="button"
          title="重新載入"
          data-toggle="tooltip"
        >
          <i class="fa fa-sync-alt"></i>
        </button>
      </div>

      <div class="pull-left">
        <select
          class="form-control input-sm"
          @change="onChangePageLength"
          v-model="local.pageLength"
        >
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
          <option value="500">500</option>
        </select>
      </div>

      <div class="pull-left dropup">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
          <span class="icon glyphicon glyphicon-th-list"></span>
        </button>
        <ul class="dropdown-menu" ref="column_menu">
          <li v-for="(column,key) in columnsHasTitle" :key="key">
            <a
              href="#"
              class="small"
              data-value="option1"
              tabindex="-1"
              @click.prevent="column.toggleVisible()"
            >
              <input type="checkbox" v-model="column.isVisible">
              &nbsp;{{column.title}}
            </a>
          </li>
        </ul>
      </div>

      <div class="pull-left btn-group">
        <button
          @click="toggleResponsive"
          :class="{active:responsive}"
          class="btn btn-default btn-sm"
          type="button"
          title="Responsive"
          data-toggle="tooltip"
        >
          <i class="fa fa-tv"></i>
        </button>

        <button
          @click="resetLocaStorage"
          class="btn btn-default btn-sm"
          type="button"
          title="clear cache"
          data-toggle="tooltip"
        >
          <i class="fa fa-times-circle"></i>
        </button>
      </div>

      <div class="pull-left dropdown" v-if="hasExport()">
        <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
          Export
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
          <li v-if="exports.indexOf('xlsx')>=0">
            <a href="#" @click.prevent="exportFile('xlsx')">XLSX</a>
          </li>
          <li v-if="exports.indexOf('csv')>=0">
            <a href="#" @click.prevent="exportFile('csv')">CSV</a>
          </li>
        </ul>
      </div>

      <div class="pull-left btn-group">
        <button
          v-for="(button,index) in buttons"
          :key="index"
          @click="onClickButton(button)"
          class="btn btn-default btn-sm"
          type="button"
          v-text="button.text"
        ></button>
      </div>

      <rt-info class="pull-right" v-bind="info"></rt-info>
    </div>
  </div>
</template>
<script>
export default {
  name: "alt-rt2",
  props: {
    ajax: {
      type: Object,
      default: () => {
        return {};
      }
    },
    pageLength: {
      type: Number,
      default: 10
    },
    cellUrl: String,
    selectable: Boolean,
    buttons: {
      type: Array,
      default: () => {
        return [];
      }
    },
    exports: {
      type: Array,
      default: () => {
        return [];
      }
    }
  },
  data() {
    var data = {
      hoverChild: [],
      total: 0,
      showChild: false,
      showIndex: [],
      local: {
        search: {},
        draw: 1,
        pageLength: this.pageLength,
        order: this.$attrs.order
      },
      page: 1,
      remoteData: [],
      columns: [],
      responsive: this.$attrs.responsive
    };
    return data;
  },
  created: function() {
    var storage = this.storage;
    //console.log(storage);
    if (storage.responsive) {
      this.responsive = storage.responsive;
    }

    if (storage.pageLength) {
      this.local.pageLength = parseInt(storage.pageLength);
    }

    if (storage.order) {
      this.local.order = storage.order;
    }

    this.columns = this.$attrs.columns.map(o => {
      o.hide = false;
      o.orderDir = "";
      o.isVisible = true;

      storage.columns = Object.assign({}, storage.columns);
      if (!storage.rows) storage.rows = {};
      var s;
      if ((s = storage.columns[o.name])) {
        if (s.isVisible === false) {
          o.isVisible = false;
        }
      }

      this.local.order.forEach(ord => {
        if (ord.name == o.name) {
          o.orderDir = ord.dir;
        }
      });

      return new Vue({
        data: o,
        methods: {
          cell(d) {
            var cell = {
              type: "text",
              column: this
            };

            if (this.type == "checkbox") {
              cell.type = "checkbox";
              var id = d[this.name];
              if (!storage.rows[id]) storage.rows[id] = {};
              cell.checked = storage.rows[id].checked;
            }

            if (this.cellStyle) {
              cell.style = { ...cell.style, ...this.cellStyle };
            }

            if (this.wrap) {
              cell.divStyle = {
                "word-wrap": "break-word",
                "white-space": "pre-wrap"
              };
            }

            if (d[this.name] == null) {
              return cell;
            } else {
              for (var i in d[this.name]) {
                cell[i] = d[this.name][i];
              }
            }

            return cell;
          },
          isDisplay() {
            return this.isVisible && !this.hide;
          },
          getContent(d) {
            var o = d[this.name];

            if (o === null) return "";

            if (Array.isArray(o)) {
              return o.join(" ");
            }

            if (typeof o == "object") {
              return o.content;
            }

            return o;
          },
          getValue(d) {
            var o = d[this.name];
            if (!o) return "";

            if (typeof o == "object") {
              if (o.type == "raw") {
                return o.content;
              } else {
                return o.value;
              }
            }
            return o;
          },
          toggleVisible() {
            this.isVisible = !this.isVisible;
            this.$emit("toggleVisible");
          }
        }
      });
    });

    this.columns.forEach(column => {
      column.$on("toggleVisible", () => {
        let storage = this.storage;
        storage.columns = storage.columns || {};
        storage.columns[column.name] = storage.columns[column.name] || {};
        storage.columns[column.name] = Object.assign(
          storage.columns[column.name],
          { isVisible: column.isVisible }
        );
        storage.save();
      });
    });
  },
  mounted() {
    if (this.ajax.url) {
      this.draw();
    }

    window.addEventListener("resize", this.resize);
  },
  computed: {
    visibleColumns() {
      return this.columns.filter(column => {
        return column.isDisplay();
      });
    },
    columnsHasTitle() {
      return this.columns.filter(column => {
        return column.title;
      });
    },
    storage() {
      let id = this.ajax.url
        .split("/")
        .filter(s => !Number(s))
        .join("/");

      var s = JSON.parse(localStorage.getItem(id)) || {};
      s.save = () => {
        var data = {};
        for (var i in s) {
          if (typeof s[i] == "function") continue;
          data[i] = s[i];
        }

        localStorage.setItem(id, JSON.stringify(data));
      };

      return s;
    },
    pageCount() {
      return Math.ceil(this.total / this.local.pageLength);
    },
    info() {
      return {
        from: (this.page - 1) * this.local.pageLength + 1,
        to: Math.min(this.page * this.local.pageLength, this.total),
        total: this.total
      };
    },
    isSearchable() {
      return this.columns.some(o => o.searchable);
    },
    hasHideColumn() {
      return this.columns.some(o => o.hide);
    }
  },
  methods: {
    checkAll(column, value) {
      this.$refs.tbody.checkAll(column, value);
    },
    getChecked(name) {
      var d = [];
      var rows = this.storage.rows[name];
      for (var r in rows) {
        d.push(r);
      }
      return d;
    },
    onClickButton(button) {
      var e = button.action + "(this);";
      eval(e);
    },
    hasExport() {
      return this.exports.count > 0;
    },
    exportFile(type) {
      this.local.draw++;
      this.$http
        .get(this.ajax.url, {
          params: {
            _rt: 1,
            draw: this.local.draw,
            columns: this.columns.map(o => {
              return {
                name: o.name,
                search: {
                  value: this.local.search[o.name]
                },
                searchMethod: o.searchMethod
              };
            }),
            order: this.local.order,
            search: this.searchData,
            type: type
          },
          responseType: "arraybuffer"
        })
        .then(function(response) {
          //console.log(response);
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
    resetLocaStorage() {
      let id = this.ajax.url
        .split("/")
        .filter(s => !Number(s))
        .join("/");
      $.localStorage.remove(id);
      this.draw();
    },
    toggleResponsive() {
      this.responsive = !this.responsive;

      //save
      var storage = this.storage;
      storage.responsive = this.responsive;
      storage.save();

      this.resize();
    },
    toggleChild() {
      this.showChild = !this.showChild;
      if (this.showChild) {
        this.$refs.tbody.showAllChild();
      } else {
        this.$refs.tbody.hideAllChild();
      }
      this.$forceUpdate();
    },
    getColumn(index) {
      return this.columns[index];
    },
    onChangePageLength() {
      //save
      var storage = this.storage;
      storage.pageLength = this.local.pageLength;
      storage.save();

      this.page = 1;
      this.draw();
    },
    gotoPage(page) {
      this.page = parseInt(page);
      this.draw();
    },
    firstPage() {
      this.page = 1;
      this.draw();
    },
    nextPage() {
      this.page++;
      this.draw();
    },
    prevPage() {
      this.page--;
      this.draw();
    },
    lastPage() {
      this.page = this.pageCount;
      this.draw();
    },
    data() {
      return this.remoteData;
    },
    order(o) {
      var storage = this.storage;

      this.local.order = [
        {
          name: o[0],
          dir: o[1]
        }
      ];
      this.columns.forEach(c => {
        if (c.name != o[0]) {
          c.orderDir = "";
        } else {
          c.orderDir = o[1];
        }
      });

      storage.order = this.local.order;

      storage.save();

      return this;
    },
    draw() {
      this.$refs.box.showLoading();
      this.local.draw++;
      Vue.http
        .get(this.ajax.url, {
          params: {
            _rt: 1,
            draw: this.local.draw,
            columns: this.columns.map(o => {
              return {
                name: o.name,
                search: {
                  value: this.local.search[o.name]
                },
                searchMethod: o.searchMethod
              };
            }),
            order: this.local.order,
            page: this.page,
            length: this.local.pageLength
          }
        })
        .then(resp => {
          this.$refs.box.hideLoading();
          try {
            if (resp.data.draw < this.local.draw) {
              return;
            }

            this.remoteData = resp.data.data;
            this.total = resp.data.total;

            this.resize();
          } catch (e) {
            alert(e.message);
          }
        });
    },
    search(name, value) {
      this.page = 1;
      this.local.search[name] = value;
      this.$emit("search", this.local.search);

      this.draw();
      return this;
    },
    resize() {
      this.columns.forEach(c => {
        c.hide = false;
      });

      if (!this.responsive) return;

      this.$nextTick(() => {
        //console.log("--");
        var parentWidth = this.$refs.table.parentElement.offsetWidth;
        //console.log("parentWidth", parentWidth);

        var total = () => {
          let total = 0;
          this.columns.forEach((c, i) => {
            let c_el = this.$refs.column[i];
            if (c_el) {
              total += c_el.$el.offsetWidth;
            }
          });
          if (
            this.columns.some(c => {
              return c.hide;
            })
          ) {
            total += 32;
          }
          return total;
        };

        var hideLastColumn = () => {
          let columns = this.columns.filter(c => {
            if (c.noHide) {
              return false;
            }
            return !c.hide;
          });
          columns = columns.reverse();
          if (columns.length > 0) {
            columns[0].hide = true; //hide last column
            return true;
          } else {
            return false; //nothing can hide
          }
        };

        var check = () => {
          let t = total();
          //console.log(t);
          if (t > parentWidth) {
            var r = hideLastColumn();

            if (r) {
              this.$nextTick(() => {
                check();
              });
            }
          }
        };

        check();
      });
    }
  }
};
</script>
