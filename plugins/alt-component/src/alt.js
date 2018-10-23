import Input from './Input.vue';
import Box from './Box.vue';
import BoxHeader from './BoxHeader.vue';
import BoxBody from './BoxBody.vue';
import BoxFooter from './BoxFooter.vue';
import icheck from './icheck.vue';
import datepicker from './Datepicker.vue';
import altDate from './Date.vue';
import altDatetime from './Datetime.vue';
import altE from './E.vue';
import altEmail from "./Email.vue";
import altForm from "./Form.vue";
import altGrid from "./Grid.vue";
import altGridSection from "./GridSection.vue";
import multiselect from "./Multiselect.vue";
import select2 from "./Select2.vue";
import multiselect2 from "./Multiselect2.vue";
import altTab from "./Tab.vue";
import altTable from "./Table.vue";
import altTableBody from "./TableBody.vue";
import altTabPane from "./TabPane.vue";
import altTimeline from "./Timeline.vue";
import xeditable from "./xeditable.vue";
import ckeditor from "./ckeditor.vue";
import roxyfileman from "./roxyfileman.vue";
import altButton from "./Button.vue";
import altCell from "./Cell.vue";
import altRT from "./RT.vue";
import altRT2 from "./RT2.vue";
import RTPagination from "./RTPagination.vue";
import RT2Tbody from "./RT2Tbody.vue";
import RTInfo from "./RTInfo.vue";
import altColumn from "./Column.vue";
import altColumnSearch from "./ColumnSearch.vue";


Vue.component("alt-input", Input);
Vue.component("alt-box", Box);
Vue.component("alt-box-header", BoxHeader);
Vue.component("alt-box-body", BoxBody);
Vue.component("alt-box-footer", BoxFooter);
Vue.component("icheck", icheck);
Vue.component("datepicker", datepicker);
Vue.component("alt-date", altDate);
Vue.component("alt-datetime", altDatetime);
Vue.component("alt-e", altE);
Vue.component("alt-email", altEmail);
Vue.component("alt-form", altForm);
Vue.component("alt-grid", altGrid);
Vue.component("alt-grid-section", altGridSection);
Vue.component("alt-multiselect", multiselect);
Vue.component("alt-tab", altTab);
Vue.component("alt-table", altTable);
Vue.component("alt-table-body", altTableBody);
Vue.component("alt-tab-pane", altTabPane);
Vue.component("alt-timeline", altTimeline);
Vue.component("xeditable", xeditable);
Vue.component("ckeditor", ckeditor);
Vue.component("roxyfileman", roxyfileman);
Vue.component("alt-button", altButton);
Vue.component("alt-cell", altCell);
Vue.component("alt-rt", altRT);
Vue.component("alt-rt2", altRT2);
Vue.component("rt-pagination", RTPagination);
Vue.component("rt2-tbody", RT2Tbody);
Vue.component("rt-info", RTInfo);
Vue.component("alt-column",altColumn);
Vue.component("alt-column-search",altColumnSearch);
Vue.component("select2",select2);
Vue.component("multiselect2", multiselect2);



new Vue({
    created() {

        document.addEventListener("DOMContentLoaded", () => {
            this.VueRegisterElement("alt-rt2");
            
            this.VueRegisterElement("alt-box");
            this.VueRegisterElement("alt-box-header");
            this.VueRegisterElement("alt-box-body");
            this.VueRegisterElement("alt-box-footer");
            this.VueRegisterElement("icheck");
            this.VueRegisterElement("datepicker");
            this.VueRegisterElement("alt-date");
            this.VueRegisterElement("alt-datetime");
            this.VueRegisterElement("alt-e");
            this.VueRegisterElement("alt-input");
            this.VueRegisterElement("alt-email");
            this.VueRegisterElement("alt-form");
            this.VueRegisterElement("alt-grid");
            this.VueRegisterElement("alt-grid-section");
            this.VueRegisterElement("alt-button");
            this.VueRegisterElement("ckeditor");
            this.VueRegisterElement("xeditable");
            this.VueRegisterElement("alt-timeline");
            this.VueRegisterElement("alt-tab");
            this.VueRegisterElement("alt-tab-pane");
            this.VueRegisterElement("alt-table");
            this.VueRegisterElement("alt-table-body");
            this.VueRegisterElement("alt-table-button");
            this.VueRegisterElement("roxyfileman");
            this.VueRegisterElement("alt-multiselect");
            this.VueRegisterElement("alt-multiselect2");
        });





    }, methods: {
        VueRegisterElement(name) {


            var config = { attributes: true, childList: true, subtree: true };

            // Callback function to execute when mutations are observed
            var callback = function (mutationsList, observer) {
                for (var mutation of mutationsList) {
                    if (mutation.type == 'childList') {
                        for (var n of mutation.addedNodes) {
                            if (n.nodeName.toLowerCase() == name) {
                                new Vue({ el: n });
                            }
                            if (n.nodeType == 1) {
                                if (n.getAttribute("is") == name) {
                                    new Vue({ el: n });
                                }

                                for (let c of n.querySelectorAll("[is='" + name + "']")) {
                                    new Vue({ el: c });
                                }


                                for (let c of n.querySelectorAll(name)) {
                                    new Vue({ el: c });
                                }
                            }
                        }
                    }
                    else if (mutation.type == 'attributes') {
                        if (mutation.attributeName == "is") {
                            if (mutation.target.getAttribute("is") == name) {
                                new Vue({ el: mutation.target });
                            }
                        }
                    }
                }
            };


            var observer = new MutationObserver(callback);

            observer.observe(document.body, config);


            for (let c of document.body.querySelectorAll(name)) {
                new Vue({ el: c });
            }

            for (let c of document.body.querySelectorAll("[is='" + name + "']")) {
                new Vue({ el: c });
            }
        }
    }
});
