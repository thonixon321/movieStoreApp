<template>
  <div class="modalBG">
    <div v-for="(type, index) in movie" :key="index">
      <div v-if="index == 0">
        <div v-if="addedToCart == false" class='modalCard'>
          <div class='modalContent'>
            <div class='modalImageDisplay'>
              <img :src="type.image" alt='type'>
            </div>
              <div class='infoSection'>
                <div class='modalCardHeader'>
                  <h2 class="font-bold">{{ type.name }}</h2>
                  <a @click="$emit('closeModal')" href='#' class="closeModal">
                    <i class='material-icons'>close</i>
                  </a>
                </div>
                <div class='modalCardBody'>
                  {{ type.description }}
                </div>
              </div>
          </div>
          <div class='modalCardFooter text-white bg-indigo-900'>
            <div v-for="(movieType, iterate) in movie" :key="iterate">
              <a v-if="checkAvailability(movieType) === 'Rental'"
              @click="addToCart(movieType.type, movieType)" class="buyLink">
                <i class='material-icons'>mail</i>
                <p class='font-bold'>Rent</p>
                <p class='price'>$ {{ movieType.price }}</p>
              </a>
              <a v-else-if="checkAvailability(movieType) === 'Buy'"
              @click="purchaseType = 'buy', addedToCart = true, typeSelected = movieType.type"
              class="buyLink">
                <i class='material-icons'>local_atm</i>
                <p class='font-bold'>Buy {{ movieType.type }}</p>
                <p class='price'>$ {{ movieType.price }}</p>
              </a>
            </div>
            <div v-if="!available">Not available</div>
          </div>
        </div>
        <div v-else class='modalCard'>
          <div class='modalCardHeader confirmQuantity'>
            <h2></h2>
            <a @click="$emit('closeModal')" href='#' class="closeModal">
              <i class='material-icons'>close</i>
            </a>
          </div>
          <div class='modalContent'>
            <div v-if="purchaseType !== 'buy'" class='rentalSection'>
              <h2><span class="font-bold">{{ type.name }}</span> has been added to your cart!</h2>
              <div class='modalImage'>
                <img :src="type.image" alt='type'>
              </div>
              <router-link class="proceed" :to="{name: 'Checkout'}">Proceed to Checkout</router-link>
              <a
              class="continueShopping"
              @click="$emit('closeModal')">
                Continue Shopping
              </a>
            </div>
            <div v-else class='buySection'>
              <transition name="slide-fade">
                <h2>How many of <span class="font-bold"> {{ type.name }} </span> would you like to buy?</h2>
                <div v-for="(movieInnerType, iterateIn) in movie" :key="iterateIn">
                  <div class="quantityContainer" v-if="movieInnerType.type === typeSelected">
                    <select v-model="quantity" name='typeQuantity' id='typeQuantity'>
                      <option v-for="(type, i) in parseInt(movieInnerType.quantity_in_stock)" :key="i" :value='i+1'>
                        {{ i+1 }}
                      </option>
                    </select>
                    <button @click="addToCart('buy', movieInnerType)">Confirm</button>
                  </div>
                </div>
              </transition>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  name: 'AddToCartModal',

  data() {
    return {
      quantity: 1,
      addedToCart: false,
      purchaseType: '',
      typeSelected: '',
      modalHidden: true,
      available: false
    }
  },

  props: {
    movie: Array,
    customer: String
  },


  computed: {
    ...mapState({
      loggedIn: state => state.loggedIn,
      cart: state => state.order.orders,
      customerLoggedIn: state => state.customer.loggedInCustomer
    }),

    ...mapGetters({
      productInCart: 'order/getOrderByProductId'
    }),

  },


  methods: {
    addToCart(type, movie) {
      console.log(type)
      if (this.loggedIn) {
        let customerId = this.customerLoggedIn.customer_id

        movie.customer_id = customerId
        this.purchaseType = type
        this.addedToCart = true

        if (type == 'Rental') {
          movie.totalPrice = movie.price
          movie.quantityOrdered = 1
          movie.quantityRemaining = movie.quantity_in_stock - 1
          console.log(movie)
          this.$store.dispatch('order/addOrder', movie)
        }
        else{
          movie.totalPrice = this.quantity * parseFloat(movie.price)
          movie.quantityOrdered = this.quantity
          //this will be the quantityRemaining in order create model
          movie.quantityRemaining = parseInt(movie.quantity_in_stock) - this.quantity
          console.log(movie)
          this.$store.dispatch('order/addOrder', movie)
          this.purchaseType = ''
        }
      }
      else{
        this.$router.push({name: "Login"})
      }

    },


    checkAvailability(movie) {
      let alreadyInCart = this.cart.find(el => el.product_id === movie.product_id)

      if (movie.type === 'Rental') {
        console.log(movie)
        if (alreadyInCart || movie.quantity_in_stock == 0) {
          this.available = false
          return false
        }
        else {
          this.available = true
          return 'Rental'
        }
      }
      else{
        if (alreadyInCart || movie.quantity_in_stock == 0) {
          this.available = false
          return false
        }
        else {
          this.available = true
          return 'Buy'
        }
      }
    }
  }

}
</script>

<style scoped>

    .modalCard {
      max-width: 35em;
      height: auto;
      padding: 1em 0 0 0;
      border-radius: 5px;
      background: white;
      transition: 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .modalContent {
      display: flex;
    }

    .modalCardHeader {
      width: 90%;
      display: flex;
      justify-content: space-between;
      border-bottom: 1px black solid;
    }

    .modalCardHeader.confirmQuantity {
      border-bottom: none;
    }

    .modalCardBody {
      display: flex;
      padding: .5em;
      height: 17.55em;
      overflow-y: auto;
      margin-bottom: 1em;
    }

      .modalItem {
        border: 1px solid black;
        border-radius: 5px;
        padding: .95em;
        margin: .53em;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
      }

      .modalItem.selected {
        background: rgb(94, 187, 1);
        color: white;
      }

    h2 {
      margin-left: 2em;
      margin-bottom: .5em;
      width: 14em;
    }

    .closeModal {
      position: relative;
      top: -.82em;
      right: -1.57em;
      cursor: pointer;
    }

    .continueShopping {
      cursor: pointer;
      margin-bottom: 1em;
    }

    .proceed {
      margin-bottom: .5em;
    }

    .continueShopping:hover,
    .proceed:hover {
      text-decoration: underline;
    }

    .modalImageDisplay {
      height: inherit;
      margin-right: .51em;
      padding: 0.5em;
    }

    img {
      width: 13em;
      max-width: 13em;
      height: 19.5em;
      margin-bottom: .51em;
    }


    .shoppingCartIcon {
      position: relative;
      top: .23em;
      right: .32em;
    }

    .modalCardFooter {
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 3em;
      border-bottom-left-radius: 5px;
      border-bottom-right-radius: 5px;
    }

    .buyLink {
      display: flex;
      padding: .2em .4em;
      margin: .62em;
    }

    .buyLink:hover {
      text-decoration: underline;
      cursor: pointer;
    }

    .buyLink i,
    .buyLink p {
      margin-right: .2em;
    }

    .buySection,
    .rentalSection {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .quantityContainer {
      display: flex;
      flex-direction: column;
    }

    select {
      width: 4em;
      border: 1px solid black;
      margin-bottom: 1em;
    }

    .quantityContainer button {
      margin-bottom: .5em;
    }
</style>