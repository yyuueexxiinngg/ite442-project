import Vuex from 'vuex'
import Vue from 'vue'
Vue.use(Vuex)

const ADD_COUNT = 'ADD_COUNT' // Use const for clear structure
const REMOVE_COUNT = 'REMOVE_COUNT'

export default new Vuex.Store({
  state: {
    token: '',
    userName: '',
    position: '',
    isLoading: false
  },
  mutations: {
    // Login
    [ADD_COUNT] (state, user) {
      sessionStorage.setItem('token', user.token)
      sessionStorage.setItem('userName', user.userName)
      sessionStorage.setItem('position', user.position)
      sessionStorage.setItem('email', user.email)
      state.token = user.token
      state.userName = user.userName
      state.position = user.position
      state.email = user.email
    },
    [REMOVE_COUNT] (state, token) {
      // Logout
      sessionStorage.removeItem('token', token)
      sessionStorage.removeItem('userName')
      sessionStorage.removeItem('position')
      sessionStorage.removeItem('email')
      state.token = null
      state.userName = null
      state.position = null
      state.email = null
    }
  }
})
