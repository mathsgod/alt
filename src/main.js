import $ from 'jquery';
window.jQuery = $;
window.$ = $;
import "jquery-ui-dist/jquery-ui.min.js";

import "bootstrap";
import bootbox from "bootbox";
import "bootstrap-datepicker";
import "daterangepicker";
import "bootstrap-select";
import "select2";
import "@fancyapps/fancybox";

import PNotify from "pnotify/dist/pnotify";
import "pnotify/dist/pnotify.nonblock";
//import "datatables-all";
import "datatables-all/media/js/dataTables.bootstrap.min.js";
//import scroller from "datatables.net-scroller-dt";
//scroller(window, $);
window.bootbox = bootbox;

window.PNotify = PNotify;

PNotify.prototype.options.styling = "fontawesome";

import daterangepicker from "daterangepicker/daterangepicker.css";

//import WebAuthn from "WebAuthn.js";
//window.WebAuthn = WebAuthn;
//
//window.PNotify = PNotify;
//PNotify.defaults.styling = 'bootstrap3';
//PNotify.defaults.icons = 'bootstrap3';


