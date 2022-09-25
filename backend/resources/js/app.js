require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue-router'
import routes from './routes'

const router = Vue.createRouter({
  history: Vue.createWebHashHistory(),
  routes
})

const app = new Vue({
  el: '#app',
  router
});
