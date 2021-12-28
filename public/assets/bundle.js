/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/scss/main.scss":
/*!****************************!*\
  !*** ./src/scss/main.scss ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://php2-assignment/./src/scss/main.scss?");

/***/ }),

/***/ "./src/ts/fileUpload.ts":
/*!******************************!*\
  !*** ./src/ts/fileUpload.ts ***!
  \******************************/
/***/ (() => {

eval("const inputs = document.querySelectorAll('.input-pic');\r\nArray.prototype.forEach.call(inputs, (input) => {\r\n    const label = input.nextElementSibling;\r\n    if (label != null) {\r\n        const labelVal = label.innerHTML;\r\n        input.addEventListener('change', (e) => {\r\n            var _a;\r\n            let fileName = '';\r\n            fileName = (_a = input.value.split('\\\\').pop()) !== null && _a !== void 0 ? _a : 'file';\r\n            const span = label.querySelector('span');\r\n            if (fileName && span != null)\r\n                span.innerHTML = fileName;\r\n            else\r\n                label.innerHTML = labelVal;\r\n        });\r\n    }\r\n});\r\n\n\n//# sourceURL=webpack://php2-assignment/./src/ts/fileUpload.ts?");

/***/ }),

/***/ "./src/ts/index.ts":
/*!*************************!*\
  !*** ./src/ts/index.ts ***!
  \*************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _inputValidation__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./inputValidation */ \"./src/ts/inputValidation.ts\");\n/* harmony import */ var _inputValidation__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_inputValidation__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _scrollBtn__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./scrollBtn */ \"./src/ts/scrollBtn.ts\");\n/* harmony import */ var _scrollBtn__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_scrollBtn__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _fileUpload__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./fileUpload */ \"./src/ts/fileUpload.ts\");\n/* harmony import */ var _fileUpload__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_fileUpload__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _nav__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nav */ \"./src/ts/nav.ts\");\n/* harmony import */ var _nav__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_nav__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _scss_main_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../scss/main.scss */ \"./src/scss/main.scss\");\n\r\n\r\n\r\n\r\n//import './export'\r\n// Styling\r\n\r\n\n\n//# sourceURL=webpack://php2-assignment/./src/ts/index.ts?");

/***/ }),

/***/ "./src/ts/inputValidation.ts":
/*!***********************************!*\
  !*** ./src/ts/inputValidation.ts ***!
  \***********************************/
/***/ (() => {

eval("const email = document.getElementById('email');\r\nconst confirmEmail = document.getElementById('confirmemail');\r\nconst password = document.getElementById('pass');\r\nconst confirmPassword = document.getElementById('confirmpass');\r\nconst nameInput = document.getElementById('name');\r\nfunction validateInputs(input, confirm) {\r\n    if (input.value !== confirm.value) {\r\n        confirm.setCustomValidity('Values do not match.');\r\n        confirm.style.backgroundColor = '#fff6f6';\r\n        confirm.style.color = 'red';\r\n    }\r\n    else {\r\n        confirm.setCustomValidity('');\r\n        confirm.style.backgroundColor = 'white';\r\n        confirm.style.color = 'black';\r\n    }\r\n}\r\nfunction validateName(input) {\r\n    const pattern = new RegExp(/\\d/);\r\n    if (pattern.exec(input.value) == null) {\r\n        input.setCustomValidity('');\r\n        input.style.backgroundColor = 'white';\r\n        input.style.color = 'black';\r\n    }\r\n    else {\r\n        input.setCustomValidity('Name should not contain numbers.');\r\n        input.style.backgroundColor = '#fff6f6';\r\n        input.style.color = 'red';\r\n    }\r\n}\r\nif (email != null && confirmEmail != null) {\r\n    email.addEventListener('change', () => {\r\n        validateInputs(email, confirmEmail);\r\n    });\r\n    confirmEmail.addEventListener('keyup', () => {\r\n        validateInputs(email, confirmEmail);\r\n    });\r\n}\r\nif (password != null && confirmPassword != null) {\r\n    password.addEventListener('change', () => {\r\n        validateInputs(password, confirmPassword);\r\n    });\r\n    confirmPassword.addEventListener('keyup', () => {\r\n        validateInputs(password, confirmPassword);\r\n    });\r\n}\r\nif (nameInput != null) {\r\n    nameInput.addEventListener('change', () => {\r\n        validateName(nameInput);\r\n    });\r\n    nameInput.addEventListener('keyup', () => {\r\n        validateName(nameInput);\r\n    });\r\n}\r\n\n\n//# sourceURL=webpack://php2-assignment/./src/ts/inputValidation.ts?");

/***/ }),

/***/ "./src/ts/nav.ts":
/*!***********************!*\
  !*** ./src/ts/nav.ts ***!
  \***********************/
/***/ (() => {

eval("var _a;\r\n(_a = document.getElementById('menu-icon')) === null || _a === void 0 ? void 0 : _a.addEventListener('click', () => {\r\n    const nav = document.getElementById('main-nav');\r\n    if (nav != null) {\r\n        nav.classList.toggle('responsive');\r\n    }\r\n});\r\nfunction changeActivePage(target) {\r\n    // Remove the .active class from each item\r\n    document.querySelectorAll('#main-nav > a.active').forEach((element) => {\r\n        element.classList.remove('active');\r\n    });\r\n    // Add .active class to target item\r\n    document.querySelectorAll('#main-nav > a').forEach((element) => {\r\n        if (element.getAttribute('href') === target) {\r\n            element.classList.add('active');\r\n        }\r\n    });\r\n}\r\nchangeActivePage(window.location.pathname);\r\n\n\n//# sourceURL=webpack://php2-assignment/./src/ts/nav.ts?");

/***/ }),

/***/ "./src/ts/scrollBtn.ts":
/*!*****************************!*\
  !*** ./src/ts/scrollBtn.ts ***!
  \*****************************/
/***/ (() => {

eval("var _a;\r\n/* Scroll back button */\r\nconst scrollBtn = document.getElementById('scroll-btn');\r\nif (scrollBtn != null) {\r\n    window.addEventListener('scroll', () => {\r\n        if (document.body.scrollTop > 350 || document.documentElement.scrollTop > 350) {\r\n            scrollBtn.style.display = 'block';\r\n            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 110) {\r\n                scrollBtn.style.position = 'absolute';\r\n                scrollBtn.style.bottom = '160px';\r\n            }\r\n            else {\r\n                scrollBtn.style.position = 'fixed';\r\n                scrollBtn.style.bottom = '40px';\r\n            }\r\n        }\r\n        else {\r\n            scrollBtn.style.display = 'none';\r\n        }\r\n    });\r\n}\r\n(_a = document.getElementById('scroll-btn')) === null || _a === void 0 ? void 0 : _a.addEventListener('click', () => {\r\n    document.body.scrollTop = 0;\r\n    document.documentElement.scrollTop = 0;\r\n});\r\n\n\n//# sourceURL=webpack://php2-assignment/./src/ts/scrollBtn.ts?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		if(__webpack_module_cache__[moduleId]) {
/******/ 			return __webpack_module_cache__[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./src/ts/index.ts");
/******/ 	
/******/ })()
;