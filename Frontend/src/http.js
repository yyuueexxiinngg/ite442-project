import axios from 'axios'
import store from '../store/store'
import router from './router'

// axios 配置
axios.defaults.timeout = 5000
axios.defaults.baseURL = process.env.API_HOST

// http request 拦截器
axios.interceptors.request.use(
  config => {
    store.state.isLoading = true
    if (store.state.token) {
      config.headers.Authorization = store.state.token
    }
    return config
  },
  err => {
    return Promise.reject(err)
  },
)

// http response 拦截器
axios.interceptors.response.use(
  response => {
    store.state.isLoading = false
    return response
  },
  error => {
    store.state.isLoading = false
    if (error.response) {
      switch (error.response.status) {
        case 401:
          // 401 清除token信息并跳转到登录页面
          store.commit('REMOVE_COUNT', store.state.token)

          // 只有在当前路由不是登录页面才跳转
          router.currentRoute.path !== 'login' &&
          router.replace({
            path: 'login',
            query: { redirect: router.currentRoute.path }
          })
      }
    }
    // console.log(JSON.stringify(error));//console : Error: Request failed with status code 402
    return Promise.reject(error)
  }
)

export default axios
