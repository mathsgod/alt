import VueRegisterElement from "vue-register-element/src/index.js";
import icheck from './icheck.vue';

import Input from './Input.vue';
import Box from './Box.vue';
import BoxHeader from './BoxHeader.vue';
import BoxBody from './BoxBody.vue';
import BoxFooter from './BoxFooter.vue';

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
import RT2 from "./RT2.vue";
import RTPagination from "./RTPagination.vue";
import RT2Tbody from "./RT2Tbody.vue";
import RT2Column from "./RT2Column.vue";
import RTInfo from "./RTInfo.vue";


import altColumn from "./Column.vue";
import altColumnSearch from "./ColumnSearch.vue";
import RTTable from "./RTTable.vue";
import RTHead from "./RTHead.vue";
import RTBody from "./RTBody.vue";
import RTColumn from "./RTColumn.vue";

import Ace from "./Ace.vue";
import Datatables from "./Datatables.vue";
import RT2Cell from "./RT2Cell.vue";

VueRegisterElement("rt2-cell", RT2Cell);
VueRegisterElement("rt2-column", RT2Column);

VueRegisterElement("alt-tab", altTab);
VueRegisterElement("alt-tab-pane", altTabPane);

VueRegisterElement("alt-grid", altGrid);
VueRegisterElement("alt-grid-section", altGridSection);

VueRegisterElement("alt-box", Box);
VueRegisterElement("alt-box-header", BoxHeader);
VueRegisterElement("alt-box-body", BoxBody);
VueRegisterElement("alt-box-footer", BoxFooter);
VueRegisterElement("alt-datatables",Datatables);
VueRegisterElement("alt-e", altE);
VueRegisterElement("icheck", icheck);
VueRegisterElement("datepicker", datepicker);
VueRegisterElement("alt-date", altDate);
VueRegisterElement("alt-datetime", altDatetime);
VueRegisterElement("alt-email", altEmail);
VueRegisterElement("alt-form", altForm);
VueRegisterElement("alt-multiselect", multiselect);
VueRegisterElement("alt-table", altTable);
VueRegisterElement("alt-table-body", altTableBody);
VueRegisterElement("alt-timeline", altTimeline);
VueRegisterElement("x-editable", xeditable);
VueRegisterElement("ckeditor", ckeditor);
VueRegisterElement("roxyfileman", roxyfileman);
VueRegisterElement("alt-button", altButton);
VueRegisterElement("alt-cell", altCell);
VueRegisterElement("alt-rt", altRT);
VueRegisterElement("alt-rt2", RT2);
VueRegisterElement("rt-pagination", RTPagination);
VueRegisterElement("rt2-tbody", RT2Tbody);
VueRegisterElement("rt-info", RTInfo);
VueRegisterElement("alt-column", altColumn);
VueRegisterElement("alt-column-search", altColumnSearch);
VueRegisterElement("select2", select2);
VueRegisterElement("multiselect2", multiselect2);
VueRegisterElement("rt-table", RTTable);
VueRegisterElement("rt-head", RTHead);
VueRegisterElement("rt-column", RTColumn);
VueRegisterElement("rt-body", RTBody);
VueRegisterElement("ace", Ace);
VueRegisterElement("alt-input", Input);
