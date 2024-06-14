window.$ = window.jQuery = window.jquery = require("./package/jquery");
// window.Swal = require("./package/sweetalert");
require("../package/mdbootstrap/js/mdb");
require("./package/validator");
// require("./package/laja");
// require("./package/upload-file");
// require("./package/common");
// require("./package/auto-grid-fill");
// require("./package/maskjs/jquery.mask");
// import { Fancybox } from "@fancyapps/ui";
// window.Fancybox = Fancybox;
window.moment = require("moment");
import Alpine from "alpinejs";
const { default: Axios } = require("axios");
window.Alpine = Alpine;
window.Axios = Axios;
const { default: anime } = require("animejs");
window.anime = anime;
// import Choices from "choices.js";
// window.Choices = Choices;
// require("./libs");
// require("./package/animation/index");

// import ApexCharts from "apexcharts";
// window.ApexCharts = ApexCharts;
// import { saveAs } from "file-saver";
// window.saveAs = saveAs;

// import Vue from 'vue';
// import Vue from "vue/dist/vue.js";
// window.Vue = Vue;

// require("./package/multiple-select");
// require("./package/exceljs");

Alpine.store("animate", {
  enter: (target, fn) => {
    if (!target) return;
    anime({
      targets: target, //this.$root.children[0]
      scale: [0.9, 1],
      opacity: [0, 1],
      direction: "forwards",
      easing: "easeInSine",
      duration: 200,
      complete: (res) => {
        fn ? fn(res) : null;
      },
    });
  },
  leave: (target, fn) => {
    if (!target) return;
    anime({
      targets: target,
      scale: [1, 0.9],
      opacity: [1, 0],
      direction: "forwards",
      easing: "easeOutSine",
      duration: 200,
      complete: (res) => {
        fn ? fn(res) : null;
      },
    });
  },
});
window.reloadData = function(url) {
    window.location.href = url;
};
