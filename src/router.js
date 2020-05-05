import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home'
import SignUp from './views/SignUp'
import Login from './views/Login'
import Checkout from './views/Checkout'
import OrderHistory from './views/OrderHistory'
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
    }

  ]
})

router.beforeEach( (to, from, next) => {
  let loggedInUser = store.state.loggedIn
  let customers = store.state.customer.customers

  console.log(loggedInUser)
  if (to.matched.some(page => page.meta.checkLoggedIn) && !loggedInUser) {
    next({name: 'Login'})
  }
  else{
    next()
  }

  if (to.name === 'Login' && customers.length === 0) {
    next({name: 'SignUp'})
  }
  else {
    next()
  }

})

export default router
