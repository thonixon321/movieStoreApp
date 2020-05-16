<template>
  <div>
    <div class="flex justify-center w-full">
      <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-2">
          <div class="flex justify-center mb-6">
            <p class="font-bold">New Password</p>
          </div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Password
          </label>
          <input v-model="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="Password">
        </div>
        <div class='relative mb-3 h-6'>
          <p v-if="feedback" class="absolute top-0 left-0 text-red-600">{{ feedback }}</p>
        </div>
        <div class="flex justify-center mb-5">
          <button  @click="updatePassword" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
            Confirm
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { axiosHandler } from '../mixins/axiosHandler'

export default {
  name: 'NewPassword',

  props: {
    email: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      password: '',
      feedback: null
    }
  },


  mixins: [axiosHandler],


  methods: {
    updatePassword() {
      var settingsObj,
          payloadObj;

      this.feedback = null

      if (this.password !== '') {
        settingsObj = {
          url: 'http://localhost:8080/rest_movieApp/api/customer/update.php',
          method: 'POST',
          callBack: this.updatePasswordResponse
        }

        payloadObj = {
          email: this.email,
          password: this.password
        }

        this.sendAxios(payloadObj, settingsObj)
      }

    },


    updatePasswordResponse(res) {
      if (res.data.message === 'customer updated') {
        this.$router.push({name:'Login'})
      }
      else{
        this.feedback = 'please enter a valid password'
      }
    }
  }
}
</script>

<style>

</style>