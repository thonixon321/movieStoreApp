<template>
  <div id="app" >
    <!-- the key here makes sure components get reloaded when the url changes, including query params -->
    <NavBar />
    <router-view class="mx-16" />

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

</style>
