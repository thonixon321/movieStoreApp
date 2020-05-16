<template>
  <div>
    <div class="flex justify-center w-full">
      <form class=" flex justify-center form bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">

          <Loader v-if="showLoader" />

        <div v-if="!showLoader" class="mb-2">
          <div class="flex justify-center mb-6">
            <p class="font-bold">Reset Password</p>
          </div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Email
          </label>
          <input v-model="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4" id="email" type="text" placeholder="Email">
        </div>
        <div class='relative mb-3 h-6'>
          <p v-if="feedback" class="absolute top-0 left-0 text-red-600">{{ feedback }}</p>
        </div>
        <div v-if="!showLoader" class="flex justify-center mb-5">
          <button  @click="sendResetEmail" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
            Send Reset Link
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { axiosHandler } from '../mixins/axiosHandler'
import Loader from './Loader'

export default {
  name: 'ChangePassword',

  data() {
    return {
      email: '',
      feedback: null,
      showLoader: false
    }
  },


  mixins: [axiosHandler],


  methods: {
    sendResetEmail() {
      var settingsObj,
          payloadObj;

      this.feedback = null

      if (this.email !== '') {
        settingsObj = {
          url: 'http://localhost:8080/rest_movieApp/api/customer/reset-password.php',
          method: 'POST',
          callBack: this.sendResetEmailResponse
        }

        payloadObj = {
          email: this.email
        }

        this.sendAxios(payloadObj, settingsObj)
        this.showLoader = true
      }
      else {
        this.feedback = 'please enter email address'
      }

    },


    sendResetEmailResponse(res) {
      console.log(res.data);
      this.showLoader = false
      if (res.data.message !== 'did not email user') {
        this.$router.push({name: 'NewPassword', params: {email: this.email}})
      }
      else{
        this.feedback = 'please enter a valid email'
      }
    }
  },


  components: {
    Loader
  }
}
</script>

<style scoped>
  form.form {
    width: 25%;
    height: 15.6em;
    flex-direction: column;
    align-items: center;
  }
</style>