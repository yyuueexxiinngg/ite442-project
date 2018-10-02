import axios from 'axios'
import store from '../store/store'
import router from './router'

// axios config
axios.defaults.timeout = 5000
axios.defaults.baseURL = process.env.API_HOST

// http request interceptor
axios.interceptors.request.use(
  config => {
    store.state.generalAlert = ''
    if (!config.disableLoading === true) {
      store.state.isLoading = true
    }
    if (store.state.token) {
      config.headers.Authorization = store.state.token
    }
    return config
  },
  err => {
    store.state.generalAlert = err.toString()
    return Promise.reject(err)
  },
)

// axios response interceptor
axios.interceptors.response.use(
  response => {
    store.state.isLoading = false
    return response
  },
  error => {
    store.state.generalAlert = error.toString()
    store.state.isLoading = false
    if (error.response) {
      switch (error.response.status) {
        case 401:
          // 401 Unauthorized header, remove the saved token and redirect to login page
          store.commit('REMOVE_COUNT', store.state.token)

          // Redirect if current page is not login page
          router.currentRoute.path !== 'login' &&
          router.replace({
            path: 'login',
            query: {redirect: router.currentRoute.path}
          })
      }
    }
    // console.log(JSON.stringify(error));//console : Error: Request failed with status code 402
    return Promise.reject(error)
  }
)

export default axios
