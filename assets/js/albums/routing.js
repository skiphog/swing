import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Albums from './Albums';
import Edit from './Edit';
import Photo from './Photo';

export default new VueRouter({
  mode: 'hash',
  base: '/my_albums',
  scrollBehavior (to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    }

    if (to.name === 'photo') {
      return {x: 0, y: 350};
    }

    return {x: 0, y: 350};
  },
  routes: [
    {path: '/', name: 'albums', component: Albums},
    {
      path: '/:id(\\d+)',
      name: 'show',
      component: Edit,
      props: true,
      children: [
        {
          path: ':photo_id(\\d+)',
          name: 'photo',
          component: Photo,
          props: true
        }
      ]
    }
  ]
});