export const namespaced = true

export const state =  {
  products: [],
  titles: []
}

export const getters = {

}

export const mutations = {
  SET_PRODUCTS(state, products) {
    state.products = products
  },

  SET_TITLES(state, titles) {
    state.titles = titles
  }

}

export const actions = {

  setProducts({ commit }, products) {
    commit('SET_PRODUCTS', products)
  },

  setMovieTitles({ commit }, titles) {
    commit('SET_TITLES', titles)
  }
}