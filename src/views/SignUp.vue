<template>
  <div>
    <div class="flex justify-center w-full">
      <form class="bg-white shadow-md rounded px-10 pt-6 pb-8 mb-4">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">
            First Name
          </label>
          <input v-model="fName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="first_name" type="text" placeholder="First Name">
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">
            Last Name
          </label>
          <input v-model="lName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="last_name" type="text" placeholder="Last Name">
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Email
          </label>
          <input v-model="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Email">
        </div>
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Password
          </label>
          <input v-model="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************">
        </div>
        <div class="flex items-center justify-center">
          <button @click="submitCustomer" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
            Sign Up!
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { axiosHandler } from '../mixins/axiosHandler'

export default {
  name: 'sign-up',

  mixins: [axiosHandler],

  computed: {

  },

  data() {
    return {
      fName: '',
      lName: '',
      password: '',
      email: '',
      feedback: null
    }
  },

  methods: {
    //user can sign up
    submitCustomer() {
      var settingsObj,
          payloadObj;

      if (this.fName !== '' && this.lName !== '' && this.password !== '' && this.email !== '') {
        this.feedback = null;

        settingsObj = {
          url: 'http://localhost:8080/rest_movieApp/api/customer/create.php',
          method: 'POST',
          callBack: this.submitCustomerResponse
        };

        payloadObj = {
          first_name: this.fName,
          last_name: this.lName,
          email: this.email,
          password: this.password
        };

        this.sendAxios(payloadObj, settingsObj);

      }
      else {
        this.feedback = "Please fill out all fields";
      }
    },

    //direct user to store once signed up
    submitCustomerResponse(res) {
      console.log(res)
      this.$router.push({name: "Home", params:{customer: this.email}})
      this.$store.dispatch('changeLogInStatus', true)
      this.$store.dispatch('changeLogInEmail', this.email)
    }
  }

}
</script>

<style scoped>

  form {
    width: 40%;
  }

</style>