module.exports =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "9e37");
/******/ })
/************************************************************************/
/******/ ({

/***/ "9e37":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  var i
  if ((i = window.document.currentScript) && (i = i.src.match(/(.+\/)[^/]+\.js$/))) {
    __webpack_require__.p = i[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ var setPublicPath = (null);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Input.vue?vue&type=template&id=3c7bb874&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return ((_vm.type)==='checkbox')?_c('input',_vm._b({directives:[{name:"model",rawName:"v-model",value:(_vm.localValue),expression:"localValue"}],staticClass:"form-control",attrs:{"type":"checkbox"},domProps:{"checked":Array.isArray(_vm.localValue)?_vm._i(_vm.localValue,null)>-1:(_vm.localValue)},on:{"input":_vm.onInput,"change":[function($event){var $$a=_vm.localValue,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.localValue=$$a.concat([$$v]))}else{$$i>-1&&(_vm.localValue=$$a.slice(0,$$i).concat($$a.slice($$i+1)))}}else{_vm.localValue=$$c}},_vm.onChange]}},'input',_vm.$props,false)):((_vm.type)==='radio')?_c('input',_vm._b({directives:[{name:"model",rawName:"v-model",value:(_vm.localValue),expression:"localValue"}],staticClass:"form-control",attrs:{"type":"radio"},domProps:{"checked":_vm._q(_vm.localValue,null)},on:{"input":_vm.onInput,"change":[function($event){_vm.localValue=null},_vm.onChange]}},'input',_vm.$props,false)):_c('input',_vm._b({directives:[{name:"model",rawName:"v-model",value:(_vm.localValue),expression:"localValue"}],staticClass:"form-control",attrs:{"type":_vm.type},domProps:{"value":(_vm.localValue)},on:{"input":[function($event){if($event.target.composing){ return; }_vm.localValue=$event.target.value},_vm.onInput],"change":_vm.onChange}},'input',_vm.$props,false))}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/Input.vue?vue&type=template&id=3c7bb874&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Input.vue?vue&type=script&lang=js&
//
//
//
//
/* harmony default export */ var Inputvue_type_script_lang_js_ = ({
  name: "bs-input",
  props: {
    required: Boolean,
    value: String,
    type: {
      type: String,
      default: "text"
    }
  },
  watch: {
    value: function value(_value) {
      this.localValue = this.value;
    }
  },
  data: function data() {
    return {
      localValue: this.value
    };
  },
  methods: {
    onInput: function onInput(evt) {
      if (evt.target.composing) return;
      this.localValue = evt.target.value;
      this.$emit("input", evt.target.value, evt);
    },
    onChange: function onChange(evt) {
      if (evt.target.composing) return;
      this.localValue = evt.target.value;
      this.$emit("change", evt.target.value, evt);
    }
  }
});
// CONCATENATED MODULE: ./src/Input.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Inputvue_type_script_lang_js_ = (Inputvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}

// CONCATENATED MODULE: ./src/Input.vue





/* normalize component */

var component = normalizeComponent(
  src_Inputvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

component.options.__file = "Input.vue"
/* harmony default export */ var Input = (component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/FormGroup.vue?vue&type=template&id=2905680c&
var FormGroupvue_type_template_id_2905680c_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"form-group"},[_vm._t("default")],2)}
var FormGroupvue_type_template_id_2905680c_staticRenderFns = []


// CONCATENATED MODULE: ./src/FormGroup.vue?vue&type=template&id=2905680c&

// CONCATENATED MODULE: ./src/FormGroup.vue

var script = {}


/* normalize component */

var FormGroup_component = normalizeComponent(
  script,
  FormGroupvue_type_template_id_2905680c_render,
  FormGroupvue_type_template_id_2905680c_staticRenderFns,
  false,
  null,
  null,
  null
  
)

FormGroup_component.options.__file = "FormGroup.vue"
/* harmony default export */ var FormGroup = (FormGroup_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/InputSelect.vue?vue&type=template&id=7e03cfdc&
var InputSelectvue_type_template_id_7e03cfdc_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"input-group"},[_c('div',{staticClass:"input-group-btn"},[_vm._m(0),_c('ul',{staticClass:"dropdown-menu"},_vm._l((_vm.options),function(o,index){return _c('li',{key:index},[_c('a',{attrs:{"href":"javascript:;"},domProps:{"textContent":_vm._s(o)},on:{"click":function($event){$event.preventDefault();_vm.itemSelected(o)}}})])}))]),_c('input',_vm._b({directives:[{name:"model",rawName:"v-model",value:(_vm.localValue),expression:"localValue"}],staticClass:"form-control",attrs:{"type":"text","name":_vm.name,"required":_vm.required},domProps:{"value":(_vm.localValue)},on:{"input":[function($event){if($event.target.composing){ return; }_vm.localValue=$event.target.value},_vm.onInput],"change":_vm.onChange}},'input',_vm.$props,false))])}
var InputSelectvue_type_template_id_7e03cfdc_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('button',{staticClass:"btn btn-default dropdown-toggle",attrs:{"type":"button","data-toggle":"dropdown","aria-haspopup":"true","aria-expanded":"false"}},[_c('i',{staticClass:"fa fa-caret-down"})])}]


// CONCATENATED MODULE: ./src/InputSelect.vue?vue&type=template&id=7e03cfdc&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/InputSelect.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ var InputSelectvue_type_script_lang_js_ = ({
  name: "bs-input-select",
  props: {
    required: Boolean,
    name: String,
    value: String,
    options: Object
  },
  data: function data() {
    return {
      localValue: this.value
    };
  },
  watch: {
    value: function value(_value) {
      this.localValue = this.value;
    }
  },
  created: function created() {
    console.log(this.$props);
  },
  methods: {
    itemSelected: function itemSelected(value) {
      this.localValue = value;
      this.$emit("input", value);
      this.$emit("change", value);
    },
    onInput: function onInput(evt) {
      this.localValue = evt.target.value;
      this.$emit("input", evt.target.value, evt);
    },
    onChange: function onChange(evt) {
      this.localValue = evt.target.value;
      this.$emit("change", evt.target.value, evt);
    }
  }
});
// CONCATENATED MODULE: ./src/InputSelect.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_InputSelectvue_type_script_lang_js_ = (InputSelectvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/InputSelect.vue





/* normalize component */

var InputSelect_component = normalizeComponent(
  src_InputSelectvue_type_script_lang_js_,
  InputSelectvue_type_template_id_7e03cfdc_render,
  InputSelectvue_type_template_id_7e03cfdc_staticRenderFns,
  false,
  null,
  null,
  null
  
)

InputSelect_component.options.__file = "InputSelect.vue"
/* harmony default export */ var InputSelect = (InputSelect_component.exports);
// CONCATENATED MODULE: ./node_modules/vue-register-element/src/index.js
const VueRegisterElement = function (name, component) {
    Vue = window.Vue;
    Vue.component(name, component);

    document.addEventListener("DOMContentLoaded", () => {

        var config = { attributes: true, childList: true, subtree: true };

        // Callback function to execute when mutations are observed
        var callback = function (mutationsList) {
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
    });


};

/* harmony default export */ var src = (VueRegisterElement);
// CONCATENATED MODULE: ./src/index.js




src("bs-input", Input);
src("bs-form-group", FormGroup);
src("bs-input-select", InputSelect);
// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js




/***/ })

/******/ });
//# sourceMappingURL=bs.common.js.map