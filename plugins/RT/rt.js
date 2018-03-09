//created by Raymond Chong
//date 2017-01-04
(function ($) {

    var RTPagination = function (el, rt) {
        this.$el = $(el);

        this.widget = {
            first_page: $('<button data-toggle="tooltip" data-container="body" title="' + rt._('First page') + '" class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span></button>'),
            prev_page: $('<button data-toggle="tooltip" data-container="body" title="' + rt._('Previous page') + '" class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></button>'),
            next_page: $('<button data-toggle="tooltip" data-container="body" title="' + rt._('Next page') + '" class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button>'),
            last_page: $('<button data-toggle="tooltip" data-container="body" title="' + rt._('Last page') + '" class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span></button>'),
            page: $('<input style="width:50px" type="text" class="form-control input-sm" value="1">')
        };

        this.update = function () {
            rt.saveStorage();
            this.widget.page.val(rt.params.page);
            if (rt.params.page == 1) {
                this.widget.first_page.prop("disabled", true);
                this.widget.prev_page.prop("disabled", true);

                if (rt.params.page == rt.pagesize()) {
                    this.widget.next_page.prop("disabled", true);
                    this.widget.last_page.prop("disabled", true);
                } else {
                    this.widget.next_page.prop("disabled", false);
                    this.widget.last_page.prop("disabled", false);
                }
            } else if (rt.params.page == rt.pagesize()) {
                this.widget.first_page.prop("disabled", false);
                this.widget.prev_page.prop("disabled", false);
                this.widget.next_page.prop("disabled", true);
                this.widget.last_page.prop("disabled", true);
            } else {
                this.widget.first_page.prop("disabled", false);
                this.widget.prev_page.prop("disabled", false);
                this.widget.next_page.prop("disabled", false);
                this.widget.last_page.prop("disabled", false);
            }
            this.widget.first_page.blur();
            this.widget.prev_page.blur();
            this.widget.next_page.blur();
            this.widget.last_page.blur();
        };

        this.widget.first_page.appendTo(this.$el).on("click", function () {
            rt.params.page = 1;
            rt.refresh();
        }.bind(this));

        this.widget.prev_page.appendTo(this.$el).on("click", function () {
            rt.params.page--;
            rt.refresh();
        }.bind(this));

        var $div = $("<div class='pull-left'>").css("-webkit-user-select", "none").appendTo(this.$el);
        this.widget.page.appendTo($div).on("keypress", function (e) {
            if (e.keyCode == 13) {
                var page = parseInt($(this).val());
                if (page >= 1 && page <= rt.pagesize()) {
                    rt.params.page = $(this).val();
                    rt.refresh();
                }
            }
        });

        this.widget.next_page.appendTo(this.$el).on("click", function () {
            rt.params.page++;
            rt.refresh();
        }.bind(this));

        this.widget.last_page.appendTo(this.$el).on("click", function () {
            rt.params.page = rt.pagesize();
            rt.refresh();
        }.bind(this));

    };

    var RT = function (el, options) {
        this.$el = $(el);
        this.options = {
            fontsize: '1em',
            height: 300,
            pagination: ["bottom"]
        };
        $.extend(this.options, options);

        this.$pagination = $([]);

        this.widget = {
            page_length: $("<select class='form-control input-sm' style='width:70px'>"),
            refresh: $('<button class="btn btn-default btn-sm" type="button" title="' + this._("Refresh") + '"><span class="icon glyphicon glyphicon-refresh"></span></button>'),
            column_vis: $('<button class="btn btn-default btn-sm" type="button" title="Show/Hide columns"><span class="icon glyphicon glyphicon-th-list"></span></button>'),
            info: $("<div class='pull-right'>")
        };

        this.data = {};
        if (this.$el.attr("data-mode") == "virtual") {
            this.virtualMode = true;
        } else {
            this.virtualMode = false;
        }

        if (this.$el.attr("data-pagination")) {
            this.options.pagination = JSON.parse(this.$el.attr("data-pagination"));
        }

        this.loading = false;
        this.init();
        this.initPagination();

        this.storage = {
            selected: []
        };
        if ($.localStorage.isSet("rt", this.params.uri)) {
            this.storage = $.localStorage.get("rt", this.params.uri);
            $.extend(this.options, this.storage.options);
            $.extend(this.params, this.storage.params);
            this.params.draw = 0;

            if (!this.params.search_remember) {
                this.params.search = [];
            }

        }

        this.initWidget();

        var that = this;

        if (this.$el.attr("length") != "") {
            this.params.length = this.$el.attr("length");
        }

        this.$el.find(".search-clear-btn").each(function (i, o) {
            $(o).on('click', function () {
                $(o).closest(".input-group").find("input").val('');
                that.search();
            });
        });

        this.$el.find(".search").each(function (i, o) {
            if ($(o).is("input")) {
                var name = $(o).attr("name");
                $(o).val(that.params.search[name]);

                if ($(o).hasClass("rt_daterangepicker")) {
                    $(o).daterangepicker({
                        "opens": "center",
                        "showDropdowns": true,
                        "autoApply": true,
                        "autoUpdateInput": false,
                        locale: {
                            format: 'YYYY-MM-DD'
                        }
                    });
                    $(o).on('apply.daterangepicker', function (ev, picker) {
                        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                        that.search();
                    });

                } else if ($(o).hasClass('date')) {
                    $(o).daterangepicker({
                        singleDatePicker: true,
                        "opens": "center",
                        "showDropdowns": true,
                        "autoApply": true,
                        "autoUpdateInput": false,
                        locale: {
                            format: 'YYYY-MM-DD'
                        }
                    });
                    $(o).on('apply.daterangepicker', function (ev, picker) {
                        $(this).val(picker.startDate.format("YYYY-MM-DD"));
                        that.search();
                    });
                }
                $(o).on("keypress", function (event) {
                    if (event.which == 13) {
                        event.preventDefault();
                        that.search();
                    }
                });
            } else if ($(o).is("select")) {
                var name = $(o).attr("name");
                $(o).val(that.params.search[name]);
                $(this).on("change", that.search.bind(that));
            }
        });

        this.pagesize = function () {
            return Math.ceil(parseInt(this.data.total) / parseInt(this.params.length));
        };

        this.$table.find(">thead tr:first th.rt-col").on("click", function () {
            var column = $(this);
            if (!column.hasClass("sortable")) return;
            that.order(column.attr("data-index"));
            that.refresh();
        });

        if (this.$el.attr("order")) {
            that.params.order = [];
            that.params.order.push({
                column: this.$el.attr("order"),
                dir: this.$el.attr("order-dir")
            })
        }


        this.refresh();
    };

    RT.prototype._ = function (key) {
        if (typeof RTTranslate != 'undefined') {
            return RTTranslate(key);
        } else {
            return key;
        }

    };

    RT.prototype.order = function (name) {
        //remove all sorting
        this.columns().each(function (i, o) {
            if ($(o).attr("data-index") != name) {
                $(o).removeClass("sorting_desc sorting_asc");
            }
        });

        var column = this.columns(name);
        if (!column.hasClass("sorting_desc")) {
            column.addClass("sorting_desc");
            column.removeClass("sorting_asc");
        } else {
            column.removeClass("sorting_desc");
            column.addClass("sorting_asc");
        }
        var dir = "";
        if (column.hasClass("sorting_desc")) {
            dir = "desc";
        } else {
            dir = "asc";
        }

        this.params.order = [];
        this.params.order.push({
            column: column.attr("sort-index"),
            dir: dir
        });
    };

    RT.prototype.clearData = function () {
        this.$tbody.empty();
    };

    RT.prototype.initHeader = function () {
        this.$header = $('<div class="rt-header"></div>');
        this.$table.before(this.$header);
        if (!this.virtualMode) {
            return;
        }

        $table = $("<table style='margin-bottom:0px' class='table table-condensed table-bordered'>").appendTo(this.$header);

        var $thead = this.$table.find(">thead").clone();

        $thead.appendTo($table);

        return;
    };

    RT.prototype.resizeHeader = function () {


        console.log("virtualmode",this.virtualMode);

        
        if (!this.virtualMode) return;
        var that = this;

        setTimeout(function () {
            that.$tbody.find("tr:first td").each(function (i, o) {

                $(that.columns().get(i)).css("min-width", $(o).outerWidth()).css("max-width", $(o).outerWidth());

                //				$(that.searchColumns().get(i)).css("max-width", $(o).outerWidth());
                //				$(that.searchColumns().get(i)).css("min-width", $(o).outerWidth());

            });

        }, 0);

        that.$header.css("width", that.$table.outerWidth());
    };

    RT.prototype.showLoading = function () {
        this.$loading.removeClass("hide");
    };

    RT.prototype.hideLoading = function () {
        this.$loading.addClass("hide");
    };

    RT.prototype.renderData = function (data) {
        var that = this;

        data.data.forEach(function (obj, i) {
            var tr = $("<tr>").appendTo(that.$tbody);
            var row = data.row[i];
            if (row) {
                for (var v in row) {
                    tr.attr(v, row[v]);
                }
            }
            tr.attr("data-identity", obj["_key"]);

            that.columns().each(function (i, c) {
                if ($(c).hasClass("subhtml")) {
                    var url = $(c).attr("data-url");
                    var td = $("<td>");
                    td.addClass("unselectable");
                    var span = $("<button class='btn btn-default btn-xs'><i class='fa fa-plus'/></button>");
                    td.append(span);
                    var new_tr = $("<tr>");
                    span.on("click", function (event) {
                        event.stopPropagation();
                        if (span.find("i").hasClass("fa-minus")) {
                            new_tr.remove();
                            span.find("i").removeClass("fa-minus").addClass("fa-plus");
                            return;
                        }
                        span.find("i").removeClass("fa-plus").addClass("fa-minus");
                        $.get(url, {
                            id: obj["_key"]
                        }).done(function (html) {
                            new_tr.empty();
                            var new_td1 = $("<td>").appendTo(new_tr);
                            var new_td2 = $("<td>").appendTo(new_tr);

                            //get the visible column count
                            var visible_length = that.columns().not(".hide").length;

                            new_td2.attr("colspan", visible_length - 1);
                            new_td2.html(html);

                            tr.after(new_tr);
                        });
                    });
                    tr.append(td);
                } else if ($(c).hasClass("visible")) {

                    var index = $(c).attr("data-index");

                    var td = $("<td>").appendTo(tr);

                    if ($(c).attr("cell-style")) {
                        td.css(JSON.parse($(c).attr("cell-style")));
                    }

                    if (typeof obj[index] === "object") {
                        td.html(obj[index]["content"]);
                        td.addClass(obj[index]["class"]);
                        try {
                            for (var k in obj[index]["css"]) {
                                td.css(k, obj[index]["css"][k]);
                            }
                        } catch (e) {

                        }

                    } else {
                        td.html(obj[index]);
                        if ($(c).attr('data-index') == "[checkbox]") {
                            var v = td.find("input[type='checkbox']").val();
                            var data = that.getSelectedValue();
                            if (data.indexOf(v) >= 0) {
                                td.find("input[type='checkbox']").prop("checked", true);
                            }

                        }

                    }

                    if ($(c).attr("cell-attr")) {
                        td.attr(JSON.parse($(c).attr("cell-attr")));
                    }


                    if ($(c).hasClass("editable")) {
                        td.on("click", function () {
                            if (td.hasClass("cell-edit")) return;
                            td.addClass("cell-edit");
                            var org_data = td.contents();
                            var org_val = td.html();
                            td.empty();

                            if ($(c).attr("editable-type") == "textarea") {
                                var textarea = $("<textarea class='form-control input-sm'>");
                                td.append(textarea);

                                textarea.html($.br2nl(org_val));
                                textarea.focus();
                                textarea.on("focusout", function () {
                                    if (textarea.val() != org_val) {
                                        var data = {};
                                        data[$(c).attr("data-index")] = textarea.val();

                                        $.post(that.attr("cell-url") + "/" + obj["_key"], data, function () {
                                            td.removeClass("cell-edit");
                                            td.empty();
                                            td.append($.nl2br(textarea.val()));
                                        });
                                    } else {
                                        td.removeClass("cell-edit");
                                        td.empty();
                                        td.append(org_data);
                                    }
                                });
                            } else if ($(c).attr("editable-type") == "select") {
                                var select_data = JSON.parse($(c).attr("editable-data"));
                                var select = $("<select class='form-control input-sm'>");
                                for (var i in select_data) {
                                    var opt = $("<option>");
                                    opt.text(select_data[i]);
                                    opt.val(i);
                                    select.append(opt);
                                    if (org_val == select_data[i]) {
                                        opt.prop("selected", true);
                                    }
                                }
                                td.append(select);
                                select.focus();
                                select.on("focusout", function () {
                                    if (select.val() != org_val) {
                                        var data = {};
                                        data[$(c).attr("data-index")] = select.val();
                                        if(that.$el.attr("cell-url")==undefined){
                                            console.error("attr cell-url not defined");
                                            return;
                                        }

                                        $.post(that.$el.attr("cell-url") + "/" + obj["_key"], data, function () {
                                            td.removeClass("cell-edit");
                                            td.empty();
                                            td.append(select.find(':selected').text());
                                        });
                                    } else {
                                        td.removeClass("cell-edit");
                                        td.empty();
                                        td.append(org_data);

                                    }
                                });
                            } else {
                                var input = $("<input class='form-control input-sm'>");
                                td.append(input);
                                input.val(org_val);
                                input.focus();
                                input.on("focusout", function () {
                                    if (input.val() != org_val) {
                                        var data = {};
                                        data[$(c).attr("data-index")] = input.val();
                                        //console.log(data);

                                        $.post(that.$table.attr("cell-url") + "/" + obj["_key"], data, function () {
                                            td.removeClass("cell-edit");
                                            td.empty();
                                            td.append(input.val());
                                        });
                                    } else {
                                        td.removeClass("cell-edit");
                                        td.empty();
                                        td.append(org_data);
                                    }

                                });
                            }
                        });
                    }
                    //td.wrapInner("<div class='rt-cell' style='overflow:hidden;'></div>");
                } else {
                    var td = $("<td class='hide'>");
                    tr.append(td);
                }

                //get attr of cell
                if ($(c).attr("cell-align")) {
                    td.attr("align", $(c).attr("cell-align"));
                }
            });
        });


    };

    RT.prototype.hideColumn = function (index) {
//        console.log("hide", index);
        this.$table.find(">thead>tr:first>th:nth-child(" + index + ")").hide();
        this.$table.find(">thead>tr:nth-child(2)>td:nth-child(" + index + ")").hide();
        this.$table.find("tbody tr td:nth-child(" + index + ")").hide();

        if (this.virtualMode) {
            this.$header.find("thead>tr:first>th:nth-child(" + index + ")").hide();
            this.$header.find("thead>tr:nth-child(2)>td:nth-child(" + index + ")").hide();
        }
    };

    RT.prototype.restoreTable = function () {
        this.$table.find(">thead tr th").show();
        this.$table.find(">thead tr td").show();

        this.$table.find(">tbody tr td").show();

        if (this.virtualMode) {
            this.$header.find(".collapsed-column").remove();
        }
    };

    RT.prototype.getHideIndex = function () {
        var total = 0;
        var hide_index = -1;
        var parentWidth = this.$table.parent().width();
        var $columns = this.$table.find(">thead tr th");

        $columns.each(function (i, o) {
            if ($(o).css('max-width') != "none") {
                if ($(o).attr("data-width") > parseInt($(o).css('max-width'))) {
                    $(o).attr('width', $(o).css('max-width'));
                }
            }

            if ($(o).css('min-width') != "none") {
                if ($(o).attr("data-width") < parseInt($(o).css('min-width'))) {
                    $(o).attr('width', $(o).css('min-width'));
                }
            }
        });

        $columns.each(function (i, o) {
            if (hide_index >= 0) return;
            if ($(o).hasClass("visible")) {
                if (total + 30 > parentWidth) {
                    hide_index = i;
                    return;
                }

                var w = $(o).outerWidth();
                $(o).attr("data-width", w);
                
                total += w;
                if (total > parentWidth) {
                    hide_index = i;
                }
            }
        });
        return hide_index;
    };

    RT.prototype.addHeaderColumn = function () {
        var that = this;
        var $thead = this.$table.find(">thead");
        //add header column
        $thead.find(">tr:first").each(function (i, tr) {
            var th = $("<th class='collapsed-column'>").prependTo(tr);
            th.attr("width", "28px");

            //add header button
            var $btn = $("<button>").addClass("btn btn-default btn-xs");
            $btn.append("<i class='fa fa-chevron-up'></i>");
            th.append($btn);
            $btn.on("click", function () {
                $btn.find("i").toggleClass("fa-chevron-down").toggleClass("fa-chevron-up");

                var is_up = $btn.find("i").hasClass("fa-chevron-up");

                that.$tbody.find(">tr").each(function (j, o) {
                    $(o).find("td:first>button").each(function (k, o) {
                        if (is_up) {
                            if ($(o).find("i.fa-chevron-up").length == 0) {
                                $(o).trigger('click');
                            }
                        } else {
                            if ($(o).find("i.fa-chevron-down").length == 0) {
                                $(o).trigger('click');
                            }
                        }
                    });
                });

            });
        });

        //add header search column
        $thead.find(">tr:nth-child(2)").prepend("<th class='collapsed-column'></th>");

        if (this.virtualMode) {
            this.$header.find("thead>tr").prepend("<th class='collapsed-column'></th>");
        }

    };

    RT.prototype.addBodyColumn = function () {
        var that = this;
        this.$tbody.find(">tr").each(function (i, tr) {
            if ($(tr).hasClass('child')) return;
            var td = $("<td class='collapsed-column'>").prependTo(tr);
            var $btn = $("<button class='btn btn-default btn-xs'><i class='fa fa-chevron-up' /></button>").appendTo(td);

            $btn.hover(function () {
                if ($(this).find("i").hasClass('fa-chevron-up')) {
                    $(tr).addClass("open");
                    $(tr).next().fadeIn();
                }
            }, function () {
                if ($(this).find("i").hasClass('fa-chevron-up')) {
                    $(tr).removeClass("open");
                    $(tr).next().hide();
                }
            });


            $btn.on("click", function (event) {
                if ($(this).find("i").hasClass('fa-chevron-up')) {
                    $(tr).addClass("open");
                    $(tr).next().show();
                } else {
                    $(tr).removeClass("open");
                    $(tr).next().hide();
                }
                $(this).find("i").toggleClass("fa-chevron-down").toggleClass("fa-chevron-up");

            });
        });
    };

    RT.prototype.renderChildRow = function (hide_index) {
        var that = this;
        //get column labels
        var column_labels = [];

        var $column = this.columns()
        for (var i = hide_index; i <= $column.length; i++) {
            column_labels.push($($column.get(i - 1)).text());
        }

        this.$tbody.find(">tr").each(function (i, tr) {
            if ($(tr).hasClass("parent")) return;
            if ($(tr).hasClass("child")) return;

            var $parent_tr = $(tr).addClass("parent");

            $child_tr = $("<tr class='child'></tr>");
            var $child_td = $("<td>").appendTo($child_tr);
            $child_td.attr("colspan", hide_index);

            $parent_tr.after($child_tr);

            $child_tr.hide();

            var j = 0;
            var ul = $("<ul>").appendTo($child_td);
            for (var i = hide_index; i <= that.columns().length; i++) {
                var label = column_labels[j++];
                $parent_tr.find("td:nth-child(" + i + ")").each(function (j, td) {
                    var $li = $("<li>");
                    $("<b>").append(label).appendTo($li);

                    $li.append("&nbsp;&nbsp;");
                    $li.append($(td).contents());
                    $li.appendTo(ul);

                    $(td).hide();
                });
            }

        });
    };

    RT.prototype.getData = function () {
        var that = this;
        //remove first button if responsive
        if (this.$table.hasClass("collapsed")) {
            this.$table.removeClass('collapsed');
            this.$table.find("tr").each(function (i, o) {
                $(o).find(":first").remove();
            })
        }

        this.showLoading();
        var url = this.$table.attr("data-url");
        this.params.draw++;
        this.loading = true;
        $.getJSON(url, this.params).done(function (data) {
            if (data.draw < that.params.draw) {
                return;
            }
            that.loading = false;
            that.hideLoading();

            that.data = data;

            that.$pagination.each(function (i, o) {
                o.update();
            });

            that.restoreTable();


            if (!that.virtualMode) {
                that.$tbody.empty();
            }

            that.renderData(data);

            //collapsed ----
            var hide_index = that.getHideIndex();

            if (that.$table.attr('responsive') === "" && hide_index >= 0) {
                //add collapsed column
                that.$table.addClass("collapsed");

                var columns = [];
                for (var i = hide_index; i <= that.columns().length; i++) {
                    that.hideColumn(i);
                }

                that.renderChildRow(hide_index);
                that.addHeaderColumn();
                that.addBodyColumn();

            } else {
                that.$el.removeClass("collapsed");
            }

            that.renderInformation();
            that.resizeHeader();
        });
    };

    RT.prototype.init = function () {
        var that = this;
        this.$wrapper = this.$el.parent();

        this.$table = this.$el;
        this.$tbody = this.$el.find('>tbody');
        if (!this.$tbody.length) {
            this.$tbody = $('<tbody></tbody>').appendTo(this.$el);
        }

        if (this.virtualMode) {
            this.$loading = $('<div style="text-align:center"><i class="fa fa-refresh fa-spin" style="font-size:30px"></i></div>');

        } else {
            this.$loading = $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            this.$wrapper.before(this.$loading);
        }

        this.$footer = this.$wrapper.next();

        this.initHeader();

        this.$table.find(">thead tr:first th").each(function(i,o){
        //    $(o).wrapInner("<div class='rt-col-label'></div>");
            var sizer=$("<div class='rt-col-sizer'>");
            $(o).append(sizer);
        });;

        this.$table.find(">thead tr:first th").resizable({
            handles: "e",
            minWidth: 28,
            resize: function (event, ui) {
                var sizer=$(event.target).find("div.rt-col-sizer")
                //var sizerID = "#" + $(event.target).attr("id") + "-sizer";
                $(sizer).width(ui.size.width);
            }
        });


        if (this.virtualMode) {
            $(window).resize(this.resizeHeader.bind(this));

            //this.$table.css('margin-top', -that.$table.find(">thead").height());
            this.$table.css("margin-bottom", "0px");

            this.$body = $('<div class="rt-body" style="overflow-y:auto;overflow-x:hidden"></div>');
            this.$body.css("height", "300px");
            this.$body.css("resize", "vertical");
            this.$table.before(this.$body);
            this.$table.appendTo(this.$body);
            this.$body.scroll(function (e) {
                if (that.loading) return;
                var s = parseInt(that.$body.height()) / 4;

                if ($(this).scrollTop() + $(this).innerHeight() + s >= $(this)[0].scrollHeight) {
                    that.params.page++;
                    that.widget.page.val(that.params.page);
                    that.getData();
                }
            });

            this.$loading.appendTo(this.$body);

        }

        this.params = {
            draw: 0,
            page: 1,
            length: 25,
            search: [],
            search_remember: false,
            uri: this.$el.attr("data-url")
        };

    };


    RT.prototype.search = function () {
        this.params.search = {

        };
        //read all search
        this.$el.find(".search").each(function (j, search) {
            if ($(search).hasClass("rt_daterangepicker")) {
                var value = $(search).val().split(" - ");
                var name = $(search).attr("name");
                this.params.search[name]['from'] = value[0];
                this.params.search[name]['to'] = value[1];
            } else {
                var name = $(search).attr("name");
                var value = $(search).val();
                if (value !== search.defaultValue || value !== "") {
                    this.params.search[name] = value;
                    search.defaultValue = value;
                }
            }
        }.bind(this));
        this.params.page = 1;
        this.refresh();
    };

    RT.prototype.refresh = function () {
        this.getData();
    };

    RT.prototype.initWidget = function () {
        var that = this;

        //header
        this.$header.prepend(this.$table.attr("rt-header"));

        //font size
        this.$table.parent().css("font-size", this.options.fontsize);

        //refresh
        var btn_group = $("<div class='pull-left btn-group'>");
        this.$footer.append(btn_group);

        this.widget.refresh.attr("data-toggle", "tooltip").appendTo(btn_group).on("click", function () {
            this.refresh();
        }.bind(this));

        //column visible
        this.widget.column_vis.attr("data-toggle", "tooltip").appendTo(btn_group).on("click", function () {
            //open
            var ul = $('<ul class="RTColumnVis ui-sortable">');
            that.columns().not(".subhtml").each(function (i, o) {
                if ($(o).attr('data-index') == undefined) return;

                var input = $('<input type="checkbox" class="iCheck" value="' + $(o).attr('data-index') + '">');
                if ($(o).hasClass("visible")) {
                    input.prop('checked', true);
                }


                var label = $("<span class='text'></span>");
                if ($(o).text() == "") {
                    label.append(' ' + $(o).attr("data-index"));
                } else {
                    label.append(' ' + $(o).text());
                }

                var li = $("<li>");
                li.append('<span class="handle"><i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i></span>');
                li.append(input);
                li.append(label);

                ul.append(li);
            });

            ul.sortable({
                placeholder: "sort-highlight",
                handle: ".handle",
                forcePlaceholderSize: true,
                zIndex: 999999
            });

            var ui_url = that.$el.attr("data-url").split("/").filter(function (a) {
                return !$.isNumeric(a);
            }).join("/");


            bootbox.dialog({
                title: "Select columns",
                message: ul,
                buttons: {
                    ResetToDefault: {
                        className: "btn-primary",
                        label: "<i class='fa fa-undo' /> Result to default",
                        callback: function () {
                            $.get("UI/save/reset", {
                                uri: ui_url
                            }).done(function () {
                                that.refresh();
                            });
                        }
                    },
                    Cancel: {
                        className: "btn-warning"
                    },
                    OK: {
                        className: "btn-success",
                        label: "<i class='fa fa-check'/> OK",
                        callback: function () {
                            var data = [];
                            ul.find('input[type="checkbox"]').each(function (i, o) {
                                var column_name = $(o).val();
                                if ($(o).is(":checked")) {
                                    that.columns(column_name).removeClass('hide');
                                } else {
                                    that.columns(column_name).addClass('hide');
                                }
                                data.push({
                                    name: column_name,
                                    visible: $(o).is(":checked")
                                });
                            });

                            $.ajax({
                                url: "UI/save",
                                type: "POST",
                                data: {
                                    RT: 1,
                                    uri: ui_url,
                                    column: data
                                }
                            }).done(function () {
                                that.refresh();
                            });

                        }
                    }
                }
            });
        });

        //page length ------------------------------------------
        var options = [10, 25, 50, 100, 500];
        var page_length = this.widget.page_length;

        options.forEach(function (v) {
            var $option = $("<option>" + v + "</option>").appendTo(page_length);
            if (that.params.length == v) {
                $option.prop("selected", true);
            }
        });

        if (this.$el.attr("paging") === "") {
            if (!this.virtualMode) {
                page_length.wrapAll("<div class='pull-left'>").parent().appendTo(this.$footer);
            }
        }

        page_length.on("change", function () {
            that.params.length = parseInt($(this).val());
            that.params.page = 1;
            that.refresh();
        });

        this.widget.info.appendTo(this.$footer);

        //page search remember

        var page_search_remember = $("<div class='btn-group'><button type='button' class='btn btn-default btn-sm' data-toggle='dropdown'><i class='fa fa-cog'></i></button>" +
            "<ul class='dropdown-menu'>" +
            "<li><a href='javascript:void(0)' data-name='search_remember'>Remember search filter</a></li>" +
            "<li><a href='javascript:void(0)' data-name='reset_font_size'>Reset font size</a></li>" +
            "</ul></div>");

        if (this.params.search_remember) {
            page_search_remember.find("[data-name='search_remember']").prepend("<i class='fa fa-check'></i>");
        }
        page_search_remember.find("[data-name='search_remember']").on("click", function () {
            that.params.search_remember = !that.params.search_remember;
            that.saveStorage();

            if (that.params.search_remember) {
                $(this).prepend("<i class='fa fa-check'></i>");
            } else {
                $(this).find("i").remove();
            }
        });

        page_search_remember.find("[data-name='reset_font_size']").on("click", this.resetFontSize.bind(this));

        this.$footer.append(page_search_remember);

        //font size
        var $font_increase_btn = $('<button data-toggle="tooltip" title="' + this._('Increase font size') + '" class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-triangle-top" aria-hidden="true"></span></button>');
        var $font_decrease_btn = $('<button data-toggle="tooltip" title="' + this._('Decrease font size') + '" class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></button>');

        this.$footer.append($font_increase_btn);
        this.$footer.append($font_decrease_btn);

        $font_increase_btn.on("click", this.increaseFontSize.bind(this)).on("click", function () {
            $(this).blur();
        });
        $font_decrease_btn.on("click", this.decreaseFontSize.bind(this)).on("click", function () {
            $(this).blur();
        });

        this.$footer.find(".rt-export a").on("click", function (e) {
            e.preventDefault();

        });
    };

    RT.prototype.decreaseFontSize = function () {
        var fs = this.options.fontsize.substring(0, this.options.fontsize.length - 2);
        if (parseFloat(fs) <= 0.8) return;
        this.options.fontsize = (parseFloat(fs) - 0.1) + "em";
        this.$table.parent().css('font-size', this.options.fontsize);
        this.saveStorage();
    };

    RT.prototype.increaseFontSize = function () {
        var fs = this.options.fontsize.substring(0, this.options.fontsize.length - 2);
        this.options.fontsize = (parseFloat(fs) + 0.1) + "em";
        this.$table.parent().css('font-size', this.options.fontsize);
        this.saveStorage();
    };

    RT.prototype.resetFontSize = function () {
        this.options.fontsize = "1em";
        this.$table.parent().css('font-size', this.options.fontsize);
        this.saveStorage();
    };

    RT.prototype.saveStorage = function () {
        $.localStorage.set("rt", this.params.uri, "params", this.params);
        $.localStorage.set("rt", this.params.uri, "options", this.options);
    };

    RT.prototype.renderInformation = function () {
        this.widget.info.empty();
        var start = (this.params.page - 1) * this.params.length + 1;
        var end = this.params.page * this.params.length;
        if (end > this.data.total) {
            end = this.data.total;
        }

        if (this.$el.attr("paging") === "") {
            this.widget.info.append(start + " - " + end + " of " + this.data.total);
        } else {
            this.widget.info.append("1 - " + this.data.total + " of " + this.data.total);
        }
    };

    RT.prototype.initPagination = function () {
        if (this.virtualMode) return;
        var that = this;
        //page
        this.$pagination = $([]);

        if (this.$el.attr("paging") === "") {

            if (this.options.pagination.indexOf('bottom') >= 0) {
                var el = $('<div class="btn-group pull-left"></div>')
                var p = new RTPagination(el, this);
                this.$pagination.push(p);
                el.appendTo(this.$footer);
            }
            //console.log(this.options.pagination);

            if (this.options.pagination.indexOf('top') >= 0) {
                var el = $('<div class="btn-group pull-left"></div>');
                var p = new RTPagination(el, this);
                this.$pagination.push(p);
                this.$header.prepend(el);
            }
        }

    };

    RT.prototype.columns = function (name) {
        var $table = this.$table;
        if (this.virtualMode) {
            $table = this.$header.find("table");
        }
        if (name == undefined) {
            return $table.find("thead tr:first th");
        } else {
            return $table.find("thead tr:first th[data-index='" + name + "']");
        }
    };

    RT.prototype.searchColumns = function (name) {
        var $table = this.$table;
        if (this.virtualMode) {
            $table = this.$header.find("table");
        }
        if (name == undefined) {
            return $table.find("thead tr:nth-child(2) td");
        } else {
            return $table.find("thead tr:nth-child(2) td[data-index='" + name + "']");
        }

    };

    RT.prototype.checkboxChange = function (checkbox) {
        var data = this.getSelectedValue();
        var val = checkbox.value;
        var index = data.indexOf(val);
        if (checkbox.checked) {
            if (index == -1) {
                data.push(val);
            }
        } else {
            data.splice(index, 1);
        }
        $.localStorage.set("rt", this.params.uri, "selectedValue", data);
    };

    RT.prototype.getSelectedValue = function () {
        var data = [];
        if ($.localStorage.isSet("rt", this.params.uri, "selectedValue")) {
            data = $.localStorage.get("rt", this.params.uri, "selectedValue");
        }
        return data;
    };

    RT.prototype.clearSelectedValue = function () {
        $.localStorage.set("rt", this.params.uri, []);
    };


    $.fn.RT = function (option) {
        this.each(function () {
            if (!$(this).data("rt")) {
                $(this).data("rt", new RT(this, option));
            }

        });
    };


    var f = function () {
        $(".RT").RT({

        });
    };

    f();

    $(document).ajaxComplete(function () {
        setTimeout(f, 0);
    });

})(jQuery);