<template>
  <div>
    <nav class="flex text-white items-center justify-between p-8 mb-5 bg-indigo-900">
        <router-link :to="{name: 'Home', params:{customer: loggedInEmail}}">
          <div class="flex items-center">
            <img class="storeLogo" src='../assets/finalLogo.png' alt='Video and More'>
          </div>
        </router-link>
        <ul class="flex mr-5">
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
        </ul>
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