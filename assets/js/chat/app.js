import Vue from 'vue';
import App from './App.vue';

Vue.config.productionTip = false;

new Vue({
  el: '#chat-app',
  render: h => h(App)
});

require('svgxuse');
