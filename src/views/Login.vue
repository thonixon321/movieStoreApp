<template>
  <div>
    <div class="flex justify-center w-full">
      <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Email
          </label>
          <input v-model="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="text" placeholder="Email">
        </div>
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Password
          </label>
          <input v-model="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************">
        </div>
        <div class='relative mb-3 h-6'>
          <p v-if="feedback" class="absolute top-0 left-0 text-red-600">{{ feedback }}</p>
        </div>
        <div class="flex justify-center mb-5">
          <button  @click="logInCustomer" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
            Sign In
          </button>
        </div>
        <div class="flex justify-center">
          <router-link :to="{name: 'ChangePassword'}" class="inline-block align-baseline font-bold text-sm mx-6 text-blue-500 hover:text-blue-800" href="#">
            Forgot Password?
          </router-link>
          <router-link :to="{name: 'SignUp'}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
            New user? Sign up here!
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { axiosHandler } from '../mixins/axiosHandler'

export default {
  name: 'Login',

  data() {
    return {
      email: '',
      password: '',
      feedback: null
    }
  },

  mixins: [axiosHandler],

  computed: {

  },


  methods: {
    logInCustomer() {
      var settingsObj,
          payloadObj;

      settingsObj = {
              url: 'http://localhost:8080/rest_movieApp/api/customer/login.php',
              method: 'POST',
              callBack: this.logInResponse
      }

      payloadObj = {
        email: this.email,
        password: this.password
      }

      this.sendAxios(payloadObj, settingsObj);
    },

    logInResponse(res) {
      var customer;
      console.log(res.data)

      if (res.data.message === 'logged in successfully') {
        customer = {
          customer_id: res.data.customer_id,
          email: res.data.email
        }
        this.$store.dispatch('customer/setLoggedInCustomer', customer)
        this.$store.dispatch('changeLogInStatus', true)
        this.$store.dispatch('changeLogInEmail', this.email)
        this.$router.push({name: 'Home', params: {customer: this.email}})
      }
      else{
        this.feedback = 'email and/or password is incorrect'
      }

    }
  }

}
</script>

<style>

</style>