import ExampleComponent from './components/ExampleComponent.vue'
// import LoginView from './views/LoginView.vue'
// import RegisterView from './views/RegisterView.vue'
// import DashboardView from './views/DashboardView.vue'

export default {
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: ExampleComponent
    },
    // {
    //   path: '/login',
    //   name: 'Login',
    //   component: LoginView,
    //   meta: {
    //     guestOnly: true
    //   }
    // },
    // {
    //   path: '/register',
    //   name: 'Register',
    //   component: RegisterView,
    //   meta: {
    //     guestOnly: true
    //   }
    // },
    // {
    //   path: '/dashboard',
    //   name: 'Dashboard',
    //   component: DashboardView,
    //   meta: {
    //     authOnly: true
    //   }
    // }
  ]
}