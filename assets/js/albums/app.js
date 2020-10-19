import Vue from 'vue';
import router from './routing';

import App from './App.vue';

new Vue({
  el: '#news-app',
  render: h => h(App),
  router
});

require('svgxuse');