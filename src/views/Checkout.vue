<template>
  <div>
    <Success v-if="!modalHidden"/>
    <h2 class="text-3xl mb-4">Cart Items:</h2>
    <ul>
      <li class="bg-white ml-4 mb-4 flex items-center rounded h-48 shadow-lg" v-for="(item, i) in cart" :key="i">
        <div class="flex flex-col items-center justify-center">
          <div class="font-bold">{{ item.type }}</div>
          <img class="p-4 h-40 w-32" :src="item.image" alt='photo of order item'>
        </div>
        <div class="text-2xl w-64 ml-10 font-bold">
          {{ item.name }}
        </div>
        <div class="ml-16 w-32">
          Quantity:
          <select name='itemQuantity' @change="getTotalPrice" v-model="item.quantityOrdered">
            <option v-for="(type, i) in parseInt(item.quantity_in_stock)" :key="i" :value='i+1'>
              {{ i+1 }}
            </option>
          </select>
        </div>
        <div class="ml-16 w-32">
           Price - ${{ parseFloat(item.price) * item.quantityOrdered }}
        </div>
        <i @click="removeItem(item)" class='material-icons text-red-400 ml-6 cursor-pointer' title="remove item">remove_circle</i>
      </li>
    </ul>
    <div class='ml-4 mt-6 font-bold text-xl'>
     Total: ${{ totalPrice }}
    </div>
    <button class="ml-4 mt-6 mb-10 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="checkout">Checkout</button>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { axiosHandler } from '../mixins/axiosHandler'
import Success from '../components/modals/Success'

export default {
  name: 'Checkout',

  mixins: [axiosHandler],


  data() {
    return {
      modalHidden: true,
      totalPrice: 0
    }
  },


  computed: {
    ...mapState({
      cart: state => state.order.orders,
      loggedInEmail: state => state.loggedInEmail
    }),


  },


  methods: {
    getTotalPrice() {
      let totalPrice = 0

      this.cart.forEach((el) => {
        totalPrice += parseFloat(el.price) * el.quantityOrdered
        el.quantityRemaining = parseFloat(el.quantity_in_stock) - el.quantityOrdered
      })

      this.totalPrice = totalPrice
    },


    removeItem(item) {
      this.$store.dispatch('order/removeOrderItem', item)
      this.getTotalPrice()
    },


    checkout() {
      let settingsObj = {
            url: 'http://localhost:8080/rest_movieApp/api/order/create.php',
            method: 'POST',
            callBack: this.checkoutResponse
          },
          payloadObj = {
            customer_id: this.cart[0].customer_id,
            total_price: this.totalPrice,
            order_items: []
          };

      this.cart.forEach((el) => {
        payloadObj.order_items.push(el)
      })

      console.log(payloadObj)

      this.sendAxios(payloadObj, settingsObj);
    },


    checkoutResponse(res) {
      let self = this,
          settingsObj = {
            url: 'http://localhost:8080/rest_movieApp/api/order/read.php',
            method: 'GET',
            callBack: this.getOrderHistoryResponse
          },
          payloadObj = {}

      this.sendAxios(payloadObj, settingsObj);

      console.log(res)
      this.$store.dispatch('order/clearOrder')
      this.modalHidden = false
      setTimeout(() => {
        self.modalHidden = true
        self.$router.push({name: 'Home', params:{customer: self.loggedInEmail}})
      }, 2000)

    },


    getOrderHistoryResponse(res) {
      console.log(res)

      this.$store.dispatch('order/setOrderHistory', res.data.data)
    }
  },


  mounted() {
    this.getTotalPrice()
  },


  components: {
    Success
  }

}
</script>

<style scoped>
  select {
    border: 1px solid rgb(89, 89, 241);
  }
</style>