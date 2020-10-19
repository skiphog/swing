import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Threads from './threads/Threads';
import Albums from './albums/Albums';
import Diaries from './diaries/Diaries';

export default new VueRouter({
  mode: 'hash',
  base: '/my_news',
  linkActiveClass: 'nav-active',
  routes: [
    {path: '/', name: 'threads', component: Threads},
    {path: '/albums', name: 'albums', component: Albums},
    {path: '/diaries', name: 'diaries', component: Diaries}
  ]
});