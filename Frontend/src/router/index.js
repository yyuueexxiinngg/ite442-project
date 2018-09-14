import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import Login from '@/components/Login'
Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
      meta: {
        requireAuth: true  // Require login to view the page or not
      }
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    }
  ]
})
