<template>
  <div>
    <a class='delete' @click="deleteAccount" href='#'>Delete Account</a>
  </div>
</template>

<script>
import { axiosHandler } from '../mixins/axiosHandler'

export default {
  name: 'Settings',

  mixins: [axiosHandler],

  data() {
    return {
      customer_id: this.$store.state.customer.loggedInCustomer.customer_id
    }
  },

  methods: {
    deleteAccount() {
      var settingsObj,
          payloadObj;

      settingsObj = {
        url: 'http://localhost:8080/rest_movieApp/api/customer/delete.php',
        method: 'POST',
        callBack: this.deleteAccountResponse
      }

      payloadObj = {
        customer_id: this.customer_id
      }

      this.sendAxios(payloadObj, settingsObj)
    },


    deleteAccountResponse(res) {
      console.log(res)
      //if successful response, change log in status, clear out customer from store, and redirect to home
      if (res.data.message === 'customer deleted') {
        this.$store.dispatch('changeLogInStatus', false)
        this.$store.dispatch('customer/deleteCustomer')
        this.$router.push({name: 'Home'})
      }
    }
  }

}
</script>

<style scoped>
  a.delete {
    text-decoration: underline;
  }
</style>