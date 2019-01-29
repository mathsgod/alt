(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["alt-component"] = factory();
	else
		root["alt-component"] = factory();
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
/******/ 	return __webpack_require__(__webpack_require__.s = "8e4d");
/******/ })
/************************************************************************/
/******/ ({

/***/ "01f9":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var LIBRARY = __webpack_require__("2d00");
var $export = __webpack_require__("5ca1");
var redefine = __webpack_require__("2aba");
var hide = __webpack_require__("32e9");
var Iterators = __webpack_require__("84f2");
var $iterCreate = __webpack_require__("41a0");
var setToStringTag = __webpack_require__("7f20");
var getPrototypeOf = __webpack_require__("38fd");
var ITERATOR = __webpack_require__("2b4c")('iterator');
var BUGGY = !([].keys && 'next' in [].keys()); // Safari has buggy iterators w/o `next`
var FF_ITERATOR = '@@iterator';
var KEYS = 'keys';
var VALUES = 'values';

var returnThis = function () { return this; };

module.exports = function (Base, NAME, Constructor, next, DEFAULT, IS_SET, FORCED) {
  $iterCreate(Constructor, NAME, next);
  var getMethod = function (kind) {
    if (!BUGGY && kind in proto) return proto[kind];
    switch (kind) {
      case KEYS: return function keys() { return new Constructor(this, kind); };
      case VALUES: return function values() { return new Constructor(this, kind); };
    } return function entries() { return new Constructor(this, kind); };
  };
  var TAG = NAME + ' Iterator';
  var DEF_VALUES = DEFAULT == VALUES;
  var VALUES_BUG = false;
  var proto = Base.prototype;
  var $native = proto[ITERATOR] || proto[FF_ITERATOR] || DEFAULT && proto[DEFAULT];
  var $default = $native || getMethod(DEFAULT);
  var $entries = DEFAULT ? !DEF_VALUES ? $default : getMethod('entries') : undefined;
  var $anyNative = NAME == 'Array' ? proto.entries || $native : $native;
  var methods, key, IteratorPrototype;
  // Fix native
  if ($anyNative) {
    IteratorPrototype = getPrototypeOf($anyNative.call(new Base()));
    if (IteratorPrototype !== Object.prototype && IteratorPrototype.next) {
      // Set @@toStringTag to native iterators
      setToStringTag(IteratorPrototype, TAG, true);
      // fix for some old engines
      if (!LIBRARY && typeof IteratorPrototype[ITERATOR] != 'function') hide(IteratorPrototype, ITERATOR, returnThis);
    }
  }
  // fix Array#{values, @@iterator}.name in V8 / FF
  if (DEF_VALUES && $native && $native.name !== VALUES) {
    VALUES_BUG = true;
    $default = function values() { return $native.call(this); };
  }
  // Define iterator
  if ((!LIBRARY || FORCED) && (BUGGY || VALUES_BUG || !proto[ITERATOR])) {
    hide(proto, ITERATOR, $default);
  }
  // Plug for library
  Iterators[NAME] = $default;
  Iterators[TAG] = returnThis;
  if (DEFAULT) {
    methods = {
      values: DEF_VALUES ? $default : getMethod(VALUES),
      keys: IS_SET ? $default : getMethod(KEYS),
      entries: $entries
    };
    if (FORCED) for (key in methods) {
      if (!(key in proto)) redefine(proto, key, methods[key]);
    } else $export($export.P + $export.F * (BUGGY || VALUES_BUG), NAME, methods);
  }
  return methods;
};


/***/ }),

/***/ "09b9":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "0a49":
/***/ (function(module, exports, __webpack_require__) {

// 0 -> Array#forEach
// 1 -> Array#map
// 2 -> Array#filter
// 3 -> Array#some
// 4 -> Array#every
// 5 -> Array#find
// 6 -> Array#findIndex
var ctx = __webpack_require__("9b43");
var IObject = __webpack_require__("626a");
var toObject = __webpack_require__("4bf8");
var toLength = __webpack_require__("9def");
var asc = __webpack_require__("cd1c");
module.exports = function (TYPE, $create) {
  var IS_MAP = TYPE == 1;
  var IS_FILTER = TYPE == 2;
  var IS_SOME = TYPE == 3;
  var IS_EVERY = TYPE == 4;
  var IS_FIND_INDEX = TYPE == 6;
  var NO_HOLES = TYPE == 5 || IS_FIND_INDEX;
  var create = $create || asc;
  return function ($this, callbackfn, that) {
    var O = toObject($this);
    var self = IObject(O);
    var f = ctx(callbackfn, that, 3);
    var length = toLength(self.length);
    var index = 0;
    var result = IS_MAP ? create($this, length) : IS_FILTER ? create($this, 0) : undefined;
    var val, res;
    for (;length > index; index++) if (NO_HOLES || index in self) {
      val = self[index];
      res = f(val, index, O);
      if (TYPE) {
        if (IS_MAP) result[index] = res;   // map
        else if (res) switch (TYPE) {
          case 3: return true;             // some
          case 5: return val;              // find
          case 6: return index;            // findIndex
          case 2: result.push(val);        // filter
        } else if (IS_EVERY) return false; // every
      }
    }
    return IS_FIND_INDEX ? -1 : IS_SOME || IS_EVERY ? IS_EVERY : result;
  };
};


/***/ }),

/***/ "0d58":
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.14 / 15.2.3.14 Object.keys(O)
var $keys = __webpack_require__("ce10");
var enumBugKeys = __webpack_require__("e11e");

module.exports = Object.keys || function keys(O) {
  return $keys(O, enumBugKeys);
};


/***/ }),

/***/ "1169":
/***/ (function(module, exports, __webpack_require__) {

// 7.2.2 IsArray(argument)
var cof = __webpack_require__("2d95");
module.exports = Array.isArray || function isArray(arg) {
  return cof(arg) == 'Array';
};


/***/ }),

/***/ "11e9":
/***/ (function(module, exports, __webpack_require__) {

var pIE = __webpack_require__("52a7");
var createDesc = __webpack_require__("4630");
var toIObject = __webpack_require__("6821");
var toPrimitive = __webpack_require__("6a99");
var has = __webpack_require__("69a8");
var IE8_DOM_DEFINE = __webpack_require__("c69a");
var gOPD = Object.getOwnPropertyDescriptor;

exports.f = __webpack_require__("9e1e") ? gOPD : function getOwnPropertyDescriptor(O, P) {
  O = toIObject(O);
  P = toPrimitive(P, true);
  if (IE8_DOM_DEFINE) try {
    return gOPD(O, P);
  } catch (e) { /* empty */ }
  if (has(O, P)) return createDesc(!pIE.f.call(O, P), O[P]);
};


/***/ }),

/***/ "1495":
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__("86cc");
var anObject = __webpack_require__("cb7c");
var getKeys = __webpack_require__("0d58");

module.exports = __webpack_require__("9e1e") ? Object.defineProperties : function defineProperties(O, Properties) {
  anObject(O);
  var keys = getKeys(Properties);
  var length = keys.length;
  var i = 0;
  var P;
  while (length > i) dP.f(O, P = keys[i++], Properties[P]);
  return O;
};


/***/ }),

/***/ "18c1":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_node_modules_css_loader_index_js_ref_6_oneOf_1_1_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_lib_index_js_ref_6_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RT2_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("c1d0");
/* harmony import */ var _AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_node_modules_css_loader_index_js_ref_6_oneOf_1_1_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_lib_index_js_ref_6_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RT2_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_node_modules_css_loader_index_js_ref_6_oneOf_1_1_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_lib_index_js_ref_6_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RT2_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_node_modules_css_loader_index_js_ref_6_oneOf_1_1_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_lib_index_js_ref_6_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RT2_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "214f":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var hide = __webpack_require__("32e9");
var redefine = __webpack_require__("2aba");
var fails = __webpack_require__("79e5");
var defined = __webpack_require__("be13");
var wks = __webpack_require__("2b4c");

module.exports = function (KEY, length, exec) {
  var SYMBOL = wks(KEY);
  var fns = exec(defined, SYMBOL, ''[KEY]);
  var strfn = fns[0];
  var rxfn = fns[1];
  if (fails(function () {
    var O = {};
    O[SYMBOL] = function () { return 7; };
    return ''[KEY](O) != 7;
  })) {
    redefine(String.prototype, KEY, strfn);
    hide(RegExp.prototype, SYMBOL, length == 2
      // 21.2.5.8 RegExp.prototype[@@replace](string, replaceValue)
      // 21.2.5.11 RegExp.prototype[@@split](string, limit)
      ? function (string, arg) { return rxfn.call(string, this, arg); }
      // 21.2.5.6 RegExp.prototype[@@match](string)
      // 21.2.5.9 RegExp.prototype[@@search](string)
      : function (string) { return rxfn.call(string, this); }
    );
  }
};


/***/ }),

/***/ "230e":
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__("d3f4");
var document = __webpack_require__("7726").document;
// typeof document.createElement is 'object' in old IE
var is = isObject(document) && isObject(document.createElement);
module.exports = function (it) {
  return is ? document.createElement(it) : {};
};


/***/ }),

/***/ "2621":
/***/ (function(module, exports) {

exports.f = Object.getOwnPropertySymbols;


/***/ }),

/***/ "28a5":
/***/ (function(module, exports, __webpack_require__) {

// @@split logic
__webpack_require__("214f")('split', 2, function (defined, SPLIT, $split) {
  'use strict';
  var isRegExp = __webpack_require__("aae3");
  var _split = $split;
  var $push = [].push;
  var $SPLIT = 'split';
  var LENGTH = 'length';
  var LAST_INDEX = 'lastIndex';
  if (
    'abbc'[$SPLIT](/(b)*/)[1] == 'c' ||
    'test'[$SPLIT](/(?:)/, -1)[LENGTH] != 4 ||
    'ab'[$SPLIT](/(?:ab)*/)[LENGTH] != 2 ||
    '.'[$SPLIT](/(.?)(.?)/)[LENGTH] != 4 ||
    '.'[$SPLIT](/()()/)[LENGTH] > 1 ||
    ''[$SPLIT](/.?/)[LENGTH]
  ) {
    var NPCG = /()??/.exec('')[1] === undefined; // nonparticipating capturing group
    // based on es5-shim implementation, need to rework it
    $split = function (separator, limit) {
      var string = String(this);
      if (separator === undefined && limit === 0) return [];
      // If `separator` is not a regex, use native split
      if (!isRegExp(separator)) return _split.call(string, separator, limit);
      var output = [];
      var flags = (separator.ignoreCase ? 'i' : '') +
                  (separator.multiline ? 'm' : '') +
                  (separator.unicode ? 'u' : '') +
                  (separator.sticky ? 'y' : '');
      var lastLastIndex = 0;
      var splitLimit = limit === undefined ? 4294967295 : limit >>> 0;
      // Make `global` and avoid `lastIndex` issues by working with a copy
      var separatorCopy = new RegExp(separator.source, flags + 'g');
      var separator2, match, lastIndex, lastLength, i;
      // Doesn't need flags gy, but they don't hurt
      if (!NPCG) separator2 = new RegExp('^' + separatorCopy.source + '$(?!\\s)', flags);
      while (match = separatorCopy.exec(string)) {
        // `separatorCopy.lastIndex` is not reliable cross-browser
        lastIndex = match.index + match[0][LENGTH];
        if (lastIndex > lastLastIndex) {
          output.push(string.slice(lastLastIndex, match.index));
          // Fix browsers whose `exec` methods don't consistently return `undefined` for NPCG
          // eslint-disable-next-line no-loop-func
          if (!NPCG && match[LENGTH] > 1) match[0].replace(separator2, function () {
            for (i = 1; i < arguments[LENGTH] - 2; i++) if (arguments[i] === undefined) match[i] = undefined;
          });
          if (match[LENGTH] > 1 && match.index < string[LENGTH]) $push.apply(output, match.slice(1));
          lastLength = match[0][LENGTH];
          lastLastIndex = lastIndex;
          if (output[LENGTH] >= splitLimit) break;
        }
        if (separatorCopy[LAST_INDEX] === match.index) separatorCopy[LAST_INDEX]++; // Avoid an infinite loop
      }
      if (lastLastIndex === string[LENGTH]) {
        if (lastLength || !separatorCopy.test('')) output.push('');
      } else output.push(string.slice(lastLastIndex));
      return output[LENGTH] > splitLimit ? output.slice(0, splitLimit) : output;
    };
  // Chakra, V8
  } else if ('0'[$SPLIT](undefined, 0)[LENGTH]) {
    $split = function (separator, limit) {
      return separator === undefined && limit === 0 ? [] : _split.call(this, separator, limit);
    };
  }
  // 21.1.3.17 String.prototype.split(separator, limit)
  return [function split(separator, limit) {
    var O = defined(this);
    var fn = separator == undefined ? undefined : separator[SPLIT];
    return fn !== undefined ? fn.call(separator, O, limit) : $split.call(String(O), separator, limit);
  }, $split];
});


/***/ }),

/***/ "2aba":
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__("7726");
var hide = __webpack_require__("32e9");
var has = __webpack_require__("69a8");
var SRC = __webpack_require__("ca5a")('src');
var TO_STRING = 'toString';
var $toString = Function[TO_STRING];
var TPL = ('' + $toString).split(TO_STRING);

__webpack_require__("8378").inspectSource = function (it) {
  return $toString.call(it);
};

(module.exports = function (O, key, val, safe) {
  var isFunction = typeof val == 'function';
  if (isFunction) has(val, 'name') || hide(val, 'name', key);
  if (O[key] === val) return;
  if (isFunction) has(val, SRC) || hide(val, SRC, O[key] ? '' + O[key] : TPL.join(String(key)));
  if (O === global) {
    O[key] = val;
  } else if (!safe) {
    delete O[key];
    hide(O, key, val);
  } else if (O[key]) {
    O[key] = val;
  } else {
    hide(O, key, val);
  }
// add fake Function#toString for correct work wrapped methods / constructors with methods like LoDash isNative
})(Function.prototype, TO_STRING, function toString() {
  return typeof this == 'function' && this[SRC] || $toString.call(this);
});


/***/ }),

/***/ "2aeb":
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.2 / 15.2.3.5 Object.create(O [, Properties])
var anObject = __webpack_require__("cb7c");
var dPs = __webpack_require__("1495");
var enumBugKeys = __webpack_require__("e11e");
var IE_PROTO = __webpack_require__("613b")('IE_PROTO');
var Empty = function () { /* empty */ };
var PROTOTYPE = 'prototype';

// Create object with fake `null` prototype: use iframe Object with cleared prototype
var createDict = function () {
  // Thrash, waste and sodomy: IE GC bug
  var iframe = __webpack_require__("230e")('iframe');
  var i = enumBugKeys.length;
  var lt = '<';
  var gt = '>';
  var iframeDocument;
  iframe.style.display = 'none';
  __webpack_require__("fab2").appendChild(iframe);
  iframe.src = 'javascript:'; // eslint-disable-line no-script-url
  // createDict = iframe.contentWindow.Object;
  // html.removeChild(iframe);
  iframeDocument = iframe.contentWindow.document;
  iframeDocument.open();
  iframeDocument.write(lt + 'script' + gt + 'document.F=Object' + lt + '/script' + gt);
  iframeDocument.close();
  createDict = iframeDocument.F;
  while (i--) delete createDict[PROTOTYPE][enumBugKeys[i]];
  return createDict();
};

module.exports = Object.create || function create(O, Properties) {
  var result;
  if (O !== null) {
    Empty[PROTOTYPE] = anObject(O);
    result = new Empty();
    Empty[PROTOTYPE] = null;
    // add "__proto__" for Object.getPrototypeOf polyfill
    result[IE_PROTO] = O;
  } else result = createDict();
  return Properties === undefined ? result : dPs(result, Properties);
};


/***/ }),

/***/ "2b4c":
/***/ (function(module, exports, __webpack_require__) {

var store = __webpack_require__("5537")('wks');
var uid = __webpack_require__("ca5a");
var Symbol = __webpack_require__("7726").Symbol;
var USE_SYMBOL = typeof Symbol == 'function';

var $exports = module.exports = function (name) {
  return store[name] || (store[name] =
    USE_SYMBOL && Symbol[name] || (USE_SYMBOL ? Symbol : uid)('Symbol.' + name));
};

$exports.store = store;


/***/ }),

/***/ "2d00":
/***/ (function(module, exports) {

module.exports = false;


/***/ }),

/***/ "2d95":
/***/ (function(module, exports) {

var toString = {}.toString;

module.exports = function (it) {
  return toString.call(it).slice(8, -1);
};


/***/ }),

/***/ "2f21":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var fails = __webpack_require__("79e5");

module.exports = function (method, arg) {
  return !!method && fails(function () {
    // eslint-disable-next-line no-useless-call
    arg ? method.call(null, function () { /* empty */ }, 1) : method.call(null);
  });
};


/***/ }),

/***/ "32e9":
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__("86cc");
var createDesc = __webpack_require__("4630");
module.exports = __webpack_require__("9e1e") ? function (object, key, value) {
  return dP.f(object, key, createDesc(1, value));
} : function (object, key, value) {
  object[key] = value;
  return object;
};


/***/ }),

/***/ "386b":
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__("5ca1");
var fails = __webpack_require__("79e5");
var defined = __webpack_require__("be13");
var quot = /"/g;
// B.2.3.2.1 CreateHTML(string, tag, attribute, value)
var createHTML = function (string, tag, attribute, value) {
  var S = String(defined(string));
  var p1 = '<' + tag;
  if (attribute !== '') p1 += ' ' + attribute + '="' + String(value).replace(quot, '&quot;') + '"';
  return p1 + '>' + S + '</' + tag + '>';
};
module.exports = function (NAME, exec) {
  var O = {};
  O[NAME] = exec(createHTML);
  $export($export.P + $export.F * fails(function () {
    var test = ''[NAME]('"');
    return test !== test.toLowerCase() || test.split('"').length > 3;
  }), 'String', O);
};


/***/ }),

/***/ "386d":
/***/ (function(module, exports, __webpack_require__) {

// @@search logic
__webpack_require__("214f")('search', 1, function (defined, SEARCH, $search) {
  // 21.1.3.15 String.prototype.search(regexp)
  return [function search(regexp) {
    'use strict';
    var O = defined(this);
    var fn = regexp == undefined ? undefined : regexp[SEARCH];
    return fn !== undefined ? fn.call(regexp, O) : new RegExp(regexp)[SEARCH](String(O));
  }, $search];
});


/***/ }),

/***/ "38fd":
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.9 / 15.2.3.2 Object.getPrototypeOf(O)
var has = __webpack_require__("69a8");
var toObject = __webpack_require__("4bf8");
var IE_PROTO = __webpack_require__("613b")('IE_PROTO');
var ObjectProto = Object.prototype;

module.exports = Object.getPrototypeOf || function (O) {
  O = toObject(O);
  if (has(O, IE_PROTO)) return O[IE_PROTO];
  if (typeof O.constructor == 'function' && O instanceof O.constructor) {
    return O.constructor.prototype;
  } return O instanceof Object ? ObjectProto : null;
};


/***/ }),

/***/ "41a0":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var create = __webpack_require__("2aeb");
var descriptor = __webpack_require__("4630");
var setToStringTag = __webpack_require__("7f20");
var IteratorPrototype = {};

// 25.1.2.1.1 %IteratorPrototype%[@@iterator]()
__webpack_require__("32e9")(IteratorPrototype, __webpack_require__("2b4c")('iterator'), function () { return this; });

module.exports = function (Constructor, NAME, next) {
  Constructor.prototype = create(IteratorPrototype, { next: descriptor(1, next) });
  setToStringTag(Constructor, NAME + ' Iterator');
};


/***/ }),

/***/ "42d3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_node_modules_css_loader_index_js_ref_6_oneOf_1_1_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_lib_index_js_ref_6_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RT_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("09b9");
/* harmony import */ var _AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_node_modules_css_loader_index_js_ref_6_oneOf_1_1_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_lib_index_js_ref_6_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RT_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_node_modules_css_loader_index_js_ref_6_oneOf_1_1_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_lib_index_js_ref_6_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RT_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_node_modules_css_loader_index_js_ref_6_oneOf_1_1_AppData_Roaming_npm_node_modules_vue_cli_service_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_lib_index_js_ref_6_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_0_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RT_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "4588":
/***/ (function(module, exports) {

// 7.1.4 ToInteger
var ceil = Math.ceil;
var floor = Math.floor;
module.exports = function (it) {
  return isNaN(it = +it) ? 0 : (it > 0 ? floor : ceil)(it);
};


/***/ }),

/***/ "4630":
/***/ (function(module, exports) {

module.exports = function (bitmap, value) {
  return {
    enumerable: !(bitmap & 1),
    configurable: !(bitmap & 2),
    writable: !(bitmap & 4),
    value: value
  };
};


/***/ }),

/***/ "4bf8":
/***/ (function(module, exports, __webpack_require__) {

// 7.1.13 ToObject(argument)
var defined = __webpack_require__("be13");
module.exports = function (it) {
  return Object(defined(it));
};


/***/ }),

/***/ "52a7":
/***/ (function(module, exports) {

exports.f = {}.propertyIsEnumerable;


/***/ }),

/***/ "5537":
/***/ (function(module, exports, __webpack_require__) {

var core = __webpack_require__("8378");
var global = __webpack_require__("7726");
var SHARED = '__core-js_shared__';
var store = global[SHARED] || (global[SHARED] = {});

(module.exports = function (key, value) {
  return store[key] || (store[key] = value !== undefined ? value : {});
})('versions', []).push({
  version: core.version,
  mode: __webpack_require__("2d00") ? 'pure' : 'global',
  copyright: '© 2018 Denis Pushkarev (zloirock.ru)'
});


/***/ }),

/***/ "55dd":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $export = __webpack_require__("5ca1");
var aFunction = __webpack_require__("d8e8");
var toObject = __webpack_require__("4bf8");
var fails = __webpack_require__("79e5");
var $sort = [].sort;
var test = [1, 2, 3];

$export($export.P + $export.F * (fails(function () {
  // IE8-
  test.sort(undefined);
}) || !fails(function () {
  // V8 bug
  test.sort(null);
  // Old WebKit
}) || !__webpack_require__("2f21")($sort)), 'Array', {
  // 22.1.3.25 Array.prototype.sort(comparefn)
  sort: function sort(comparefn) {
    return comparefn === undefined
      ? $sort.call(toObject(this))
      : $sort.call(toObject(this), aFunction(comparefn));
  }
});


/***/ }),

/***/ "5ca1":
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__("7726");
var core = __webpack_require__("8378");
var hide = __webpack_require__("32e9");
var redefine = __webpack_require__("2aba");
var ctx = __webpack_require__("9b43");
var PROTOTYPE = 'prototype';

var $export = function (type, name, source) {
  var IS_FORCED = type & $export.F;
  var IS_GLOBAL = type & $export.G;
  var IS_STATIC = type & $export.S;
  var IS_PROTO = type & $export.P;
  var IS_BIND = type & $export.B;
  var target = IS_GLOBAL ? global : IS_STATIC ? global[name] || (global[name] = {}) : (global[name] || {})[PROTOTYPE];
  var exports = IS_GLOBAL ? core : core[name] || (core[name] = {});
  var expProto = exports[PROTOTYPE] || (exports[PROTOTYPE] = {});
  var key, own, out, exp;
  if (IS_GLOBAL) source = name;
  for (key in source) {
    // contains in native
    own = !IS_FORCED && target && target[key] !== undefined;
    // export native or passed
    out = (own ? target : source)[key];
    // bind timers to global for call from export context
    exp = IS_BIND && own ? ctx(out, global) : IS_PROTO && typeof out == 'function' ? ctx(Function.call, out) : out;
    // extend global
    if (target) redefine(target, key, out, type & $export.U);
    // export
    if (exports[key] != out) hide(exports, key, exp);
    if (IS_PROTO && expProto[key] != out) expProto[key] = out;
  }
};
global.core = core;
// type bitmap
$export.F = 1;   // forced
$export.G = 2;   // global
$export.S = 4;   // static
$export.P = 8;   // proto
$export.B = 16;  // bind
$export.W = 32;  // wrap
$export.U = 64;  // safe
$export.R = 128; // real proto method for `library`
module.exports = $export;


/***/ }),

/***/ "5dbc":
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__("d3f4");
var setPrototypeOf = __webpack_require__("8b97").set;
module.exports = function (that, target, C) {
  var S = target.constructor;
  var P;
  if (S !== C && typeof S == 'function' && (P = S.prototype) !== C.prototype && isObject(P) && setPrototypeOf) {
    setPrototypeOf(that, P);
  } return that;
};


/***/ }),

/***/ "613b":
/***/ (function(module, exports, __webpack_require__) {

var shared = __webpack_require__("5537")('keys');
var uid = __webpack_require__("ca5a");
module.exports = function (key) {
  return shared[key] || (shared[key] = uid(key));
};


/***/ }),

/***/ "626a":
/***/ (function(module, exports, __webpack_require__) {

// fallback for non-array-like ES3 and non-enumerable old V8 strings
var cof = __webpack_require__("2d95");
// eslint-disable-next-line no-prototype-builtins
module.exports = Object('z').propertyIsEnumerable(0) ? Object : function (it) {
  return cof(it) == 'String' ? it.split('') : Object(it);
};


/***/ }),

/***/ "6821":
/***/ (function(module, exports, __webpack_require__) {

// to indexed object, toObject with fallback for non-array-like ES3 strings
var IObject = __webpack_require__("626a");
var defined = __webpack_require__("be13");
module.exports = function (it) {
  return IObject(defined(it));
};


/***/ }),

/***/ "69a8":
/***/ (function(module, exports) {

var hasOwnProperty = {}.hasOwnProperty;
module.exports = function (it, key) {
  return hasOwnProperty.call(it, key);
};


/***/ }),

/***/ "6a99":
/***/ (function(module, exports, __webpack_require__) {

// 7.1.1 ToPrimitive(input [, PreferredType])
var isObject = __webpack_require__("d3f4");
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

/***/ "6bde":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return _typeof; });
function _typeof2(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof2 = function _typeof2(obj) { return typeof obj; }; } else { _typeof2 = function _typeof2(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof2(obj); }

function _typeof(obj) {
  if (typeof Symbol === "function" && _typeof2(Symbol.iterator) === "symbol") {
    _typeof = function _typeof(obj) {
      return _typeof2(obj);
    };
  } else {
    _typeof = function _typeof(obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : _typeof2(obj);
    };
  }

  return _typeof(obj);
}

/***/ }),

/***/ "6d2c":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var core_js_modules_es6_regexp_search__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("386d");
/* harmony import */ var core_js_modules_es6_regexp_search__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_regexp_search__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es6_regexp_split__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__("28a5");
/* harmony import */ var core_js_modules_es6_regexp_split__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_regexp_split__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var C_Users_maths_Desktop_web_alt_alt_component_node_modules_babel_runtime_helpers_builtin_es6_typeof__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__("6bde");
/* harmony import */ var C_Users_maths_Desktop_web_alt_alt_component_node_modules_babel_runtime_helpers_builtin_es6_objectSpread__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__("c93e");
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__("ac6a");
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__("7f7f");
/* harmony import */ var core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_function_name__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_es6_object_assign__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__("f751");
/* harmony import */ var core_js_modules_es6_object_assign__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_object_assign__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var core_js_modules_es6_number_constructor__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__("c5f6");
/* harmony import */ var core_js_modules_es6_number_constructor__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es6_number_constructor__WEBPACK_IMPORTED_MODULE_7__);








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
/* harmony default export */ __webpack_exports__["a"] = ({
  name: "alt-rt2",
  props: {
    ajax: {
      type: Object,
      default: {}
    },
    pageLength: {
      type: Number,
      default: 10
    },
    cellUrl: String,
    selectable: Boolean,
    buttons: {
      type: Array,
      default: function _default() {
        return [];
      }
    },
    exports: {
      type: Array,
      default: function _default() {
        return [];
      }
    }
  },
  data: function data() {
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
  created: function created() {
    var _this = this;

    var storage = this.storage;

    if (storage.responsive) {
      this.responsive = storage.responsive;
    }

    if (storage.pageLength) {
      this.local.pageLength = parseInt(storage.pageLength);
    }

    if (storage.order) {
      this.local.order = storage.order;
    }

    this.columns = this.$attrs.columns.map(function (o) {
      o.hide = false;
      o.orderDir = "";
      o.isVisible = true;
      storage.columns = Object.assign({}, storage.columns);
      var s;

      if (s = storage.columns[o.name]) {
        if (s.isVisible === false) {
          o.isVisible = false;
        }
      }

      _this.local.order.forEach(function (ord) {
        if (ord.name == o.name) {
          o.orderDir = ord.dir;
        }
      });

      return new Vue({
        data: o,
        methods: {
          cell: function cell(d) {
            var cell = {
              type: "text"
            };

            if (this.cellStyle) {
              cell.style = Object(C_Users_maths_Desktop_web_alt_alt_component_node_modules_babel_runtime_helpers_builtin_es6_objectSpread__WEBPACK_IMPORTED_MODULE_3__[/* default */ "a"])({}, cell.style, this.cellStyle);
            }

            if (this.wrap) {
              cell.divStyle = {
                "word-wrap": "break-word",
                "white-space": "pre-wrap"
              };
            }

            if (d[this.name] == null) {
              return cell;
            } else if (typeof d[this.name] == "string") {
              cell.type = "text";
            } else {
              for (var i in d[this.name]) {
                cell[i] = d[this.name][i];
              }
            }

            return cell;
          },
          isDisplay: function isDisplay() {
            return this.isVisible && !this.hide;
          },
          getContent: function getContent(d) {
            var o = d[this.name];
            if (o === null) return "";

            if (Array.isArray(o)) {
              return o.join(" ");
            }

            if (Object(C_Users_maths_Desktop_web_alt_alt_component_node_modules_babel_runtime_helpers_builtin_es6_typeof__WEBPACK_IMPORTED_MODULE_2__[/* default */ "a"])(o) == "object") {
              return o.content;
            }

            return o;
          },
          getValue: function getValue(d) {
            var o = d[this.name];
            if (!o) return "";

            if (Object(C_Users_maths_Desktop_web_alt_alt_component_node_modules_babel_runtime_helpers_builtin_es6_typeof__WEBPACK_IMPORTED_MODULE_2__[/* default */ "a"])(o) == "object") {
              if (o.type == "raw") {
                return o.content;
              } else {
                return o.value;
              }
            }

            return o;
          },
          toggleVisible: function toggleVisible() {
            this.isVisible = !this.isVisible;
            this.$emit("toggleVisible");
          }
        }
      });
    });
    this.columns.forEach(function (column) {
      column.$on("toggleVisible", function () {
        var storage = _this.storage;
        storage.columns = storage.columns || {};
        storage.columns[column.name] = storage.columns[column.name] || {};
        storage.columns[column.name] = Object.assign(storage.columns[column.name], {
          isVisible: column.isVisible
        });
        _this.storage = storage;
      });
    });
  },
  mounted: function mounted() {
    if (this.ajax.url) {
      this.draw();
    }

    window.addEventListener("resize", this.resize);
  },
  computed: {
    visibleColumns: function visibleColumns() {
      return this.columns.filter(function (column) {
        return column.isDisplay();
      });
    },
    columnsHasTitle: function columnsHasTitle() {
      return this.columns.filter(function (column) {
        return column.title;
      });
    },
    storage: {
      get: function get() {
        var id = this.ajax.url.split("/").filter(function (s) {
          return !Number(s);
        }).join("/");
        return $.localStorage.get(id) || {};
      },
      set: function set(data) {
        var id = this.ajax.url.split("/").filter(function (s) {
          return !Number(s);
        }).join("/");
        $.localStorage.set(id, data);
      }
    },
    pageCount: function pageCount() {
      return Math.ceil(this.total / this.local.pageLength);
    },
    info: function info() {
      return {
        from: (this.page - 1) * this.local.pageLength + 1,
        to: Math.min(this.page * this.local.pageLength, this.total),
        total: this.total
      };
    },
    isSearchable: function isSearchable() {
      return this.columns.some(function (o) {
        return o.searchable;
      });
    },
    hasHideColumn: function hasHideColumn() {
      return this.columns.some(function (o) {
        return o.hide;
      });
    }
  },
  methods: {
    onClickButton: function onClickButton(button) {
      var e = button.action + "(this);";
      eval(e);
    },
    hasExport: function hasExport() {
      return this.exports.count > 0;
    },
    exportFile: function exportFile(type) {
      var _this2 = this;

      this.local.draw++;
      this.$http.get(this.ajax.url, {
        params: {
          _rt: 1,
          draw: this.local.draw,
          columns: this.columns.map(function (o) {
            return {
              name: o.name,
              search: {
                value: _this2.local.search[o.name]
              },
              searchMethod: o.searchMethod
            };
          }),
          order: this.local.order,
          search: this.searchData,
          type: type
        },
        responseType: "arraybuffer"
      }).then(function (response) {
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
    updateData: function updateData(key, field, value) {
      if (!this.cellUrl) {
        console.log("cell-url not found");
        return;
      }

      this.$http.post(this.cellUrl, {
        _pk: key,
        name: field,
        value: value
      }).then(function (resp) {
        console.log("done");
      });
    },
    resetLocaStorage: function resetLocaStorage() {
      var id = this.ajax.url.split("/").filter(function (s) {
        return !Number(s);
      }).join("/");
      $.localStorage.remove(id);
      this.draw();
    },
    toggleResponsive: function toggleResponsive() {
      this.responsive = !this.responsive; //save

      var storage = this.storage;
      storage.responsive = this.responsive;
      this.storage = storage;
      this.resize();
    },
    toggleChild: function toggleChild() {
      this.showChild = !this.showChild;

      if (this.showChild) {
        this.$refs.tbody.showAllChild();
      } else {
        this.$refs.tbody.hideAllChild();
      }

      this.$forceUpdate();
    },
    getColumn: function getColumn(index) {
      return this.columns[index];
    },
    onChangePageLength: function onChangePageLength() {
      //save
      var storage = this.storage;
      storage.pageLength = this.local.pageLength;
      this.storage = storage;
      this.page = 1;
      this.draw();
    },
    gotoPage: function gotoPage(page) {
      this.page = parseInt(page);
      this.draw();
    },
    firstPage: function firstPage() {
      this.page = 1;
      this.draw();
    },
    nextPage: function nextPage() {
      this.page++;
      this.draw();
    },
    prevPage: function prevPage() {
      this.page--;
      this.draw();
    },
    lastPage: function lastPage() {
      this.page = this.pageCount;
      this.draw();
    },
    data: function data() {
      return this.remoteData;
    },
    order: function order(o) {
      var storage = this.storage;
      this.local.order = [{
        name: o[0],
        dir: o[1]
      }];
      this.columns.forEach(function (c) {
        if (c.name != o[0]) {
          c.orderDir = "";
        } else {
          c.orderDir = o[1];
        }
      });
      storage.order = this.local.order;
      this.storage = storage;
      return this;
    },
    draw: function draw() {
      var _this3 = this;

      this.$refs.box.showLoading();
      this.local.draw++;
      Vue.http.get(this.ajax.url, {
        params: {
          _rt: 1,
          draw: this.local.draw,
          columns: this.columns.map(function (o) {
            return {
              name: o.name,
              search: {
                value: _this3.local.search[o.name]
              },
              searchMethod: o.searchMethod
            };
          }),
          order: this.local.order,
          page: this.page,
          length: this.local.pageLength
        }
      }).then(function (resp) {
        _this3.$refs.box.hideLoading();

        try {
          if (resp.data.draw < _this3.local.draw) {
            return;
          }

          _this3.remoteData = resp.data.data;
          _this3.total = resp.data.total;

          _this3.resize();
        } catch (e) {
          alert(e.message);
        }
      });
    },
    search: function search(name, value) {
      this.page = 1;
      this.local.search[name] = value;
      this.$emit("search", this.local.search);
      this.draw();
      return this;
    },
    resize: function resize() {
      var _this4 = this;

      this.columns.forEach(function (c) {
        c.hide = false;
      });
      if (!this.responsive) return;
      this.$nextTick(function () {
        //console.log("--");
        var parentWidth = _this4.$refs.table.parentElement.offsetWidth; //console.log("parentWidth", parentWidth);

        var total = function total() {
          var total = 0;

          _this4.columns.forEach(function (c, i) {
            var c_el = _this4.$refs.column[i];

            if (c_el) {
              total += c_el.$el.offsetWidth;
            }
          });

          if (_this4.columns.some(function (c) {
            return c.hide;
          })) {
            total += 32;
          }

          return total;
        };

        var hideLastColumn = function hideLastColumn() {
          var columns = _this4.columns.filter(function (c) {
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

        var check = function check() {
          var t = total(); //console.log(t);

          if (t > parentWidth) {
            var r = hideLastColumn();

            if (r) {
              _this4.$nextTick(function () {
                check();
              });
            }
          }
        };

        check();
      });
    }
  }
});

/***/ }),

/***/ "7333":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// 19.1.2.1 Object.assign(target, source, ...)
var getKeys = __webpack_require__("0d58");
var gOPS = __webpack_require__("2621");
var pIE = __webpack_require__("52a7");
var toObject = __webpack_require__("4bf8");
var IObject = __webpack_require__("626a");
var $assign = Object.assign;

// should work with symbols and should have deterministic property order (V8 bug)
module.exports = !$assign || __webpack_require__("79e5")(function () {
  var A = {};
  var B = {};
  // eslint-disable-next-line no-undef
  var S = Symbol();
  var K = 'abcdefghijklmnopqrst';
  A[S] = 7;
  K.split('').forEach(function (k) { B[k] = k; });
  return $assign({}, A)[S] != 7 || Object.keys($assign({}, B)).join('') != K;
}) ? function assign(target, source) { // eslint-disable-line no-unused-vars
  var T = toObject(target);
  var aLen = arguments.length;
  var index = 1;
  var getSymbols = gOPS.f;
  var isEnum = pIE.f;
  while (aLen > index) {
    var S = IObject(arguments[index++]);
    var keys = getSymbols ? getKeys(S).concat(getSymbols(S)) : getKeys(S);
    var length = keys.length;
    var j = 0;
    var key;
    while (length > j) if (isEnum.call(S, key = keys[j++])) T[key] = S[key];
  } return T;
} : $assign;


/***/ }),

/***/ "7514":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// 22.1.3.8 Array.prototype.find(predicate, thisArg = undefined)
var $export = __webpack_require__("5ca1");
var $find = __webpack_require__("0a49")(5);
var KEY = 'find';
var forced = true;
// Shouldn't skip holes
if (KEY in []) Array(1)[KEY](function () { forced = false; });
$export($export.P + $export.F * forced, 'Array', {
  find: function find(callbackfn /* , that = undefined */) {
    return $find(this, callbackfn, arguments.length > 1 ? arguments[1] : undefined);
  }
});
__webpack_require__("9c6c")(KEY);


/***/ }),

/***/ "7726":
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef


/***/ }),

/***/ "77f1":
/***/ (function(module, exports, __webpack_require__) {

var toInteger = __webpack_require__("4588");
var max = Math.max;
var min = Math.min;
module.exports = function (index, length) {
  index = toInteger(index);
  return index < 0 ? max(index + length, 0) : min(index, length);
};


/***/ }),

/***/ "79e5":
/***/ (function(module, exports) {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};


/***/ }),

/***/ "7f20":
/***/ (function(module, exports, __webpack_require__) {

var def = __webpack_require__("86cc").f;
var has = __webpack_require__("69a8");
var TAG = __webpack_require__("2b4c")('toStringTag');

module.exports = function (it, tag, stat) {
  if (it && !has(it = stat ? it : it.prototype, TAG)) def(it, TAG, { configurable: true, value: tag });
};


/***/ }),

/***/ "7f7f":
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__("86cc").f;
var FProto = Function.prototype;
var nameRE = /^\s*function ([^ (]*)/;
var NAME = 'name';

// 19.2.4.2 name
NAME in FProto || __webpack_require__("9e1e") && dP(FProto, NAME, {
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

/***/ "8378":
/***/ (function(module, exports) {

var core = module.exports = { version: '2.5.7' };
if (typeof __e == 'number') __e = core; // eslint-disable-line no-undef


/***/ }),

/***/ "84f2":
/***/ (function(module, exports) {

module.exports = {};


/***/ }),

/***/ "86cc":
/***/ (function(module, exports, __webpack_require__) {

var anObject = __webpack_require__("cb7c");
var IE8_DOM_DEFINE = __webpack_require__("c69a");
var toPrimitive = __webpack_require__("6a99");
var dP = Object.defineProperty;

exports.f = __webpack_require__("9e1e") ? Object.defineProperty : function defineProperty(O, P, Attributes) {
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

/***/ "8b97":
/***/ (function(module, exports, __webpack_require__) {

// Works with __proto__ only. Old v8 can't work with null proto objects.
/* eslint-disable no-proto */
var isObject = __webpack_require__("d3f4");
var anObject = __webpack_require__("cb7c");
var check = function (O, proto) {
  anObject(O);
  if (!isObject(proto) && proto !== null) throw TypeError(proto + ": can't set as prototype!");
};
module.exports = {
  set: Object.setPrototypeOf || ('__proto__' in {} ? // eslint-disable-line
    function (test, buggy, set) {
      try {
        set = __webpack_require__("9b43")(Function.call, __webpack_require__("11e9").f(Object.prototype, '__proto__').set, 2);
        set(test, []);
        buggy = !(test instanceof Array);
      } catch (e) { buggy = true; }
      return function setPrototypeOf(O, proto) {
        check(O, proto);
        if (buggy) O.__proto__ = proto;
        else set(O, proto);
        return O;
      };
    }({}, false) : undefined),
  check: check
};


/***/ }),

/***/ "8e4d":
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
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Input.vue?vue&type=template&id=5b7fe0e7&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('input',_vm._b({staticClass:"form-control",attrs:{"name":_vm.name,"type":_vm.type},domProps:{"value":_vm.value},on:{"input":_vm.onInput}},'input',_vm.$props,false))}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/Input.vue?vue&type=template&id=5b7fe0e7&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Input.vue?vue&type=script&lang=js&
//
//
//
/* harmony default export */ var Inputvue_type_script_lang_js_ = ({
  name: "alt-input",
  props: {
    type: String,
    name: String,
    required: Boolean,
    value: String
  },
  mounted: function mounted() {
    if (this.required) {
      $(this.$el).after('<i class="fa fa-asterisk form-control-feedback" style="top:10px"></i>');
      $(this.$el).closest(".form-group").addClass("has-feedback");

      if ($(this.$el).closest(".form-group").length == 0) {
        $(this.$el).css("margin-bottom", "0px");
        $(this.$el).addClass("form-group has-feedback");
      } //   $(this.$el).closest("form").validate()

    }
  },
  methods: {
    onInput: function onInput(event) {
      this.$emit("input", event.target.value); //this.$emit("input",);
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
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Box.vue?vue&type=template&id=0c080f06&
var Boxvue_type_template_id_0c080f06_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"box",class:{'collapsed-box':_vm.collapsed}},[(_vm.loading)?_c('div',{staticClass:"overlay"},[_c('i',{staticClass:"fa fa-spin fa-sync-alt"})]):_vm._e(),_vm._t("default")],2)}
var Boxvue_type_template_id_0c080f06_staticRenderFns = []


// CONCATENATED MODULE: ./src/Box.vue?vue&type=template&id=0c080f06&

// EXTERNAL MODULE: ./node_modules/core-js/modules/web.dom.iterable.js
var web_dom_iterable = __webpack_require__("ac6a");

// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/builtin/es6/defineProperty.js
var defineProperty = __webpack_require__("a322");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Box.vue?vue&type=script&lang=js&


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

/* eslint-disable */
/* harmony default export */ var Boxvue_type_script_lang_js_ = ({
  name: "alt-box",
  data: function data() {
    return {
      loading: false,
      isPinned: true
    };
  },
  props: Object(defineProperty["a" /* default */])({
    collapsible: Boolean,
    collapsed: Boolean,
    closeable: Boolean,
    pinable: Boolean,
    acl: Boolean,
    aclGroup: Array,
    dataUri: String,
    dataAclUri: String,
    dataUrl: String
  }, "dataUri", String),
  computed: {
    header: function header() {
      return this.$children.filter(function (o) {
        return o.$vnode.componentOptions.tag == "alt-box-header";
      });
    },
    body: function body() {
      return this.$slots.default.filter(function (o) {
        if (o.componentOptions == undefined) return false;
        return o.componentOptions.tag == "alt-box-body";
      }).map(function (o) {
        return o.componentInstance;
      });
    },
    footer: function footer() {
      return this.$slots.default.filter(function (o) {
        if (o.componentOptions == undefined) return false;
        return o.componentOptions.tag == "alt-box-footer";
      }).map(function (o) {
        return o.componentInstance;
      });
    }
  },
  mounted: function mounted() {
    var _this = this;

    this.header.forEach(function (h) {
      h.collapsible = _this.collapsible;
      h.closeable = _this.closeable;
      h.collapsed = _this.collapsed;
      h.dataUrl = _this.dataUrl;
      h.pinable = _this.pinable;
    });
    this.header.forEach(function (h) {
      h.$on("pinned", function (isPinned) {
        _this.isPinned = isPinned;
      });
      h.$on("collapsed", function (collapsed) {
        var data = {};
        data.type = "box";
        data.layout = {
          collapsed: collapsed
        };
        data.uri = _this.dataUri;

        _this.$http.post("UI/save", data);

        _this.body.forEach(function (e) {
          if (collapsed) {
            $(e.$el).slideUp(500);
          } else {
            $(e.$el).slideDown(500);
          }
        });

        _this.footer.forEach(function (e) {
          if (collapsed) {
            $(e.$el).slideUp(500);
          } else {
            $(e.$el).slideDown(500);
          }
        });
      });
      h.$on("acl", function (acl) {
        var data = acl;
        data.path = _this.dataAclUri;

        _this.$http.post("ACL/box", data);
      });
    });

    if (this.dataUrl) {
      this.reload();
    }
  },
  methods: {
    reload: function reload() {
      var _this2 = this;

      this.showLoading();
      this.$http.get(this.dataUrl).then(function (resp) {
        _this2.hideLoading();

        _this2.body[0].setContent(resp.body);
      });
    },
    showLoading: function showLoading() {
      this.loading = true;
    },
    hideLoading: function hideLoading() {
      this.loading = false;
    },
    pin: function pin() {
      this.header.forEach(function (h) {
        return h.pin();
      });
    },
    unpin: function unpin() {
      this.header.forEach(function (h) {
        return h.unpin();
      });
    }
  }
});
// CONCATENATED MODULE: ./src/Box.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Boxvue_type_script_lang_js_ = (Boxvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Box.vue





/* normalize component */

var Box_component = normalizeComponent(
  src_Boxvue_type_script_lang_js_,
  Boxvue_type_template_id_0c080f06_render,
  Boxvue_type_template_id_0c080f06_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Box_component.options.__file = "Box.vue"
/* harmony default export */ var Box = (Box_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/BoxHeader.vue?vue&type=template&id=253aa0de&
var BoxHeadervue_type_template_id_253aa0de_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"box-header"},[_c('h3',{staticClass:"box-title"},[(_vm.icon)?_c('i',{class:_vm.icon}):_vm._e(),_vm._t("default")],2),_c('div',{staticClass:"box-tools pull-right"},[_vm._t("tools"),(_vm.dataUrl)?_c('button',{staticClass:"btn btn-box-tool",attrs:{"type":"button"},on:{"click":function($event){_vm.$parent.reload()}}},[_c('i',{staticClass:"fa fa-sync-alt"})]):_vm._e(),(_vm.acl_group)?_c('div',{staticClass:"btn-group"},[_vm._m(0),_c('ul',{staticClass:"dropdown-menu pull-right",attrs:{"role":"menu"}},_vm._l((_vm.acl_group),function(acl,index){return _c('li',{key:index,class:{checked:acl.selected,disabled:acl.disabled}},[_c('a',{attrs:{"href":"#"},on:{"click":function($event){$event.preventDefault();_vm.aclClicked(acl)}}},[_c('i',{staticClass:"fa fa-check"}),_vm._v(_vm._s(acl.name))])])}))]):_vm._e(),(_vm.pinable)?_c('button',{staticClass:"btn btn-box-tool",attrs:{"type":"button"},on:{"click":function($event){_vm.togglePin()}}},[_c('i',{staticClass:"fa fa-fw",class:[_vm.pinned?'fa-thumbtack':'fa-arrows-alt']})]):_vm._e(),(_vm.collapsible)?_c('button',{staticClass:"btn btn-box-tool",attrs:{"type":"button"},on:{"click":function($event){_vm.toggleCollapse()}}},[_c('i',{staticClass:"fa fa-fw",class:[_vm.collapsed?'fa-plus':'fa-minus']})]):_vm._e(),(_vm.closeable)?_c('button',{staticClass:"btn btn-box-tool",attrs:{"type":"button","data-widget":"remove"},on:{"click":function($event){_vm.$emit('closed')}}},[_c('i',{staticClass:"fa fa-fw fa-times"})]):_vm._e()],2)])}
var BoxHeadervue_type_template_id_253aa0de_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('button',{staticClass:"btn btn-box-tool dropdown-toggle",attrs:{"type":"button","data-toggle":"dropdown"}},[_c('i',{staticClass:"fa fa-lock"})])}]


// CONCATENATED MODULE: ./src/BoxHeader.vue?vue&type=template&id=253aa0de&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/BoxHeader.vue?vue&type=script&lang=js&
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
//
/* harmony default export */ var BoxHeadervue_type_script_lang_js_ = ({
  name: "alt-box-header",
  props: {
    acl: Boolean,
    aclGroup: Array,
    icon: String
  },
  data: function data() {
    return {
      pinable: false,
      dataUrl: "",
      collapsible: false,
      collapsed: false,
      closeable: false,
      pinned: true,
      acl_group: this.aclGroup
    };
  },
  mounted: function mounted() {},
  methods: {
    togglePin: function togglePin() {
      this.pinned = !this.pinned;
      this.$emit("pinned", this.pinned);
      this.$parent.$emit("pinned", this.pinned);
    },
    toggleCollapse: function toggleCollapse() {
      this.collapsed = !this.collapsed;
      this.$emit("collapsed", this.collapsed);
    },
    aclClicked: function aclClicked(acl) {
      acl.selected = !acl.selected;
      this.$emit("acl", acl);
    },
    pin: function pin() {
      this.pinned = true;
    },
    unpin: function unpin() {
      this.pinned = false;
    }
  }
});
// CONCATENATED MODULE: ./src/BoxHeader.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_BoxHeadervue_type_script_lang_js_ = (BoxHeadervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/BoxHeader.vue





/* normalize component */

var BoxHeader_component = normalizeComponent(
  src_BoxHeadervue_type_script_lang_js_,
  BoxHeadervue_type_template_id_253aa0de_render,
  BoxHeadervue_type_template_id_253aa0de_staticRenderFns,
  false,
  null,
  null,
  null
  
)

BoxHeader_component.options.__file = "BoxHeader.vue"
/* harmony default export */ var BoxHeader = (BoxHeader_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/BoxBody.vue?vue&type=template&id=ddd1ac16&
var BoxBodyvue_type_template_id_ddd1ac16_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"box-body"},[(_vm.content)?_c('div',{domProps:{"innerHTML":_vm._s(_vm.content)}}):_vm._t("default")],2)}
var BoxBodyvue_type_template_id_ddd1ac16_staticRenderFns = []


// CONCATENATED MODULE: ./src/BoxBody.vue?vue&type=template&id=ddd1ac16&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/BoxBody.vue?vue&type=script&lang=js&
//
//
//
//
//
//
/* harmony default export */ var BoxBodyvue_type_script_lang_js_ = ({
  data: function data() {
    return {
      content: null
    };
  },
  methods: {
    setContent: function setContent(content) {
      this.content = content;
    }
  }
});
// CONCATENATED MODULE: ./src/BoxBody.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_BoxBodyvue_type_script_lang_js_ = (BoxBodyvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/BoxBody.vue





/* normalize component */

var BoxBody_component = normalizeComponent(
  src_BoxBodyvue_type_script_lang_js_,
  BoxBodyvue_type_template_id_ddd1ac16_render,
  BoxBodyvue_type_template_id_ddd1ac16_staticRenderFns,
  false,
  null,
  null,
  null
  
)

BoxBody_component.options.__file = "BoxBody.vue"
/* harmony default export */ var BoxBody = (BoxBody_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/BoxFooter.vue?vue&type=template&id=273da12f&
var BoxFootervue_type_template_id_273da12f_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"box-footer"},[_vm._t("default")],2)}
var BoxFootervue_type_template_id_273da12f_staticRenderFns = []


// CONCATENATED MODULE: ./src/BoxFooter.vue?vue&type=template&id=273da12f&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/BoxFooter.vue?vue&type=script&lang=js&
//
//
//
//
//
/* harmony default export */ var BoxFootervue_type_script_lang_js_ = ({
  name: "alt-box-footer"
});
// CONCATENATED MODULE: ./src/BoxFooter.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_BoxFootervue_type_script_lang_js_ = (BoxFootervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/BoxFooter.vue





/* normalize component */

var BoxFooter_component = normalizeComponent(
  src_BoxFootervue_type_script_lang_js_,
  BoxFootervue_type_template_id_273da12f_render,
  BoxFootervue_type_template_id_273da12f_staticRenderFns,
  false,
  null,
  null,
  null
  
)

BoxFooter_component.options.__file = "BoxFooter.vue"
/* harmony default export */ var BoxFooter = (BoxFooter_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/icheck.vue?vue&type=template&id=e4d662a4&
var icheckvue_type_template_id_e4d662a4_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('input',{attrs:{"type":"checkbox"},domProps:{"value":_vm.value}})}
var icheckvue_type_template_id_e4d662a4_staticRenderFns = []


// CONCATENATED MODULE: ./src/icheck.vue?vue&type=template&id=e4d662a4&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/icheck.vue?vue&type=script&lang=js&
//
//
//
/* harmony default export */ var icheckvue_type_script_lang_js_ = ({
  props: {
    value: {
      default: null
    },
    checkboxClass: {
      type: String,
      default: "icheckbox_square-blue"
    },
    radioClass: {
      type: String,
      default: "iradio_square-blue"
    },
    checked: {
      type: Boolean
    }
  },
  created: function created() {},
  mounted: function mounted() {
    var _this = this;

    $(this.$el).iCheck(this._props);

    if (this.checked) {
      $(this.$el).iCheck("check");
    }

    $(this.$el).on("ifClicked", function (event) {
      $(_this.$el).trigger("click");

      _this.$emit("click", event);
    }).on("ifChanged", function (event) {
      _this.$emit("change", event);
    }).on("ifChecked", function (event) {
      _this.$emit("input", _this.$el.value);

      _this.$emit("checked", event);
    }).on("ifUnchecked", function (event) {
      _this.$emit("input", null);

      _this.$emit("unchecked", event);
    });
  }
});
// CONCATENATED MODULE: ./src/icheck.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_icheckvue_type_script_lang_js_ = (icheckvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/icheck.vue





/* normalize component */

var icheck_component = normalizeComponent(
  src_icheckvue_type_script_lang_js_,
  icheckvue_type_template_id_e4d662a4_render,
  icheckvue_type_template_id_e4d662a4_staticRenderFns,
  false,
  null,
  null,
  null
  
)

icheck_component.options.__file = "icheck.vue"
/* harmony default export */ var icheck = (icheck_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Datepicker.vue?vue&type=template&id=07aeb6e1&
var Datepickervue_type_template_id_07aeb6e1_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('input',{staticClass:"form-control",domProps:{"value":_vm.value}})}
var Datepickervue_type_template_id_07aeb6e1_staticRenderFns = []


// CONCATENATED MODULE: ./src/Datepicker.vue?vue&type=template&id=07aeb6e1&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Datepicker.vue?vue&type=script&lang=js&
//
//
//
/* harmony default export */ var Datepickervue_type_script_lang_js_ = ({
  props: {
    value: {
      type: String
    },
    format: {
      type: String,
      default: "yyyy-mm-dd"
    },
    todayHighlight: {
      type: Boolean,
      default: true
    },
    todayBtn: {
      type: String,
      default: "linked"
    },
    autoclose: {
      type: Boolean,
      default: true
    }
  },
  data: function data() {
    return {
      datepicker: null
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.datepicker = $(this.$el).datepicker(this.$props);
    this.datepicker.on("change", function (o) {
      _this.$emit("input", o.target.value);
    });
  }
});
// CONCATENATED MODULE: ./src/Datepicker.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Datepickervue_type_script_lang_js_ = (Datepickervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Datepicker.vue





/* normalize component */

var Datepicker_component = normalizeComponent(
  src_Datepickervue_type_script_lang_js_,
  Datepickervue_type_template_id_07aeb6e1_render,
  Datepickervue_type_template_id_07aeb6e1_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Datepicker_component.options.__file = "Datepicker.vue"
/* harmony default export */ var Datepicker = (Datepicker_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Date.vue?vue&type=template&id=666c1f72&
var Datevue_type_template_id_666c1f72_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{staticClass:"input-group"},[_c('span',{staticClass:"input-group-addon"},[_c('i',{class:_vm.icon})]),_c("datepicker",_vm._b({ref:"datepicker",tag:"input",staticClass:"form-control",attrs:{"value":_vm.value},on:{"input":function($event){_vm.$emit('input',$event)}}},'input',_vm.$props,false)),(_vm.required)?_c('i',{staticClass:"fa fa-asterisk form-control-feedback",staticStyle:{"top":"10px"}}):_vm._e()])])}
var Datevue_type_template_id_666c1f72_staticRenderFns = []


// CONCATENATED MODULE: ./src/Date.vue?vue&type=template&id=666c1f72&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Date.vue?vue&type=script&lang=js&
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
/* harmony default export */ var Datevue_type_script_lang_js_ = ({
  name: "alt-date",
  props: {
    value: {
      type: String
    },
    icon: {
      type: String,
      default: "far fa-calendar-alt"
    },
    name: String,
    required: Boolean,
    type: Boolean,
    format: {
      type: String,
      default: "yyyy-mm-dd"
    },
    todayHighlight: {
      type: Boolean,
      default: true
    },
    todayBtn: {
      type: String,
      default: "linked"
    },
    autoclose: {
      type: Boolean,
      default: true
    }
  },
  mounted: function mounted() {
    if (this.required) {
      $(this.$el).closest(".form-group").addClass("has-feedback");

      if ($(this.$el).closest(".form-group").length == 0) {
        $(this.$el).css("margin-bottom", "0px");
        $(this.$el).addClass("form-group has-feedback");
      }
    }
  }
});
// CONCATENATED MODULE: ./src/Date.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Datevue_type_script_lang_js_ = (Datevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Date.vue





/* normalize component */

var Date_component = normalizeComponent(
  src_Datevue_type_script_lang_js_,
  Datevue_type_template_id_666c1f72_render,
  Datevue_type_template_id_666c1f72_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Date_component.options.__file = "Date.vue"
/* harmony default export */ var src_Date = (Date_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Datetime.vue?vue&type=template&id=f8847de4&
var Datetimevue_type_template_id_f8847de4_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{staticClass:"input-group"},[_c('span',{staticClass:"input-group-addon"},[_c('i',{class:_vm.icon})]),_c('input',_vm._b({ref:"input",staticClass:"form-control",attrs:{"name":_vm.name},domProps:{"value":_vm.value}},'input',_vm.$props,false)),(_vm.required)?_c('i',{staticClass:"fa fa-asterisk form-control-feedback",staticStyle:{"top":"10px"}}):_vm._e()])])}
var Datetimevue_type_template_id_f8847de4_staticRenderFns = []


// CONCATENATED MODULE: ./src/Datetime.vue?vue&type=template&id=f8847de4&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Datetime.vue?vue&type=script&lang=js&
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
/* harmony default export */ var Datetimevue_type_script_lang_js_ = ({
  name: "alt-datetime",
  props: {
    name: String,
    required: Boolean,
    type: Boolean,
    value: String,
    sideBySide: {
      type: Boolean,
      default: true
    },
    format: {
      type: String,
      default: "YYYY-MM-DD HH:mm"
    },
    icon: {
      type: String,
      default: "far fa-calendar-alt"
    }
  },
  mounted: function mounted() {
    if (this.required) {
      $(this.$el).closest(".form-group").addClass("has-feedback");

      if ($(this.$el).closest(".form-group").length == 0) {
        $(this.$el).css("margin-bottom", "0px");
        $(this.$el).addClass("form-group has-feedback");
      }
    }

    $(this.$refs.input).datetimepicker({
      format: this.format
    });
  }
});
// CONCATENATED MODULE: ./src/Datetime.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Datetimevue_type_script_lang_js_ = (Datetimevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Datetime.vue





/* normalize component */

var Datetime_component = normalizeComponent(
  src_Datetimevue_type_script_lang_js_,
  Datetimevue_type_template_id_f8847de4_render,
  Datetimevue_type_template_id_f8847de4_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Datetime_component.options.__file = "Datetime.vue"
/* harmony default export */ var Datetime = (Datetime_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/E.vue?vue&type=template&id=2be87667&
var Evue_type_template_id_2be87667_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (_vm.hasHeader())?_c('alt-box',[_c('alt-box-header',[_vm._v(_vm._s(_vm.header))]),_c('alt-box-body',[_c('div',{staticClass:"form-horizontal clearfix"},[_vm._t("default")],2)])],1):_c('div',{staticClass:"form-horizontal clearfix"},[_vm._t("default")],2)}
var Evue_type_template_id_2be87667_staticRenderFns = []


// CONCATENATED MODULE: ./src/E.vue?vue&type=template&id=2be87667&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/E.vue?vue&type=script&lang=js&
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
/* harmony default export */ var Evue_type_script_lang_js_ = ({
  props: {
    header: String
  },
  methods: {
    hasHeader: function hasHeader() {
      if (this.header) {
        return true;
      }

      return false;
    }
  }
});
// CONCATENATED MODULE: ./src/E.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Evue_type_script_lang_js_ = (Evue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/E.vue





/* normalize component */

var E_component = normalizeComponent(
  src_Evue_type_script_lang_js_,
  Evue_type_template_id_2be87667_render,
  Evue_type_template_id_2be87667_staticRenderFns,
  false,
  null,
  null,
  null
  
)

E_component.options.__file = "E.vue"
/* harmony default export */ var E = (E_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Email.vue?vue&type=template&id=121fe762&
var Emailvue_type_template_id_121fe762_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"input-group"},[_vm._m(0),_c('input',_vm._b({staticClass:"form-control",attrs:{"type":"email","name":_vm.name},domProps:{"value":_vm.value}},'input',_vm.$props,false)),(_vm.required)?_c('i',{staticClass:"fa fa-asterisk form-control-feedback",staticStyle:{"top":"10px"}}):_vm._e()])}
var Emailvue_type_template_id_121fe762_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',{staticClass:"input-group-addon"},[_c('i',{staticClass:"far fa-envelope"})])}]


// CONCATENATED MODULE: ./src/Email.vue?vue&type=template&id=121fe762&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Email.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
/* harmony default export */ var Emailvue_type_script_lang_js_ = ({
  props: {
    name: String,
    required: Boolean,
    type: Boolean,
    value: String
  },
  mounted: function mounted() {
    if (this.required) {
      $(this.$el).closest(".form-group").addClass("has-feedback");

      if ($(this.$el).closest(".form-group").length == 0) {
        $(this.$el).css("margin-bottom", "0px");
        $(this.$el).addClass("form-group has-feedback");
      }
    }
  }
});
// CONCATENATED MODULE: ./src/Email.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Emailvue_type_script_lang_js_ = (Emailvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Email.vue





/* normalize component */

var Email_component = normalizeComponent(
  src_Emailvue_type_script_lang_js_,
  Emailvue_type_template_id_121fe762_render,
  Emailvue_type_template_id_121fe762_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Email_component.options.__file = "Email.vue"
/* harmony default export */ var Email = (Email_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Form.vue?vue&type=template&id=6657ac63&
var Formvue_type_template_id_6657ac63_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('form',{attrs:{"novalidate":""},on:{"submit":function($event){_vm.onSubmit($event)}}},[_vm._t("default")],2)}
var Formvue_type_template_id_6657ac63_staticRenderFns = []


// CONCATENATED MODULE: ./src/Form.vue?vue&type=template&id=6657ac63&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Form.vue?vue&type=script&lang=js&
//
//
//
//
//
//
/* harmony default export */ var Formvue_type_script_lang_js_ = ({
  name: "alt-form",
  created: function created() {
    $(this.$el).validate();
  },
  methods: {
    onSubmit: function onSubmit(e) {
      if ($(this.$el).valid()) {
        this.$emit("submit", e);
      } else {
        e.preventDefault();
      }
    }
  }
});
// CONCATENATED MODULE: ./src/Form.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Formvue_type_script_lang_js_ = (Formvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Form.vue





/* normalize component */

var Form_component = normalizeComponent(
  src_Formvue_type_script_lang_js_,
  Formvue_type_template_id_6657ac63_render,
  Formvue_type_template_id_6657ac63_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Form_component.options.__file = "Form.vue"
/* harmony default export */ var Form = (Form_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Grid.vue?vue&type=template&id=598f4c43&
var Gridvue_type_template_id_598f4c43_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_vm._t("default")],2)}
var Gridvue_type_template_id_598f4c43_staticRenderFns = []


// CONCATENATED MODULE: ./src/Grid.vue?vue&type=template&id=598f4c43&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Grid.vue?vue&type=script&lang=js&

//
//
//
//
//
/* harmony default export */ var Gridvue_type_script_lang_js_ = ({
  props: {
    dataUrl: String,
    sortable: Boolean
  },
  mounted: function mounted() {
    var _this = this;

    this.$children.forEach(function (c) {
      c.$on("pinned", function (v) {
        if (v) {
          _this.$children.forEach(function (c) {
            return c.endSort();
          });
        } else {
          _this.$children.forEach(function (c) {
            return c.startSort();
          });
        }
      });
      c.$on("sortstop", _this.sortStop);
    });
  },
  computed: {
    isDragDrop: function isDragDrop() {
      return this.$children.some(function (c) {
        return !c.isPinned();
      });
    }
  },
  methods: {
    sortStop: function sortStop() {
      var grid = $(this.$el);
      var data = [];
      grid.children("div.row").each(function (i, row) {
        data[i] = [];
        $(row).children("section").each(function (j, section) {
          data[i][j] = [];
          $(section).children("div[grid-item]").each(function (k, item) {
            data[i][j].push($(item).attr("grid-item"));
          });
        });
      });
      this.$http.post("UI/save", {
        type: "grid",
        layout: data,
        uri: this.$attrs["data-uri"]
      }).then(function (resp) {});
    }
  }
});
// CONCATENATED MODULE: ./src/Grid.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Gridvue_type_script_lang_js_ = (Gridvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Grid.vue





/* normalize component */

var Grid_component = normalizeComponent(
  src_Gridvue_type_script_lang_js_,
  Gridvue_type_template_id_598f4c43_render,
  Gridvue_type_template_id_598f4c43_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Grid_component.options.__file = "Grid.vue"
/* harmony default export */ var Grid = (Grid_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/GridSection.vue?vue&type=template&id=1e38a5b7&
var GridSectionvue_type_template_id_1e38a5b7_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('section',{style:(_vm.getStyle())},[_vm._t("default")],2)}
var GridSectionvue_type_template_id_1e38a5b7_staticRenderFns = []


// CONCATENATED MODULE: ./src/GridSection.vue?vue&type=template&id=1e38a5b7&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/GridSection.vue?vue&type=script&lang=js&

//
//
//
//
//
/* harmony default export */ var GridSectionvue_type_script_lang_js_ = ({
  computed: {
    box: function box() {
      if (!this.$slots.default) return [];
      return this.$children.filter(function (o) {
        return o.$vnode.componentOptions.tag == "alt-box";
      });
    }
  },
  data: function data() {
    return {
      isDragDrop: false
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.$children.forEach(function (c) {
      c.$on("pinned", function (v) {
        _this.$emit("pinned", v);
      });
    });
  },
  methods: {
    getStyle: function getStyle() {
      if (this.isDragDrop) {
        return {
          "min-height": "100px"
        };
      } else {
        return {
          "min-height": "0px"
        };
      }
    },
    isPinned: function isPinned() {
      //no children
      if (this.box.length == 0) return true;
      return this.box.every(function (b) {
        return b.isPinned;
      });
    },
    startSort: function startSort() {
      var _this2 = this;

      this.isDragDrop = true;
      this.box.forEach(function (b) {
        return b.unpin();
      });
      $(this.$el).sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
      }).on("sortstop", function (event, ui) {
        _this2.$emit("sortstop");
      });
    },
    endSort: function endSort() {
      this.isDragDrop = false;
      this.box.forEach(function (b) {
        return b.pin();
      });
      $(this.$el).sortable("destroy");
    }
  }
});
// CONCATENATED MODULE: ./src/GridSection.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_GridSectionvue_type_script_lang_js_ = (GridSectionvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/GridSection.vue





/* normalize component */

var GridSection_component = normalizeComponent(
  src_GridSectionvue_type_script_lang_js_,
  GridSectionvue_type_template_id_1e38a5b7_render,
  GridSectionvue_type_template_id_1e38a5b7_staticRenderFns,
  false,
  null,
  null,
  null
  
)

GridSection_component.options.__file = "GridSection.vue"
/* harmony default export */ var GridSection = (GridSection_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Multiselect.vue?vue&type=template&id=23681a7c&
var Multiselectvue_type_template_id_23681a7c_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('select',{attrs:{"multiple":""}},[_vm._t("default")],2)}
var Multiselectvue_type_template_id_23681a7c_staticRenderFns = []


// CONCATENATED MODULE: ./src/Multiselect.vue?vue&type=template&id=23681a7c&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Multiselect.vue?vue&type=script&lang=js&
//
//
//
//
//
/* harmony default export */ var Multiselectvue_type_script_lang_js_ = ({
  name: "alt-multiselect",
  props: {
    includeSelectAllOption: {
      type: Boolean,
      default: true
    },
    enableFiltering: {
      type: Boolean,
      default: true
    }
  },
  mounted: function mounted() {
    $(this.$el).multiselect(this._props);
  }
});
// CONCATENATED MODULE: ./src/Multiselect.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Multiselectvue_type_script_lang_js_ = (Multiselectvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Multiselect.vue





/* normalize component */

var Multiselect_component = normalizeComponent(
  src_Multiselectvue_type_script_lang_js_,
  Multiselectvue_type_template_id_23681a7c_render,
  Multiselectvue_type_template_id_23681a7c_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Multiselect_component.options.__file = "Multiselect.vue"
/* harmony default export */ var Multiselect = (Multiselect_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Select2.vue?vue&type=template&id=05f15486&
var Select2vue_type_template_id_05f15486_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('select',{staticClass:"form-control"},[_vm._t("default")],2)}
var Select2vue_type_template_id_05f15486_staticRenderFns = []


// CONCATENATED MODULE: ./src/Select2.vue?vue&type=template&id=05f15486&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Select2.vue?vue&type=script&lang=js&
//
//
//
/* harmony default export */ var Select2vue_type_script_lang_js_ = ({
  name: "select2",
  props: {
    value: {
      type: String
    }
  },
  mounted: function mounted() {
    var _this = this;

    $(this.$el).select2();

    if (this.value) {
      $(this.$el).val(this.value);
      $(this.$el).trigger("change");
    }

    $(this.$el).on("change", function () {
      var data = $(_this.$el).select2("data");
      var v = data.map(function (s) {
        return s.id;
      });

      _this.$emit("input", v[0]);

      _this.$emit("change", v[0]);
    });
  },
  methods: {
    empty: function empty() {
      $(this.$el).empty();
    }
  }
});
// CONCATENATED MODULE: ./src/Select2.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Select2vue_type_script_lang_js_ = (Select2vue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Select2.vue





/* normalize component */

var Select2_component = normalizeComponent(
  src_Select2vue_type_script_lang_js_,
  Select2vue_type_template_id_05f15486_render,
  Select2vue_type_template_id_05f15486_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Select2_component.options.__file = "Select2.vue"
/* harmony default export */ var Select2 = (Select2_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Multiselect2.vue?vue&type=template&id=90091bf0&
var Multiselect2vue_type_template_id_90091bf0_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('select',{staticClass:"form-control",attrs:{"multiple":""}},[_vm._t("default")],2)}
var Multiselect2vue_type_template_id_90091bf0_staticRenderFns = []


// CONCATENATED MODULE: ./src/Multiselect2.vue?vue&type=template&id=90091bf0&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Multiselect2.vue?vue&type=script&lang=js&
//
//
//
/* harmony default export */ var Multiselect2vue_type_script_lang_js_ = ({
  name: "multiselect2",
  props: {
    value: {
      type: Array
    }
  },
  watch: {
    value: function value(newValue, oldValue) {
      $(this.$el).val(newValue);
      $(this.$el).trigger("change.select2");
    }
  },
  mounted: function mounted() {
    var _this = this;

    $(this.$el).select2();

    if (this.value) {
      $(this.$el).val(this.value);
      $(this.$el).trigger("change.select2");
    }

    $(this.$el).on("change", function () {
      console.log("onchange");
      var data = $(_this.$el).select2("data");
      var v = data.map(function (s) {
        return s.id;
      });

      _this.$emit("input", v);
    });
  }
});
// CONCATENATED MODULE: ./src/Multiselect2.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Multiselect2vue_type_script_lang_js_ = (Multiselect2vue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Multiselect2.vue





/* normalize component */

var Multiselect2_component = normalizeComponent(
  src_Multiselect2vue_type_script_lang_js_,
  Multiselect2vue_type_template_id_90091bf0_render,
  Multiselect2vue_type_template_id_90091bf0_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Multiselect2_component.options.__file = "Multiselect2.vue"
/* harmony default export */ var Multiselect2 = (Multiselect2_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Tab.vue?vue&type=template&id=0b70b104&
var Tabvue_type_template_id_0b70b104_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('ul',{staticClass:"nav nav-tabs"},_vm._l((_vm.tabs),function(tab,i){return _c('li',{key:i,class:[tab.active?'active':''],on:{"click":function($event){$event.preventDefault();_vm.selectTab(tab)}}},[_c('a',{attrs:{"href":"#"},domProps:{"innerHTML":_vm._s(tab.name)}})])})),_c('div',{staticClass:"tab-content"},[_vm._t("default")],2)])}
var Tabvue_type_template_id_0b70b104_staticRenderFns = []


// CONCATENATED MODULE: ./src/Tab.vue?vue&type=template&id=0b70b104&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es6.array.find.js
var es6_array_find = __webpack_require__("7514");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es6.function.name.js
var es6_function_name = __webpack_require__("7f7f");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Tab.vue?vue&type=script&lang=js&



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
/* harmony default export */ var Tabvue_type_script_lang_js_ = ({
  props: {
    name: String
  },
  data: function data() {
    return {
      tabs: [],
      selectedTab: null,
      xhr: null
    };
  },
  created: function created() {
    this.tabs = this.$children;
  },
  mounted: function mounted() {
    var _this = this;

    this.tabs.forEach(function (t) {
      t.$on("loaded", function () {
        _this.$localStorage.set(_this.name + "/tab", t.name);
      });
    });
    var name;

    if (name = this.$localStorage.get(this.name + "/tab")) {
      var _tab = this.tabs.find(function (t) {
        return t.name == name;
      });

      if (_tab) {
        this.selectTab(_tab);
        return;
      }
    }

    var tab = this.tabs.find(function (t) {
      return t.active;
    });

    if (tab) {
      this.selectTab(tab);
    }
  },
  methods: {
    selectTab: function selectTab(tab) {
      if (this.xhr) {
        this.xhr.abort();
      }

      this.selectedTab = tab;
      this.tabs.forEach(function (t) {
        t.active = false;
      });
      this.xhr = tab.select();
    }
  }
});
// CONCATENATED MODULE: ./src/Tab.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Tabvue_type_script_lang_js_ = (Tabvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Tab.vue





/* normalize component */

var Tab_component = normalizeComponent(
  src_Tabvue_type_script_lang_js_,
  Tabvue_type_template_id_0b70b104_render,
  Tabvue_type_template_id_0b70b104_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Tab_component.options.__file = "Tab.vue"
/* harmony default export */ var Tab = (Tab_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Table.vue?vue&type=template&id=326512dc&
var Tablevue_type_template_id_326512dc_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('table',[_vm._t("default")],2)}
var Tablevue_type_template_id_326512dc_staticRenderFns = []


// CONCATENATED MODULE: ./src/Table.vue?vue&type=template&id=326512dc&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Table.vue?vue&type=script&lang=js&

//
//
//
/* harmony default export */ var Tablevue_type_script_lang_js_ = ({
  props: {
    formCreate: Boolean,
    formName: String
  },
  data: function data() {
    return {};
  },
  mounted: function mounted() {
    var _this = this;

    if (this.formCreate) {
      var o = this.$el;
      var $table = $(o);
      if ($table.prop("tagName") != "TABLE") $table = $table.find("table:first");
      var form_name = this.formName;
      var form_addbottom = $table.attr("form-addbottom") == "";
      var max_row = $table.attr("max-row") ? $table.attr("max-row") : -1;
      var tr = $table.find("tr:first");
      var a = $("<a href='javascript:void(0)' class='btn btn-xs btn-primary'><i class='fa fa-plus'/></a>");
      var th = $("<th style='text-align:center;width:36px' align='center'>").prependTo(tr);
      th.append(a);
      var del_index = [];
      $table.find("tr").each(function (i, o) {
        if (i == 0) return;
        var $tr = $(o);
        var $td = $("<td style='text-align:center' align='center'>").prependTo($tr);
        var a = $("<a href='javascript:void(0)' class='btn btn-xs btn-danger'><i class='fa fa-minus' /></a>").prependTo($td);
        a.on("click", function () {
          var del_input = $("<input type='hidden' />");

          if (form_name != undefined) {
            del_input.attr("name", form_name + "[d][]");
          } else {
            del_input.attr("name", "_d[]");
          }

          del_input.val($tr.attr("data-index"));

          if ($tr.attr("data-index")) {
            $table.after(del_input);
          }

          $tr.remove();
        });
      });
      var index = 0;
      $(a).on("click", function () {
        var $table = $(o); //count row

        if (max_row > 0) {
          if ($table.find("tbody tr").length >= max_row) {
            return false;
          }
        }

        var tr = $table.find("tr:first");
        var new_tr = $("<tr>");
        $(tr).find("th").each(function (i, o) {
          if (i == 0) {
            var a = $("<a href='javascript:void(0)' class='btn btn-xs btn-warning'><i class='fa fa-minus' /></a>");
            var td = $("<td>").appendTo(new_tr);
            td.attr("align", "center");
            a.appendTo(td);
            a.on("click", function () {
              new_tr.remove();
            });
          } else {
            var div = $("<div>");
            div.append($(o).attr("c-tpl"));
            div.find("input,textarea,select").each(function (i, obj) {
              var name = $(obj).attr("name");
              var index_of = name.indexOf("[]");

              if (form_name != "") {
                if (index_of >= 0) {
                  $(obj).attr("name", form_name + "[c][" + index + "][" + name.substring(0, index_of) + "][]");
                } else {
                  $(obj).attr("name", form_name + "[c][" + index + "][" + name + "]");
                }
              } else {
                if (index_of >= 0) {
                  $(obj).attr("name", "_c[" + index + "][" + name.substring(0, index_of) + "][]");
                } else {
                  $(obj).attr("name", "_c[" + index + "][" + name + "]");
                }
              }
            });
            new_tr.append("<td><div class='form-group no-margin'>" + div.html() + "</div></td>");
          }
        });

        if (form_addbottom) {
          $table.append(new_tr);
        } else {
          if ($table.find("tbody")) {
            $table.find("tbody").prepend(new_tr);
          } else {
            tr.after(new_tr);
          }
        }

        index++;
        $(".datetimepicker").each(function (i, o) {
          $(o).dp();
        });
        return false;
      });
    }

    var btn = $(this.$el).find(".table-childrow-btn");
    $(this.$el).find(".table-childrow-btn").off("click").on("click", function () {
      var $this = btn;
      var tr = btn.closest("tr");
      var colspan = $(tr).find("td").length;

      if ($this.hasClass("table-childrow-close")) {
        $this.removeClass("table-childrow-close").addClass("table-childrow-open");
        $this.find("i").removeClass("fa-chevron-up").addClass("fa-chevron-down");
        var s = new Date().getTime();
        $this.attr("data-id", s);
        var new_tr = $("<tr class='table-childrow'>");
        new_tr.attr("data-id", s);
        tr.after(new_tr);
        var new_td = $("<td>");
        new_tr.append(new_td);
        new_td.attr("colspan", colspan);

        if ($this.attr("data-url")) {
          _this.$emit("loading", true);

          $.get($this.attr("data-url")).done(function (html) {
            _this.$emit("loading", false);

            new_td.html(html);
          });
        } else {
          new_td.html($this.attr("data-child"));
        }
      } else {
        $this.removeClass("table-childrow-open").addClass("table-childrow-close");
        $this.find("i").removeClass("fa-chevron-down").addClass("fa-chevron-up");

        var _s = $this.attr("data-id");

        var $table = $this.closest("table");
        $table.find("tr.table-childrow[data-id='" + _s + "']").remove();
      }
    });
  },
  methods: {}
});
// CONCATENATED MODULE: ./src/Table.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Tablevue_type_script_lang_js_ = (Tablevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Table.vue





/* normalize component */

var Table_component = normalizeComponent(
  src_Tablevue_type_script_lang_js_,
  Tablevue_type_template_id_326512dc_render,
  Tablevue_type_template_id_326512dc_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Table_component.options.__file = "Table.vue"
/* harmony default export */ var Table = (Table_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/TableBody.vue?vue&type=template&id=22f2ea94&
var TableBodyvue_type_template_id_22f2ea94_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c("div")}
var TableBodyvue_type_template_id_22f2ea94_staticRenderFns = []


// CONCATENATED MODULE: ./src/TableBody.vue?vue&type=template&id=22f2ea94&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/TableBody.vue?vue&type=script&lang=js&
//
//
//
/* harmony default export */ var TableBodyvue_type_script_lang_js_ = ({
  render: function render(createElement) {
    var rows = this.$parent.newRows.map(function (r) {
      return createElement("alt-row");
    });
    return createElement("tbody", [rows, this.$slots.default]);
  },
  methods: {}
});
// CONCATENATED MODULE: ./src/TableBody.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_TableBodyvue_type_script_lang_js_ = (TableBodyvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/TableBody.vue





/* normalize component */

var TableBody_component = normalizeComponent(
  src_TableBodyvue_type_script_lang_js_,
  TableBodyvue_type_template_id_22f2ea94_render,
  TableBodyvue_type_template_id_22f2ea94_staticRenderFns,
  false,
  null,
  null,
  null
  
)

TableBody_component.options.__file = "TableBody.vue"
/* harmony default export */ var TableBody = (TableBody_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/TabPane.vue?vue&type=template&id=1f07f77e&
var TabPanevue_type_template_id_1f07f77e_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"tab-pane",class:{active:_vm.active}},[_vm._t("default")],2)}
var TabPanevue_type_template_id_1f07f77e_staticRenderFns = []


// CONCATENATED MODULE: ./src/TabPane.vue?vue&type=template&id=1f07f77e&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/TabPane.vue?vue&type=script&lang=js&
//
//
//
//
//
//
/* harmony default export */ var TabPanevue_type_script_lang_js_ = ({
  props: {
    name: {
      required: true
    },
    url: String
  },
  data: function data() {
    return {
      active: false,
      html: ""
    };
  },
  methods: {
    loading: function loading() {
      $(this.$el).html('<div class="box box-solid"><div class="box-body no-padding"><div class="overlay"><i class="fa fa-spinner fa-spin"></i></div></div></div>');
    },
    select: function select() {
      var _this = this;

      this.active = true; //download

      if (this.url) {
        $(this.$el).html("");
        this.loading();
        return $.get(this.url, function (html) {
          $(_this.$el).html(html);

          _this.$emit("loaded");
        }).fail(function () {
          $(_this.$el).html("error when loading this page");
        });
      }

      this.$emit("loaded");
    }
  }
});
// CONCATENATED MODULE: ./src/TabPane.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_TabPanevue_type_script_lang_js_ = (TabPanevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/TabPane.vue





/* normalize component */

var TabPane_component = normalizeComponent(
  src_TabPanevue_type_script_lang_js_,
  TabPanevue_type_template_id_1f07f77e_render,
  TabPanevue_type_template_id_1f07f77e_staticRenderFns,
  false,
  null,
  null,
  null
  
)

TabPane_component.options.__file = "TabPane.vue"
/* harmony default export */ var TabPane = (TabPane_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Timeline.vue?vue&type=template&id=643d7533&
var Timelinevue_type_template_id_643d7533_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('ul',{staticClass:"timeline"},[_vm._l((_vm.data),function(item,index){return [(item.type=='label')?_c('li',{key:index,staticClass:"time-label"},[_c('span',{class:item.class},[_vm._v("\n                "+_vm._s(item.content)+"\n            ")])]):(item.type=='item')?_c('li',{key:index},[_c('i',{class:item.icon}),_c('div',{staticClass:"timeline-item"},[(item.time)?_c('span',{staticClass:"time"},[_c('i',{staticClass:"far fa-clock"}),_vm._v(" "+_vm._s(item.time))]):_vm._e(),(item.header)?_c('h3',{staticClass:"timeline-header",domProps:{"innerHTML":_vm._s(item.header)}}):_vm._e(),(item.body)?_c('div',{staticClass:"timeline-body",domProps:{"innerHTML":_vm._s(item.body)}}):_vm._e(),(item.footer)?_c('div',{staticClass:"timeline-footer",domProps:{"innerHTML":_vm._s(item.footer)}}):_vm._e()])]):_vm._e()]})],2)}
var Timelinevue_type_template_id_643d7533_staticRenderFns = []


// CONCATENATED MODULE: ./src/Timeline.vue?vue&type=template&id=643d7533&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Timeline.vue?vue&type=script&lang=js&
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
//
//
/* harmony default export */ var Timelinevue_type_script_lang_js_ = ({
  props: {
    dataUrl: String
  },
  data: function data() {
    return {
      data: Array
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.$http.get(this.dataUrl).then(function (resp) {
      _this.data = resp.body;
    });
  }
});
// CONCATENATED MODULE: ./src/Timeline.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Timelinevue_type_script_lang_js_ = (Timelinevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Timeline.vue





/* normalize component */

var Timeline_component = normalizeComponent(
  src_Timelinevue_type_script_lang_js_,
  Timelinevue_type_template_id_643d7533_render,
  Timelinevue_type_template_id_643d7533_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Timeline_component.options.__file = "Timeline.vue"
/* harmony default export */ var Timeline = (Timeline_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/xeditable.vue?vue&type=template&id=e593a8fc&
var xeditablevue_type_template_id_e593a8fc_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('a',[_vm._t("default")],2)}
var xeditablevue_type_template_id_e593a8fc_staticRenderFns = []


// CONCATENATED MODULE: ./src/xeditable.vue?vue&type=template&id=e593a8fc&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/xeditable.vue?vue&type=script&lang=js&
//
//
//
//
//
/* harmony default export */ var xeditablevue_type_script_lang_js_ = ({
  props: {},
  mounted: function mounted() {
    var dp = $(this.$el).editable();

    if ($(this.$el).attr("data-custom-type") == "date") {
      dp.on("shown", function (e, editable) {
        $(editable.input.$input).datepicker({
          format: "yyyy-mm-dd",
          todayHighlight: true,
          todayBtn: "linked",
          autoclose: true
        });
      });
    }

    if ($(this.$el).attr("data-custom-type") == "datetime") {
      dp.on("shown", function (e, editable) {
        $(editable.input.$input).datetimepicker({
          sideBySide: true,
          format: "YYYY-MM-DD HH:mm"
        });
      });
    }
  }
});
// CONCATENATED MODULE: ./src/xeditable.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_xeditablevue_type_script_lang_js_ = (xeditablevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/xeditable.vue





/* normalize component */

var xeditable_component = normalizeComponent(
  src_xeditablevue_type_script_lang_js_,
  xeditablevue_type_template_id_e593a8fc_render,
  xeditablevue_type_template_id_e593a8fc_staticRenderFns,
  false,
  null,
  null,
  null
  
)

xeditable_component.options.__file = "xeditable.vue"
/* harmony default export */ var xeditable = (xeditable_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/ckeditor.vue?vue&type=template&id=ceb4cc20&
var ckeditorvue_type_template_id_ceb4cc20_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.value),expression:"value"}],domProps:{"value":(_vm.value)},on:{"input":function($event){if($event.target.composing){ return; }_vm.value=$event.target.value}}})}
var ckeditorvue_type_template_id_ceb4cc20_staticRenderFns = []


// CONCATENATED MODULE: ./src/ckeditor.vue?vue&type=template&id=ceb4cc20&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es6.regexp.replace.js
var es6_regexp_replace = __webpack_require__("a481");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/ckeditor.vue?vue&type=script&lang=js&

//
//
//
/* harmony default export */ var ckeditorvue_type_script_lang_js_ = ({
  name: "ckeditor",
  data: function data() {
    return {
      value: this.data
    };
  },
  props: {
    data: String
  },
  mounted: function mounted() {
    if (typeof CKEDITOR != "undefined") {
      var base = $("base").attr("href");
      CKEDITOR.config.filebrowserImageBrowseUrl = base + "plugins/RoxyFileman.1.4.5/fileman/index.html?type=image";
      CKEDITOR.config.filebrowserBrowseUrl = base + "plugins/RoxyFileman.1.4.5/fileman/index.html";
      CKEDITOR.replace(this.$el);
    }
  }
});
// CONCATENATED MODULE: ./src/ckeditor.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_ckeditorvue_type_script_lang_js_ = (ckeditorvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/ckeditor.vue





/* normalize component */

var ckeditor_component = normalizeComponent(
  src_ckeditorvue_type_script_lang_js_,
  ckeditorvue_type_template_id_ceb4cc20_render,
  ckeditorvue_type_template_id_ceb4cc20_staticRenderFns,
  false,
  null,
  null,
  null
  
)

ckeditor_component.options.__file = "ckeditor.vue"
/* harmony default export */ var ckeditor = (ckeditor_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/roxyfileman.vue?vue&type=template&id=3eb3f4d6&
var roxyfilemanvue_type_template_id_3eb3f4d6_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('input',{staticClass:"form-control"})}
var roxyfilemanvue_type_template_id_3eb3f4d6_staticRenderFns = []


// CONCATENATED MODULE: ./src/roxyfileman.vue?vue&type=template&id=3eb3f4d6&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/roxyfileman.vue?vue&type=script&lang=js&
//
//
//
/* harmony default export */ var roxyfilemanvue_type_script_lang_js_ = ({
  name: "roxyfileman",
  mounted: function mounted() {
    $(this.$el).on("click", function () {
      var d = new Date().getTime();
      d = "roxy_" + d;
      $(this).attr("data-roxy-id", d);
      var resize_width = $(this).attr("roxy-resize-width");
      var type = $(this).attr("roxy-type") == undefined ? "image" : $(this).attr("roxy-type");
      var session = "";

      if ($(this).attr("roxy-session")) {
        session = $(this).attr("roxy-session");
      }

      var path = "plugins/RoxyFileman.1.4.5/fileman/index.html?type=" + type + "&integration=custom&txtFieldId=" + d + "&session=" + session;

      if (resize_width != undefined) {
        path += "&resize_width=" + resize_width;
      }

      $.fancybox.open({
        src: path,
        type: "iframe"
      });
    });
  }
});
// CONCATENATED MODULE: ./src/roxyfileman.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_roxyfilemanvue_type_script_lang_js_ = (roxyfilemanvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/roxyfileman.vue





/* normalize component */

var roxyfileman_component = normalizeComponent(
  src_roxyfilemanvue_type_script_lang_js_,
  roxyfilemanvue_type_template_id_3eb3f4d6_render,
  roxyfilemanvue_type_template_id_3eb3f4d6_staticRenderFns,
  false,
  null,
  null,
  null
  
)

roxyfileman_component.options.__file = "roxyfileman.vue"
/* harmony default export */ var roxyfileman = (roxyfileman_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Button.vue?vue&type=template&id=7bb7e900&
var Buttonvue_type_template_id_7bb7e900_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('button',{on:{"click":_vm.onClick}},[(_vm.iconClass)?_c('i',{class:_vm.iconClass}):_vm._e(),_vm._t("default")],2)}
var Buttonvue_type_template_id_7bb7e900_staticRenderFns = []


// CONCATENATED MODULE: ./src/Button.vue?vue&type=template&id=7bb7e900&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Button.vue?vue&type=script&lang=js&
//
//
//
//
//
//
/* harmony default export */ var Buttonvue_type_script_lang_js_ = ({
  name: "alt-button",
  props: {
    icon: String,
    type: String
  },
  data: function data() {
    return {
      submitting: false,
      iconClass: this.icon
    };
  },
  methods: {
    onClick: function onClick(event) {
      if (this.type == "submit") {
        var form = this.$el.form;

        if (!$(form).valid()) {
          return false;
        }

        if (this.submitting) {
          event.preventDefault();
          return;
        }

        this.submitting = true;
        this.iconClass = "fa fa-spinner fa-spin";
      }
    }
  }
});
// CONCATENATED MODULE: ./src/Button.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Buttonvue_type_script_lang_js_ = (Buttonvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Button.vue





/* normalize component */

var Button_component = normalizeComponent(
  src_Buttonvue_type_script_lang_js_,
  Buttonvue_type_template_id_7bb7e900_render,
  Buttonvue_type_template_id_7bb7e900_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Button_component.options.__file = "Button.vue"
/* harmony default export */ var Button = (Button_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Cell.vue?vue&type=template&id=134770a3&
var Cellvue_type_template_id_134770a3_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('td',[_vm._t("default")],2)}
var Cellvue_type_template_id_134770a3_staticRenderFns = []


// CONCATENATED MODULE: ./src/Cell.vue?vue&type=template&id=134770a3&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Cell.vue?vue&type=script&lang=js&
//
//
//
//
/* harmony default export */ var Cellvue_type_script_lang_js_ = ({
  name: "alt-cell"
});
// CONCATENATED MODULE: ./src/Cell.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Cellvue_type_script_lang_js_ = (Cellvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Cell.vue





/* normalize component */

var Cell_component = normalizeComponent(
  src_Cellvue_type_script_lang_js_,
  Cellvue_type_template_id_134770a3_render,
  Cellvue_type_template_id_134770a3_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Cell_component.options.__file = "Cell.vue"
/* harmony default export */ var Cell = (Cell_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RT.vue?vue&type=template&id=6db09736&
var RTvue_type_template_id_6db09736_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('alt-box',{ref:"box"},[_c('alt-box-body',{staticClass:"no-padding",class:{'table-responsive':!_vm.tableResponsive}},[_c('div',{staticStyle:{"position":"relative","clear":"both"}},[_c('div',{style:(_vm.tableContainerStyle)},[_c('rt-table',{ref:"table",staticClass:"rt table-bordered",attrs:{"responsive":_vm.tableResponsive,"sort-field":_vm.sortField,"sort-dir":_vm.sortDir,"cell-url":_vm.cellUrl,"selectable":_vm.selectable,"page-number":_vm.page,"page-size":_vm.page_size,"data-url":_vm.source},on:{"resized":_vm.resized,"refreshed":_vm.refreshed,"loading":function($event){_vm.$refs.box.showLoading()},"loaded":function($event){_vm.$refs.box.hideLoading()}}},[_vm._t("default")],2)],1),(_vm.hasFixedColumn)?_c('div',{staticStyle:{"position":"absolute","top":"0","left":"0","overflow":"hidden"}},[_c('table',{staticClass:"table table-hover table-condensed rt table-bordered",staticStyle:{"background-color":"white"}},[_c('thead',[_c('tr',_vm._l((_vm.fixedColumns()),function(c,index){return _c('th',{key:index,staticClass:"unselectable sorting_desc",style:({width:c.getWidth()+'px'}),attrs:{"data-width":c.getWidth()}},[_vm._v(_vm._s(c.field))])}))]),_c('tbody',_vm._l((_vm.getData()),function(d,index){return _c('tr',{key:index},_vm._l((_vm.fixedColumns()),function(c,key){return _c('td',{key:key,style:({width:c.getWidth()+'px', height:c.getHeight()+'px'})},[_c('span',{domProps:{"innerHTML":_vm._s(c.getDataValue(d))}}),_c('span',[_vm._v(_vm._s(c.$el.offsetWidth))])])}))}))])]):_vm._e()])]),_c('alt-box-footer',[_c("rt-pagination",{tag:"div",attrs:{"page":_vm.page,"page-count":_vm.page_count},on:{"first-page":function($event){_vm.$refs.table.firstPage()},"last-page":function($event){_vm.$refs.table.lastPage()},"prev-page":function($event){_vm.$refs.table.prevPage()},"next-page":function($event){_vm.$refs.table.nextPage()},"change-page":function($event){_vm.$refs.table.gotoPage($event)}}}),_c('div',{staticClass:"pull-left btn-group"},[_c('button',{staticClass:"btn btn-default btn-sm",attrs:{"type":"button","title":"重新載入","data-toggle":"tooltip"},on:{"click":function($event){_vm.$refs.table.refresh()}}},[_c('i',{staticClass:"fa fa-sync-alt"})])]),_c('div',{staticClass:"pull-left dropup"},[_c('button',{staticClass:"btn btn-default btn-sm dropdown-toggle",attrs:{"type":"button","data-toggle":"dropdown"}},[_c('span',{staticClass:"icon glyphicon glyphicon-th-list"})]),_c('ul',{ref:"column_menu",staticClass:"dropdown-menu"},_vm._l((_vm.columnSequence),function(col,key){return (_vm.getColumn(col).title)?_c('li',{key:key,attrs:{"data-field":col}},[_c('a',{staticClass:"small",attrs:{"href":"#","data-value":"option1","tabIndex":"-1"},on:{"click":function($event){$event.preventDefault();_vm.getColumn(col).toggleVisible()}}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.getColumn(col).isVisible),expression:"getColumn(col).isVisible"}],attrs:{"type":"checkbox"},domProps:{"checked":Array.isArray(_vm.getColumn(col).isVisible)?_vm._i(_vm.getColumn(col).isVisible,null)>-1:(_vm.getColumn(col).isVisible)},on:{"change":function($event){var $$a=_vm.getColumn(col).isVisible,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.getColumn(col), "isVisible", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.getColumn(col), "isVisible", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.getColumn(col), "isVisible", $$c)}}}}),_vm._v(" "+_vm._s(_vm.getColumn(col).title))])]):_vm._e()}))]),_c('div',{staticClass:"pull-left"},[_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.page_size),expression:"page_size"}],staticClass:"form-control input-sm",staticStyle:{"width":"70px"},on:{"change":[function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.page_size=$event.target.multiple ? $$selectedVal : $$selectedVal[0]},function($event){_vm.changePageSize()}]}},[_c('option',{attrs:{"value":"10"}},[_vm._v("10")]),_c('option',{attrs:{"value":"25"}},[_vm._v("25")]),_c('option',{attrs:{"value":"50"}},[_vm._v("50")]),_c('option',{attrs:{"value":"100"}},[_vm._v("100")]),_c('option',{attrs:{"value":"500"}},[_vm._v("500")])])]),_c('div',{staticClass:"pull-left btn-group"},[_c('button',{staticClass:"btn btn-default btn-sm",class:{active:_vm.tableResponsive},attrs:{"type":"button","title":"Responsive","data-toggle":"tooltip"},on:{"click":_vm.toggleResponsive}},[_c('i',{staticClass:"fa fa-tv"})]),_c('button',{staticClass:"btn btn-default btn-sm",attrs:{"type":"button","title":"clear cache","data-toggle":"tooltip"},on:{"click":_vm.resetLocaStorage}},[_c('i',{staticClass:"fa fa-times-circle"})])]),(_vm.showExport())?_c('div',{staticClass:"pull-left dropdown"},[_c('button',{staticClass:"btn btn-default btn-sm dropdown-toggle",attrs:{"type":"button","data-toggle":"dropdown"}},[_vm._v("\n                Export\n                "),_c('span',{staticClass:"caret"})]),_c('ul',{staticClass:"dropdown-menu",attrs:{"aria-labelledby":"dropdownMenu1"}},[(_vm.exportXlsx)?_c('li',[_c('a',{attrs:{"href":"#"},on:{"click":function($event){$event.preventDefault();_vm.exportFile('xlsx')}}},[_vm._v("XLSX")])]):_vm._e(),(_vm.exportCsv)?_c('li',[_c('a',{attrs:{"href":"#"},on:{"click":function($event){$event.preventDefault();_vm.exportFile('csv')}}},[_vm._v("CSV")])]):_vm._e()])]):_vm._e(),(_vm.$slots.buttons)?_c('div',{staticClass:"pull-left btn-group"},[_vm._t("buttons")],2):_vm._e(),_c('rt-info',{staticClass:"pull-right",attrs:{"total":_vm.total,"from":_vm.from,"to":_vm.to}})],1)],1)}
var RTvue_type_template_id_6db09736_staticRenderFns = []


// CONCATENATED MODULE: ./src/RT.vue?vue&type=template&id=6db09736&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es6.string.fixed.js
var es6_string_fixed = __webpack_require__("d263");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es6.number.constructor.js
var es6_number_constructor = __webpack_require__("c5f6");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RT.vue?vue&type=script&lang=js&




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
/* harmony default export */ var RTvue_type_script_lang_js_ = ({
  name: "alt-rt",
  props: {
    responsive: {
      type: Boolean,
      default: false
    },
    exportXlsx: Boolean,
    exportCsv: Boolean,
    cellUrl: {
      type: String
    },
    source: {
      type: String,
      require: true
    },
    sortDir: String,
    sortField: String,
    pageSize: {
      type: Number,
      default: 25
    },
    pageNumber: {
      type: Number,
      default: 1
    },
    selectable: Boolean
  },
  created: function created() {},
  mounted: function mounted() {
    var _this = this;

    this.total = this.$refs.table.total;
    this.from = 1;
    this.to = 1;
    this.hasFixedColumn = this.$refs.table.hasFixedColumn();
    this.columns = this.$refs.table.columns;
    this.$refs.table.$on("resize", function () {
      this.$forceUpdate();
    }); //window.addEventListener('resize', this.resize);

    this.columns.forEach(function (o) {
      _this.columnSequence.push(o.field);
    }); //console.log(this.columnSequence);

    new Sortable(this.$refs.column_menu, {
      /*    group: 'widget',
                /*onAdd: this.onAdd,*/
      onRemove: this.onRemoveColumnSequence,

      /*onEnd: this.onEnd,
                onStart: this.onStart,*/
      onUpdate: this.onUpdateColumnSequence
    });
  },
  computed: {},
  data: function data() {
    var storage = $.localStorage.get(this.source) || {};
    var data = {
      columns: [],
      hasFixedColumn: false,
      tableResponsive: this.responsive,
      from: 0,
      to: 0,
      total: 0,
      page_size: this.pageSize,
      page_count: 0,
      page: this.pageNumber,
      columnSequence: []
    };

    if (typeof storage.responsive !== "undefined") {
      data.tableResponsive = storage.responsive;
    }

    if (typeof storage.page_size !== "undefined") {
      data.page_size = parseInt(storage.page_size);
    }

    return data;
  },
  methods: {
    onRemoveColumnSequence: function onRemoveColumnSequence(evt) {
      this.columnSequence.splice(evt.oldIndex, 1);
    },
    onUpdateColumnSequence: function onUpdateColumnSequence(evt) {
      var field = evt.item.dataset.field;
      this.columnSequence.splice(evt.oldIndex, 1);
      this.columnSequence.splice(evt.nexIndex, 0, field);
    },
    getColumn: function getColumn(field) {
      return this.columns.find(function (c) {
        return c.field == field;
      });
    },
    resetLocaStorage: function resetLocaStorage() {
      $.localStorage.remove(this.source);
      this.$refs.table.reset();
    },
    showExport: function showExport() {
      return this.exportXlsx || this.exportCsv;
    },
    exportFile: function exportFile(type) {
      return this.$refs.table.exportFile(type);
    },
    tableContainerStyle: function tableContainerStyle() {
      var style = {};

      if (this.responsive) {
        style.overflow = "auto";
      }

      return style;
    },
    resized: function resized() {
      this.$forceUpdate();
    },
    resize: function resize() {
      this.$forceUpdate();
    },
    fixedColumns: function fixedColumns() {
      if (!this.$refs.table.hasFixedColumn()) {
        return [];
      }

      var cs = this.$refs.table.columns.filter(function (o) {
        return o.fixed;
      });
      return cs;
    },
    getData: function getData() {
      return this.$refs.table.data;
    },
    toggleResponsive: function toggleResponsive() {
      this.tableResponsive = !this.tableResponsive; //save

      var storage = $.localStorage.get(this.source) || {};
      storage.responsive = this.tableResponsive;
      $.localStorage.set(this.source, storage);
    },
    refreshed: function refreshed() {
      var table = this.$refs.table;
      this.total = table.getTotal();
      this.from = table.getFrom();
      this.to = table.getTo();
      this.page = table.getPage();
      this.page_count = table.getPageCount();
      this.$forceUpdate();
    },
    changePageSize: function changePageSize() {
      this.$refs.table.setPageSize(this.page_size); //save

      var storage = $.localStorage.get(this.source) || {};
      storage.page_size = this.page_size;
      $.localStorage.set(this.source, storage);
    }
  }
});
// CONCATENATED MODULE: ./src/RT.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_RTvue_type_script_lang_js_ = (RTvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/RT.vue?vue&type=style&index=0&lang=css&
var RTvue_type_style_index_0_lang_css_ = __webpack_require__("42d3");

// CONCATENATED MODULE: ./src/RT.vue






/* normalize component */

var RT_component = normalizeComponent(
  src_RTvue_type_script_lang_js_,
  RTvue_type_template_id_6db09736_render,
  RTvue_type_template_id_6db09736_staticRenderFns,
  false,
  null,
  null,
  null
  
)

RT_component.options.__file = "RT.vue"
/* harmony default export */ var RT = (RT_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RT2.vue?vue&type=template&id=76dfd31c&
var RT2vue_type_template_id_76dfd31c_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c("alt-box",{ref:"box",tag:"div",staticClass:"box no-border"},[_c("alt-box-body",{tag:"div",staticClass:"box-body no-padding",class:{'table-responsive':!_vm.responsive}},[_c('table',{ref:"table",staticClass:"table table-hover table-condensed table-bordered rt"},[_c('thead',[_c('tr',[(_vm.hasHideColumn)?_c('td',[_c('button',{staticClass:"btn btn-default btn-xs",on:{"click":_vm.toggleChild}},[(!_vm.showChild)?_c('i',{staticClass:"fa fa-fw fa-chevron-up"}):_vm._e(),(_vm.showChild)?_c('i',{staticClass:"fa fa-fw fa-chevron-down"}):_vm._e()])]):_vm._e(),_vm._l((_vm.visibleColumns),function(column,key){return _c("alt-column",_vm._b({key:key,ref:"column",refInFor:true,tag:"th",on:{"order":_vm.order,"draw":_vm.draw,"search":function($event){_vm.$emit('search',$event)}}},'th',column.$data,false))})],2),(_vm.isSearchable)?_c('tr',[(_vm.hasHideColumn)?_c('td'):_vm._e(),_vm._l((_vm.visibleColumns),function(column,key){return _c("alt-column-search",_vm._b({key:key,tag:"td",on:{"search":_vm.search}},'td',column.$data,false))})],2):_vm._e()]),_c("rt2-tbody",{ref:"tbody",tag:"tbody",attrs:{"selectable":_vm.selectable,"data":_vm.data(),"columns":_vm.columns},on:{"update-data":_vm.updateData,"data-deleted":_vm.draw}})])]),_c('div',{staticClass:"box-footer"},[_c('rt-pagination',{attrs:{"page":_vm.page,"page-count":_vm.pageCount},on:{"change-page":_vm.gotoPage,"first-page":_vm.firstPage,"next-page":_vm.nextPage,"prev-page":_vm.prevPage,"last-page":_vm.lastPage}}),_c('div',{staticClass:"pull-left btn-group"},[_c('button',{staticClass:"btn btn-default btn-sm",attrs:{"type":"button","title":"重新載入","data-toggle":"tooltip"},on:{"click":_vm.draw}},[_c('i',{staticClass:"fa fa-sync-alt"})])]),_c('div',{staticClass:"pull-left"},[_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.local.pageLength),expression:"local.pageLength"}],staticClass:"form-control input-sm",on:{"change":[function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.$set(_vm.local, "pageLength", $event.target.multiple ? $$selectedVal : $$selectedVal[0])},_vm.onChangePageLength]}},[_c('option',{attrs:{"value":"10"}},[_vm._v("10")]),_c('option',{attrs:{"value":"25"}},[_vm._v("25")]),_c('option',{attrs:{"value":"50"}},[_vm._v("50")]),_c('option',{attrs:{"value":"100"}},[_vm._v("100")]),_c('option',{attrs:{"value":"500"}},[_vm._v("500")])])]),_c('div',{staticClass:"pull-left dropup"},[_vm._m(0),_c('ul',{ref:"column_menu",staticClass:"dropdown-menu"},_vm._l((_vm.columnsHasTitle),function(column,key){return _c('li',{key:key},[_c('a',{staticClass:"small",attrs:{"href":"#","data-value":"option1","tabindex":"-1"},on:{"click":function($event){$event.preventDefault();column.toggleVisible()}}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(column.isVisible),expression:"column.isVisible"}],attrs:{"type":"checkbox"},domProps:{"checked":Array.isArray(column.isVisible)?_vm._i(column.isVisible,null)>-1:(column.isVisible)},on:{"change":function($event){var $$a=column.isVisible,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(column, "isVisible", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(column, "isVisible", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(column, "isVisible", $$c)}}}}),_vm._v("\n             "+_vm._s(column.title)+"\n          ")])])}))]),_c('div',{staticClass:"pull-left btn-group"},[_c('button',{staticClass:"btn btn-default btn-sm",class:{active:_vm.responsive},attrs:{"type":"button","title":"Responsive","data-toggle":"tooltip"},on:{"click":_vm.toggleResponsive}},[_c('i',{staticClass:"fa fa-tv"})]),_c('button',{staticClass:"btn btn-default btn-sm",attrs:{"type":"button","title":"clear cache","data-toggle":"tooltip"},on:{"click":_vm.resetLocaStorage}},[_c('i',{staticClass:"fa fa-times-circle"})])]),(_vm.hasExport())?_c('div',{staticClass:"pull-left dropdown"},[_vm._m(1),_c('ul',{staticClass:"dropdown-menu",attrs:{"aria-labelledby":"dropdownMenu1"}},[(_vm.exports.indexOf('xlsx')>=0)?_c('li',[_c('a',{attrs:{"href":"#"},on:{"click":function($event){$event.preventDefault();_vm.exportFile('xlsx')}}},[_vm._v("XLSX")])]):_vm._e(),(_vm.exports.indexOf('csv')>=0)?_c('li',[_c('a',{attrs:{"href":"#"},on:{"click":function($event){$event.preventDefault();_vm.exportFile('csv')}}},[_vm._v("CSV")])]):_vm._e()])]):_vm._e(),_c('div',{staticClass:"pull-left btn-group"},_vm._l((_vm.buttons),function(button,index){return _c('button',{key:index,staticClass:"btn btn-default btn-sm",attrs:{"type":"button"},domProps:{"textContent":_vm._s(button.text)},on:{"click":function($event){_vm.onClickButton(button)}}})})),_c('rt-info',_vm._b({staticClass:"pull-right"},'rt-info',_vm.info,false))],1)])}
var RT2vue_type_template_id_76dfd31c_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('button',{staticClass:"btn btn-default btn-sm dropdown-toggle",attrs:{"type":"button","data-toggle":"dropdown"}},[_c('span',{staticClass:"icon glyphicon glyphicon-th-list"})])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('button',{staticClass:"btn btn-default btn-sm dropdown-toggle",attrs:{"type":"button","data-toggle":"dropdown"}},[_vm._v("\n        Export\n        "),_c('span',{staticClass:"caret"})])}]


// CONCATENATED MODULE: ./src/RT2.vue?vue&type=template&id=76dfd31c&

// EXTERNAL MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RT2.vue?vue&type=script&lang=js&
var RT2vue_type_script_lang_js_ = __webpack_require__("6d2c");

// CONCATENATED MODULE: ./src/RT2.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_RT2vue_type_script_lang_js_ = (RT2vue_type_script_lang_js_["a" /* default */]); 
// EXTERNAL MODULE: ./src/RT2.vue?vue&type=style&index=0&lang=css&
var RT2vue_type_style_index_0_lang_css_ = __webpack_require__("18c1");

// CONCATENATED MODULE: ./src/RT2.vue






/* normalize component */

var RT2_component = normalizeComponent(
  src_RT2vue_type_script_lang_js_,
  RT2vue_type_template_id_76dfd31c_render,
  RT2vue_type_template_id_76dfd31c_staticRenderFns,
  false,
  null,
  null,
  null
  
)

RT2_component.options.__file = "RT2.vue"
/* harmony default export */ var RT2 = (RT2_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTPagination.vue?vue&type=template&id=5d309ce9&
var RTPaginationvue_type_template_id_5d309ce9_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"btn-group pull-left"},[_c('button',{staticClass:"btn btn-default btn-sm",attrs:{"data-toggle":"tooltip","title":"最前一頁","type":"button","disabled":_vm.firstPageDisabled},on:{"click":function($event){$event.preventDefault();_vm.$emit('first-page')}}},[_c('span',{staticClass:"glyphicon glyphicon-step-backward"})]),_c('button',{staticClass:"btn btn-default btn-sm",attrs:{"data-toggle":"tooltip","title":"上一頁","type":"button","disabled":_vm.prevPageDisabled},on:{"click":function($event){$event.preventDefault();_vm.$emit('prev-page')}}},[_c('span',{staticClass:"glyphicon glyphicon-chevron-left"})]),_c('div',{staticClass:"pull-left",staticStyle:{"user-select":"none"}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.p),expression:"p"}],staticClass:"form-control input-sm",staticStyle:{"width":"60px"},attrs:{"min":"1","max":_vm.pageCount,"type":"number"},domProps:{"value":(_vm.p)},on:{"change":_vm.changePage,"input":function($event){if($event.target.composing){ return; }_vm.p=$event.target.value}}})]),_c('button',{staticClass:"btn btn-default btn-sm",attrs:{"data-toggle":"tooltip","title":"下一頁","type":"button","disabled":_vm.nextPageDisabled},on:{"click":function($event){$event.preventDefault();_vm.$emit('next-page')}}},[_c('span',{staticClass:"glyphicon glyphicon-chevron-right"})]),_c('button',{staticClass:"btn btn-default btn-sm",attrs:{"data-toggle":"tooltip","title":"最後一頁","type":"button","disabled":_vm.lastPageDisabled},on:{"click":function($event){$event.preventDefault();_vm.$emit('last-page')}}},[_c('span',{staticClass:"glyphicon glyphicon-step-forward"})])])}
var RTPaginationvue_type_template_id_5d309ce9_staticRenderFns = []


// CONCATENATED MODULE: ./src/RTPagination.vue?vue&type=template&id=5d309ce9&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTPagination.vue?vue&type=script&lang=js&

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
//
//
//
//
/* harmony default export */ var RTPaginationvue_type_script_lang_js_ = ({
  name: "rt-pagination",
  props: {
    page: {
      type: Number,
      require: true,
      default: 1
    },
    pageCount: {
      type: Number,
      default: 1
    }
  },
  computed: {
    firstPageDisabled: function firstPageDisabled() {
      return this.page <= 1;
    },
    prevPageDisabled: function prevPageDisabled() {
      return this.page <= 1;
    },
    nextPageDisabled: function nextPageDisabled() {
      return this.pageCount == this.page;
    },
    lastPageDisabled: function lastPageDisabled() {
      return this.pageCount == this.page;
    }
  },
  watch: {
    page: function page(v) {
      //   console.log(v);
      this.p = v;
    }
  },
  data: function data() {
    return {
      p: this.page
    };
  },
  methods: {
    changePage: function changePage() {
      this.$emit("change-page", this.p);
    }
  }
});
// CONCATENATED MODULE: ./src/RTPagination.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_RTPaginationvue_type_script_lang_js_ = (RTPaginationvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/RTPagination.vue





/* normalize component */

var RTPagination_component = normalizeComponent(
  src_RTPaginationvue_type_script_lang_js_,
  RTPaginationvue_type_template_id_5d309ce9_render,
  RTPaginationvue_type_template_id_5d309ce9_staticRenderFns,
  false,
  null,
  null,
  null
  
)

RTPagination_component.options.__file = "RTPagination.vue"
/* harmony default export */ var RTPagination = (RTPagination_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RT2Tbody.vue?vue&type=template&id=1fbb9142&
var RT2Tbodyvue_type_template_id_1fbb9142_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('tbody',[_vm._l((_vm.data),function(d,index){return [_c('tr',{key:index,class:_vm.getRowClass(d),style:(_vm.getStyle(d)),on:{"click":function($event){_vm.onClickRow(d)}}},[(_vm.hasHideColumn)?_c('td',[_c('button',{staticClass:"btn btn-default btn-xs",on:{"click":function($event){_vm.toggleRowChild(index)},"mouseenter":function($event){_vm.mouseEnterRow(index)},"mouseleave":function($event){_vm.mouseLeaveRow(index)}}},[(!_vm.showIndex[index])?_c('i',{staticClass:"fa fa-fw fa-chevron-up"}):_vm._e(),(_vm.showIndex[index])?_c('i',{staticClass:"fa fa-fw fa-chevron-down"}):_vm._e()])]):_vm._e(),_vm._l((_vm.visibleColumns),function(column,key){return _c('td',{key:key,style:(column.cell(d).style),on:{"click":function($event){_vm.onClickCell(column,index)}}},[(_vm.isEditMode(column,index))?[(column.editType=='text')?[_c('input',{staticClass:"form-control input-sm",attrs:{"type":"text"},domProps:{"value":column.getValue(d)},on:{"blur":function($event){_vm.updateData(index,d,column,$event.target.value)}}})]:(column.editType=='select')?[_c('select',{staticClass:"formControl",on:{"blur":function($event){_vm.updateData(index,d,column,$event.target.value)}}},_vm._l((column.editData),function(opt,opt_key){return _c('option',{key:opt_key,domProps:{"value":opt.value,"selected":opt.value==column.getValue(d).value,"textContent":_vm._s(opt.label)}})}))]:(column.editType=='date')?[_c('input',{staticClass:"form-control input-sm",attrs:{"type":"text"},domProps:{"value":column.getValue(d)},on:{"blur":function($event){_vm.updateData(index,d,column,$event.target.value)}}})]:_vm._e()]:[(column.cell(d).type=='html')?_c('div',{style:(column.cell(d).divStyle),domProps:{"innerHTML":_vm._s(column.getContent(d))}}):_vm._e(),(column.cell(d).type=='text')?_c('div',{style:(column.cell(d).divStyle),domProps:{"textContent":_vm._s(column.getContent(d))}}):_vm._e(),(column.type=='checkbox')?_c("icheck",{tag:"input",attrs:{"type":"checkbox"}}):_vm._e(),(column.type=='deletes')?_c('input',{attrs:{"type":"checkbox"}}):(column.cell(d).type=='delete')?_c('button',{staticClass:"btn btn-xs btn-danger",on:{"click":function($event){_vm.deleteRow(d[column.data].content)}}},[_c('i',{staticClass:"fa fa-fw fa-times"})]):(column.type=='sub-row')?_c('button',{staticClass:"btn btn-xs btn-default",on:{"click":function($event){_vm.toggleSubRow(index,_vm.r)}}},[(_vm.subRow[index])?_c('i',{staticClass:"fa fa-fw fa-minus"}):_vm._e(),(!_vm.subRow[index])?_c('i',{staticClass:"fa fa-fw fa-plus"}):_vm._e()]):(column.type=='link')?_c('a',{attrs:{"href":column.href},domProps:{"innerHTML":_vm._s(column.content)}}):_vm._e()]],2)})],2),_c('tr',{directives:[{name:"show",rawName:"v-show",value:(_vm.showChild(index)),expression:"showChild(index)"}],key:index,staticClass:"child"},[_c('td',{attrs:{"colspan":_vm.showColumnCount}},[_c('ul',_vm._l((_vm.hideColumns),function(column,key){return _c('li',{key:key},[_c('b',{domProps:{"innerHTML":_vm._s(column.title)}}),_vm._v("  \n            "),(column.cell(d).type=='html')?_c('span',{domProps:{"innerHTML":_vm._s(column.getContent(d))}}):_vm._e(),(column.cell(d).type=='text')?_c('span',{domProps:{"textContent":_vm._s(column.getContent(d))}}):_vm._e()])}))])])]})],2)}
var RT2Tbodyvue_type_template_id_1fbb9142_staticRenderFns = []


// CONCATENATED MODULE: ./src/RT2Tbody.vue?vue&type=template&id=1fbb9142&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RT2Tbody.vue?vue&type=script&lang=js&

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
/* harmony default export */ var RT2Tbodyvue_type_script_lang_js_ = ({
  name: "rt2-tbody",
  props: {
    data: Array,
    columns: Array,
    selectable: Boolean
  },
  data: function data() {
    return {
      hoverChild: [],
      showIndex: [],
      editMode: false,
      editColumn: null,
      editIndex: null,
      selectedData: []
    };
  },
  computed: {
    hideColumns: function hideColumns() {
      return this.columns.filter(function (column) {
        return column.hide;
      });
    },
    visibleColumns: function visibleColumns() {
      return this.columns.filter(function (column) {
        return column.isDisplay();
      });
    },
    hasHideColumn: function hasHideColumn() {
      return this.columns.some(function (o) {
        return o.hide;
      });
    },
    showColumnCount: function showColumnCount() {
      return this.columns.filter(function (c) {
        return !c.hide;
      }).length + 1;
    }
  },
  methods: {
    getStyle: function getStyle(d) {
      if (d.__row__.style) {
        return d.__row__.style;
      }
    },
    getRowClass: function getRowClass(d) {
      var c = [];

      if (this.isSelected(d)) {
        c.push("selected");
      }

      if (d.__row__) {
        c = c.concat(d.__row__.class);
      }

      return c;
    },
    deleteRow: function deleteRow(uri) {
      var _this = this;

      if (confirm("Are your sure?")) {
        this.$http.delete(uri).then(function () {
          _this.$emit("data-deleted");
        });
      }
    },
    isSelected: function isSelected(d) {
      return this.selectedData.indexOf(d) >= 0;
    },
    onClickRow: function onClickRow(d) {
      if (!this.selectable) return;

      if (this.isSelected(d)) {
        var index = this.selectedData.indexOf(d);
        this.selectedData.splice(index, 1);
      } else {
        this.selectedData.push(d);
      }
    },
    isEditMode: function isEditMode(column, index) {
      if (!this.editMode) return false;

      if (this.editColumn == column && this.editIndex == index) {
        return true;
      }

      return false;
    },
    onClickCell: function onClickCell(column, index) {
      if (!column.editable) return false;
      this.editMode = true;
      this.editColumn = column;
      this.editIndex = index;
    },
    showAllChild: function showAllChild() {
      var _this2 = this;

      this.data.forEach(function (o, i) {
        _this2.showIndex[i] = true;
      });
      this.$forceUpdate();
    },
    hideAllChild: function hideAllChild() {
      var _this3 = this;

      this.data.forEach(function (o, i) {
        _this3.showIndex[i] = false;
      });
      this.$forceUpdate();
    },
    toggleRowChild: function toggleRowChild(index) {
      this.showIndex[index] = !this.showIndex[index];
      this.$forceUpdate();
    },
    showChild: function showChild(index) {
      if (this.hoverChild[index]) return true;
      return this.showIndex[index];
    },
    mouseLeaveRow: function mouseLeaveRow(index) {
      this.hoverChild[index] = false;
      this.$forceUpdate();
    },
    mouseEnterRow: function mouseEnterRow(index) {
      this.hoverChild[index] = true;
      this.$forceUpdate();
    },
    updateData: function updateData(index, r, column, value) {
      if (!column.editable) return;
      this.editMode = false;

      if (column.editType == "text") {
        if (column.getValue(r) != value) {
          r[column.data] = value;
          this.$emit("update-data", r._key, column.data, value);
        }

        return;
      }

      if (column.editType == "select") {
        if (r[column.data].value != value) {
          r[column.data].value = value;
          r[column.data].content = column.editData[value].label;
          this.$emit("update-data", r._key, column.data, value);
        }
      }
      /* else {
                    if (r[column.field] != value) {
                        r[column.field] = value;
                        this.$emit("update-data", r._key, column.field, value);
                    }
                }*/

    }
  }
});
// CONCATENATED MODULE: ./src/RT2Tbody.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_RT2Tbodyvue_type_script_lang_js_ = (RT2Tbodyvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/RT2Tbody.vue





/* normalize component */

var RT2Tbody_component = normalizeComponent(
  src_RT2Tbodyvue_type_script_lang_js_,
  RT2Tbodyvue_type_template_id_1fbb9142_render,
  RT2Tbodyvue_type_template_id_1fbb9142_staticRenderFns,
  false,
  null,
  null,
  null
  
)

RT2Tbody_component.options.__file = "RT2Tbody.vue"
/* harmony default export */ var RT2Tbody = (RT2Tbody_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTInfo.vue?vue&type=template&id=6166295c&
var RTInfovue_type_template_id_6166295c_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_vm._v(_vm._s(_vm.from)+" - "+_vm._s(_vm.to)+" of "+_vm._s(_vm.total))])}
var RTInfovue_type_template_id_6166295c_staticRenderFns = []


// CONCATENATED MODULE: ./src/RTInfo.vue?vue&type=template&id=6166295c&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTInfo.vue?vue&type=script&lang=js&
//
//
//
//
/* harmony default export */ var RTInfovue_type_script_lang_js_ = ({
  name: "rt-info",
  props: ["from", "to", "total"]
});
// CONCATENATED MODULE: ./src/RTInfo.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_RTInfovue_type_script_lang_js_ = (RTInfovue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/RTInfo.vue





/* normalize component */

var RTInfo_component = normalizeComponent(
  src_RTInfovue_type_script_lang_js_,
  RTInfovue_type_template_id_6166295c_render,
  RTInfovue_type_template_id_6166295c_staticRenderFns,
  false,
  null,
  null,
  null
  
)

RTInfo_component.options.__file = "RTInfo.vue"
/* harmony default export */ var RTInfo = (RTInfo_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Column.vue?vue&type=template&id=78c821ac&
var Columnvue_type_template_id_78c821ac_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('th',{staticClass:"unselectable",class:{
      sortable:_vm.orderable,
      sorting_desc:(_vm.orderDir=='desc'),
      sorting_asc:(_vm.orderDir=='asc')
  },style:(_vm.getStyle()),on:{"click":_vm.click}},[(_vm.type=='checkbox')?_c("icheck",{tag:"input",attrs:{"type":"checkbox"},on:{"change":_vm.checkboxChange}}):_c('div',{domProps:{"textContent":_vm._s(_vm.title)}})])}
var Columnvue_type_template_id_78c821ac_staticRenderFns = []


// CONCATENATED MODULE: ./src/Column.vue?vue&type=template&id=78c821ac&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Column.vue?vue&type=script&lang=js&

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
/* harmony default export */ var Columnvue_type_script_lang_js_ = ({
  name: "alt-column",
  props: {
    name: String,
    data: String,
    title: String,
    orderable: Boolean,
    orderDir: String,
    isVisible: {
      type: Boolean,
      default: true
    },
    width: String,
    minWidth: String,
    maxWidth: String,
    overflow: String,
    type: String
  },
  data: function data() {
    return {
      hide: false,
      local: {
        orderDir: this.orderDir
      }
    };
  },
  methods: {
    checkboxChange: function checkboxChange(e) {
      console.log(e.target.checked);
    },
    click: function click() {
      if (!this.orderable) return;

      if (this.local.orderDir == "desc") {
        this.order("asc");
      } else {
        this.order("desc");
      }

      this.draw();
    },
    search: function search(_search) {
      this.local.search = _search;
      return this;
    },
    order: function order(dir) {
      if (!this.orderable) return this;
      this.local.orderDir = dir;
      this.$emit("order", [this.name, dir]);
      return this;
    },
    draw: function draw() {
      this.$emit("draw");
      return this;
    },
    getStyle: function getStyle() {
      var style = {};

      if (this.width) {
        style.width = this.width;
      }

      if (this.minWidth) {
        style["min-width"] = this.minWidth;
      }

      if (this.maxWidth) {
        style["max-width"] = this.maxWidth;
      }

      if (this.overflow) {
        style["overflow"] = this.overflow;
      }

      return style;
    }
  }
});
// CONCATENATED MODULE: ./src/Column.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Columnvue_type_script_lang_js_ = (Columnvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Column.vue





/* normalize component */

var Column_component = normalizeComponent(
  src_Columnvue_type_script_lang_js_,
  Columnvue_type_template_id_78c821ac_render,
  Columnvue_type_template_id_78c821ac_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Column_component.options.__file = "Column.vue"
/* harmony default export */ var Column = (Column_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/ColumnSearch.vue?vue&type=template&id=67261cc6&
var ColumnSearchvue_type_template_id_67261cc6_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('td',[(_vm.searchable && _vm.searchType=='text')?_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.search),expression:"search"}],staticClass:"form-control input-sm search",attrs:{"type":"text"},domProps:{"value":(_vm.search)},on:{"keyup":function($event){if(!('button' in $event)&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }_vm.doSearch()},"input":function($event){if($event.target.composing){ return; }_vm.search=$event.target.value}}}):_vm._e(),(_vm.searchable && _vm.searchType=='equal')?_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.search),expression:"search"}],staticClass:"form-control input-sm search",attrs:{"type":"text"},domProps:{"value":(_vm.search)},on:{"keyup":function($event){if(!('button' in $event)&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }_vm.doSearch()},"input":function($event){if($event.target.composing){ return; }_vm.search=$event.target.value}}}):_vm._e(),(_vm.searchable && _vm.searchType=='date')?_c('input',{ref:"search",staticClass:"form-control input-sm search",attrs:{"value":""}}):_vm._e(),(_vm.searchable && _vm.searchType=='select' && !_vm.searchOptGroup)?_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.search),expression:"search"}],ref:"search",staticClass:"form-control search",on:{"change":[function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.search=$event.target.multiple ? $$selectedVal : $$selectedVal[0]},function($event){_vm.doSearch()}]}},[_c('option'),_vm._l((_vm.searchOption),function(o){return _c('option',{domProps:{"value":o.value,"textContent":_vm._s(o.label)}})})],2):_vm._e(),(_vm.searchable && _vm.searchType=='select' && _vm.searchOptGroup)?_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.search),expression:"search"}],ref:"search",staticClass:"form-control search",on:{"change":[function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.search=$event.target.multiple ? $$selectedVal : $$selectedVal[0]},function($event){_vm.doSearch()}]}},[_c('option'),_vm._l((_vm.searchOptGroup),function(label,group){return _c('optgroup',{attrs:{"label":label}},_vm._l((_vm.searchOption),function(o){return (o.group==group)?_c('option',{domProps:{"value":o.value,"textContent":_vm._s(o.label)}}):_vm._e()}))})],2):_vm._e(),(_vm.searchable && _vm.searchType=='multiselect')?_c('select',{ref:"search",staticClass:"form-control search",attrs:{"multiple":_vm.searchMultiple}},[(!_vm.searchMultiple)?_c('option',{attrs:{"value":""}},[_vm._v("None selected")]):_vm._e(),_vm._l((_vm.searchOption),function(o){return (!_vm.searchOptGroup)?_c('option',{domProps:{"value":o.value,"textContent":_vm._s(o.label)}}):_vm._e()}),_vm._l((_vm.searchOptGroup),function(label,group){return (_vm.searchOptGroup)?_c('optgroup',{attrs:{"label":label}},_vm._l((_vm.searchOption),function(o){return (o.group==group)?_c('option',{domProps:{"value":o.value,"textContent":_vm._s(o.label)}}):_vm._e()})):_vm._e()})],2):_vm._e()])}
var ColumnSearchvue_type_template_id_67261cc6_staticRenderFns = []


// CONCATENATED MODULE: ./src/ColumnSearch.vue?vue&type=template&id=67261cc6&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es6.regexp.split.js
var es6_regexp_split = __webpack_require__("28a5");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es6.regexp.search.js
var es6_regexp_search = __webpack_require__("386d");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/ColumnSearch.vue?vue&type=script&lang=js&



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
/* harmony default export */ var ColumnSearchvue_type_script_lang_js_ = ({
  name: "alt-column-search",
  props: {
    name: String,
    data: String,
    searchOption: Array,
    searchType: String,
    searchable: Boolean,
    searchOptGroup: Array,
    searchMultiple: Boolean
  },
  data: function data() {
    return {
      search: ""
    };
  },
  mounted: function mounted() {
    var _this = this;

    if (this.searchType == "text") {//console.log(this.$refs.search);
    }

    if (this.searchType == "multiselect") {
      var search = $(this.$refs.search);
      search.multiselect({
        enableFiltering: true,
        buttonWidth: "100%"
      });
      search.on("change", function () {
        _this.$emit("search", _this.name, search.val());
      });
      return;
    }

    if (this.searchType == "date") {
      var search = $(this.$refs.search);
      search.keypress(function (e) {
        if (e.which == 13) {
          var v = _this.$refs.search.value;

          if (v == "") {
            _this.search = "";

            _this.doSearch();
          } else if (v.indexOf("to") >= 0) {
            var s = {}; //range

            var a = v.split("to");
            s.from = a[0].trim();
            s.to = a[1].trim();
            _this.search = s;

            _this.doSearch();
          } else {
            var s = {};
            s.from = v;
            s.to = v;
            _this.search = s;

            _this.doSearch();
          }
        }
      });
      search.daterangepicker({
        //singleDatePicker: true,
        opens: "center",
        showDropdowns: true,
        //"autoApply": true,
        autoUpdateInput: false,
        locale: {
          format: "YYYY-MM-DD",
          cancelLabel: "Clear"
        },
        ranges: {
          Today: [moment(), moment()],
          Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
          "Last 7 Days": [moment().subtract(6, "days"), moment()],
          "Last 30 Days": [moment().subtract(29, "days"), moment()],
          "This Month": [moment().startOf("month"), moment().endOf("month")],
          "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
          "This Year": [moment().startOf("year"), moment().endOf("year")],
          "Last Year": [moment().subtract(1, "year").startOf("year"), moment().subtract(1, "year").endOf("year")]
        }
      });
      search.on("apply.daterangepicker", function (ev, picker) {
        console.log("update");

        if (picker.startDate.format("YYYY-MM-DD") == picker.endDate.format("YYYY-MM-DD")) {
          search.val(picker.startDate.format("YYYY-MM-DD"));
        } else {
          search.val(picker.startDate.format("YYYY-MM-DD") + " to " + picker.endDate.format("YYYY-MM-DD"));
        }

        var s = {};
        s.from = picker.startDate.format("YYYY-MM-DD");
        s.to = picker.endDate.format("YYYY-MM-DD");
        _this.search = s;

        _this.doSearch();
      });
      search.on("cancel.daterangepicker", function (ev, picker) {
        search.val("");
        _this.search = "";

        _this.doSearch();
      });
    }
  },
  methods: {
    doSearch: function doSearch() {
      this.$emit("search", this.name, this.search);
    }
  }
});
// CONCATENATED MODULE: ./src/ColumnSearch.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_ColumnSearchvue_type_script_lang_js_ = (ColumnSearchvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/ColumnSearch.vue





/* normalize component */

var ColumnSearch_component = normalizeComponent(
  src_ColumnSearchvue_type_script_lang_js_,
  ColumnSearchvue_type_template_id_67261cc6_render,
  ColumnSearchvue_type_template_id_67261cc6_staticRenderFns,
  false,
  null,
  null,
  null
  
)

ColumnSearch_component.options.__file = "ColumnSearch.vue"
/* harmony default export */ var ColumnSearch = (ColumnSearch_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTTable.vue?vue&type=template&id=590e476d&
var RTTablevue_type_template_id_590e476d_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('table',{staticClass:"table table-hover table-condensed"},[_c("rt-head",{tag:"thead",attrs:{"hide-index":_vm.hideIndex},on:{"toggle-child":_vm.toggleChild,"toggle-visible":_vm.toggleVisible,"sort":_vm.sortByColumn,"search":_vm.search}},[_vm._t("default")],2),_c("rt-body",{ref:"body",tag:"tbody",attrs:{"columns":_vm.columns,"data":_vm.data},on:{"update-data":_vm.updateData,"data-deleted":_vm.refresh}})])}
var RTTablevue_type_template_id_590e476d_staticRenderFns = []


// CONCATENATED MODULE: ./src/RTTable.vue?vue&type=template&id=590e476d&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es6.array.sort.js
var es6_array_sort = __webpack_require__("55dd");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTTable.vue?vue&type=script&lang=js&




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
/* harmony default export */ var RTTablevue_type_script_lang_js_ = ({
  name: "rt-table",
  props: {
    dataUrl: {
      type: String
    },
    cellUrl: {
      type: String
    },
    responsive: {
      type: Boolean,
      default: false
    },
    sortField: String,
    sortDir: String,
    pageSize: {
      type: Number,
      default: 25
    },
    pageNumber: {
      type: Number,
      default: 1
    },
    selectable: Boolean
  },
  watch: {
    page: function page() {
      console.log("page changed:" + this.page);
    },
    responsive: function responsive() {
      this.resize();
    }
  },
  data: function data() {
    var storage = $.localStorage.get(this.dataUrl);
    var data = {
      draw: 0,
      page: 1,
      data: [],
      columns: [],
      page_size: this.pageSize,
      total: 0,
      sort: this.sortField,
      sort_dir: this.sortDir,
      searchData: {},
      hideIndex: -1
    };

    if (storage) {
      if (storage.page) {
        data.page = storage.page;
      }

      if (storage.order.column) {
        data.sort = storage.order.column;
      }

      if (storage.order.dir) {
        data.sort_dir = storage.order.dir;
      }
    }

    return data;
  },
  mounted: function mounted() {
    var _this = this;

    //get the columns
    var cs = this.$slots.default.filter(function (o) {
      if (o.componentOptions == undefined) return;
      return o.componentOptions.tag == "rt-column";
    });
    this.columns = cs.map(function (c) {
      return c.componentInstance;
    });
    this.columns.forEach(function (c, i) {
      c.index = i;
    });
    this.refresh();
    this.columns.forEach(function (o) {
      if (o.field == _this.sort) {
        o.dir = _this.sort_dir;
      } else {
        o.dir = null;
      }
    });
    window.addEventListener("resize", this.resize);
  },
  computed: {},
  methods: {
    toggleVisible: function toggleVisible(data) {
      data.RT2 = 1;
      data.uri = this.dataUrl;
      this.$http.post("UI/save", data).then(function (resp) {
        console.log(resp);
      });
    },
    hasFixedColumn: function hasFixedColumn() {
      return this.columns.some(function (c) {
        return c.fixed;
      });
    },
    toggleChild: function toggleChild(value) {
      this.$refs.body.toggleChild(value);
    },
    updateData: function updateData(key, field, value) {
      if (!this.cellUrl) {
        console.log("cell-url not found");
        return;
      }

      this.$http.post(this.cellUrl, {
        _pk: key,
        name: field,
        value: value
      }).then(function (resp) {
        console.log("done");
      });
    },
    search: function search(data) {
      this.searchData = data;
      this.page = 1;
      this.refresh();
    },
    sortByColumn: function sortByColumn(a) {
      this.sortBy(a.field, a.dir);
    },
    getPage: function getPage() {
      return this.page;
    },
    getTo: function getTo() {
      var i = this.page_size * this.page;
      return Math.min(i, this.total);
    },
    getFrom: function getFrom() {
      return this.page_size * (this.page - 1) + 1;
    },
    getPageCount: function getPageCount() {
      return Math.ceil(this.total / this.page_size);
    },
    getTotal: function getTotal() {
      return this.total;
    },
    setPageSize: function setPageSize(p) {
      this.page_size = p;
      this.page = 1;
      this.refresh();
    },
    doSomething: function doSomething() {
      console.log("do something");
    },
    firstPage: function firstPage() {
      this.page = 1;
      this.refresh();
    },
    gotoPage: function gotoPage(page) {
      this.page = parseInt(page);
      this.refresh();
    },
    prevPage: function prevPage() {
      this.page--;
      this.refresh();
    },
    nextPage: function nextPage() {
      this.page++;
      this.refresh();
    },
    lastPage: function lastPage() {
      this.page = this.getPageCount();
      this.refresh();
    },
    sortBy: function sortBy(name, dir) {
      this.sort = name;

      if (dir == undefined) {
        this.sort_dir = "asc";
      } else {
        this.sort_dir = dir;
      }

      this.refresh();
    },
    exportFile: function exportFile(type) {
      this.draw++;
      this.$http.get(this.dataUrl, {
        params: {
          draw: this.draw,
          page: this.page,
          column: this.columns.map(function (s) {
            return s.field;
          }),
          order: [{
            column: this.sort,
            dir: this.sort_dir
          }],
          search: this.searchData,
          type: type
        },
        responseType: "arraybuffer"
      }).then(function (response) {
        console.log(response);
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
    reset: function reset() {
      this.page = 1;
      this.page_size = 25;
      this.columns.forEach(function (o) {
        o.dir = null;
      });
      this.searchData = {};
      this.sort = null;
      this.sort_dir = null;
      this.refresh();
    },
    refresh: function refresh() {
      var storage = $.localStorage.get(this.dataUrl) || {};
      storage.page = this.page;
      storage.order = {
        column: this.sort,
        dir: this.sort_dir
      };
      $.localStorage.set(this.dataUrl, storage);
      this.$emit("loading");
      this.draw++;
      this.$http.get(this.dataUrl, {
        params: {
          dataUrl: this.dataUrl,
          draw: this.draw,
          page: this.page,
          length: this.page_size,
          column: this.columns.map(function (s) {
            return s.field;
          }),
          order: [{
            column: this.sort,
            dir: this.sort_dir
          }],
          search: this.searchData
        }
      }).then(function (r) {
        if (r.data.draw < this.draw) {
          return;
        }

        this.data = r.data.data;
        this.total = r.data.total;
        this.$emit("loaded");
        this.$emit("refreshed");
        this.resize();
      });
    },
    resize: function resize() {
      //show all column
      this.columns.forEach(function (c) {
        c.hide = false;
      });
      this.$nextTick(function () {
        this.$emit("resized");
      });
      if (!this.responsive) return;
      this.$nextTick(function () {
        //get parent width
        var parentWidth = this.$el.parentElement.offsetWidth; //console.log("parent width", parentWidth);

        var total = 0; //show

        var hide_index = -1;
        this.columns.forEach(function (c, i) {
          if (hide_index >= 0) return;

          if (total + 29 > parentWidth) {
            hide_index = i;
            return;
          }

          var w = c.$el.offsetWidth;
          total += w;

          if (total > parentWidth) {
            hide_index = i;
          }
        });

        if (hide_index >= 0) {
          this.columns.forEach(function (c, i) {
            if (i >= hide_index) {
              c.hide = true;
            }
          });
        }

        this.$emit("resized");
      });
    }
  }
});
// CONCATENATED MODULE: ./src/RTTable.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_RTTablevue_type_script_lang_js_ = (RTTablevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/RTTable.vue





/* normalize component */

var RTTable_component = normalizeComponent(
  src_RTTablevue_type_script_lang_js_,
  RTTablevue_type_template_id_590e476d_render,
  RTTablevue_type_template_id_590e476d_staticRenderFns,
  false,
  null,
  null,
  null
  
)

RTTable_component.options.__file = "RTTable.vue"
/* harmony default export */ var RTTable = (RTTable_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTHead.vue?vue&type=template&id=0f36ae61&
var RTHeadvue_type_template_id_0f36ae61_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('thead',[_c('tr',[(_vm.hasHideColumn)?_c('th',{staticClass:"width:29px;max-width:29px"},[_c('button',{staticClass:"btn btn-default btn-xs",on:{"click":_vm.toggleChild}},[(!_vm.showChild)?_c('i',{staticClass:"fa fa-fw fa-chevron-up"}):_vm._e(),(_vm.showChild)?_c('i',{staticClass:"fa fa-fw fa-chevron-down"}):_vm._e()])]):_vm._e(),_vm._t("default")],2),(_vm.hasSearchColumn)?_c('tr',[(_vm.hasHideColumn)?_c('td'):_vm._e(),_vm._l((_vm.columns),function(column,key){return (column.display())?_c('td',{key:key},[(column.searchable)?[(column.searchType=='text')?[_c('div',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.search[column.field]),expression:"search[column.field]"}],staticClass:"form-control input-sm search",attrs:{"type":"text"},domProps:{"value":(_vm.search[column.field])},on:{"keyup":function($event){if(!('button' in $event)&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }_vm.doSearch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.search, column.field, $event.target.value)}}})])]:_vm._e(),(column.searchType=='date')?[_c('div',{staticClass:"date input-group input-group-sm"},[_c('input',{staticClass:"form-control date",attrs:{"placeholder":"from","data-field":column.field,"data-search":"from"}}),_c('span',{staticClass:"input-group-btn"},[_c('button',{staticClass:"search-clear-btn btn btn-default",attrs:{"type":"button"},on:{"click":function($event){_vm.clearSearchDate(column,'from')}}},[_c('i',{staticClass:"glyphicon glyphicon-remove"})])])]),_c('div',{staticClass:"date input-group input-group-sm"},[_c('input',{staticClass:"form-control date",attrs:{"placeholder":"to","data-field":column.field,"data-search":"to"}}),_c('span',{staticClass:"input-group-btn"},[_c('button',{staticClass:"search-clear-btn btn btn-default",attrs:{"type":"button"},on:{"click":function($event){_vm.clearSearchDate(column,'to')}}},[_c('i',{staticClass:"glyphicon glyphicon-remove"})])])])]:_vm._e(),(column.searchType=='select')?[_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.search[column.field]),expression:"search[column.field]"}],staticClass:"form-control",on:{"change":[function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.$set(_vm.search, column.field, $event.target.multiple ? $$selectedVal : $$selectedVal[0])},_vm.doSearch]}},[_c('option'),_vm._l((column.searchOption),function(opt,i){return _c('option',{key:i,domProps:{"value":opt.value,"textContent":_vm._s(opt.label)}})})],2)]:_vm._e(),(column.searchType=='multiselect')?[_c('select',{staticClass:"form-control",attrs:{"multiple":"","search-type":"multiselect","data-field":column.field},on:{"change":_vm.doSearch}},_vm._l((column.searchOption),function(opt,i){return _c('option',{key:i,domProps:{"value":opt.value,"textContent":_vm._s(opt.label)}})}))]:_vm._e(),(column.searchType=='select2')?[_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.search[column.field]),expression:"search[column.field]"}],staticClass:"form-control",attrs:{"data-field":column.field,"search-type":"select2"},on:{"change":function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.$set(_vm.search, column.field, $event.target.multiple ? $$selectedVal : $$selectedVal[0])}}},[_c('option'),_vm._l((column.searchOption),function(opt,i){return _c('option',{key:i,domProps:{"value":opt.value,"textContent":_vm._s(opt.label)}})})],2)]:_vm._e(),(column.searchType=='range')?[_c('div',{staticClass:"input-group input-group-sm"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.search[column.field].from),expression:"search[column.field].from"}],staticClass:"search form-control",attrs:{"placeholder":"from","data-field":column.field,"data-search":"from"},domProps:{"value":(_vm.search[column.field].from)},on:{"keyup":function($event){if(!('button' in $event)&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }_vm.doSearch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.search[column.field], "from", $event.target.value)}}}),_c('span',{staticClass:"input-group-btn"},[_c('button',{staticClass:"search-clear-btn btn btn-default",attrs:{"type":"button"},on:{"click":function($event){_vm.clearSearchDate(column,'from')}}},[_c('i',{staticClass:"glyphicon glyphicon-remove"})])])]),_c('div',{staticClass:"input-group input-group-sm"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.search[column.field].to),expression:"search[column.field].to"}],staticClass:"search form-control",attrs:{"placeholder":"to","data-field":column.field,"data-search":"to"},domProps:{"value":(_vm.search[column.field].to)},on:{"keyup":function($event){if(!('button' in $event)&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }_vm.doSearch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.search[column.field], "to", $event.target.value)}}}),_c('span',{staticClass:"input-group-btn"},[_c('button',{staticClass:"search-clear-btn btn btn-default",attrs:{"type":"button"},on:{"click":function($event){_vm.clearSearchDate(column,'to')}}},[_c('i',{staticClass:"glyphicon glyphicon-remove"})])])])]:_vm._e()]:_vm._e()],2):_vm._e()})],2):_vm._e()])}
var RTHeadvue_type_template_id_0f36ae61_staticRenderFns = []


// CONCATENATED MODULE: ./src/RTHead.vue?vue&type=template&id=0f36ae61&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTHead.vue?vue&type=script&lang=js&



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
//
//
//
//
//
/* harmony default export */ var RTHeadvue_type_script_lang_js_ = ({
  name: "rt-head",
  props: {
    hideIndex: Number
  },
  data: function data() {
    return {
      showChild: false,
      search: {},
      columns: []
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.columns = this.$slots.default.filter(function (o) {
      if (o.componentOptions == undefined) return false;
      return o.componentOptions.tag == "rt-column";
    }).map(function (o) {
      return o.componentInstance;
    });
    this.columns.forEach(function (column) {
      column.$on("toggle-visible", function (a) {
        this.$parent.$emit("toggle-visible", {
          name: this.field,
          visible: a
        });
      });
    });
    this.$on("sort", function (a) {
      _this.columns.forEach(function (column) {
        if (column.field != a.field) {
          column.dir = "";
        }
      });
    });
    this.columns.forEach(function (c) {
      if (c.searchType == "multiselect") {
        _this.search[c.field] = [];
      } else if (c.searchType == "date") {
        _this.search[c.field] = {
          from: "",
          to: ""
        };
      } else if (c.searchType == "range") {
        _this.search[c.field] = {
          from: "",
          to: ""
        };
      }
    });
    this.$nextTick(function () {
      var _this2 = this;

      $(function () {
        $(".rt [search-type='multiselect']").each(function (i, o) {
          $(o).multiselect({
            buttonClass: "btn btn-default btn-xs",
            enableFiltering: true
          });
          $(o).on("change", function () {
            var field = $(o).attr("data-field");
            _this2.search[field] = $(o).val();

            _this2.doSearch();
          });
        });
        $(".rt [search-type='select2']").each(function (i, o) {
          $(o).select2();
          $(o).on("change", function () {
            var field = $(o).attr("data-field");
            _this2.search[field] = $(o).val();

            _this2.doSearch();
          });
        });
        $(".rt input.date").each(function (i, o) {
          $(o).daterangepicker({
            singleDatePicker: true,
            opens: "center",
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            locale: {
              format: "YYYY-MM-DD"
            }
          });
          $(o).on("apply.daterangepicker", function (ev, picker) {
            var o = picker.element.get(0);
            $(o).val(picker.startDate.format("YYYY-MM-DD"));
            var field = $(o).attr("data-field");

            if ($(o).attr("data-search") == "from") {
              _this2.search[field].from = picker.startDate.format("YYYY-MM-DD");
            } else {
              _this2.search[field].to = picker.startDate.format("YYYY-MM-DD");
            }

            _this2.$emit("search", _this2.search);
          });
        });
      });
    });
  },
  computed: {
    hasSearchColumn: function hasSearchColumn() {
      return this.columns.some(function (c) {
        return c.searchable;
      });
    },
    hasHideColumn: function hasHideColumn() {
      return this.columns.some(function (c) {
        return c.hide;
      });
    }
  },
  methods: {
    toggleChild: function toggleChild() {
      this.showChild = !this.showChild;
      this.$emit("toggle-child", this.showChild);
    },
    clearSearchDate: function clearSearchDate(column, v) {
      $("[data-field='" + column.field + "'][data-search='" + v + "']").val("");
      this.search[column.field][v] = "";
      this.doSearch();
    },
    doSearch: function doSearch() {
      this.$emit("search", this.search);
    }
  }
});
// CONCATENATED MODULE: ./src/RTHead.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_RTHeadvue_type_script_lang_js_ = (RTHeadvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/RTHead.vue





/* normalize component */

var RTHead_component = normalizeComponent(
  src_RTHeadvue_type_script_lang_js_,
  RTHeadvue_type_template_id_0f36ae61_render,
  RTHeadvue_type_template_id_0f36ae61_staticRenderFns,
  false,
  null,
  null,
  null
  
)

RTHead_component.options.__file = "RTHead.vue"
/* harmony default export */ var RTHead = (RTHead_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTBody.vue?vue&type=template&id=a5ac1c64&
var RTBodyvue_type_template_id_a5ac1c64_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('tbody',[_vm._l((_vm.data),function(r,index){return [_c('tr',{class:{active:r._selected},style:(_vm.getStyle(r)),on:{"click":function($event){_vm.rowClicked(r)}}},[(_vm.hasHideColumn)?_c('td',[_c('button',{staticClass:"btn btn-default btn-xs",on:{"click":function($event){_vm.toggleRowChild(index)},"mouseenter":function($event){_vm.mouseEnter(index)},"mouseleave":function($event){_vm.mouseLeave(index)}}},[(!_vm.showIndex[index])?_c('i',{staticClass:"fa fa-fw fa-chevron-up"}):_vm._e(),(_vm.showIndex[index])?_c('i',{staticClass:"fa fa-fw fa-chevron-down"}):_vm._e()])]):_vm._e(),_vm._l((_vm.columns),function(column){return (column.display())?_c('td',{style:(_vm.getCellStyle(column,index,r)),on:{"click":function($event){_vm.cellClicked(column,index,r)}}},[(_vm.isEditMode(column,index))?[(column.editType=='text')?[_c('input',{staticClass:"form-control input-sm",attrs:{"type":"text"},domProps:{"value":_vm.getValue(r[column.field])},on:{"blur":function($event){_vm.updateData(index,r,column,$event.target.value)}}})]:(column.editType=='select')?[_c('select',{staticClass:"formControl",on:{"blur":function($event){_vm.updateData(index,r,column,$event.target.value)}}},_vm._l((column.editData),function(opt){return _c('option',{domProps:{"value":opt.value,"selected":opt.value==r[column.field].value,"textContent":_vm._s(opt.label)}})}))]:(column.editType=='date')?[_c('input',{staticClass:"form-control input-sm",attrs:{"type":"text"},domProps:{"value":_vm.getValue(r[column.field])},on:{"blur":function($event){_vm.updateData(index,r,column,$event.target.value)}}})]:_vm._e()]:[(column.cell(r).type=='deletes')?_c('input',{attrs:{"type":"checkbox"}}):(column.cell(r).type=='delete')?_c('button',{staticClass:"btn btn-xs btn-danger",on:{"click":function($event){_vm.deleteRow(r[column.field].uri)}}},[_c('i',{staticClass:"fa fa-fw fa-times"})]):(column.cell(r).type=='sub-row')?_c('button',{staticClass:"btn btn-xs btn-default",on:{"click":function($event){_vm.toggleSubRow(index,r)}}},[(_vm.subRow[index])?_c('i',{staticClass:"fa fa-fw fa-minus"}):_vm._e(),(!_vm.subRow[index])?_c('i',{staticClass:"fa fa-fw fa-plus"}):_vm._e()]):(column.cell(r).type=='link')?_c('a',{attrs:{"href":column.cell(r).href},domProps:{"innerHTML":_vm._s(column.cell(r).content)}}):_c('div',{style:(column.cell(r).style),domProps:{"innerHTML":_vm._s(column.cell(r).content)}})]],2):_vm._e()})],2),_c('tr',{directives:[{name:"show",rawName:"v-show",value:(_vm.showChild(index)),expression:"showChild(index)"}],staticClass:"child"},[_c('td',{attrs:{"colspan":_vm.showColumnCount}},[_c('ul',_vm._l((_vm.columns),function(column){return (column.hide)?_c('li',[_c('b',{domProps:{"innerHTML":_vm._s(column.title)}}),_vm._v("  "),_c('span',{domProps:{"innerHTML":_vm._s(_vm.getValue(r[column.field]))}})]):_vm._e()}))])]),(_vm.subRow[index])?_c('tr',[_c('td',{attrs:{"colspan":_vm.showColumnCount},domProps:{"innerHTML":_vm._s(_vm.subRow[index])}})]):_vm._e()]})],2)}
var RTBodyvue_type_template_id_a5ac1c64_staticRenderFns = []


// CONCATENATED MODULE: ./src/RTBody.vue?vue&type=template&id=a5ac1c64&

// EXTERNAL MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTBody.vue?vue&type=script&lang=js&
var RTBodyvue_type_script_lang_js_ = __webpack_require__("cdca");

// CONCATENATED MODULE: ./src/RTBody.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_RTBodyvue_type_script_lang_js_ = (RTBodyvue_type_script_lang_js_["a" /* default */]); 
// CONCATENATED MODULE: ./src/RTBody.vue





/* normalize component */

var RTBody_component = normalizeComponent(
  src_RTBodyvue_type_script_lang_js_,
  RTBodyvue_type_template_id_a5ac1c64_render,
  RTBodyvue_type_template_id_a5ac1c64_staticRenderFns,
  false,
  null,
  null,
  null
  
)

RTBody_component.options.__file = "RTBody.vue"
/* harmony default export */ var RTBody = (RTBody_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTColumn.vue?vue&type=template&id=68d76bd6&
var RTColumnvue_type_template_id_68d76bd6_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (_vm.display())?_c('th',{staticClass:"unselectable",class:{
        sortable:_vm.sortable,
        sorting_desc:(_vm.dir=='desc'),
        sorting_asc:(_vm.dir=='asc')
    },style:(_vm.style),on:{"click":_vm.sort}},[_c('div',{staticStyle:{"overflow":"hidden"},attrs:{"title":_vm.title},domProps:{"innerHTML":_vm._s(_vm.title)}}),_c('div',{ref:"resizer"}),(_vm.type=='deletes')?[_c('button',{staticClass:"btn btn-xs btn-danger",on:{"click":function($event){_vm.$emit('deletes')}}},[_c('i',{staticClass:"fa fa-fw fa-times"})])]:_vm._e()],2):_vm._e()}
var RTColumnvue_type_template_id_68d76bd6_staticRenderFns = []


// CONCATENATED MODULE: ./src/RTColumn.vue?vue&type=template&id=68d76bd6&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/RTColumn.vue?vue&type=script&lang=js&
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
/* harmony default export */ var RTColumnvue_type_script_lang_js_ = ({
  name: "rt-column",
  props: {
    type: String,
    field: String,
    title: String,
    sortable: Boolean,
    sortDir: String,
    searchable: Boolean,
    searchMultiple: Boolean,
    searchType: {
      type: String,
      default: "text"
    },
    searchOption: {
      type: Array
    },
    editable: Boolean,
    editType: String,
    editData: {
      type: Array
    },
    width: String,
    maxWidth: String,
    resizable: Boolean,
    fixed: Boolean,
    hidden: Boolean,
    align: String,
    wrap: Boolean
  },
  data: function data() {
    return {
      isVisible: !this.hidden,
      hide: false,
      index: -1,
      hideIndex: -1,
      dir: this.sortDir
    };
  },
  mounted: function mounted() {
    var _this = this;

    var that = this;

    if (this.resizable) {
      this.$nextTick(function () {
        $(function () {
          $(_this.$el).resizable({
            handles: "e",
            minWidth: 28,
            resize: function resize(event, ui) {
              $(that.$refs.resizer).width(ui.size.width);
            }
          });
        });
      });
    }
  },
  computed: {
    style: function style() {
      var style = {};
      style.width = this.width;
      style.maxWidth = this.maxWidth;
      return style;
    }
  },
  updated: function updated() {},
  methods: {
    cell: function cell(r) {
      var cell = {
        type: "text"
      };

      if (r[this.field] == null) {
        return cell;
      }

      if (typeof r[this.field] == "string") {
        cell.content = r[this.field];
      } else {
        for (var i in r[this.field]) {
          cell[i] = r[this.field][i];
        }
      }

      if (this.wrap) {
        Vue.set(cell, "style", {
          "word-wrap": "break-word",
          "white-space": "pre-wrap"
        });
      }

      return cell;
    },
    display: function display() {
      return this.isVisible && !this.hide;
    },
    getWidth: function getWidth() {
      return this.$el.offsetWidth;
    },
    getHeight: function getHeight() {
      return this.$el.offsetHeight;
    },
    getDataValue: function getDataValue(data) {
      var v = data[this.field];

      if (typeof v === "string") {
        return v;
      }

      return v.content;
    },
    sort: function sort() {
      if (!this.sortable) return false;

      if (this.dir == "" || this.dir == "asc") {
        this.dir = "desc";
      } else {
        this.dir = "asc";
      }

      this.$parent.$emit("sort", {
        field: this.field,
        dir: this.dir
      });
    },
    toggleVisible: function toggleVisible() {
      this.isVisible = !this.isVisible;
      this.$emit("toggle-visible", this.isVisible);
    }
  }
});
// CONCATENATED MODULE: ./src/RTColumn.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_RTColumnvue_type_script_lang_js_ = (RTColumnvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/RTColumn.vue





/* normalize component */

var RTColumn_component = normalizeComponent(
  src_RTColumnvue_type_script_lang_js_,
  RTColumnvue_type_template_id_68d76bd6_render,
  RTColumnvue_type_template_id_68d76bd6_staticRenderFns,
  false,
  null,
  null,
  null
  
)

RTColumn_component.options.__file = "RTColumn.vue"
/* harmony default export */ var RTColumn = (RTColumn_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules//.cache//vue-loader","cacheIdentifier":"545da000-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Ace.vue?vue&type=template&id=0b51394f&
var Acevue_type_template_id_0b51394f_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('textarea')}
var Acevue_type_template_id_0b51394f_staticRenderFns = []


// CONCATENATED MODULE: ./src/Ace.vue?vue&type=template&id=0b51394f&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Ace.vue?vue&type=script&lang=js&
//
//
//
//
/* harmony default export */ var Acevue_type_script_lang_js_ = ({
  mounted: function mounted() {
    var _this = this;

    if (this.$slots.default) {
      this.$el.value = this.$slots.default[0].text;
    }

    var $that = $(this.$el);
    $(this.$el).addClass("hide");
    var $div = $("<div style='height:400px'></div>");
    $div.insertAfter(this.$el);
    $div.html(this.$el.value);
    this.$nextTick(function () {
      var editor = ace.edit($div[0]);
      var mode = $(_this.$el).attr("ace-mode");

      if (mode) {
        editor.session.setMode("ace/mode/" + mode);
      }

      editor.getSession().setValue($(_this.$el).val());
      editor.getSession().on("change", function () {
        //                console.log(editor.getSession().getValue());
        $that.val(editor.getSession().getValue());
      });
    });
  }
});
// CONCATENATED MODULE: ./src/Ace.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Acevue_type_script_lang_js_ = (Acevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/Ace.vue





/* normalize component */

var Ace_component = normalizeComponent(
  src_Acevue_type_script_lang_js_,
  Acevue_type_template_id_0b51394f_render,
  Acevue_type_template_id_0b51394f_staticRenderFns,
  false,
  null,
  null,
  null
  
)

Ace_component.options.__file = "Ace.vue"
/* harmony default export */ var Ace = (Ace_component.exports);
// CONCATENATED MODULE: ./src/alt.js








































src("alt-input", Input);
src("alt-box", Box);
src("alt-box-header", BoxHeader);
src("alt-box-body", BoxBody);
src("alt-box-footer", BoxFooter);
src("icheck", icheck);
src("datepicker", Datepicker);
src("alt-date", src_Date);
src("alt-datetime", Datetime);
src("alt-e", E);
src("alt-email", Email);
src("alt-form", Form);
src("alt-grid", Grid);
src("alt-grid-section", GridSection);
src("alt-multiselect", Multiselect);
src("alt-tab", Tab);
src("alt-table", Table);
src("alt-table-body", TableBody);
src("alt-tab-pane", TabPane);
src("alt-timeline", Timeline);
src("x-editable", xeditable);
src("ckeditor", ckeditor);
src("roxyfileman", roxyfileman);
src("alt-button", Button);
src("alt-cell", Cell);
src("alt-rt", RT);
src("alt-rt2", RT2);
src("rt-pagination", RTPagination);
src("rt2-tbody", RT2Tbody);
src("rt-info", RTInfo);
src("alt-column", Column);
src("alt-column-search", ColumnSearch);
src("select2", Select2);
src("multiselect2", Multiselect2);
src("rt-table", RTTable);
src("rt-head", RTHead);
src("rt-column", RTColumn);
src("rt-body", RTBody);
src("ace", Ace);
// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js




/***/ }),

/***/ "9093":
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.7 / 15.2.3.4 Object.getOwnPropertyNames(O)
var $keys = __webpack_require__("ce10");
var hiddenKeys = __webpack_require__("e11e").concat('length', 'prototype');

exports.f = Object.getOwnPropertyNames || function getOwnPropertyNames(O) {
  return $keys(O, hiddenKeys);
};


/***/ }),

/***/ "9b43":
/***/ (function(module, exports, __webpack_require__) {

// optional / simple context binding
var aFunction = __webpack_require__("d8e8");
module.exports = function (fn, that, length) {
  aFunction(fn);
  if (that === undefined) return fn;
  switch (length) {
    case 1: return function (a) {
      return fn.call(that, a);
    };
    case 2: return function (a, b) {
      return fn.call(that, a, b);
    };
    case 3: return function (a, b, c) {
      return fn.call(that, a, b, c);
    };
  }
  return function (/* ...args */) {
    return fn.apply(that, arguments);
  };
};


/***/ }),

/***/ "9c6c":
/***/ (function(module, exports, __webpack_require__) {

// 22.1.3.31 Array.prototype[@@unscopables]
var UNSCOPABLES = __webpack_require__("2b4c")('unscopables');
var ArrayProto = Array.prototype;
if (ArrayProto[UNSCOPABLES] == undefined) __webpack_require__("32e9")(ArrayProto, UNSCOPABLES, {});
module.exports = function (key) {
  ArrayProto[UNSCOPABLES][key] = true;
};


/***/ }),

/***/ "9def":
/***/ (function(module, exports, __webpack_require__) {

// 7.1.15 ToLength
var toInteger = __webpack_require__("4588");
var min = Math.min;
module.exports = function (it) {
  return it > 0 ? min(toInteger(it), 0x1fffffffffffff) : 0; // pow(2, 53) - 1 == 9007199254740991
};


/***/ }),

/***/ "9e1e":
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__("79e5")(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ "a322":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return _defineProperty; });
function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

/***/ }),

/***/ "a481":
/***/ (function(module, exports, __webpack_require__) {

// @@replace logic
__webpack_require__("214f")('replace', 2, function (defined, REPLACE, $replace) {
  // 21.1.3.14 String.prototype.replace(searchValue, replaceValue)
  return [function replace(searchValue, replaceValue) {
    'use strict';
    var O = defined(this);
    var fn = searchValue == undefined ? undefined : searchValue[REPLACE];
    return fn !== undefined
      ? fn.call(searchValue, O, replaceValue)
      : $replace.call(String(O), searchValue, replaceValue);
  }, $replace];
});


/***/ }),

/***/ "aa77":
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__("5ca1");
var defined = __webpack_require__("be13");
var fails = __webpack_require__("79e5");
var spaces = __webpack_require__("fdef");
var space = '[' + spaces + ']';
var non = '\u200b\u0085';
var ltrim = RegExp('^' + space + space + '*');
var rtrim = RegExp(space + space + '*$');

var exporter = function (KEY, exec, ALIAS) {
  var exp = {};
  var FORCE = fails(function () {
    return !!spaces[KEY]() || non[KEY]() != non;
  });
  var fn = exp[KEY] = FORCE ? exec(trim) : spaces[KEY];
  if (ALIAS) exp[ALIAS] = fn;
  $export($export.P + $export.F * FORCE, 'String', exp);
};

// 1 -> String#trimLeft
// 2 -> String#trimRight
// 3 -> String#trim
var trim = exporter.trim = function (string, TYPE) {
  string = String(defined(string));
  if (TYPE & 1) string = string.replace(ltrim, '');
  if (TYPE & 2) string = string.replace(rtrim, '');
  return string;
};

module.exports = exporter;


/***/ }),

/***/ "aae3":
/***/ (function(module, exports, __webpack_require__) {

// 7.2.8 IsRegExp(argument)
var isObject = __webpack_require__("d3f4");
var cof = __webpack_require__("2d95");
var MATCH = __webpack_require__("2b4c")('match');
module.exports = function (it) {
  var isRegExp;
  return isObject(it) && ((isRegExp = it[MATCH]) !== undefined ? !!isRegExp : cof(it) == 'RegExp');
};


/***/ }),

/***/ "ac6a":
/***/ (function(module, exports, __webpack_require__) {

var $iterators = __webpack_require__("cadf");
var getKeys = __webpack_require__("0d58");
var redefine = __webpack_require__("2aba");
var global = __webpack_require__("7726");
var hide = __webpack_require__("32e9");
var Iterators = __webpack_require__("84f2");
var wks = __webpack_require__("2b4c");
var ITERATOR = wks('iterator');
var TO_STRING_TAG = wks('toStringTag');
var ArrayValues = Iterators.Array;

var DOMIterables = {
  CSSRuleList: true, // TODO: Not spec compliant, should be false.
  CSSStyleDeclaration: false,
  CSSValueList: false,
  ClientRectList: false,
  DOMRectList: false,
  DOMStringList: false,
  DOMTokenList: true,
  DataTransferItemList: false,
  FileList: false,
  HTMLAllCollection: false,
  HTMLCollection: false,
  HTMLFormElement: false,
  HTMLSelectElement: false,
  MediaList: true, // TODO: Not spec compliant, should be false.
  MimeTypeArray: false,
  NamedNodeMap: false,
  NodeList: true,
  PaintRequestList: false,
  Plugin: false,
  PluginArray: false,
  SVGLengthList: false,
  SVGNumberList: false,
  SVGPathSegList: false,
  SVGPointList: false,
  SVGStringList: false,
  SVGTransformList: false,
  SourceBufferList: false,
  StyleSheetList: true, // TODO: Not spec compliant, should be false.
  TextTrackCueList: false,
  TextTrackList: false,
  TouchList: false
};

for (var collections = getKeys(DOMIterables), i = 0; i < collections.length; i++) {
  var NAME = collections[i];
  var explicit = DOMIterables[NAME];
  var Collection = global[NAME];
  var proto = Collection && Collection.prototype;
  var key;
  if (proto) {
    if (!proto[ITERATOR]) hide(proto, ITERATOR, ArrayValues);
    if (!proto[TO_STRING_TAG]) hide(proto, TO_STRING_TAG, NAME);
    Iterators[NAME] = ArrayValues;
    if (explicit) for (key in $iterators) if (!proto[key]) redefine(proto, key, $iterators[key], true);
  }
}


/***/ }),

/***/ "be13":
/***/ (function(module, exports) {

// 7.2.1 RequireObjectCoercible(argument)
module.exports = function (it) {
  if (it == undefined) throw TypeError("Can't call method on  " + it);
  return it;
};


/***/ }),

/***/ "c1d0":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "c366":
/***/ (function(module, exports, __webpack_require__) {

// false -> Array#indexOf
// true  -> Array#includes
var toIObject = __webpack_require__("6821");
var toLength = __webpack_require__("9def");
var toAbsoluteIndex = __webpack_require__("77f1");
module.exports = function (IS_INCLUDES) {
  return function ($this, el, fromIndex) {
    var O = toIObject($this);
    var length = toLength(O.length);
    var index = toAbsoluteIndex(fromIndex, length);
    var value;
    // Array#includes uses SameValueZero equality algorithm
    // eslint-disable-next-line no-self-compare
    if (IS_INCLUDES && el != el) while (length > index) {
      value = O[index++];
      // eslint-disable-next-line no-self-compare
      if (value != value) return true;
    // Array#indexOf ignores holes, Array#includes - not
    } else for (;length > index; index++) if (IS_INCLUDES || index in O) {
      if (O[index] === el) return IS_INCLUDES || index || 0;
    } return !IS_INCLUDES && -1;
  };
};


/***/ }),

/***/ "c5f6":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var global = __webpack_require__("7726");
var has = __webpack_require__("69a8");
var cof = __webpack_require__("2d95");
var inheritIfRequired = __webpack_require__("5dbc");
var toPrimitive = __webpack_require__("6a99");
var fails = __webpack_require__("79e5");
var gOPN = __webpack_require__("9093").f;
var gOPD = __webpack_require__("11e9").f;
var dP = __webpack_require__("86cc").f;
var $trim = __webpack_require__("aa77").trim;
var NUMBER = 'Number';
var $Number = global[NUMBER];
var Base = $Number;
var proto = $Number.prototype;
// Opera ~12 has broken Object#toString
var BROKEN_COF = cof(__webpack_require__("2aeb")(proto)) == NUMBER;
var TRIM = 'trim' in String.prototype;

// 7.1.3 ToNumber(argument)
var toNumber = function (argument) {
  var it = toPrimitive(argument, false);
  if (typeof it == 'string' && it.length > 2) {
    it = TRIM ? it.trim() : $trim(it, 3);
    var first = it.charCodeAt(0);
    var third, radix, maxCode;
    if (first === 43 || first === 45) {
      third = it.charCodeAt(2);
      if (third === 88 || third === 120) return NaN; // Number('+0x1') should be NaN, old V8 fix
    } else if (first === 48) {
      switch (it.charCodeAt(1)) {
        case 66: case 98: radix = 2; maxCode = 49; break; // fast equal /^0b[01]+$/i
        case 79: case 111: radix = 8; maxCode = 55; break; // fast equal /^0o[0-7]+$/i
        default: return +it;
      }
      for (var digits = it.slice(2), i = 0, l = digits.length, code; i < l; i++) {
        code = digits.charCodeAt(i);
        // parseInt parses a string to a first unavailable symbol
        // but ToNumber should return NaN if a string contains unavailable symbols
        if (code < 48 || code > maxCode) return NaN;
      } return parseInt(digits, radix);
    }
  } return +it;
};

if (!$Number(' 0o1') || !$Number('0b1') || $Number('+0x1')) {
  $Number = function Number(value) {
    var it = arguments.length < 1 ? 0 : value;
    var that = this;
    return that instanceof $Number
      // check on 1..constructor(foo) case
      && (BROKEN_COF ? fails(function () { proto.valueOf.call(that); }) : cof(that) != NUMBER)
        ? inheritIfRequired(new Base(toNumber(it)), that, $Number) : toNumber(it);
  };
  for (var keys = __webpack_require__("9e1e") ? gOPN(Base) : (
    // ES3:
    'MAX_VALUE,MIN_VALUE,NaN,NEGATIVE_INFINITY,POSITIVE_INFINITY,' +
    // ES6 (in case, if modules with ES6 Number statics required before):
    'EPSILON,isFinite,isInteger,isNaN,isSafeInteger,MAX_SAFE_INTEGER,' +
    'MIN_SAFE_INTEGER,parseFloat,parseInt,isInteger'
  ).split(','), j = 0, key; keys.length > j; j++) {
    if (has(Base, key = keys[j]) && !has($Number, key)) {
      dP($Number, key, gOPD(Base, key));
    }
  }
  $Number.prototype = proto;
  proto.constructor = $Number;
  __webpack_require__("2aba")(global, NUMBER, $Number);
}


/***/ }),

/***/ "c69a":
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__("9e1e") && !__webpack_require__("79e5")(function () {
  return Object.defineProperty(__webpack_require__("230e")('div'), 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ "c93e":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return _objectSpread; });
/* harmony import */ var _defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("a322");

function _objectSpread(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? arguments[i] : {};
    var ownKeys = Object.keys(source);

    if (typeof Object.getOwnPropertySymbols === 'function') {
      ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) {
        return Object.getOwnPropertyDescriptor(source, sym).enumerable;
      }));
    }

    ownKeys.forEach(function (key) {
      Object(_defineProperty__WEBPACK_IMPORTED_MODULE_0__[/* default */ "a"])(target, key, source[key]);
    });
  }

  return target;
}

/***/ }),

/***/ "ca5a":
/***/ (function(module, exports) {

var id = 0;
var px = Math.random();
module.exports = function (key) {
  return 'Symbol('.concat(key === undefined ? '' : key, ')_', (++id + px).toString(36));
};


/***/ }),

/***/ "cadf":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var addToUnscopables = __webpack_require__("9c6c");
var step = __webpack_require__("d53b");
var Iterators = __webpack_require__("84f2");
var toIObject = __webpack_require__("6821");

// 22.1.3.4 Array.prototype.entries()
// 22.1.3.13 Array.prototype.keys()
// 22.1.3.29 Array.prototype.values()
// 22.1.3.30 Array.prototype[@@iterator]()
module.exports = __webpack_require__("01f9")(Array, 'Array', function (iterated, kind) {
  this._t = toIObject(iterated); // target
  this._i = 0;                   // next index
  this._k = kind;                // kind
// 22.1.5.2.1 %ArrayIteratorPrototype%.next()
}, function () {
  var O = this._t;
  var kind = this._k;
  var index = this._i++;
  if (!O || index >= O.length) {
    this._t = undefined;
    return step(1);
  }
  if (kind == 'keys') return step(0, index);
  if (kind == 'values') return step(0, O[index]);
  return step(0, [index, O[index]]);
}, 'values');

// argumentsList[@@iterator] is %ArrayProto_values% (9.4.4.6, 9.4.4.7)
Iterators.Arguments = Iterators.Array;

addToUnscopables('keys');
addToUnscopables('values');
addToUnscopables('entries');


/***/ }),

/***/ "cb7c":
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__("d3f4");
module.exports = function (it) {
  if (!isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};


/***/ }),

/***/ "cd1c":
/***/ (function(module, exports, __webpack_require__) {

// 9.4.2.3 ArraySpeciesCreate(originalArray, length)
var speciesConstructor = __webpack_require__("e853");

module.exports = function (original, length) {
  return new (speciesConstructor(original))(length);
};


/***/ }),

/***/ "cdca":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("ac6a");
/* harmony import */ var core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_iterable__WEBPACK_IMPORTED_MODULE_0__);

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
/* harmony default export */ __webpack_exports__["a"] = ({
  name: "rt-body",
  props: {
    columns: Array,
    data: Array,
    showResponsive: {
      type: Boolean,
      default: true
    }
  },
  data: function data() {
    return {
      editMode: false,
      editIndex: null,
      editField: null,
      showIndex: [],
      subRow: [],
      hoverChild: []
    };
  },
  computed: {
    hasHideColumn: function hasHideColumn() {
      return this.columns.some(function (c) {
        return c.hide;
      });
    },
    showColumnCount: function showColumnCount() {
      return this.columns.filter(function (c) {
        return !c.hide;
      }).length + 1;
    }
  },
  methods: {
    rowClicked: function rowClicked(data) {
      if (!this.$parent.selectable) return;
      data._selected = !data._selected;
      this.$forceUpdate();
    },
    getCellStyle: function getCellStyle(col, index, data) {
      var style = {};

      if (col.align) {
        style["text-align"] = col.align;
      }

      return style;
    },
    mouseLeave: function mouseLeave(index) {
      this.hoverChild[index] = false;
      this.$forceUpdate(); //                console.log("mouseleave",index);
    },
    mouseEnter: function mouseEnter(index) {
      this.hoverChild[index] = true;
      this.$forceUpdate(); //                console.log("mouseenter",index);
    },
    showChild: function showChild(index) {
      if (this.hoverChild[index]) return true;
      return this.showIndex[index];
    },
    toggleSubRow: function toggleSubRow(index, r) {
      var _this = this;

      if (this.subRow[index]) {
        this.subRow[index] = null;
        this.$forceUpdate();
        return;
      }

      this.$http.get(r["[subhtml]"].url).then(function (resp) {
        _this.subRow[index] = resp.body;

        _this.$forceUpdate();

        _this.$nextTick(function () {
          var div = $(this.subRow[index]);
          div.each(function (i, o) {
            if (o.tagName == "SCRIPT") {
              eval(o.innerHTML);
            }
          });
        });
      });
    },
    deleteRow: function deleteRow(uri) {
      var _this2 = this;

      if (confirm("Are your sure?")) {
        this.$http.get(uri).then(function (resp) {
          _this2.$emit("data-deleted");
        });
      }
    },
    toggleChild: function toggleChild(value) {
      var _this3 = this;

      this.data.forEach(function (r, i) {
        _this3.showIndex[i] = value;
      });
      this.$forceUpdate();
    },
    toggleRowChild: function toggleRowChild(index) {
      if (this.showIndex[index]) {
        this.showIndex[index] = false;
      } else {
        this.showIndex[index] = true;
      }

      this.$forceUpdate();
    },
    isShowChild: function isShowChild(index) {
      return this.showIndex[index];
    },
    updateData: function updateData(index, r, column, value) {
      this.editMode = false;

      if (column.editType == "select") {
        if (r[column.field].value != value) {
          this.data[index][column.field].value = value;
          this.data[index][column.field].content = column.editData[value].label;
          this.$emit("update-data", r._key, column.field, value);
        }
      } else {
        if (r[column.field] != value) {
          r[column.field] = value;
          this.$emit("update-data", r._key, column.field, value);
        }
      }
    },
    isEditMode: function isEditMode(column, index) {
      if (!this.editMode) return false;

      if (this.editField == column.field && this.editIndex == index) {
        return true;
      }

      return false;
    },
    cellClicked: function cellClicked(column, index, r) {
      if (!column.editable) return false;
      this.editMode = true;
      this.editField = column.field;
      this.editIndex = index;
    },
    getValue: function getValue(v) {
      if (typeof v === "string") {
        return v;
      }

      return v.content;
    },
    getContent: function getContent(v) {
      if (typeof v === "string") {
        return v;
      }

      return v.content;
    },
    getStyle: function getStyle(r) {
      if (r._row == undefined) return {};
      return r._row.style;
    }
  },
  mounted: function mounted() {}
});

/***/ }),

/***/ "ce10":
/***/ (function(module, exports, __webpack_require__) {

var has = __webpack_require__("69a8");
var toIObject = __webpack_require__("6821");
var arrayIndexOf = __webpack_require__("c366")(false);
var IE_PROTO = __webpack_require__("613b")('IE_PROTO');

module.exports = function (object, names) {
  var O = toIObject(object);
  var i = 0;
  var result = [];
  var key;
  for (key in O) if (key != IE_PROTO) has(O, key) && result.push(key);
  // Don't enum bug & hidden keys
  while (names.length > i) if (has(O, key = names[i++])) {
    ~arrayIndexOf(result, key) || result.push(key);
  }
  return result;
};


/***/ }),

/***/ "d263":
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// B.2.3.6 String.prototype.fixed()
__webpack_require__("386b")('fixed', function (createHTML) {
  return function fixed() {
    return createHTML(this, 'tt', '', '');
  };
});


/***/ }),

/***/ "d3f4":
/***/ (function(module, exports) {

module.exports = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};


/***/ }),

/***/ "d53b":
/***/ (function(module, exports) {

module.exports = function (done, value) {
  return { value: value, done: !!done };
};


/***/ }),

/***/ "d8e8":
/***/ (function(module, exports) {

module.exports = function (it) {
  if (typeof it != 'function') throw TypeError(it + ' is not a function!');
  return it;
};


/***/ }),

/***/ "e11e":
/***/ (function(module, exports) {

// IE 8- don't enum bug keys
module.exports = (
  'constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf'
).split(',');


/***/ }),

/***/ "e853":
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__("d3f4");
var isArray = __webpack_require__("1169");
var SPECIES = __webpack_require__("2b4c")('species');

module.exports = function (original) {
  var C;
  if (isArray(original)) {
    C = original.constructor;
    // cross-realm fallback
    if (typeof C == 'function' && (C === Array || isArray(C.prototype))) C = undefined;
    if (isObject(C)) {
      C = C[SPECIES];
      if (C === null) C = undefined;
    }
  } return C === undefined ? Array : C;
};


/***/ }),

/***/ "f751":
/***/ (function(module, exports, __webpack_require__) {

// 19.1.3.1 Object.assign(target, source)
var $export = __webpack_require__("5ca1");

$export($export.S + $export.F, 'Object', { assign: __webpack_require__("7333") });


/***/ }),

/***/ "fab2":
/***/ (function(module, exports, __webpack_require__) {

var document = __webpack_require__("7726").document;
module.exports = document && document.documentElement;


/***/ }),

/***/ "fdef":
/***/ (function(module, exports) {

module.exports = '\x09\x0A\x0B\x0C\x0D\x20\xA0\u1680\u180E\u2000\u2001\u2002\u2003' +
  '\u2004\u2005\u2006\u2007\u2008\u2009\u200A\u202F\u205F\u3000\u2028\u2029\uFEFF';


/***/ })

/******/ });
});
//# sourceMappingURL=alt-component.umd.js.map