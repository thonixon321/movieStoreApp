<template>
  <div class="modalBG">
    <div class='modalCard relative flex flex-col justify-center align-center'>
      <h2 class="text-xl font-bold uppercase p-2"><span class="underline">{{movieTitle}} </span>:</h2>
      <div v-for="(rating, index) in ratings" :key="index">
        <div v-if="rating.rating == '0.25'" class="star flex flex-col">
            <div class="flex filledStar">
              <i class='material-icons scale-110'>star</i>
              <div class="text-black font-bold">
                <div> from <span class="italic">{{ rating.name }}</span></div>
              </div>
            </div>
            <div>- "{{ rating.message }}"</div>
        </div>
        <div v-if="rating.rating == '0.5'" class="star flex flex-col">
            <div class="flex filledStar">
              <i class='material-icons scale-110'>star</i>
              <i class='material-icons scale-110'>star</i>
              <div class="text-black font-bold">
                <div> from <span class="italic">{{ rating.name }}</span></div>
              </div>
            </div>
            <div>- "{{ rating.message }}"</div>
        </div>
        <div v-if="rating.rating == '0.75'" class="star flex flex-col">
          <div class="filledStar flex">
            <i class='material-icons scale-110'>star</i>
            <i class='material-icons scale-110'>star</i>
            <i class='material-icons scale-110'>star</i>
            <div class="text-black font-bold">
              <div> from <span class="italic">{{ rating.name }}</span></div>
            </div>
          </div>
          <div>- "{{ rating.message }}"</div>
        </div>
        <div v-if="rating.rating == '1'" class="star flex flex-col">
          <div class="filledStar flex">
            <i class='material-icons scale-110'>star</i>
            <i class='material-icons scale-110'>star</i>
            <i class='material-icons scale-110'>star</i>
            <i class='material-icons scale-110'>star</i>
            <div class="text-black font-bold">
              <div> from <span class="italic">{{ rating.name }}</span></div>
            </div>
          </div>
          <div>- "{{ rating.message }}"</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'Reviews',

  props: {
    ratings: Array
  },


  data() {
    return {

    }
  },


  computed: {
     ...mapState({
      storedCustomer: state => state.customer.loggedInCustomer
    }),

    movieTitle() {
      let split = this.ratings[0].movie_id.split('_')
      let join = split.join(' ')

      return join
    }

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
        color: rgb(212, 212, 62)
      }

</style>