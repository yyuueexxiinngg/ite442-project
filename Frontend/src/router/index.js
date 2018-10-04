import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import Login from '@/components/Login'
import Receipt from '@/components/Receipt'
import Repair_Status from '@/components/Repair_Status'
import Create_Repair_Form from '@/components/Create_Repair_Form'
import Update_Repair_Form from '@/components/Update_Repair_Form'
import In_Progerss from '@/components/In_Progress'
import store from '../../store/store'

Vue.use(Router)

let router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
      meta: {
        requireAuth: 'customer',  // Require login to view the page or not
        title: 'Home',
        icon: 'home'
      }
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: {
        title: 'Login',
        icon: 'dashboard',
        notInNav: true
      }
    },
    {
      path: '/receipt',
      name: 'receipt',
      component: Receipt,
      meta: {
        title: 'Receipt',
        icon: 'receipt'
      }
    },
    {
      path: '/repair_status',
      name: 'repair_status',
      component: Repair_Status,
      meta: {
        title: 'Repair Status',
        icon: 'timeline'
      }
    },
    {
      path: '/in_progress',
      name: 'in_progress',
      component: In_Progerss,
      meta: {
        title: 'In Progress',
        requireAuth: 'employee',
        icon: 'timeline'
      }
    },
    {
      path: '/create_repair_form',
      name: 'create_repair_form',
      component: Create_Repair_Form,
      meta: {
        title: 'Create Repair Form',
        requireAuth: 'employee',
        icon: 'description'
      }
    },
    {
      path: '/update_repair_form',
      name: 'update_repair_form',
      component: Update_Repair_Form,
      meta: {
        title: 'Update Repair Form',
        requireAuth: 'employee',
        icon: 'description'
      }
    }
  ]
})

// Before entering the router
router.beforeEach((to, from, next) => {
  // get token and position from session storge
  store.state.token = sessionStorage.getItem('token')
  store.state.userName = sessionStorage.getItem('userName')
  store.state.position = sessionStorage.getItem('position')
  store.state.email = sessionStorage.getItem('email')
  if (to.meta.requireAuth) {  // Check is the router needs permission
    if ((store.state.token !== null && store.state.token !== '') || to.meta.requireAuth === 'customer') {  // Check if the token exist
      let require = to.meta.requireAuth
      let loginPosition = store.state.position
      if (require === 'admin' && loginPosition === 'admin') {
        next()
      } else if (require === 'manager' && (loginPosition === 'manager' || loginPosition === 'admin')) {
        next()
      } else if (require === 'employee' && (loginPosition === 'manager' || loginPosition === 'employee' || loginPosition === 'admin')) {
        next()
      } else if (require === 'customer') {
        next()
      } else {
        store.state.generalAlert = 'No enough permission, ' + require + ' needed, but you currently login as ' + loginPosition
        next({
          path: '/login',
          query: {redirect: to.fullPath}  // Redirect to entry after login
        })
      }
    } else {
      store.state.generalAlert = 'Require ' + to.meta.requireAuth + ' permission to view the page, please login.'
      next({
        path: '/login',
        query: {redirect: to.fullPath}  // Redirect to entry after login
      })
    }
  } else {
    next()
  }
})

export default router
