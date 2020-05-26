<template>
  <div>
    <nav class="flex text-white items-center justify-between p-8 mb-5 bg-indigo-900">
        <router-link :to="{name: 'Home', params:{customer: loggedInEmail}}">
          <div class="flex items-center">
            <img class="storeLogo" src='../assets/finalLogo.png' alt='Video and More'>
          </div>
        </router-link>
        <div @click="openDrawer = true" class='hamburgerNav cursor-pointer'>
          <div class="w-8 h-1 mb-2 bg-white"></div>
          <div class="w-8 h-1 mb-2 bg-white"></div>
        </div>
        <ul class="nav-full flex mr-5">
          <li v-if="!loggedIn" class="text-lg font-bold mr-5">
            <router-link :to="{name: 'SignUp'}">Sign up</router-link>
          </li>
          <li @click="logOut" v-if="loggedIn" class="text-lg font-bold mr-5">
            <router-link :to="{name: 'Home'}">Log out</router-link>
          </li>
          <li v-if="loggedIn" class="text-lg font-bold mr-5">
            <router-link :to="{name: 'Settings'}">Settings</router-link>
          </li>
          <li v-if="!loggedIn" class="text-lg font-bold mr-5">
            <router-link :to="{name: 'Login'}">Log in</router-link>
          </li>
          <li v-if="cart.length" class="text-lg font-bold mr-5">
            <router-link :to="{name: 'Checkout'}">
              Checkout ({{cart.length}})
            </router-link>
          </li>
          <li class="text-lg font-bold mr-5" v-if="loggedIn && customerHistory(loggedInEmail).length">
            <router-link :to="{name: 'OrderHistory', params:{history: customerHistory(loggedInEmail)}}">
              Order History
            </router-link>
          </li>
          <li class="text-lg font-bold mr-5">
            <router-link :to="{name: 'Gsap'}">
              GSAP
            </router-link>
          </li>
        </ul>
        <transition
         name="expand-show"
        >
          <ul v-if="openDrawer" class="drawer flex flex-col fixed top-0 right-0 w-64 h-full justify-around items-center bg-yellow-600 z-40">
            <a @click="openDrawer = false" href='#' class="closeDrawer absolute top-0 left-0">
              <i class='material-icons'>close</i>
            </a>
            <li v-if="!loggedIn" class="text-lg font-bold mr-5">
              <router-link :to="{name: 'SignUp'}">Sign up</router-link>
            </li>
            <li @click="logOut" v-if="loggedIn" class="text-lg font-bold mr-5">
              <router-link :to="{name: 'Home'}">Log out</router-link>
            </li>
            <li v-if="loggedIn" class="text-lg font-bold mr-5">
              <router-link :to="{name: 'Settings'}">Settings</router-link>
            </li>
            <li v-if="!loggedIn" class="text-lg font-bold mr-5">
              <router-link :to="{name: 'Login'}">Log in</router-link>
            </li>
            <li v-if="cart.length" class="text-lg font-bold mr-5">
              <router-link :to="{name: 'Checkout'}">
                Checkout ({{cart.length}})
              </router-link>
            </li>
            <li class="text-lg font-bold mr-5" v-if="loggedIn && customerHistory(loggedInEmail).length">
              <router-link :to="{name: 'OrderHistory', params:{history: customerHistory(loggedInEmail)}}">
                Order History
              </router-link>
            </li>
            <li class="text-lg font-bold mr-5">
              <router-link :to="{name: 'Gsap'}">
                GSAP
              </router-link>
            </li>
          </ul>
        </transition>
    </nav>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { axiosHandler } from '../mixins/axiosHandler'

export default {
  name: 'NavBar',

  data() {
    return {
      openDrawer: false
    }
  },


  mixins: [axiosHandler],


  computed: {
    ...mapState({
      loggedIn: state => state.loggedIn,
      loggedInEmail: state =>state.loggedInEmail,
      cart: state => state.order.orders,
      orderHistory: state => state.order.ordersHistory,
      customerLoggedIn: state => state.customer.loggedInCustomer
    }),


    ...mapGetters({
      customerHistory: 'order/getOrderHistoryByCustomerEmail'
    })
  },


  methods: {
    logOut() {
      var settingsObj,
          payloadObj,
          customer = this.customerLoggedIn;

      settingsObj = {
              url: 'http://localhost:8080/rest_movieApp/api/customer/logout.php',
              method: 'POST',
              callBack: this.logOutResponse
      }

      payloadObj = {
        customer_id: customer.customer_id
      }

      this.sendAxios(payloadObj, settingsObj);

    },


    logOutResponse(res) {
      console.log(res);
      this.$store.dispatch('changeLogInStatus', false)
      this.$store.dispatch('order/clearOrder')
    }
  }

}
</script>

<style scoped>
  nav {
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 5;
  }

  img.storeLogo {
    width: 15em;
    height: 8em;
  }

  .brand-logo {
    margin-left: 2em;
  }

  li {
    margin-right: 1em;
  }

  a:hover, li:hover{
    text-decoration: underline;
  }
</style>