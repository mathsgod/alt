//created by Raymond Chong
//date 2017-12-12
Vue.component("draggable", {
    model: {
        prop: 'list'

    },
    props: {
        name: {

        },
        list: {
            type: Array
        }, options: {
            type: Object,
            default: function () {
                return {

                };
            }
        }
    },
    data: function () {
        return {
        };
    },
    render: function (h) {
        return h("div", null, this.$slots.default);
    }, mounted: function () {
        var options = this.options;
        options.start = this.onStart;
        options.stop = this.onStop;
        this.draggable = $(this.$el).children().draggable(options).disableSelection();
    }, methods: {
        uuid: function () {
            function s4() {
                return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
            }
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
        },
        onStop: function (evt, ui) {
            console.log(this.name, "drag stop", ui.helper);
        },
        onStart: function (evt, ui) {
            var a = $(this.$el).children().index(ui.helper.context);
            var data = jQuery.extend({}, this.list[a]);
            data.uuid = this.uuid();
            ui.helper.data("item", data);
            console.log("drag start");
        }
    }
});

