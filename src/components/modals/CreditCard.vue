<template>

  <div class="modalBG">

    <!--Modal-->
    <div class="modal fixed w-full h-full top-0 left-0 flex items-center justify-center">
      <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

      <div class="modal-container bg-white w-4/12 rounded shadow-lg z-50 overflow-y-auto">

        <!-- Add margin if you want to see some of the overlay behind the modal-->
        <div class="modal-content py-4 text-left px-6">
          <!--Title-->
          <div v-if="!showLoader" class="flex justify-between items-center pb-3">
            <p class="text-2xl font-bold">Your Payment Information</p>
            <div @click="$emit('closeInfo')" class="modal-close cursor-pointer z-50">
              <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
              </svg>
            </div>
          </div>


          <!--Body-->
          <div class="bg-white my-4 p-8 rounded-lg">
            <div class="loaderCenter">
              <Loader v-if="showLoader" />
            </div>
            <div v-if="!showLoader" class="w-full">
              <label for="payment" class="block text-sm mb-2">Credit Card</label>
              <div class="flex">
                <input type="text" id="payment" class="placeholder-white w-5/6 flex-1 text-sm bg-grey-light text-grey-darkest rounded-l p-3 focus:outline-none" placeholder="Card Number">
                <input type="text" id="payment" class="placeholder-white w-1/6 inline-block text-sm bg-grey-light text-grey-darkest p-3 focus:outline-none" placeholder="MM / YY">
                <input type="text" id="payment" class="placeholder-white w-1/6 inline-block text-sm bg-grey-light text-grey-darkest rounded-r p-3 focus:outline-none" placeholder="CVC">
              </div>
            </div>
          </div>

          <!--Footer-->
          <div v-if="!showLoader" class="flex justify-end pt-2">
            <button @click="$emit('placeOrder')" class="px-4 bg-transparent p-3 rounded-lg text-indigo-500 hover:bg-gray-100 hover:text-indigo-400 mr-2">Place Order</button>
            <button @click="$emit('closeInfo')" class="modal-close px-4 bg-indigo-500 p-3 rounded-lg text-white hover:bg-indigo-400">Close</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loader from '../Loader'

export default {
  name: 'CreditCardInfo',

  props: {
    showLoader: Boolean
  },

  components: {
    Loader
  }
}
</script>

<style scoped>
   .modalBG {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
    z-index: 3;
    background: rgba(70, 69, 69, 0.6);
   }
  .modal {
    transition: opacity 0.25s ease;
  }

  div.loaderCenter {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  input#payment {
    background: rgb(196, 196, 196);
    color: white;
  }
</style>