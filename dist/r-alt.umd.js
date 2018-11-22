(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["r-alt"] = factory();
	else
		root["r-alt"] = factory();
})((typeof self !== 'undefined' ? self : this), function() {
return /******/ (function(modules) { // webpackBootstrap
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
/******/ 	return __webpack_require__(__webpack_require__.s = "4205");
/******/ })
/************************************************************************/
/******/ ({

/***/ "213e":
/***/ (function(module, exports) {

module.exports = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};


/***/ }),

/***/ "2eee":
/***/ (function(module, exports, __webpack_require__) {

// 7.1.1 ToPrimitive(input [, PreferredType])
var isObject = __webpack_require__("213e");
// instead of the ES6 spec version, we didn't implement @@toPrimitive case
// and the second argument - flag - preferred type is a string
module.exports = function (it, S) {
  if (!isObject(it)) return it;
  var fn, val;
  if (S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  if (typeof (fn = it.valueOf) == 'function' && !isObject(val = fn.call(it))) return val;
  if (!S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  throw TypeError("Can't convert object to primitive value");
};


/***/ }),

/***/ "4205":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  var i
  if ((i = window.document.currentScript) && (i = i.src.match(/(.+\/)[^/]+\.js$/))) {
    __webpack_require__.p = i[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ var setPublicPath = (null);

// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/cache-loader/dist/cjs.js??ref--0-0!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/vue-loader/lib??vue-loader-options!./src/ImgBox.vue?vue&type=template&id=f3a77410&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('form',[_c('input',{ref:"file",staticStyle:{"display":"none"},attrs:{"type":"file","accept":"image/*"},on:{"change":_vm.onSelectedFile}})]),_c('div',{staticClass:"btn-group"},[_c('button',{staticClass:"btn btn-xs btn-primary",on:{"click":_vm.onUpload}},[_c('i',{staticClass:"fa fa-upload"})]),(_vm.displayImage)?_c('button',{staticClass:"btn btn-xs btn-danger"},[_c('i',{staticClass:"fa fa-times",on:{"click":_vm.onDelete}})]):_vm._e()]),_c('div',[(_vm.loading)?_c('div',[_c('i',{staticClass:"fa fa-refresh fa-spin fa-2x fa-fw"}),_c('span',{staticClass:"sr-only"},[_vm._v("Loading...")])]):_vm._e(),(_vm.displayImage)?_c('img',{attrs:{"width":"100px","src":_vm.imgSrc}}):_vm._e()])])}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/ImgBox.vue?vue&type=template&id=f3a77410&

// EXTERNAL MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/core-js/modules/es6.function.name.js
var es6_function_name = __webpack_require__("b887");

// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib??ref--12-1!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/cache-loader/dist/cjs.js??ref--0-0!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/vue-loader/lib??vue-loader-options!./src/ImgBox.vue?vue&type=script&lang=js&

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
/* harmony default export */ var ImgBoxvue_type_script_lang_js_ = ({
  props: ["imgSrc", "uploadUri", "deleteUri", "hasImage", "imgWidth"],
  data: function data() {
    return {
      displayImage: true,
      loading: false
    };
  },
  created: function created() {
    if (this.$props.hasImage) {
      this.$data.displayImage = true;
    } else {
      this.$data.displayImage = false;
    }
  },
  methods: {
    onSelectedFile: function onSelectedFile() {
      if (!this.$props.uploadUri) {
        console.error("upload-uri not defined");
        return;
      }

      var formData = new FormData();
      var f = this.$refs.file;
      var file = f.files[0];
      formData.append("file", file, file.name);
      var xhr = new XMLHttpRequest();
      xhr.open("POST", this.$props.uploadUri, true); // Set up a handler for when the request finishes.

      var that = this;
      that.$data.displayImage = false;

      xhr.onload = function () {
        if (xhr.status === 200) {
          that.$data.loading = false;
          that.$data.displayImage = true;
        } else {
          alert("An error occurred!");
        }
      };

      this.$data.loading = true;
      xhr.send(formData);
    },
    onUpload: function onUpload() {
      this.$refs.file.click();
    },
    onDelete: function onDelete() {
      if (!this.$props.deleteUri) {
        console.error("delete-uri not defined");
        return;
      }

      var that = this;
      this.$data.displayImage = false;
      that.$data.loading = true;
      var xhr = new XMLHttpRequest();
      xhr.open("GET", this.$props.deleteUri, true);

      xhr.onload = function () {
        if (xhr.status === 200) {
          that.$data.loading = false;
        } else {
          alert("An error occurred!");
        }
      };

      xhr.send();
    }
  }
});
// CONCATENATED MODULE: ./src/ImgBox.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_ImgBoxvue_type_script_lang_js_ = (ImgBoxvue_type_script_lang_js_); 
// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/vue-loader/lib/runtime/componentNormalizer.js
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

// CONCATENATED MODULE: ./src/ImgBox.vue





/* normalize component */

var component = normalizeComponent(
  src_ImgBoxvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

component.options.__file = "ImgBox.vue"
/* harmony default export */ var ImgBox = (component.exports);
// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service-global/node_modules/@vue/cli-service/lib/commands/build/entry-lib.js


/* harmony default export */ var entry_lib = __webpack_exports__["default"] = (ImgBox);



/***/ }),

/***/ "72c8":
/***/ (function(module, exports, __webpack_require__) {

var anObject = __webpack_require__("f1d8");
var IE8_DOM_DEFINE = __webpack_require__("782d");
var toPrimitive = __webpack_require__("2eee");
var dP = Object.defineProperty;

exports.f = __webpack_require__("9ed9") ? Object.defineProperty : function defineProperty(O, P, Attributes) {
  anObject(O);
  P = toPrimitive(P, true);
  anObject(Attributes);
  if (IE8_DOM_DEFINE) try {
    return dP(O, P, Attributes);
  } catch (e) { /* empty */ }
  if ('get' in Attributes || 'set' in Attributes) throw TypeError('Accessors not supported!');
  if ('value' in Attributes) O[P] = Attributes.value;
  return O;
};


/***/ }),

/***/ "782d":
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__("9ed9") && !__webpack_require__("f680")(function () {
  return Object.defineProperty(__webpack_require__("8337")('div'), 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ "80d1":
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef


/***/ }),

/***/ "8337":
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__("213e");
var document = __webpack_require__("80d1").document;
// typeof document.createElement is 'object' in old IE
var is = isObject(document) && isObject(document.createElement);
module.exports = function (it) {
  return is ? document.createElement(it) : {};
};


/***/ }),

/***/ "9ed9":
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__("f680")(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ "b887":
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__("72c8").f;
var FProto = Function.prototype;
var nameRE = /^\s*function ([^ (]*)/;
var NAME = 'name';

// 19.2.4.2 name
NAME in FProto || __webpack_require__("9ed9") && dP(FProto, NAME, {
  configurable: true,
  get: function () {
    try {
      return ('' + this).match(nameRE)[1];
    } catch (e) {
      return '';
    }
  }
});


/***/ }),

/***/ "f1d8":
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__("213e");
module.exports = function (it) {
  if (!isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};


/***/ }),

/***/ "f680":
/***/ (function(module, exports) {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};


/***/ })

/******/ })["default"];
});
//# sourceMappingURL=r-alt.umd.js.map