import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import store from './store'
import VueSweetalert2 from 'vue-sweetalert2'

import 'font-awesome/css/font-awesome.min.css'
import 'sweetalert2/dist/sweetalert2.min.css';
import CKEditor from '@ckeditor/ckeditor5-vue';

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.bundle.js'
import '@/assets/style.scss'

require('@/store/subscriber')

axios.defaults.baseURL = 'http://127.0.0.1:8000/api'
axios.defaults.withCredentials = true

store.dispatch('auth/me', localStorage.getItem('token')).then(() => {
  const app = createApp(App)

  app.use(router)
  app.use(store)
  app.use(CKEditor)
  app.use(VueSweetalert2)
  app.mount('#app')
})