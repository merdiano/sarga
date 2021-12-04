import VueCarousel from 'vue-carousel';
Vue.directive("debounce", require("./directives/debounce"));

Vue.use(VueCarousel);
Vue.component('seller-category', require('./components/seller-category'));