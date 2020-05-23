<template>
  <div>
    <AddToCart @closeModal="modalHidden = true" :customer="customer" :movie="movieSelected" v-if="!modalHidden" />
    <Reviews v-if="usersRatings.length" :ratings="usersRatings" />
    <form @submit.prevent="searchMovies" class="search pt-2 relative mx-auto text-gray-600">
      <input v-model="search" class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
        type="search" name="search" placeholder="Search">
      <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
        <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
          viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
          width="512px" height="512px">
          <path
            d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
        </svg>
      </button>
    </form>
    <h1 class="text-3xl font-bold mb-3">Available Now: </h1>
    <div class="flex flex-wrap">
      <div v-for="(movie, index) in allProducts" :key="index">
        <div v-for="(type, i) in movie[movieTitles[index]]" :key="i">
          <div v-if="i == 0" class="movieCard max-w-sm rounded overflow-hidden mx-4 mb-10 shadow-lg">
            <img class="w-full" :src="type.image" alt="movie image">
            <div class="relative px-6 py-4 movieDesc">
              <button @click="openModal(movie[movieTitles[index]])" class="addToCart" title="add to cart">
                <i class="material-icons">add_shopping_cart</i>
              </button>
              <div class="font-bold text-xl mb-2">{{ type.name }}</div>
              <p class="text-gray-700 text-base">
                {{ type.description }}
              </p>
            </div>
            <div class="px-6 flex justify-between mb-2">
              <div class="flex h-8">

                <div v-for="(genre, g) in splitGenres(type.genre)" :key="g" class="inline-block bg-indigo-900 rounded-full px-3 py-1 text-white text-sm font-semibold mr-2">
                  #{{ genre }}
                </div>
              </div>
              <div v-if="type.ratings.length" class="flex flex-col">
                <div class="flex flex-col items-center mb-4">
                  <div class="text-sm font-bold">
                    Customer Rating:
                  </div>
                  <div class="text-sm font-bold">
                    {{ type.ratings[0].rating_avg * 100 }}%
                  </div>
                  <a href='#' @click="goToReviews(type.ratings)" class="underline font-bold text-sm">See Reviews</a>
                </div>
              </div>
              <div class="text-sm font-bold" v-else>
                No Ratings
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { axiosHandler } from '../mixins/axiosHandler'
import AddToCart from '../components/modals/AddToCart'
import Reviews from '../components/modals/Reviews'

import { mapState, mapGetters } from 'vuex'

export default {
  name: 'Home',

  props: {
    customer: String
  },

  data() {
    return {
      modalHidden: true,
      movieSelected: {},
      moviesArr: [],
      moviesRatings: [],
      usersRatings: [],
      search: ''
    }
  },

  mixins: [axiosHandler],


  computed: {
    ...mapState({
      loggedIn: state => state.loggedIn,
      storedCustomer: state => state.customer.loggedInCustomer,
      allCustomers: state => state.customer.customers,
      allProducts: state => state.product.products,
      movieTitles: state => state.product.titles
    }),


    ...mapGetters({
      userIdsWhoRatedProduct: 'product/getUserIdsThatRatedProduct'
    }),

  },


  methods: {

    searchMovies() {
      var settingsObj,
          payloadObj;

      settingsObj = {
        url: 'http://localhost:8080/rest_movieApp/api/product/search.php?search='+this.search,
        method:'GET',
        callBack: this.getMoviesResponse
      }

      payloadObj = {}

      this.sendAxios(payloadObj, settingsObj)
    },


    splitGenres(string) {
      let split = string.split(',')

      if (split !== undefined) {
        return split
      }
      else {
        return [string]
      }
    },


    getMoviesResponse(res){
      let organizedMoviesArr = []

      if (res.data.data !== undefined && res.data.data.length) {
        console.log(res.data.data)
        res.data.data.forEach((el) => {
          if (this.moviesArr.indexOf(el.movie_id) < 0) {
            this.moviesArr.push(el.movie_id)
          }
        })

        for (var i = 0; i < this.moviesArr.length; i++) {
          var tempObj = {}
          tempObj[this.moviesArr[i]] = []

          organizedMoviesArr.push(tempObj)
        }

        res.data.data.forEach((el) => {

          organizedMoviesArr.forEach((innerEl) => {
            let newObj = {}
            let newArr = []
            let keyName = ''

            for (var key in innerEl) {
              keyName = key
            }
            //match any movie ratings associated with the movie_id
            this.moviesRatings.forEach((ratingEl) => {
              if (ratingEl.movie_id == keyName) {
                newArr.push(ratingEl)
              }
            })
            //match the key name (innerEl) to the movie_id
            if (keyName === el.movie_id) {
              newObj.product_id = el.product_id
              newObj.name = el.name
              newObj.price = el.price
              newObj.type = el.type
              newObj.genre = el.genre
              newObj.description = el.description
              newObj.quantity_in_stock = el.quantity_in_stock
              newObj.image = el.image
              newObj.ratings = newArr
              //need an array of ratings (objects with info about customer who rated, rating, rating message)
              innerEl[keyName].push(newObj)
            }
          })

        })
        //separated data by movie (each movie can have several product_ids and types, e.g. DVD, rental, VHS)
        this.$store.dispatch('product/setProducts', organizedMoviesArr)
        this.$store.dispatch('product/setMovieTitles', this.moviesArr)
      }


    },


    getMoviesRatingsResponse(res) {
      var settingsObj,
          payloadObj;
      console.log(res.data)
      this.moviesRatings = res.data

      settingsObj = {
        url: 'http://localhost:8080/rest_movieApp/api/product/read.php',
        method: 'GET',
        callBack: this.getMoviesResponse
      }

      payloadObj = {}

      this.sendAxios(payloadObj, settingsObj)

    },


    getCustomersResponse(res) {
      console.log(res.data.data)

      if (res.data.data.length) {
        this.$store.dispatch('customer/fetchedCustomers', res.data.data)
      }
    },


    getSessLoginResponse(res) {
      var customer,
          settingsObj,
          payloadObj;

      console.log(res)
      console.log(this.loggedIn)

      if (res.data.message === 'auth success') {
        this.email = res.data.email
        customer = {
          customer_id: res.data.customer_id,
          email: res.data.email,
          name: res.data.name
        }
        this.$store.dispatch('customer/setLoggedInCustomer', customer)
        this.$store.dispatch('changeLogInStatus', true)
        this.$store.dispatch('changeLogInEmail', this.email)
        //get any order history for the logged in user
        settingsObj = {
          url: 'http://localhost:8080/rest_movieApp/api/order/read.php',
          method: 'GET',
          callBack: this.getOrderHistoryResponse
        }
        payloadObj = {}

        this.sendAxios(payloadObj, settingsObj);
      }

    },


    openModal(movie) {
      this.modalHidden = false;

      this.movieSelected = movie;
    },


    getOrderHistoryResponse(res) {
      console.log(res.data)
      if (res.data.message !== "No orders Found") {
        this.$store.dispatch('order/setOrderHistory', res.data.data)
      }
    },


    goToReviews(ratings) {
      console.log(ratings)
      let titlesArr = []

      ratings.forEach((el) => {
        let foundCustomer = this.allCustomers.find(customer => customer.customer_id === el.customer_id)
        if (titlesArr.indexOf(el.customer_id) === -1) {
          if (foundCustomer) {
            console.log(foundCustomer)
            el.name = foundCustomer.first_name + " " + foundCustomer.last_name
          }
          this.usersRatings.push(el)
          titlesArr.push(el.customer_id)
        }
      })

    }
  },


  mounted() {
    var settingsObj,
        payloadObj;

    if (this.loggedIn === false) {

      //check if user has already been authenticated and session has not expired
      settingsObj = {
          url: 'http://localhost:8080/rest_movieApp/api/customer/sess-login.php',
          method: 'GET',
          callBack: this.getSessLoginResponse
        }

      payloadObj = {}

      this.sendAxios(payloadObj, settingsObj)
      //fetch all of our products to display
    }
    //need to fetch order history if user is logged in
    else {
        settingsObj = {
          url: 'http://localhost:8080/rest_movieApp/api/order/read.php',
          method: 'GET',
          callBack: this.getOrderHistoryResponse
        }

        payloadObj = {}

        this.sendAxios(payloadObj, settingsObj)
    }
    //get products if they aren't already in vuex store
    if (this.allProducts.length === 0) {
      settingsObj = {
        url: 'http://localhost:8080/rest_movieApp/api/product/read-ratings.php',
        method: 'GET',
        callBack: this.getMoviesRatingsResponse
      }

      payloadObj = {}

      this.sendAxios(payloadObj, settingsObj)

    }

    settingsObj = {
        url: 'http://localhost:8080/rest_movieApp/api/customer/read.php',
        method: 'GET',
        callBack: this.getCustomersResponse
      }

    payloadObj = {}

    this.sendAxios(payloadObj, settingsObj)


  },


  components: {
    AddToCart,
    Reviews
  }

}
</script>

<style scoped>
  form.search {
    width: 15em
  }

  div.movieCard {
    height: 43em;
    background: white;
  }

  div.movieDesc {
    background: white;
    height: 12em;
  }

  img {
    height: 26rem;
  }

  button.addToCart {
    position: absolute;
    top: -1.2em;
    right: .42em;
    width: 3.15em;
    height: 3.15em;
    border-radius: 50%;
    color: white;
    background: crimson;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  button.addToCart:hover {
    background: rgb(253, 95, 127);
  }

  .modalHidden {
    display: none;
  }
</style>