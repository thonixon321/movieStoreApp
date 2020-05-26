<template>
  <div id="app" >
    <!-- the key here makes sure components get reloaded when the url changes, including query params -->
    <NavBar />
    <transition name="slide-fade" mode="out-in">
      <router-view class="mt-56 mx-16" />
    </transition>
  </div>
</template>

<script>

import NavBar from './components/NavBar'

export default {

  methods: {

  },

  mounted() {

  },

  components: {
    NavBar
  }

}
</script>

<style>
html {
  -webkit-text-size-adjust: 100%;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
body {
  margin: 0;
  font-family: 'Manrope', sans-serif;
  font-size: 16px;
  line-height: 1.5;
  background: #f5f3f3;
}
#app {
  box-sizing: border-box;
}

.modalBG {
  display: flex;
  position: fixed;
  top: 70px;
  left: 0;
  width: 100%;
  height: 100%;
  align-items: center;
  justify-content: center;
  z-index: 3;
  background: rgba(70, 69, 69, 0.6);
}

.fade-enter {
  opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity .51s ease-out;
}

.fade-leave-to {
  opacity: 0;
}

.slide-fade-enter {
  transform: translateX(10px);
  opacity: 0;
}

.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all .2s ease;
}

.slide-fade-leave-to {
  transform: translateX(-10px);
  opacity: 0;
}

.slide-up-enter {
  transform: translateY(10px);
  opacity: 0;
}

.slide-up-enter-active {
  transition: all .2s ease;
}

.expand-show-enter,
.expand-show-leave-to {
  opacity: 0;
  width: 0em;
}

.expand-show-enter-active,
.expand-show-leave-active {
  transition: all .2s ease;
}


.hamburgerNav {
  display: none;
}

.drawer {
  display: none;
}

/* .slide-up-move {
  transition: transform .5s ease-out;
} */

@media only screen and (max-width: 1000px) {
  .nav-full {
    display: none
  }

  .hamburgerNav {
    display: block;
  }

  .drawer {
    display: flex;
  }
}
</style>
