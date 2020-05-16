import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home'
import SignUp from './views/SignUp'
import Login from './views/Login'
import Checkout from './views/Checkout'
import OrderHistory from './views/OrderHistory'
import Settings from './views/Settings'
import ChangePassword from './components/ChangePassword'
import NewPassword from './components/NewPassword'
import { store } from './store/store'

Vue.use(Router)

const router = new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      props: true,
      component: Home
    },
    {
      path: '/sign-up',
      name: 'SignUp',
      component: SignUp
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/settings',
      name: 'Settings',
      component: Settings,
      meta: {checkLoggedIn: true}
    },
    {
      path: '/checkout',
      name: 'Checkout',
      component: Checkout,
      meta: {checkLoggedIn: true}
    },
    {
      path: '/order-history',
      name: 'OrderHistory',
      props: true,
      component: OrderHistory,
      meta: {checkLoggedIn: true}
    },
    {
      path: '/change-password',
      name: 'ChangePassword',
      component: ChangePassword
    },
    {
      path: '/new-password',
      name: 'NewPassword',
      props: true,
      component: NewPassword
    },

  ]
})

router.beforeEach( (to, from, next) => {
  let loggedInUser = store.state.loggedIn

  console.log('logged in: '+loggedInUser)
  //pages that can have sensitive user info are guarded - user must be logged in
  if (to.matched.some(page => page.meta.checkLoggedIn) && !loggedInUser) {
    next({name: 'Home'})
  }
  else{
    next()
  }

})

export default router
