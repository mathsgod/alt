//created by Raymond Chong
//date 2017-12-12
Vue.component("sortable", {
    model: {
        prop: 'list'
    },
    props: {
        name: {

        },
        list: {
            type: Array
        }, options: {
            type: Object, default: function () {
                return {

                };
            }
        }
    },
    data: function () {
        return {
            start_index: null,
            source_container: null,
            sortable: null,
            items: null,
            selected_item: null
        };
    },
    render: function (h) {
        return h("div", null, this.$slots.default);
    }, mounted: function () {
        for (var i = 0; i < this.list.length; i++) {
            this.list[i].uuid = this.uuid();
            console.log(this.list[i].uuid);
        }

        var options = this.options;
        options.update = this.onUpdate;
        options.start = this.onStart;
        options.receive = this.onReceive;
        options.remove = this.onRemove;
        options.stop = this.onStop;
        options.sort = this.onSort;
        options.over = this.onOver;
        options.out = this.onOut;

        this.sortable = $(this.$el).sortable(options).disableSelection();

    }, methods: {
        uuid: function () {
            function s4() {
                return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
            }
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
        },
        onStop: function (evt, ui) {
            var a = $(this.$el).find(this.options.items).index(ui.item);
            console.log(this.name, "sort stop", a);

            if (a == undefined) {
                consle.log("undef");
                return;
            }

            if (a >= 0) {
                console.log(ui.item.data("item"));
                if (this.selected_item) {
                    this.list.splice(this.start_index, 1);
                    this.list.splice(a, 0, this.selected_item);
                }
            }
            this.selected_item = null;
        },
        onReceive: function (evt, ui) {
            var a = $(this.$el).find(this.options.items).index(ui.item);
            var data = ui.item.data("item");

            if (a == -1) {
                a = $(this.$el).find(this.options.items).index(ui.helper)
                data = ui.helper.data("item");
                ui.helper.remove();
            }


            this.list.splice(a, 0, data);

            //bind back item
            this.$nextTick(function () {
                $($(this.$el).find(this.options.items).get(a)).data("item", data);
            });

        },
        onRemove: function (evt, ui) {
            console.log(this.name, "sort remove");
            var a = $(this.$el).find(this.options.items).index(ui.item);
            if (a == -1) {
                this.list.splice(this.start_index, 1);
            }
            console.log(this.name, "remove ", a);
        },
        onStart: function (evt, ui) {
            var a = $(this.$el).find(this.options.items).index(ui.item);


            this.start_index = a;
            this.selected_item = this.list[a];

            ui.item.data('item', this.selected_item);

            console.log(this.name, "sort start", this.selected_item);
        },
        onUpdate: function (evt, ui) {
            console.log(this.name, "update ");
        }
    }
});