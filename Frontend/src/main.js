// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Vuetify from 'vuetify'
import App from './App'
import router from './router'
import store from '../store/store'
import axios from './http'

Vue.use(Vuetify)
Vue.prototype.axios = axios
Vue.config.productionTip = false

router.beforeEach((to, from, next) => {
  store.state.token = sessionStorage.getItem('token')
  store.state.userName = sessionStorage.getItem('userName')
  store.state.position = sessionStorage.getItem('position')
  store.state.email = sessionStorage.getItem('email')
  if (to.meta.requireAuth) {  // 判断该路由是否需要登录权限
    if (store.state.token !== null && store.state.token !== '') {  // 通过vuex state获取当前的token是否存
      next()
    } else {
      next({
        path: '/login',
        query: {redirect: to.fullPath}  // 将跳转的路由path作为参数，登录成功后跳转到该路由
      })
    }
  } else {
    next()
  }
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})
