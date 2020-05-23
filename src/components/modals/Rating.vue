<template>
  <div class="modalBG">
    <div class='modalCard relative flex flex-col justify-center align-center'>
      <a @click="$emit('closeModal')" href='#' class="closeModal absolute top-0 right-0">
        <i class='material-icons'>close</i>
      </a>
      <div v-if="!submitted">
        <div class='modalCardHeader'>
          <h2 class="text-2xl mx-4">How would you rate <span class="italic">{{ title }}</span> ?</h2>
        </div>
        <div class='modalBody flex justify-center p-2'>
          <div @mouseleave="unhighlightStars(1)">
            <a @mouseover="highlightStars(1)" v-if="!starOne" href='#' class="star">
              <i class='material-icons scale-110'>star_border</i>
            </a>
            <a v-else @click="selectStar(1)" @mouseleave="unhighlightStars(1)" href='#' class="star filledStar">
              <i class='material-icons scale-110'>star</i>
            </a>
            <a @mouseover="highlightStars(2)" v-if="!starTwo" href='#' class="star">
              <i class='material-icons scale-110'>star_border</i>
            </a>
            <a v-else href='#' @click="selectStar(2)" @mouseleave="unhighlightStars(2)" class="star filledStar">
              <i class='material-icons'>star</i>
            </a>
            <a v-if="!starThree" @mouseover="highlightStars(3)" href='#' class="star">
              <i class='material-icons scale-110'>star_border</i>
            </a>
            <a v-else href='#' @click="selectStar(3)" @mouseleave="unhighlightStars(3)" class="star filledStar">
              <i class='material-icons scale-110'>star</i>
            </a>
            <a v-if="!starFour" @mouseover="highlightStars(4)" href='#' class="star">
              <i class='material-icons'>star_border</i>
            </a>
            <a v-else href='#' @click="selectStar(4)" @mouseleave="unhighlightStars(4)" class="star filledStar">
              <i class='material-icons'>star</i>
            </a>
          </div>
        </div>
        <div class='comments p-4 flex flex-col'>
          <p class="text-xl">Comments:</p>
          <textarea class="border-2" v-model="comments" name='comments' id='comments' cols='5' rows='5'></textarea>
        </div>
        <div class='relative mb-2 ml-10 h-6'>
          <p v-if="feedback" class="absolute top-0 left-0 text-red-600">{{ feedback }}</p>
        </div>
        <div class="flex justify-center mb-2">
          <button @click="submitRating" class="w-48 bg-indigo-900 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
            Submit
          </button>
        </div>
      </div>
      <div class="text-xl font-bold" v-else>
        Thanks for rating!
      </div>
    </div>

  </div>
</template>

<script>
import { mapState } from 'vuex'
import { axiosHandler } from '../../mixins/axiosHandler';

  export default {

    name: '',

    data: function() {
      return{
        starOne: false,
        starTwo: false,
        starThree: false,
        starFour: false,
        lockStars: 0,
        feedback: null,
        submitted: false,
        comments: ''
      }
    },


    mixins: [axiosHandler],



    computed: {
       ...mapState({
         storedCustomer: state => state.customer.loggedInCustomer,
       }),

    },



    props: {
      title: String,
      movie_id: String
    },



    methods: {

      submitRating() {
        var settingsObj,
            payloadObj,
            ratingPercent;

        if (this.lockStars !== 0) {

          if (this.lockStars === 1) {
            ratingPercent = 0.25
          }
          else if (this.lockStars === 2) {
            ratingPercent = 0.5
          }
          else if (this.lockStars === 3) {
            ratingPercent = 0.75
          }
          else {
            ratingPercent = 1
          }

          settingsObj = {
            url: 'http://localhost:8080/rest_movieApp/api/product/rate.php',
            method: 'POST',
            callBack: this.submitRatingResponse
          }

          payloadObj = {
            rating: ratingPercent,
            name: this.movie_id,
            message: this.comments,
            customer_id: this.storedCustomer.customer_id
          }

          this.sendAxios(payloadObj, settingsObj)
        }
        else {
          this.feedback = 'please select a star to submit'
        }
      },


      submitRatingResponse(res) {
        console.log(res)
        if (res.data.message === 'product rated') {
          this.submitted = true

          setTimeout(() => {
            this.$emit('closeModal')
          }, 2000)
        }
      },


      highlightStars(num) {

        console.log(num)
        if (num === 1) {
          this.starOne = true
        }
        else if (num === 2) {
          this.starOne = true
          this.starTwo = true
        }
        else if (num === 3) {
          this.starOne = true
          this.starTwo = true
          this.starThree = true
        }
        else {
          this.starOne = true
          this.starTwo = true
          this.starThree = true
          this.starFour = true
        }
      },


      unhighlightStars(num) {
        if (num === 1 && this.lockStars === 0) {
          this.starOne = false
          this.starTwo = false
          this.starThree = false
          this.starFour = false
        }
        else if (num === 2 && this.lockStars < 2) {
          this.starTwo = false
          this.starThree = false
          this.starFour = false
        }
        else if (num === 3 && this.lockStars < 3) {
          this.starThree = false
          this.starFour = false
        }
        else if (num === 4 && this.lockStars < 4) {
          this.starFour = false
        }
      },


      selectStar(num) {
        this.lockStars = num
        this.feedback = null

        if (num === 1) {
          this.starOne = true
          this.starTwo = false
          this.starThree = false
          this.starFour = false
        }
        else if (num === 2) {
          this.starOne = true
          this.starTwo = true
          this.starThree = false
          this.starFour = false
        }
        else if (num === 3) {
          this.starOne = true
          this.starTwo = true
          this.starThree = true
          this.starFour = false
        }
        else {
          this.starOne = true
          this.starTwo = true
          this.starThree = true
          this.starFour = true
        }
      }

    },


    mounted: function() {

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

    .modalCard {
      width: 18rem;
      height: auto;
      padding: 1em 0 0 0;
      border-radius: 5px;
      background: white;
      transition: 0.3s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

      .filledStar {
        color: rgb(241, 241, 69)
      }

      textarea {
        resize: none;
      }
</style>