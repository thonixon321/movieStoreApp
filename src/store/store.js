import Vue from 'vue'
import Vuex from 'vuex'
import * as customer from './modules/customer'
import * as order from './modules/order'

//import all public items into the user namespace - so user.user can
//access the value that was exported from this file
// import * as user from './modules/user'

Vue.use(Vuex)

export const store = new Vuex.Store({
  modules: {
    customer,
    order
  },

  state: {
    loggedIn: false,
    loggedInEmail: ''
  },

  getters: {

  },

  mutations: {
    SET_STATUS(state, status) {
      state.loggedIn = status
    },

    SET_EMAIL(state, email) {
      state.loggedInEmail = email
    }
  },

  actions: {
    changeLogInStatus({ commit }, status) {
      commit('SET_STATUS', status)
    },

    changeLogInEmail({ commit }, email) {
      commit('SET_EMAIL', email)
    }
  }
})


