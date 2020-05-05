<template>
  <div>
    <AddToCart @closeModal="modalHidden = true" :customer="customer" :movie="movieSelected" v-if="!modalHidden" />
    <h1 class="text-3xl mb-3">Available Now: </h1>
    <div class="flex flex-wrap">
      <div v-for="(movie, index) in movies" :key="index">
        <div v-for="(type, i) in movie[moviesArr[index]]" :key="i">
          <div v-if="i == 0" class="max-w-sm rounded overflow-hidden mx-4 mb-10 shadow-lg">
            <img class="w-full" :src="type.image" alt="movie image">
            <div class="relative px-6 py-4 bg-white-100">
              <button @click="openModal(movie[moviesArr[index]])" class="addToCart">
                <i class="material-icons">add_shopping_cart</i>
              </button>
              <div class="font-bold text-xl mb-2">{{ type.name }}</div>
              <p class="text-gray-700 text-base">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus quia, nulla! Maiores et perferendis eaque, exercitationem praesentium nihil.
              </p>
            </div>
            <div class="px-6 py-4 bg-white-100">
              <span v-for="(genre, g) in splitGenres(type.genre)" :key="g" class="inline-block bg-blue-600 rounded-full px-3 py-1 text-white text-sm font-semibold mr-2">#{{ genre }}</span>
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
import { mapState } from 'vuex'

export default {
  name: 'Home',

  props: {
    customer: String
  },

  data() {
    return {
      movies: [],
      modalHidden: true,
      movieSelected: {},
      moviesArr: []
    }
  },

  mixins: [axiosHandler],


  computed: {
    ...mapState({
      loggedIn: state => state.loggedIn,
      storedCustomers: state => state.customer.customers
    }),


  },


  methods: {

    splitGenres(string) {
      let split = string.split(',')
      console.log(split)
      if (split !== undefined) {
        return split
      }
      else {
        return [string]
      }
    },

    getMoviesResponse(res){
      let organizedMoviesArr = []

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
          let keyName = ''

          for (var key in innerEl) {
            keyName = key
          }
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

            innerEl[keyName].push(newObj)
          }
        })

      })

      //separated data by movie (each movie can have several product_ids and types, e.g. DVD, rental, VHS)
      this.movies = organizedMoviesArr

      this.getCustomers()
    },


    getCustomers() {
      var settingsObj,
        payloadObj;

      settingsObj = {
        url: 'http://localhost:8080/rest_movieApp/api/customer/read.php',
        method: 'GET',
        callBack: this.getCustomersResponse
      };

      payloadObj = {};

      this.sendAxios(payloadObj, settingsObj);
    },


    getCustomersResponse(res) {
      this.$store.dispatch('customer/fetchedCustomers', res.data.data)
    },


    openModal(movie) {
      this.modalHidden = false;

      this.movieSelected = movie;
    },


    getOrderHistoryResponse(res) {
      console.log(res.data)
      if (res.data.data !== undefined) {
        this.$store.dispatch('order/setOrderHistory', res.data.data)
      }
    }
  },


  mounted() {
    var settingsObj,
        payloadObj;

    settingsObj = {
      url: 'http://localhost:8080/rest_movieApp/api/product/read.php',
      method: 'GET',
      callBack: this.getMoviesResponse
    };

    payloadObj = {};

    this.sendAxios(payloadObj, settingsObj);

    console.log(this.loggedIn)
    if (this.loggedIn) {
      settingsObj = {
            url: 'http://localhost:8080/rest_movieApp/api/order/read.php',
            method: 'GET',
            callBack: this.getOrderHistoryResponse
      }
      payloadObj = {}

      this.sendAxios(payloadObj, settingsObj);
    }

  },


  components: {
    AddToCart
  }

}
</script>

<style scoped>
  img {
    height: 33rem;
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