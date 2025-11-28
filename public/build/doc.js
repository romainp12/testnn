(self["webpackChunk"] = self["webpackChunk"] || []).push([["doc"],{

/***/ "./public/bundles/apiplatform/init-swagger-ui.js":
/*!*******************************************************!*\
  !*** ./public/bundles/apiplatform/init-swagger-ui.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


__webpack_require__(/*! core-js/modules/es.array.concat.js */ "./node_modules/core-js/modules/es.array.concat.js");

__webpack_require__(/*! core-js/modules/es.object.define-property.js */ "./node_modules/core-js/modules/es.object.define-property.js");

__webpack_require__(/*! core-js/modules/es.object.get-own-property-descriptor.js */ "./node_modules/core-js/modules/es.object.get-own-property-descriptor.js");

__webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");

__webpack_require__(/*! core-js/modules/es.string.replace.js */ "./node_modules/core-js/modules/es.string.replace.js");

__webpack_require__(/*! core-js/modules/web.timers.js */ "./node_modules/core-js/modules/web.timers.js");

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

window.onload = function () {
  manageWebbyDisplay();
  new MutationObserver(function (mutations, self) {
    var op = document.getElementById("operations-".concat(data.shortName, "-").concat(data.operationId));
    if (!op) return;
    self.disconnect();
    op.querySelector('.opblock-summary').click();
    var tryOutObserver = new MutationObserver(function (mutations, self) {
      var tryOut = op.querySelector('.try-out__btn');
      if (!tryOut) return;
      self.disconnect();
      tryOut.click();

      if (data.id) {
        var inputId = op.querySelector('.parameters input[placeholder="id"]');
        inputId.value = data.id;
        reactTriggerChange(inputId);
      }

      var _iterator = _createForOfIteratorHelper(op.querySelectorAll('.parameters input')),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var input = _step.value;

          if (input.placeholder in data.queryParameters) {
            input.value = data.queryParameters[input.placeholder];
            reactTriggerChange(input);
          }
        } // Wait input values to be populated before executing the query

      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      setTimeout(function () {
        op.querySelector('.execute').click();
        op.scrollIntoView();
      }, 500);
    });
    tryOutObserver.observe(document, {
      childList: true,
      subtree: true
    });
  }).observe(document, {
    childList: true,
    subtree: true
  });
  var data = JSON.parse(document.getElementById('swagger-data').innerText);
  var ui = SwaggerUIBundle({
    spec: data.spec,
    dom_id: '#swagger-ui',
    validatorUrl: null,
    oauth2RedirectUrl: data.oauth.redirectUrl,
    presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
    plugins: [SwaggerUIBundle.plugins.DownloadUrl],
    layout: 'StandaloneLayout'
  });

  if (data.oauth.enabled) {
    ui.initOAuth({
      clientId: data.oauth.clientId,
      clientSecret: data.oauth.clientSecret,
      realm: data.oauth.type,
      appName: data.spec.info.title,
      scopeSeparator: ' ',
      additionalQueryStringParams: {}
    });
  } // Workaround for https://github.com/swagger-api/swagger-ui/issues/3028
  // Adapted from https://github.com/vitalyq/react-trigger-change/blob/master/lib/change.js
  // Copyright (c) 2017 Vitaly Kuznetsov
  // MIT License


  function reactTriggerChange(node) {
    // Do not try to delete non-configurable properties.
    // Value and checked properties on DOM elements are non-configurable in PhantomJS.
    function deletePropertySafe(elem, prop) {
      var desc = Object.getOwnPropertyDescriptor(elem, prop);

      if (desc && desc.configurable) {
        delete elem[prop];
      }
    } // React 16
    // Cache artificial value property descriptor.
    // Property doesn't exist in React <16, descriptor is undefined.


    var descriptor = Object.getOwnPropertyDescriptor(node, 'value'); // React 0.14: IE9
    // React 15: IE9-IE11
    // React 16: IE9
    // Dispatch focus.

    var focusEvent = document.createEvent('UIEvents');
    focusEvent.initEvent('focus', false, false);
    node.dispatchEvent(focusEvent); // React 0.14: IE9
    // React 15: IE9-IE11
    // React 16
    // In IE9-10 imperative change of node value triggers propertychange event.
    // Update inputValueTracking cached value.
    // Remove artificial value property.
    // Restore initial value to trigger event with it.

    var initialValue = node.value;
    node.value = initialValue + '#';
    deletePropertySafe(node, 'value');
    node.value = initialValue; // React 15: IE11
    // For unknown reason React 15 added listener for propertychange with addEventListener.
    // This doesn't work, propertychange events are deprecated in IE11,
    // but allows us to dispatch fake propertychange which is handled by IE11.

    var propertychangeEvent = document.createEvent('HTMLEvents');
    propertychangeEvent.initEvent('propertychange', false, false);
    propertychangeEvent.propertyName = 'value';
    node.dispatchEvent(propertychangeEvent); // React 0.14: IE10-IE11, non-IE
    // React 15: non-IE
    // React 16: IE10-IE11, non-IE

    var inputEvent = document.createEvent('HTMLEvents');
    inputEvent.initEvent('input', true, false);
    node.dispatchEvent(inputEvent); // React 16
    // Restore artificial value property descriptor.

    if (descriptor) {
      Object.defineProperty(node, 'value', descriptor);
    }
  }

  function manageWebbyDisplay() {
    var webby = document.getElementsByClassName('webby')[0];
    if (!webby) return;
    var web = document.getElementsByClassName('web')[0];
    webby.classList.add('calm');
    web.classList.add('calm');
    webby.addEventListener('click', function () {
      if (webby.classList.contains('frighten')) {
        return;
      }

      webby.classList.replace('calm', 'frighten');
      web.classList.replace('calm', 'frighten');
      setTimeout(function () {
        webby.classList.replace('frighten', 'calm');
        web.classList.replace('frighten', 'calm');
      }, 10000);
    });
  }
};

/***/ })

},
0,[["./public/bundles/apiplatform/init-swagger-ui.js","runtime","vendors-node_modules_core-js_internals_export_js-node_modules_core-js_internals_to-object_js","vendors-node_modules_core-js_modules_es_array_concat_js-node_modules_core-js_modules_es_objec-6b6f75"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9wdWJsaWMvYnVuZGxlcy9hcGlwbGF0Zm9ybS9pbml0LXN3YWdnZXItdWkuanMiXSwibmFtZXMiOlsid2luZG93Iiwib25sb2FkIiwibWFuYWdlV2ViYnlEaXNwbGF5IiwiTXV0YXRpb25PYnNlcnZlciIsIm11dGF0aW9ucyIsInNlbGYiLCJvcCIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJkYXRhIiwic2hvcnROYW1lIiwib3BlcmF0aW9uSWQiLCJkaXNjb25uZWN0IiwicXVlcnlTZWxlY3RvciIsImNsaWNrIiwidHJ5T3V0T2JzZXJ2ZXIiLCJ0cnlPdXQiLCJpZCIsImlucHV0SWQiLCJ2YWx1ZSIsInJlYWN0VHJpZ2dlckNoYW5nZSIsInF1ZXJ5U2VsZWN0b3JBbGwiLCJpbnB1dCIsInBsYWNlaG9sZGVyIiwicXVlcnlQYXJhbWV0ZXJzIiwic2V0VGltZW91dCIsInNjcm9sbEludG9WaWV3Iiwib2JzZXJ2ZSIsImNoaWxkTGlzdCIsInN1YnRyZWUiLCJKU09OIiwicGFyc2UiLCJpbm5lclRleHQiLCJ1aSIsIlN3YWdnZXJVSUJ1bmRsZSIsInNwZWMiLCJkb21faWQiLCJ2YWxpZGF0b3JVcmwiLCJvYXV0aDJSZWRpcmVjdFVybCIsIm9hdXRoIiwicmVkaXJlY3RVcmwiLCJwcmVzZXRzIiwiYXBpcyIsIlN3YWdnZXJVSVN0YW5kYWxvbmVQcmVzZXQiLCJwbHVnaW5zIiwiRG93bmxvYWRVcmwiLCJsYXlvdXQiLCJlbmFibGVkIiwiaW5pdE9BdXRoIiwiY2xpZW50SWQiLCJjbGllbnRTZWNyZXQiLCJyZWFsbSIsInR5cGUiLCJhcHBOYW1lIiwiaW5mbyIsInRpdGxlIiwic2NvcGVTZXBhcmF0b3IiLCJhZGRpdGlvbmFsUXVlcnlTdHJpbmdQYXJhbXMiLCJub2RlIiwiZGVsZXRlUHJvcGVydHlTYWZlIiwiZWxlbSIsInByb3AiLCJkZXNjIiwiT2JqZWN0IiwiZ2V0T3duUHJvcGVydHlEZXNjcmlwdG9yIiwiY29uZmlndXJhYmxlIiwiZGVzY3JpcHRvciIsImZvY3VzRXZlbnQiLCJjcmVhdGVFdmVudCIsImluaXRFdmVudCIsImRpc3BhdGNoRXZlbnQiLCJpbml0aWFsVmFsdWUiLCJwcm9wZXJ0eWNoYW5nZUV2ZW50IiwicHJvcGVydHlOYW1lIiwiaW5wdXRFdmVudCIsImRlZmluZVByb3BlcnR5Iiwid2ViYnkiLCJnZXRFbGVtZW50c0J5Q2xhc3NOYW1lIiwid2ViIiwiY2xhc3NMaXN0IiwiYWRkIiwiYWRkRXZlbnRMaXN0ZW5lciIsImNvbnRhaW5zIiwicmVwbGFjZSJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7O0FBQWE7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FBRWJBLE1BQU0sQ0FBQ0MsTUFBUCxHQUFnQixZQUFXO0FBQ3ZCQyxvQkFBa0I7QUFFbEIsTUFBSUMsZ0JBQUosQ0FBcUIsVUFBVUMsU0FBVixFQUFxQkMsSUFBckIsRUFBMkI7QUFDNUMsUUFBTUMsRUFBRSxHQUFHQyxRQUFRLENBQUNDLGNBQVQsc0JBQXNDQyxJQUFJLENBQUNDLFNBQTNDLGNBQXdERCxJQUFJLENBQUNFLFdBQTdELEVBQVg7QUFDQSxRQUFJLENBQUNMLEVBQUwsRUFBUztBQUVURCxRQUFJLENBQUNPLFVBQUw7QUFFQU4sTUFBRSxDQUFDTyxhQUFILENBQWlCLGtCQUFqQixFQUFxQ0MsS0FBckM7QUFDQSxRQUFNQyxjQUFjLEdBQUcsSUFBSVosZ0JBQUosQ0FBcUIsVUFBVUMsU0FBVixFQUFxQkMsSUFBckIsRUFBMkI7QUFDbkUsVUFBTVcsTUFBTSxHQUFHVixFQUFFLENBQUNPLGFBQUgsQ0FBaUIsZUFBakIsQ0FBZjtBQUNBLFVBQUksQ0FBQ0csTUFBTCxFQUFhO0FBRWJYLFVBQUksQ0FBQ08sVUFBTDtBQUVBSSxZQUFNLENBQUNGLEtBQVA7O0FBQ0EsVUFBSUwsSUFBSSxDQUFDUSxFQUFULEVBQWE7QUFDVCxZQUFNQyxPQUFPLEdBQUdaLEVBQUUsQ0FBQ08sYUFBSCxDQUFpQixxQ0FBakIsQ0FBaEI7QUFDQUssZUFBTyxDQUFDQyxLQUFSLEdBQWdCVixJ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QUFxQ1EsVUFBckM7QUFDSDtBQUNKOztBQUVELFdBQVNoRSxrQkFBVCxHQUE4QjtBQUMxQixRQUFNMEUsS0FBSyxHQUFHckUsUUFBUSxDQUFDc0Usc0JBQVQsQ0FBZ0MsT0FBaEMsRUFBeUMsQ0FBekMsQ0FBZDtBQUNBLFFBQUksQ0FBQ0QsS0FBTCxFQUFZO0FBRVosUUFBTUUsR0FBRyxHQUFHdkUsUUFBUSxDQUFDc0Usc0JBQVQsQ0FBZ0MsS0FBaEMsRUFBdUMsQ0FBdkMsQ0FBWjtBQUNBRCxTQUFLLENBQUNHLFNBQU4sQ0FBZ0JDLEdBQWhCLENBQW9CLE1BQXBCO0FBQ0FGLE9BQUcsQ0FBQ0MsU0FBSixDQUFjQyxHQUFkLENBQWtCLE1BQWxCO0FBQ0FKLFNBQUssQ0FBQ0ssZ0JBQU4sQ0FBdUIsT0FBdkIsRUFBZ0MsWUFBTTtBQUNsQyxVQUFJTCxLQUFLLENBQUNHLFNBQU4sQ0FBZ0JHLFFBQWhCLENBQXlCLFVBQXpCLENBQUosRUFBMEM7QUFDdEM7QUFDSDs7QUFDRE4sV0FBSyxDQUFDRyxTQUFOLENBQWdCSSxPQUFoQixDQUF3QixNQUF4QixFQUFnQyxVQUFoQztBQUNBTCxTQUFHLENBQUNDLFNBQUosQ0FBY0ksT0FBZCxDQUFzQixNQUF0QixFQUE4QixVQUE5QjtBQUNBMUQsZ0JBQVUsQ0FBQyxZQUFNO0FBQ2JtRCxhQUFLLENBQUNHLFNBQU4sQ0FBZ0JJLE9BQWhCLENBQXdCLFVBQXhCLEVBQW9DLE1BQXBDO0FBQ0FMLFdBQUcsQ0FBQ0MsU0FBSixDQUFjSSxPQUFkLENBQXNCLFVBQXRCLEVBQWtDLE1BQWxDO0FBQ0gsT0FIUyxFQUdQLEtBSE8sQ0FBVjtBQUlILEtBVkQ7QUFXSDtBQUNKLENBcEpELEMiLCJmaWxlIjoiZG9jLmpzIiwic291cmNlc0NvbnRlbnQiOlsiJ3VzZSBzdHJpY3QnO1xuXG53aW5kb3cub25sb2FkID0gZnVuY3Rpb24oKSB7XG4gICAgbWFuYWdlV2ViYnlEaXNwbGF5KCk7XG5cbiAgICBuZXcgTXV0YXRpb25PYnNlcnZlcihmdW5jdGlvbiAobXV0YXRpb25zLCBzZWxmKSB7XG4gICAgICAgIGNvbnN0IG9wID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoYG9wZXJhdGlvbnMtJHtkYXRhLnNob3J0TmFtZX0tJHtkYXRhLm9wZXJhdGlvbklkfWApO1xuICAgICAgICBpZiAoIW9wKSByZXR1cm47XG5cbiAgICAgICAgc2VsZi5kaXNjb25uZWN0KCk7XG5cbiAgICAgICAgb3AucXVlcnlTZWxlY3RvcignLm9wYmxvY2stc3VtbWFyeScpLmNsaWNrKCk7XG4gICAgICAgIGNvbnN0IHRyeU91dE9ic2VydmVyID0gbmV3IE11dGF0aW9uT2JzZXJ2ZXIoZnVuY3Rpb24gKG11dGF0aW9ucywgc2VsZikge1xuICAgICAgICAgICAgY29uc3QgdHJ5T3V0ID0gb3AucXVlcnlTZWxlY3RvcignLnRyeS1vdXRfX2J0bicpO1xuICAgICAgICAgICAgaWYgKCF0cnlPdXQpIHJldHVybjtcblxuICAgICAgICAgICAgc2VsZi5kaXNjb25uZWN0KCk7XG5cbiAgICAgICAgICAgIHRyeU91dC5jbGljaygpO1xuICAgICAgICAgICAgaWYgKGRhdGEuaWQpIHtcbiAgICAgICAgICAgICAgICBjb25zdCBpbnB1dElkID0gb3AucXVlcnlTZWxlY3RvcignLnBhcmFtZXRlcnMgaW5wdXRbcGxhY2Vob2xkZXI9XCJpZFwiXScpO1xuICAgICAgICAgICAgICAgIGlucHV0SWQudmFsdWUgPSBkYXRhLmlkO1xuICAgICAgICAgICAgICAgIHJlYWN0VHJpZ2dlckNoYW5nZShpbnB1dElkKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgZm9yIChjb25zdCBpbnB1dCBvZiBvcC5xdWVyeVNlbGVjdG9yQWxsKCcucGFyYW1ldGVycyBpbnB1dCcpKSB7XG4gICAgICAgICAgICAgICAgaWYgKGlucHV0LnBsYWNlaG9sZGVyIGluIGRhdGEucXVlcnlQYXJhbWV0ZXJzKSB7XG4gICAgICAgICAgICAgICAgICAgIGlucHV0LnZhbHVlID0gZGF0YS5xdWVyeVBhcmFtZXRlcnNbaW5wdXQucGxhY2Vob2xkZXJdO1xuICAgICAgICAgICAgICAgICAgICByZWFjdFRyaWdnZXJDaGFuZ2UoaW5wdXQpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy8gV2FpdCBpbnB1dCB2YWx1ZXMgdG8gYmUgcG9wdWxhdGVkIGJlZm9yZSBleGVjdXRpbmcgdGhlIHF1ZXJ5XG4gICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICAgICAgb3AucXVlcnlTZWxlY3RvcignLmV4ZWN1dGUnKS5jbGljaygpO1xuICAgICAgICAgICAgICAgIG9wLnNjcm9sbEludG9WaWV3KCk7XG4gICAgICAgICAgICB9LCA1MDApO1xuICAgICAgICB9KTtcblxuICAgICAgICB0cnlPdXRPYnNlcnZlci5vYnNlcnZlKGRvY3VtZW50LCB7Y2hpbGRMaXN0OiB0cnVlLCBzdWJ0cmVlOiB0cnVlfSk7XG4gICAgfSkub2JzZXJ2ZShkb2N1bWVudCwge2NoaWxkTGlzdDogdHJ1ZSwgc3VidHJlZTogdHJ1ZX0pO1xuXG4gICAgY29uc3QgZGF0YSA9IEpTT04ucGFyc2UoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3N3YWdnZXItZGF0YScpLmlubmVyVGV4dCk7XG4gICAgY29uc3QgdWkgPSBTd2FnZ2VyVUlCdW5kbGUoe1xuICAgICAgICBzcGVjOiBkYXRhLnNwZWMsXG4gICAgICAgIGRvbV9pZDogJyNzd2FnZ2VyLXVpJyxcbiAgICAgICAgdmFsaWRhdG9yVXJsOiBudWxsLFxuICAgICAgICBvYXV0aDJSZWRpcmVjdFVybDogZGF0YS5vYXV0aC5yZWRpcmVjdFVybCxcbiAgICAgICAgcHJlc2V0czogW1xuICAgICAgICAgICAgU3dhZ2dlclVJQnVuZGxlLnByZXNldHMuYXBpcyxcbiAgICAgICAgICAgIFN3YWdnZXJVSVN0YW5kYWxvbmVQcmVzZXQsXG4gICAgICAgIF0sXG4gICAgICAgIHBsdWdpbnM6IFtcbiAgICAgICAgICAgIFN3YWdnZXJVSUJ1bmRsZS5wbHVnaW5zLkRvd25sb2FkVXJsLFxuICAgICAgICBdLFxuICAgICAgICBsYXlvdXQ6ICdTdGFuZGFsb25lTGF5b3V0JyxcbiAgICB9KTtcblxuICAgIGlmIChkYXRhLm9hdXRoLmVuYWJsZWQpIHtcbiAgICAgICAgdWkuaW5pdE9BdXRoKHtcbiAgICAgICAgICAgIGNsaWVudElkOiBkYXRhLm9hdXRoLmNsaWVudElkLFxuICAgICAgICAgICAgY2xpZW50U2VjcmV0OiBkYXRhLm9hdXRoLmNsaWVudFNlY3JldCxcbiAgICAgICAgICAgIHJlYWxtOiBkYXRhLm9hdXRoLnR5cGUsXG4gICAgICAgICAgICBhcHBOYW1lOiBkYXRhLnNwZWMuaW5mby50aXRsZSxcbiAgICAgICAgICAgIHNjb3BlU2VwYXJhdG9yOiAnICcsXG4gICAgICAgICAgICBhZGRpdGlvbmFsUXVlcnlTdHJpbmdQYXJhbXM6IHt9XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIC8vIFdvcmthcm91bmQgZm9yIGh0dHBzOi8vZ2l0aHViLmNvbS9zd2FnZ2VyLWFwaS9zd2FnZ2VyLXVpL2lzc3Vlcy8zMDI4XG4gICAgLy8gQWRhcHRlZCBmcm9tIGh0dHBzOi8vZ2l0aHViLmNvbS92aXRhbHlxL3JlYWN0LXRyaWdnZXItY2hhbmdlL2Jsb2IvbWFzdGVyL2xpYi9jaGFuZ2UuanNcbiAgICAvLyBDb3B5cmlnaHQgKGMpIDIwMTcgVml0YWx5IEt1em5ldHNvdlxuICAgIC8vIE1JVCBMaWNlbnNlXG4gICAgZnVuY3Rpb24gcmVhY3RUcmlnZ2VyQ2hhbmdlKG5vZGUpIHtcbiAgICAgICAgLy8gRG8gbm90IHRyeSB0byBkZWxldGUgbm9uLWNvbmZpZ3VyYWJsZSBwcm9wZXJ0aWVzLlxuICAgICAgICAvLyBWYWx1ZSBhbmQgY2hlY2tlZCBwcm9wZXJ0aWVzIG9uIERPTSBlbGVtZW50cyBhcmUgbm9uLWNvbmZpZ3VyYWJsZSBpbiBQaGFudG9tSlMuXG4gICAgICAgIGZ1bmN0aW9uIGRlbGV0ZVByb3BlcnR5U2FmZShlbGVtLCBwcm9wKSB7XG4gICAgICAgICAgICBjb25zdCBkZXNjID0gT2JqZWN0LmdldE93blByb3BlcnR5RGVzY3JpcHRvcihlbGVtLCBwcm9wKTtcbiAgICAgICAgICAgIGlmIChkZXNjICYmIGRlc2MuY29uZmlndXJhYmxlKSB7XG4gICAgICAgICAgICAgICAgZGVsZXRlIGVsZW1bcHJvcF07XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICAvLyBSZWFjdCAxNlxuICAgICAgICAvLyBDYWNoZSBhcnRpZmljaWFsIHZhbHVlIHByb3BlcnR5IGRlc2NyaXB0b3IuXG4gICAgICAgIC8vIFByb3BlcnR5IGRvZXNuJ3QgZXhpc3QgaW4gUmVhY3QgPDE2LCBkZXNjcmlwdG9yIGlzIHVuZGVmaW5lZC5cbiAgICAgICAgY29uc3QgZGVzY3JpcHRvciA9IE9iamVjdC5nZXRPd25Qcm9wZXJ0eURlc2NyaXB0b3Iobm9kZSwgJ3ZhbHVlJyk7XG5cbiAgICAgICAgLy8gUmVhY3QgMC4xNDogSUU5XG4gICAgICAgIC8vIFJlYWN0IDE1OiBJRTktSUUxMVxuICAgICAgICAvLyBSZWFjdCAxNjogSUU5XG4gICAgICAgIC8vIERpc3BhdGNoIGZvY3VzLlxuICAgICAgICBjb25zdCBmb2N1c0V2ZW50ID0gZG9jdW1lbnQuY3JlYXRlRXZlbnQoJ1VJRXZlbnRzJyk7XG4gICAgICAgIGZvY3VzRXZlbnQuaW5pdEV2ZW50KCdmb2N1cycsIGZhbHNlLCBmYWxzZSk7XG4gICAgICAgIG5vZGUuZGlzcGF0Y2hFdmVudChmb2N1c0V2ZW50KTtcblxuICAgICAgICAvLyBSZWFjdCAwLjE0OiBJRTlcbiAgICAgICAgLy8gUmVhY3QgMTU6IElFOS1JRTExXG4gICAgICAgIC8vIFJlYWN0IDE2XG4gICAgICAgIC8vIEluIElFOS0xMCBpbXBlcmF0aXZlIGNoYW5nZSBvZiBub2RlIHZhbHVlIHRyaWdnZXJzIHByb3BlcnR5Y2hhbmdlIGV2ZW50LlxuICAgICAgICAvLyBVcGRhdGUgaW5wdXRWYWx1ZVRyYWNraW5nIGNhY2hlZCB2YWx1ZS5cbiAgICAgICAgLy8gUmVtb3ZlIGFydGlmaWNpYWwgdmFsdWUgcHJvcGVydHkuXG4gICAgICAgIC8vIFJlc3RvcmUgaW5pdGlhbCB2YWx1ZSB0byB0cmlnZ2VyIGV2ZW50IHdpdGggaXQuXG4gICAgICAgIGNvbnN0IGluaXRpYWxWYWx1ZSA9IG5vZGUudmFsdWU7XG4gICAgICAgIG5vZGUudmFsdWUgPSBpbml0aWFsVmFsdWUgKyAnIyc7XG4gICAgICAgIGRlbGV0ZVByb3BlcnR5U2FmZShub2RlLCAndmFsdWUnKTtcbiAgICAgICAgbm9kZS52YWx1ZSA9IGluaXRpYWxWYWx1ZTtcblxuICAgICAgICAvLyBSZWFjdCAxNTogSUUxMVxuICAgICAgICAvLyBGb3IgdW5rbm93biByZWFzb24gUmVhY3QgMTUgYWRkZWQgbGlzdGVuZXIgZm9yIHByb3BlcnR5Y2hhbmdlIHdpdGggYWRkRXZlbnRMaXN0ZW5lci5cbiAgICAgICAgLy8gVGhpcyBkb2Vzbid0IHdvcmssIHByb3BlcnR5Y2hhbmdlIGV2ZW50cyBhcmUgZGVwcmVjYXRlZCBpbiBJRTExLFxuICAgICAgICAvLyBidXQgYWxsb3dzIHVzIHRvIGRpc3BhdGNoIGZha2UgcHJvcGVydHljaGFuZ2Ugd2hpY2ggaXMgaGFuZGxlZCBieSBJRTExLlxuICAgICAgICBjb25zdCBwcm9wZXJ0eWNoYW5nZUV2ZW50ID0gZG9jdW1lbnQuY3JlYXRlRXZlbnQoJ0hUTUxFdmVudHMnKTtcbiAgICAgICAgcHJvcGVydHljaGFuZ2VFdmVudC5pbml0RXZlbnQoJ3Byb3BlcnR5Y2hhbmdlJywgZmFsc2UsIGZhbHNlKTtcbiAgICAgICAgcHJvcGVydHljaGFuZ2VFdmVudC5wcm9wZXJ0eU5hbWUgPSAndmFsdWUnO1xuICAgICAgICBub2RlLmRpc3BhdGNoRXZlbnQocHJvcGVydHljaGFuZ2VFdmVudCk7XG5cbiAgICAgICAgLy8gUmVhY3QgMC4xNDogSUUxMC1JRTExLCBub24tSUVcbiAgICAgICAgLy8gUmVhY3QgMTU6IG5vbi1JRVxuICAgICAgICAvLyBSZWFjdCAxNjogSUUxMC1JRTExLCBub24tSUVcbiAgICAgICAgY29uc3QgaW5wdXRFdmVudCA9IGRvY3VtZW50LmNyZWF0ZUV2ZW50KCdIVE1MRXZlbnRzJyk7XG4gICAgICAgIGlucHV0RXZlbnQuaW5pdEV2ZW50KCdpbnB1dCcsIHRydWUsIGZhbHNlKTtcbiAgICAgICAgbm9kZS5kaXNwYXRjaEV2ZW50KGlucHV0RXZlbnQpO1xuXG4gICAgICAgIC8vIFJlYWN0IDE2XG4gICAgICAgIC8vIFJlc3RvcmUgYXJ0aWZpY2lhbCB2YWx1ZSBwcm9wZXJ0eSBkZXNjcmlwdG9yLlxuICAgICAgICBpZiAoZGVzY3JpcHRvcikge1xuICAgICAgICAgICAgT2JqZWN0LmRlZmluZVByb3BlcnR5KG5vZGUsICd2YWx1ZScsIGRlc2NyaXB0b3IpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgZnVuY3Rpb24gbWFuYWdlV2ViYnlEaXNwbGF5KCkge1xuICAgICAgICBjb25zdCB3ZWJieSA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3dlYmJ5JylbMF07XG4gICAgICAgIGlmICghd2ViYnkpIHJldHVybjtcblxuICAgICAgICBjb25zdCB3ZWIgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd3ZWInKVswXTtcbiAgICAgICAgd2ViYnkuY2xhc3NMaXN0LmFkZCgnY2FsbScpO1xuICAgICAgICB3ZWIuY2xhc3NMaXN0LmFkZCgnY2FsbScpO1xuICAgICAgICB3ZWJieS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcbiAgICAgICAgICAgIGlmICh3ZWJieS5jbGFzc0xpc3QuY29udGFpbnMoJ2ZyaWdodGVuJykpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB3ZWJieS5jbGFzc0xpc3QucmVwbGFjZSgnY2FsbScsICdmcmlnaHRlbicpO1xuICAgICAgICAgICAgd2ViLmNsYXNzTGlzdC5yZXBsYWNlKCdjYWxtJywgJ2ZyaWdodGVuJyk7XG4gICAgICAgICAgICBzZXRUaW1lb3V0KCgpID0+IHtcbiAgICAgICAgICAgICAgICB3ZWJieS5jbGFzc0xpc3QucmVwbGFjZSgnZnJpZ2h0ZW4nLCAnY2FsbScpO1xuICAgICAgICAgICAgICAgIHdlYi5jbGFzc0xpc3QucmVwbGFjZSgnZnJpZ2h0ZW4nLCAnY2FsbScpO1xuICAgICAgICAgICAgfSwgMTAwMDApO1xuICAgICAgICB9KTtcbiAgICB9XG59O1xuIl0sInNvdXJjZVJvb3QiOiIifQ==