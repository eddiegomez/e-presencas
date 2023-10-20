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
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/dashboard.init.js":
/*!**********************************************!*\
  !*** ./resources/js/pages/dashboard.init.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
Template Name: Shreyu - Responsive Bootstrap 4 Admin Dashboard
Author: CoderThemes
Version: 1.0.0
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Dashboard init js
*/
!function ($) {
  "use strict";

  var ChatApp = function ChatApp() {
    this.$body = $("body"), this.$chatInput = $('.chat-input'), this.$chatList = $('.conversation-list'), this.$chatSendBtn = $('.chat-send'), this.$chatForm = $("#chat-form");
  };

  ChatApp.prototype.save = function () {
    var chatText = this.$chatInput.val();
    var chatTime = moment().format("h:mm");

    if (chatText == "") {
      this.$chatInput.focus();
      return false;
    } else {
      $('<li class="clearfix odd"><div class="chat-avatar"><img src="assets/images/users/avatar-7.jpg" alt="male"><i>' + chatTime + '</i></div><div class="conversation-text"><div class="ctext-wrap"><i>Shreyu</i><p>' + chatText + '</p></div></div></li>').appendTo('.conversation-list');
      this.$chatInput.focus();
      this.$chatList.animate({
        scrollTop: this.$chatList.prop("scrollHeight")
      }, 1000);
      return true;
    }
  }; // init


  ChatApp.prototype.init = function () {
    var $this = this; //binding keypress event on chat input box - on enter we are adding the chat into chat list - 

    $this.$chatInput.keypress(function (ev) {
      var p = ev.which;

      if (p == 13) {
        $this.save();
        return false;
      }
    }); //binding send button click

    $this.$chatForm.on('submit', function (ev) {
      ev.preventDefault();
      $this.save();
      $this.$chatForm.removeClass('was-validated');
      $this.$chatInput.val('');
      return false;
    });
  }, //init ChatApp
  $.ChatApp = new ChatApp(), $.ChatApp.Constructor = ChatApp;
}(window.jQuery), function ($) {
  "use strict";

  var Dashboard = function Dashboard() {};

  Dashboard.prototype.initCharts = function () {
    window.Apex = {
      chart: {
        parentHeightOffset: 0,
        toolbar: {
          show: false
        }
      },
      grid: {
        padding: {
          left: 0,
          right: 0
        }
      },
      colors: ["#5369f8", "#43d39e", "#f77e53", "#ffbe0b"],
      tooltip: {
        theme: 'dark',
        x: {
          show: false
        }
      }
    }; // 
    // Stats
    //

    var options2 = {
      chart: {
        type: 'area',
        height: 45,
        width: 90,
        sparkline: {
          enabled: true
        }
      },
      series: [{
        data: [25, 66, 41, 85, 63, 25, 44, 12, 36, 9, 54]
      }],
      stroke: {
        width: 2,
        curve: 'smooth'
      },
      markers: {
        size: 0
      },
      colors: ["#727cf5"],
      tooltip: {
        fixed: {
          enabled: false
        },
        x: {
          show: false
        },
        y: {
          title: {
            formatter: function formatter(seriesName) {
              return '';
            }
          }
        },
        marker: {
          show: false
        }
      },
      fill: {
        type: 'gradient',
        gradient: {
          type: "vertical",
          shadeIntensity: 1,
          inverseColors: false,
          opacityFrom: 0.45,
          opacityTo: 0.05,
          stops: [45, 100]
        }
      }
    };
    new ApexCharts(document.querySelector("#today-revenue-chart"), options2).render();
    new ApexCharts(document.querySelector("#today-product-sold-chart"), $.extend({}, options2, {
      colors: ['#f77e53']
    })).render();
    new ApexCharts(document.querySelector("#today-new-customer-chart"), $.extend({}, options2, {
      colors: ['#43d39e']
    })).render();
    new ApexCharts(document.querySelector("#today-new-visitors-chart"), $.extend({}, options2, {
      colors: ['#ffbe0b']
    })).render(); // ------------------- revenue chart

    function getDaysInMonth(month, year) {
      var date = new Date(year, month, 1);
      var days = [];
      var idx = 0;

      while (date.getMonth() === month && idx < 15) {
        var d = new Date(date);
        days.push(d.getDate() + " " + d.toLocaleString('en-us', {
          month: 'short'
        }));
        date.setDate(date.getDate() + 1);
        idx += 1;
      }

      return days;
    }

    var now = new Date();
    var labels = getDaysInMonth(now.getMonth(), now.getFullYear());
    var options = {
      chart: {
        height: 296,
        type: 'area'
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 4
      },
      series: [{
        name: 'Revenue',
        data: [10, 20, 5, 15, 10, 20, 15, 25, 20, 30, 25, 40, 30, 50, 35]
      }],
      zoom: {
        enabled: false
      },
      legend: {
        show: false
      },
      colors: ['#43d39e'],
      xaxis: {
        type: 'string',
        categories: labels,
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false
        },
        labels: {}
      },
      yaxis: {
        labels: {
          formatter: function formatter(val) {
            return val + "k";
          }
        }
      },
      fill: {
        type: 'gradient',
        gradient: {
          type: "vertical",
          shadeIntensity: 1,
          inverseColors: false,
          opacityFrom: 0.45,
          opacityTo: 0.05,
          stops: [45, 100]
        }
      }
    };
    var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
    chart.render();
    /* ------------- target */

    var options = {
      chart: {
        height: 296,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '45%'
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      series: [{
        name: 'Net Profit',
        data: [35, 44, 55, 57, 56, 61]
      }, {
        name: 'Revenue',
        data: [52, 76, 85, 101, 98, 87]
      }],
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        axisBorder: {
          show: false
        }
      },
      legend: {
        show: false
      },
      grid: {
        row: {
          colors: ['transparent', 'transparent'],
          // takes an array which will be repeated on columns
          opacity: 0.2
        },
        borderColor: '#f3f4f7'
      },
      tooltip: {
        y: {
          formatter: function formatter(val) {
            return "$ " + val + " thousands";
          }
        }
      }
    };
    var chart = new ApexCharts(document.querySelector("#targets-chart"), options);
    chart.render(); // sales by category --------------------------------------------------

    var options = {
      plotOptions: {
        pie: {
          donut: {
            size: '70%'
          },
          expandOnClick: false
        }
      },
      chart: {
        height: 298,
        type: 'donut'
      },
      legend: {
        show: true,
        position: 'right',
        horizontalAlign: 'left',
        itemMargin: {
          horizontal: 6,
          vertical: 3
        }
      },
      series: [44, 55, 41, 17],
      labels: ['Clothes 44k', 'Smartphons 55k', 'Electronics 41k', 'Other 17k'],
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom'
          }
        }
      }],
      tooltip: {
        y: {
          formatter: function formatter(value) {
            return value + "k";
          }
        }
      }
    };
    var chart = new ApexCharts(document.querySelector("#sales-by-category-chart"), options);
    chart.render();
  }, //initializing
  Dashboard.prototype.init = function () {
    // date picker
    $('#dash-daterange').flatpickr({
      mode: "range",
      defaultDate: [moment().subtract(7, 'days').format('YYYY-MM-DD'), moment().format('YYYY-MM-DD')]
    }); // calendar

    $('#calendar-widget').flatpickr({
      inline: true,
      shorthandCurrentMonth: true
    }); // chat

    $.ChatApp.init(); // charts

    this.initCharts();
  }, $.Dashboard = new Dashboard(), $.Dashboard.Constructor = Dashboard;
}(window.jQuery), //initializing main application module
function ($) {
  "use strict";

  $.Dashboard.init();
}(window.jQuery);

/***/ }),

/***/ "./resources/scss/app-dark.scss":
/*!**************************************!*\
  !*** ./resources/scss/app-dark.scss ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/scss/app-rtl.scss":
/*!*************************************!*\
  !*** ./resources/scss/app-rtl.scss ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/scss/app.scss":
/*!*********************************!*\
  !*** ./resources/scss/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/scss/bootstrap-dark.scss":
/*!********************************************!*\
  !*** ./resources/scss/bootstrap-dark.scss ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/scss/bootstrap.scss":
/*!***************************************!*\
  !*** ./resources/scss/bootstrap.scss ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/scss/icons.scss":
/*!***********************************!*\
  !*** ./resources/scss/icons.scss ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** multi ./resources/js/pages/dashboard.init.js ./resources/scss/bootstrap.scss ./resources/scss/bootstrap-dark.scss ./resources/scss/icons.scss ./resources/scss/app-rtl.scss ./resources/scss/app.scss ./resources/scss/app-dark.scss ***!
  \********************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/nikhil/projects/themes/shreyu/laravel/resources/js/pages/dashboard.init.js */"./resources/js/pages/dashboard.init.js");
__webpack_require__(/*! /Users/nikhil/projects/themes/shreyu/laravel/resources/scss/bootstrap.scss */"./resources/scss/bootstrap.scss");
__webpack_require__(/*! /Users/nikhil/projects/themes/shreyu/laravel/resources/scss/bootstrap-dark.scss */"./resources/scss/bootstrap-dark.scss");
__webpack_require__(/*! /Users/nikhil/projects/themes/shreyu/laravel/resources/scss/icons.scss */"./resources/scss/icons.scss");
__webpack_require__(/*! /Users/nikhil/projects/themes/shreyu/laravel/resources/scss/app-rtl.scss */"./resources/scss/app-rtl.scss");
__webpack_require__(/*! /Users/nikhil/projects/themes/shreyu/laravel/resources/scss/app.scss */"./resources/scss/app.scss");
module.exports = __webpack_require__(/*! /Users/nikhil/projects/themes/shreyu/laravel/resources/scss/app-dark.scss */"./resources/scss/app-dark.scss");


/***/ })

/******/ });