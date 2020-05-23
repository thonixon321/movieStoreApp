<template>
  <div class="flex flex-col">
    <h1 class="text-5xl mb-4 font-bold">Order History: </h1>
    <div class="mx-12 bg-white ml-4 mb-4 rounded p-8 h-auto shadow-lg" v-for="(order, index) in history" :key="index">
      <div v-for="(title, t) in order.orderItems" :key="t">
        <h2 class="text-3xl" v-if="t == 0"><span class="font-bold"> Date:</span> {{ title.order_date }}</h2>
        <h2 class="text-3xl" v-if="t == 0"><span class="font-bold">Total:</span> ${{ title.total_price }}</h2>
        <h2 class="text-3xl font-bold" v-if="t == 0">Items:</h2>
      </div>
      <div class="flex flex-wrap">
        <div v-for="(item, i) in order.orderItems" :key="i">
          <Rating @closeModal="item.rating = false" :movie_id="item.movie_id" :title="item.name" v-if="item.rating" />
          <ul>
            <li class="border-2 ml-4 mb-4 flex items-center rounded">
              <div class="flex flex-col items-center justify-center">
                <p class="font-bold">{{ item.type }}</p>
                <img class="p-2 h-40 w-32" :src='item.image' :alt='item.name'>
              </div>
              <div class="flex items-center justify-center">
                <div class='flex flex-col mt-5'>
                  <p class="text-2xl w-64 ml-10 font-bold">
                    {{ item.name }}
                  </p>
                  <a v-if="checkRating(userIdsWhoRatedProduct(item.movie_id))" @click="item.rating = true" href='#' class="ml-10 underline">Rate movie</a>
                  <p v-else class="ml-10">Thanks for rating!</p>
                </div>
                <p class="mr-4">Price: ${{ item.price }}</p>
                <p class="mr-4">Quantity: {{ item.quantity }}</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Rating from '../components/modals/Rating';
import { mapState, mapGetters } from 'vuex'

export default {
  name: 'OrderHistory',

  props: {
    history: Array
  },

  data() {
    return {
      userIds: []
    }
  },


  computed: {
    ...mapState({
      storedCustomer: state => state.customer.loggedInCustomer,
      allProducts: state => state.product.products,
      allHistory: state => state.order.ordersHistory
    }),

    ...mapGetters({
      userIdsWhoRatedProduct: 'product/getUserIdsThatRatedProduct'
    }),

  },


  methods: {
    checkRating(ids) {
      var self = this
      //return false if user
      if (ids.length) {

        ids.forEach(function(el) {
          if (el.customer_id == self.storedCustomer.customer_id) {
            return false
          }
        })

      }
      else{
        return true
      }
    }
  },


  components: {
    Rating
  },


  mounted() {

  }

}
</script>

<style scoped>

</style>