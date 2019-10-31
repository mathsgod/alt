import VueRegisterElement from "vue-register-element/dist/main.js";
import Box from "./Box.vue";

import Input from './Input.vue';
import AltBox from './AltBox.vue';
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


import altColumn from "./Column.vue";
import altColumnSearch from "./ColumnSearch.vue";
import RTTable from "./RTTable.vue";
import RTHead from "./RTHead.vue";
import RTBody from "./RTBody.vue";
import RTColumn from "./RTColumn.vue";

import Ace from "./Ace.vue";
import Datatables from "./Datatables.vue";

VueRegisterElement("alt-tab", altTab);
VueRegisterElement("alt-tab-pane", altTabPane);

VueRegisterElement("alt-grid", altGrid);
VueRegisterElement("alt-grid-section", altGridSection);

VueRegisterElement("box", Box);
VueRegisterElement("alt-box", AltBox);
VueRegisterElement("box-header", BoxHeader);
VueRegisterElement("alt-box-header", BoxHeader);
VueRegisterElement("alt-box-body", BoxBody);
VueRegisterElement("box-body", BoxBody);
VueRegisterElement("alt-box-footer", BoxFooter);
VueRegisterElement("box-footer", BoxFooter);
VueRegisterElement("alt-datatables", Datatables);
VueRegisterElement("alt-e", altE);
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

import RTInfo from "./RTInfo.vue";
import RTPageination from "./RTPagination.vue";
VueRegisterElement("rt-info", RTInfo);
VueRegisterElement("rt-pagination", RTPageination);

