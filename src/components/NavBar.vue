<template>
  <div>
    <nav class="flex text-white items-center justify-between p-8 mb-5 bg-blue-600">
        <router-link :to="{name: 'Home', params:{customer: loggedInEmail}}">Video and More</router-link>
        <ul class="flex mr-5">
          <li v-if="!loggedIn" class="mr-5">
            <router-link :to="{name: 'SignUp'}">Sign up</router-link>
          </li>
          <li @click="logOut" v-if="loggedIn" class="mr-5">
            <router-link :to="{name: 'Home'}">Log out</router-link>
          </li>
          <li v-if="!loggedIn">
            <router-link :to="{name: 'Login'}">Log in</router-link>
          </li>
          <li v-if="cart.length">
            <router-link :to="{name: 'Checkout'}">
              Proceed to Checkout ({{cart.length}})
            </router-link>
          </li>
          <li v-if="loggedIn && customerHistory(loggedInEmail)">
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

export default {
  name: 'NavBar',

  data() {
    return {

    }
  },


  computed: {
    ...mapState({
      loggedIn: state => state.loggedIn,
      loggedInEmail: state =>state.loggedInEmail,
      cart: state => state.order.orders,
      orderHistory: state => state.order.ordersHistory
    }),


    ...mapGetters({
      customerHistory: 'order/getOrderHistoryByCustomerEmail'
    })
  },


  methods: {
    logOut() {
      this.$store.dispatch('changeLogInStatus', false)
      this.$store.dispatch('order/clearOrder')
    }
  }

}
</script>

<style scoped>
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