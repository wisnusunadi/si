"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_ppic_gudang_Gbj_vue"],{

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/ppic/gudang/Gbj.vue?vue&type=style&index=0&lang=scss&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/ppic/gudang/Gbj.vue?vue&type=style&index=0&lang=scss& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "thead {\n  text-align: center;\n}\n.semuaproduk td:nth-child(1),\n.semuaproduk td:nth-child(2),\n.semuaproduk td:nth-child(3),\n.semuaproduk td:nth-child(5),\n.semuaproduk td:nth-child(7),\n.semuaproduk td:nth-child(8) {\n  text-align: center;\n}\n.perproduk td:nth-child(1),\n.perproduk td:nth-child(2),\n.perproduk td:nth-child(3),\n.perproduk td:nth-child(6) {\n  text-align: center;\n}\n.pertanggal td:nth-child(1),\n.pertanggal td:nth-child(2),\n.pertanggal td:nth-child(4),\n.pertanggal td:nth-child(7) {\n  text-align: center;\n}\n.center {\n  width: 80%;\n  margin-left: auto;\n  margin-right: auto;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/runtime/api.js":
/*!*****************************************************!*\
  !*** ./node_modules/css-loader/dist/runtime/api.js ***!
  \*****************************************************/
/***/ ((module) => {



/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (cssWithMappingToString) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join("");
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === "string") {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, ""]];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var i = 0; i < this.length; i++) {
        // eslint-disable-next-line prefer-destructuring
        var id = this[i][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _i = 0; _i < modules.length; _i++) {
      var item = [].concat(modules[_i]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        // eslint-disable-next-line no-continue
        continue;
      }

      if (mediaQuery) {
        if (!item[2]) {
          item[2] = mediaQuery;
        } else {
          item[2] = "".concat(mediaQuery, " and ").concat(item[2]);
        }
      }

      list.push(item);
    }
  };

  return list;
};

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/ppic/gudang/Gbj.vue?vue&type=style&index=0&lang=scss&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/ppic/gudang/Gbj.vue?vue&type=style&index=0&lang=scss& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Gbj_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[3]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Gbj.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/ppic/gudang/Gbj.vue?vue&type=style&index=0&lang=scss&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Gbj_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Gbj_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js":
/*!****************************************************************************!*\
  !*** ./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js ***!
  \****************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {



var isOldIE = function isOldIE() {
  var memo;
  return function memorize() {
    if (typeof memo === 'undefined') {
      // Test for IE <= 9 as proposed by Browserhacks
      // @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
      // Tests for existence of standard globals is to allow style-loader
      // to operate correctly into non-standard environments
      // @see https://github.com/webpack-contrib/style-loader/issues/177
      memo = Boolean(window && document && document.all && !window.atob);
    }

    return memo;
  };
}();

var getTarget = function getTarget() {
  var memo = {};
  return function memorize(target) {
    if (typeof memo[target] === 'undefined') {
      var styleTarget = document.querySelector(target); // Special case to return head of iframe instead of iframe itself

      if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
        try {
          // This will throw an exception if access to iframe is blocked
          // due to cross-origin restrictions
          styleTarget = styleTarget.contentDocument.head;
        } catch (e) {
          // istanbul ignore next
          styleTarget = null;
        }
      }

      memo[target] = styleTarget;
    }

    return memo[target];
  };
}();

var stylesInDom = [];

function getIndexByIdentifier(identifier) {
  var result = -1;

  for (var i = 0; i < stylesInDom.length; i++) {
    if (stylesInDom[i].identifier === identifier) {
      result = i;
      break;
    }
  }

  return result;
}

function modulesToDom(list, options) {
  var idCountMap = {};
  var identifiers = [];

  for (var i = 0; i < list.length; i++) {
    var item = list[i];
    var id = options.base ? item[0] + options.base : item[0];
    var count = idCountMap[id] || 0;
    var identifier = "".concat(id, " ").concat(count);
    idCountMap[id] = count + 1;
    var index = getIndexByIdentifier(identifier);
    var obj = {
      css: item[1],
      media: item[2],
      sourceMap: item[3]
    };

    if (index !== -1) {
      stylesInDom[index].references++;
      stylesInDom[index].updater(obj);
    } else {
      stylesInDom.push({
        identifier: identifier,
        updater: addStyle(obj, options),
        references: 1
      });
    }

    identifiers.push(identifier);
  }

  return identifiers;
}

function insertStyleElement(options) {
  var style = document.createElement('style');
  var attributes = options.attributes || {};

  if (typeof attributes.nonce === 'undefined') {
    var nonce =  true ? __webpack_require__.nc : 0;

    if (nonce) {
      attributes.nonce = nonce;
    }
  }

  Object.keys(attributes).forEach(function (key) {
    style.setAttribute(key, attributes[key]);
  });

  if (typeof options.insert === 'function') {
    options.insert(style);
  } else {
    var target = getTarget(options.insert || 'head');

    if (!target) {
      throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");
    }

    target.appendChild(style);
  }

  return style;
}

function removeStyleElement(style) {
  // istanbul ignore if
  if (style.parentNode === null) {
    return false;
  }

  style.parentNode.removeChild(style);
}
/* istanbul ignore next  */


var replaceText = function replaceText() {
  var textStore = [];
  return function replace(index, replacement) {
    textStore[index] = replacement;
    return textStore.filter(Boolean).join('\n');
  };
}();

function applyToSingletonTag(style, index, remove, obj) {
  var css = remove ? '' : obj.media ? "@media ".concat(obj.media, " {").concat(obj.css, "}") : obj.css; // For old IE

  /* istanbul ignore if  */

  if (style.styleSheet) {
    style.styleSheet.cssText = replaceText(index, css);
  } else {
    var cssNode = document.createTextNode(css);
    var childNodes = style.childNodes;

    if (childNodes[index]) {
      style.removeChild(childNodes[index]);
    }

    if (childNodes.length) {
      style.insertBefore(cssNode, childNodes[index]);
    } else {
      style.appendChild(cssNode);
    }
  }
}

function applyToTag(style, options, obj) {
  var css = obj.css;
  var media = obj.media;
  var sourceMap = obj.sourceMap;

  if (media) {
    style.setAttribute('media', media);
  } else {
    style.removeAttribute('media');
  }

  if (sourceMap && typeof btoa !== 'undefined') {
    css += "\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))), " */");
  } // For old IE

  /* istanbul ignore if  */


  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    while (style.firstChild) {
      style.removeChild(style.firstChild);
    }

    style.appendChild(document.createTextNode(css));
  }
}

var singleton = null;
var singletonCounter = 0;

function addStyle(obj, options) {
  var style;
  var update;
  var remove;

  if (options.singleton) {
    var styleIndex = singletonCounter++;
    style = singleton || (singleton = insertStyleElement(options));
    update = applyToSingletonTag.bind(null, style, styleIndex, false);
    remove = applyToSingletonTag.bind(null, style, styleIndex, true);
  } else {
    style = insertStyleElement(options);
    update = applyToTag.bind(null, style, options);

    remove = function remove() {
      removeStyleElement(style);
    };
  }

  update(obj);
  return function updateStyle(newObj) {
    if (newObj) {
      if (newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap) {
        return;
      }

      update(obj = newObj);
    } else {
      remove();
    }
  };
}

module.exports = function (list, options) {
  options = options || {}; // Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
  // tags it will allow on a page

  if (!options.singleton && typeof options.singleton !== 'boolean') {
    options.singleton = isOldIE();
  }

  list = list || [];
  var lastIdentifiers = modulesToDom(list, options);
  return function update(newList) {
    newList = newList || [];

    if (Object.prototype.toString.call(newList) !== '[object Array]') {
      return;
    }

    for (var i = 0; i < lastIdentifiers.length; i++) {
      var identifier = lastIdentifiers[i];
      var index = getIndexByIdentifier(identifier);
      stylesInDom[index].references--;
    }

    var newLastIdentifiers = modulesToDom(newList, options);

    for (var _i = 0; _i < lastIdentifiers.length; _i++) {
      var _identifier = lastIdentifiers[_i];

      var _index = getIndexByIdentifier(_identifier);

      if (stylesInDom[_index].references === 0) {
        stylesInDom[_index].updater();

        stylesInDom.splice(_index, 1);
      }
    }

    lastIdentifiers = newLastIdentifiers;
  };
};

/***/ }),

/***/ "./resources/js/ppic/gudang/Gbj.vue":
/*!******************************************!*\
  !*** ./resources/js/ppic/gudang/Gbj.vue ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Gbj_vue_vue_type_template_id_1e3d30ab___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Gbj.vue?vue&type=template&id=1e3d30ab& */ "./resources/js/ppic/gudang/Gbj.vue?vue&type=template&id=1e3d30ab&");
/* harmony import */ var _Gbj_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Gbj.vue?vue&type=style&index=0&lang=scss& */ "./resources/js/ppic/gudang/Gbj.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");

var script = {}
;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  script,
  _Gbj_vue_vue_type_template_id_1e3d30ab___WEBPACK_IMPORTED_MODULE_0__.render,
  _Gbj_vue_vue_type_template_id_1e3d30ab___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/ppic/gudang/Gbj.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/ppic/gudang/Gbj.vue?vue&type=style&index=0&lang=scss&":
/*!****************************************************************************!*\
  !*** ./resources/js/ppic/gudang/Gbj.vue?vue&type=style&index=0&lang=scss& ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_2_node_modules_sass_loader_dist_cjs_js_clonedRuleSet_11_0_rules_0_use_3_node_modules_vue_loader_lib_index_js_vue_loader_options_Gbj_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[1]!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[2]!../../../../node_modules/sass-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[3]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Gbj.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-11[0].rules[0].use[3]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/ppic/gudang/Gbj.vue?vue&type=style&index=0&lang=scss&");


/***/ }),

/***/ "./resources/js/ppic/gudang/Gbj.vue?vue&type=template&id=1e3d30ab&":
/*!*************************************************************************!*\
  !*** ./resources/js/ppic/gudang/Gbj.vue?vue&type=template&id=1e3d30ab& ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Gbj_vue_vue_type_template_id_1e3d30ab___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Gbj_vue_vue_type_template_id_1e3d30ab___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Gbj_vue_vue_type_template_id_1e3d30ab___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Gbj.vue?vue&type=template&id=1e3d30ab& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/ppic/gudang/Gbj.vue?vue&type=template&id=1e3d30ab&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/ppic/gudang/Gbj.vue?vue&type=template&id=1e3d30ab&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/ppic/gudang/Gbj.vue?vue&type=template&id=1e3d30ab& ***!
  \****************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm._m(0)
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-lg-12" }, [
        _c(
          "ul",
          {
            staticClass: "nav nav-tabs",
            attrs: { id: "myTab", role: "tablist" }
          },
          [
            _c(
              "li",
              { staticClass: "nav-item", attrs: { role: "presentation" } },
              [
                _c(
                  "a",
                  {
                    staticClass: "nav-link active",
                    attrs: {
                      id: "semua-produk-tab",
                      "data-toggle": "tab",
                      href: "#semua-produk",
                      role: "tab",
                      "aria-controls": "semua-produk",
                      "aria-selected": "true"
                    }
                  },
                  [_vm._v("Semua Produk")]
                )
              ]
            ),
            _vm._v(" "),
            _c(
              "li",
              { staticClass: "nav-item", attrs: { role: "presentation" } },
              [
                _c(
                  "a",
                  {
                    staticClass: "nav-link",
                    attrs: {
                      id: "produk-tab",
                      "data-toggle": "tab",
                      href: "#produk",
                      role: "tab",
                      "aria-controls": "produk",
                      "aria-selected": "false"
                    }
                  },
                  [_vm._v("Per Produk")]
                )
              ]
            ),
            _vm._v(" "),
            _c(
              "li",
              { staticClass: "nav-item", attrs: { role: "presentation" } },
              [
                _c(
                  "a",
                  {
                    staticClass: "nav-link",
                    attrs: {
                      id: "tanggal-tab",
                      "data-toggle": "tab",
                      href: "#tanggal",
                      role: "tab",
                      "aria-controls": "tanggal",
                      "aria-selected": "false"
                    }
                  },
                  [_vm._v("Per Tanggal")]
                )
              ]
            )
          ]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "tab-content card", attrs: { id: "myTabContent" } },
          [
            _c(
              "div",
              {
                staticClass: "tab-pane fade show active card-body",
                attrs: {
                  id: "semua-produk",
                  role: "tabpanel",
                  "aria-labelledby": "semua-produk-tab"
                }
              },
              [
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-lg-12" }, [
                    _c(
                      "span",
                      {
                        staticClass: "dropdown float-right",
                        staticStyle: { "margin-right": "5px" },
                        attrs: { id: "filter" }
                      },
                      [
                        _c(
                          "button",
                          {
                            staticClass: "btn btn-outline-info dropdown-toggle",
                            attrs: {
                              type: "button",
                              id: "dropdownFilter",
                              "data-toggle": "dropdown",
                              "aria-haspopup": "true",
                              "aria-expanded": "false"
                            }
                          },
                          [_vm._v("\n                Filter\n              ")]
                        ),
                        _vm._v(" "),
                        _c(
                          "ul",
                          {
                            staticClass: "dropdown-menu dropdown-menu-right",
                            attrs: {
                              id: "filter_dd",
                              "aria-labelledby": "dropdownFilter"
                            }
                          },
                          [
                            _c("li", [
                              _c("span", { staticClass: "dropdown-header" }, [
                                _vm._v("Kelompok Produk")
                              ])
                            ]),
                            _vm._v(" "),
                            _c("li", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "dropdown-item jenis_po po_online",
                                  attrs: { id: "jenis_po", name: "jenis_po" }
                                },
                                [
                                  _c("input", {
                                    attrs: {
                                      type: "checkbox",
                                      value: "alat_kesehatan"
                                    }
                                  }),
                                  _vm._v(" Alat\n                    Kesehatan")
                                ]
                              )
                            ]),
                            _vm._v(" "),
                            _c("li", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "dropdown-item jenis_po po_offline",
                                  attrs: { id: "jenis_po", name: "jenis_po" }
                                },
                                [
                                  _c("input", {
                                    attrs: {
                                      type: "checkbox",
                                      value: "sarana_kesehatan"
                                    }
                                  }),
                                  _vm._v(
                                    " Sarana\n                    Kesehatan"
                                  )
                                ]
                              )
                            ]),
                            _vm._v(" "),
                            _c("li", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "dropdown-item jenis_po po_offline",
                                  attrs: { id: "jenis_po", name: "jenis_po" }
                                },
                                [
                                  _c("input", {
                                    attrs: {
                                      type: "checkbox",
                                      value: "aksesoris"
                                    }
                                  }),
                                  _vm._v("\n                    Aksesoris")
                                ]
                              )
                            ]),
                            _vm._v(" "),
                            _c("li", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "dropdown-item jenis_po po_offline",
                                  attrs: { id: "jenis_po", name: "jenis_po" }
                                },
                                [
                                  _c("input", {
                                    attrs: { type: "checkbox", value: "lain" }
                                  }),
                                  _vm._v(" Lain-lain")
                                ]
                              )
                            ])
                          ]
                        )
                      ]
                    )
                  ])
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "row", staticStyle: { "margin-top": "5px" } },
                  [
                    _c("div", { staticClass: "col-lg-12" }, [
                      _c("div", { staticClass: "table-responsive" }, [
                        _c(
                          "table",
                          {
                            staticClass: "table table-hover semuaproduk",
                            attrs: { width: "100%" }
                          },
                          [
                            _c(
                              "thead",
                              {
                                staticStyle: {
                                  "text-align": "center",
                                  "font-size": "15px"
                                }
                              },
                              [
                                _c("tr", [
                                  _c("th", [_vm._v("No")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Kode Produk")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Merk")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Nama Produk")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Kelompok Produk")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Stok")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Satuan")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Aksi")])
                                ])
                              ]
                            ),
                            _vm._v(" "),
                            _c("tbody", { attrs: { id: "tbodies" } }, [
                              _c("tr", [
                                _c("td", [_vm._v("1")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("BJAA01NB001")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("Elitech")]),
                                _vm._v(" "),
                                _c("td", [
                                  _vm._v("Anesthesia Nebulizer Promist 1")
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "span",
                                    { staticClass: "badge blue-text" },
                                    [_vm._v("Alat Kesehatan")]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "span",
                                    {
                                      staticClass: "float-right",
                                      staticStyle: { color: "green" }
                                    },
                                    [_vm._v("1000")]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("td", [_vm._v("unit")]),
                                _vm._v(" "),
                                _c("td", [
                                  _c("i", { staticClass: "fas fa-search" })
                                ])
                              ]),
                              _vm._v(" "),
                              _c("tr", [
                                _c("td", [_vm._v("2")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("BJOZ03G0001")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("Elitech")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("Ozone Generator OZ - 3G")]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "span",
                                    { staticClass: "badge orange-text" },
                                    [_vm._v("Sarana Kesehatan")]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "span",
                                    {
                                      staticClass: "float-right",
                                      staticStyle: { color: "red" }
                                    },
                                    [_vm._v("5")]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("td", [_vm._v("unit")]),
                                _vm._v(" "),
                                _c("td", [
                                  _c("i", { staticClass: "fas fa-search" })
                                ])
                              ]),
                              _vm._v(" "),
                              _c("tr", [
                                _c("td", [_vm._v("3")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("BJZZ03T0010")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("Elitech")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("Trolley Get 388UO")]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "span",
                                    { staticClass: "badge purple-text" },
                                    [_vm._v("Aksesoris")]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "span",
                                    {
                                      staticClass: "float-right",
                                      staticStyle: {
                                        color: "rgba(0, 0, 0, 0.4)"
                                      }
                                    },
                                    [_vm._v("0")]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("td", [_vm._v("unit")]),
                                _vm._v(" "),
                                _c("td", [
                                  _c("i", { staticClass: "fas fa-search" })
                                ])
                              ])
                            ])
                          ]
                        )
                      ])
                    ])
                  ]
                )
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "tab-pane fade card-body",
                attrs: {
                  id: "produk",
                  role: "tabpanel",
                  "aria-labelledby": "produk-tab"
                }
              },
              [
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-lg-12" }, [
                    _c("div", { staticClass: "form-horizontal" }, [
                      _c("div", { staticClass: "form-group row" }, [
                        _c(
                          "label",
                          {
                            staticClass: "col-sm-5 col-form-label",
                            staticStyle: { "text-align": "right" },
                            attrs: { for: "produk" }
                          },
                          [_vm._v("Cari Produk")]
                        ),
                        _vm._v(" "),
                        _c("div", { staticClass: "col-sm-7" }, [
                          _c("div", { staticClass: "select2-info" }, [
                            _c(
                              "select",
                              {
                                staticClass:
                                  "select2 custom-select form-control produk",
                                staticStyle: { width: "40%" },
                                attrs: {
                                  "data-dropdown-css-class": "select2-info",
                                  name: "produk",
                                  id: "produk"
                                }
                              },
                              [
                                _c("option", { attrs: { value: "" } }, [
                                  _vm._v("Tes")
                                ]),
                                _vm._v(" "),
                                _c("option", { attrs: { value: "" } }, [
                                  _vm._v("ERP")
                                ]),
                                _vm._v(" "),
                                _c("option", { attrs: { value: "" } }, [
                                  _vm._v("SPA")
                                ])
                              ]
                            )
                          ])
                        ])
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-lg-12" }, [
                    _c(
                      "span",
                      {
                        staticClass: "dropdown float-right",
                        staticStyle: { "margin-right": "5px" },
                        attrs: { id: "filter" }
                      },
                      [
                        _c(
                          "button",
                          {
                            staticClass: "btn btn-outline-info dropdown-toggle",
                            attrs: {
                              type: "button",
                              id: "dropdownFilter",
                              "data-toggle": "dropdown",
                              "aria-haspopup": "true",
                              "aria-expanded": "false"
                            }
                          },
                          [_vm._v("\n                Filter\n              ")]
                        ),
                        _vm._v(" "),
                        _c(
                          "ul",
                          {
                            staticClass: "dropdown-menu dropdown-menu-right",
                            attrs: {
                              id: "filter_dd",
                              "aria-labelledby": "dropdownFilter"
                            }
                          },
                          [
                            _c("li", [
                              _c("span", { staticClass: "dropdown-header" }, [
                                _vm._v("Asal / Tujuan")
                              ])
                            ]),
                            _vm._v(" "),
                            _c("li", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "dropdown-item jenis_po po_online",
                                  attrs: { id: "jenis_po", name: "jenis_po" }
                                },
                                [
                                  _c("input", {
                                    attrs: {
                                      type: "checkbox",
                                      value: "alat_kesehatan"
                                    }
                                  }),
                                  _vm._v("\n                    Produksi")
                                ]
                              )
                            ]),
                            _vm._v(" "),
                            _c("li", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "dropdown-item jenis_po po_offline",
                                  attrs: { id: "jenis_po", name: "jenis_po" }
                                },
                                [
                                  _c("input", {
                                    attrs: {
                                      type: "checkbox",
                                      value: "sarana_kesehatan"
                                    }
                                  }),
                                  _vm._v("\n                    QC")
                                ]
                              )
                            ]),
                            _vm._v(" "),
                            _c("li", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "dropdown-item jenis_po po_offline",
                                  attrs: { id: "jenis_po", name: "jenis_po" }
                                },
                                [
                                  _c("input", {
                                    attrs: {
                                      type: "checkbox",
                                      value: "aksesoris"
                                    }
                                  }),
                                  _vm._v(
                                    " Sarana\n                    Kesehatan"
                                  )
                                ]
                              )
                            ]),
                            _vm._v(" "),
                            _c("li", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "dropdown-item jenis_po po_offline",
                                  attrs: { id: "jenis_po", name: "jenis_po" }
                                },
                                [
                                  _c("input", {
                                    attrs: { type: "checkbox", value: "lain" }
                                  }),
                                  _vm._v(" Teknik")
                                ]
                              )
                            ])
                          ]
                        )
                      ]
                    )
                  ])
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "row", staticStyle: { "margin-top": "5px" } },
                  [
                    _c("div", { staticClass: "col-lg-3" }, [
                      _c("div", { staticClass: "card" }, [
                        _c("div", { staticClass: "card-body" }, [
                          _c("div", { staticClass: "form-horizontal" }, [
                            _c("div", { staticClass: "form-group row" }, [
                              _c("span", { staticClass: "col-form-label" }, [
                                _c("h4", [_vm._v("Info")])
                              ]),
                              _vm._v(" "),
                              _c(
                                "span",
                                { staticClass: "float-right success-text" },
                                [_c("b", [_vm._v("Tersedia")])]
                              )
                            ]),
                            _vm._v(" "),
                            _c("div", { staticClass: "form-group row" }, [
                              _c("span", { staticClass: "text-muted" }, [
                                _vm._v("Nama Produk")
                              ]),
                              _vm._v(" "),
                              _c("span", { staticClass: "float-right" }, [
                                _vm._v("FOX-BABY")
                              ])
                            ]),
                            _vm._v(" "),
                            _c("div", { staticClass: "form-group row" }, [
                              _c("span", { staticClass: "text-muted" }, [
                                _vm._v("Kelompok Produk")
                              ]),
                              _vm._v(" "),
                              _c("span", { staticClass: "float-right" }, [
                                _vm._v("Alat Kesehatan")
                              ])
                            ]),
                            _vm._v(" "),
                            _c("div", { staticClass: "form-group row" }, [
                              _c("span", { staticClass: "text-muted" }, [
                                _vm._v("Stok Terakhir")
                              ]),
                              _vm._v(" "),
                              _c("span", { staticClass: "float-right" }, [
                                _vm._v("FOX-BABY")
                              ])
                            ])
                          ])
                        ])
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "col-lg-9" }, [
                      _c("div", { staticClass: "table-responsive" }, [
                        _c(
                          "table",
                          {
                            staticClass:
                              "table table-hover table-striped perproduk",
                            attrs: { width: "100%" }
                          },
                          [
                            _c(
                              "thead",
                              {
                                staticStyle: {
                                  "text-align": "center",
                                  "font-size": "15px"
                                }
                              },
                              [
                                _c("tr", [
                                  _c("th", [_vm._v("No")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Tanggal")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Asal / Tujuan")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Keterangan")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Jumlah")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Aksi")])
                                ])
                              ]
                            ),
                            _vm._v(" "),
                            _c("tbody", { attrs: { id: "tbodies" } }, [
                              _c("tr", [
                                _c("td", [_vm._v("1")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("24-09-2021")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("Produksi")]),
                                _vm._v(" "),
                                _c("td", [
                                  _vm._v("Ref Hasil Produksi 0001/BPPB/09/21")
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "span",
                                    { staticStyle: { color: "green" } },
                                    [
                                      _c("i", { staticClass: "fas fa-plus" }),
                                      _c(
                                        "span",
                                        { staticClass: "float-right" },
                                        [_vm._v("1000")]
                                      )
                                    ]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c("a", { attrs: { href: "" } }, [
                                    _c("i", { staticClass: "fas fa-search" })
                                  ])
                                ])
                              ])
                            ])
                          ]
                        )
                      ])
                    ])
                  ]
                )
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "tab-pane fade card-body",
                attrs: {
                  id: "tanggal",
                  role: "tabpanel",
                  "aria-labelledby": "tanggal-tab"
                }
              },
              [
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-lg-12" }, [
                    _c("div", { staticClass: "form-horizontal" }, [
                      _c("div", { staticClass: "form-group row" }, [
                        _c(
                          "label",
                          {
                            staticClass: "col-sm-5 col-form-label",
                            staticStyle: { "text-align": "right" },
                            attrs: { for: "detail_produk_id" }
                          },
                          [_vm._v("Tanggal")]
                        ),
                        _vm._v(" "),
                        _c("div", { staticClass: "col-sm-2" }, [
                          _c("input", {
                            staticClass: "form-control",
                            attrs: { type: "date" }
                          })
                        ])
                      ])
                    ])
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "col-lg-12" }, [
                    _c(
                      "span",
                      {
                        staticClass: "dropdown float-right",
                        staticStyle: { "margin-right": "5px" },
                        attrs: { id: "filter" }
                      },
                      [
                        _c(
                          "button",
                          {
                            staticClass: "btn btn-outline-info dropdown-toggle",
                            attrs: {
                              type: "button",
                              id: "dropdownFilter",
                              "data-toggle": "dropdown",
                              "aria-haspopup": "true",
                              "aria-expanded": "false"
                            }
                          },
                          [_vm._v("\n                Filter\n              ")]
                        ),
                        _vm._v(" "),
                        _c(
                          "ul",
                          {
                            staticClass: "dropdown-menu dropdown-menu-right",
                            attrs: {
                              id: "filter_dd",
                              "aria-labelledby": "dropdownFilter"
                            }
                          },
                          [
                            _c("li", [
                              _c("span", { staticClass: "dropdown-header" }, [
                                _vm._v("Pilih Produk")
                              ])
                            ]),
                            _vm._v(" "),
                            _c("li", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "dropdown-item jenis_po po_online",
                                  attrs: { id: "jenis_po", name: "jenis_po" }
                                },
                                [
                                  _c(
                                    "select",
                                    {
                                      staticClass:
                                        "\n                        select2\n                        custom-select\n                        form-control\n                        @error('produk')\n                        is-invalid\n                        @enderror\n                        produk\n                      ",
                                      staticStyle: { width: "50%" },
                                      attrs: {
                                        "data-dropdown-css-class":
                                          "select2-info",
                                        name: "produk",
                                        id: "produk"
                                      }
                                    },
                                    [
                                      _c(
                                        "option",
                                        { attrs: { value: "Tes" } },
                                        [_vm._v("Tes")]
                                      )
                                    ]
                                  )
                                ]
                              )
                            ])
                          ]
                        )
                      ]
                    )
                  ])
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "row", staticStyle: { "margin-top": "5px" } },
                  [
                    _c("div", { staticClass: "col-lg-12" }, [
                      _c("div", { staticClass: "table-responsive" }, [
                        _c(
                          "table",
                          {
                            staticClass: "table table-hover pertanggal",
                            attrs: { width: "100%" }
                          },
                          [
                            _c(
                              "thead",
                              {
                                staticStyle: {
                                  "text-align": "center",
                                  "font-size": "15px"
                                }
                              },
                              [
                                _c("tr", [
                                  _c("th", [_vm._v("No")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Tanggal")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Nama Produk")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Asal / Tujuan")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Keterangan")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Jumlah")]),
                                  _vm._v(" "),
                                  _c("th", [_vm._v("Aksi")])
                                ])
                              ]
                            ),
                            _vm._v(" "),
                            _c("tbody", { attrs: { id: "tbodies" } }, [
                              _c("tr", [
                                _c("td", [_vm._v("1")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("24-09-2021")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("FOX-BABY")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("Produksi")]),
                                _vm._v(" "),
                                _c("td", [
                                  _vm._v("Ref Hasil Produksi 0001/BPPB/09/21")
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "span",
                                    { staticStyle: { color: "green" } },
                                    [
                                      _c("i", { staticClass: "fas fa-plus" }),
                                      _c(
                                        "span",
                                        { staticClass: "float-right" },
                                        [_vm._v("1000")]
                                      )
                                    ]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c("a", { attrs: { href: "" } }, [
                                    _c("i", { staticClass: "fas fa-search" })
                                  ])
                                ])
                              ]),
                              _vm._v(" "),
                              _c("tr", [
                                _c("td", [_vm._v("2")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("24-09-2021")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("CMS-600 PLUS")]),
                                _vm._v(" "),
                                _c("td", [_vm._v("Produksi")]),
                                _vm._v(" "),
                                _c("td", [
                                  _vm._v("Ref Hasil Produksi 0001/BPPB/09/21")
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c(
                                    "span",
                                    { staticStyle: { color: "red" } },
                                    [
                                      _c("i", { staticClass: "fas fa-plus" }),
                                      _c(
                                        "span",
                                        { staticClass: "float-right" },
                                        [_vm._v("10")]
                                      )
                                    ]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("td", [
                                  _c("a", { attrs: { href: "" } }, [
                                    _c("i", { staticClass: "fas fa-search" })
                                  ])
                                ])
                              ])
                            ])
                          ]
                        )
                      ])
                    ])
                  ]
                )
              ]
            )
          ]
        )
      ])
    ])
  }
]
render._withStripped = true



/***/ })

}]);