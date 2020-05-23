export const namespaced = true

export const state =  {
  products: [],
  titles: []
}

export const getters = {
  getUserIdsThatRatedProduct: state => movieId => {
    var userIds = []

    state.products.forEach((el) => {
      console.log(el[movieId])
      if (el[movieId] !== undefined) {
        el[movieId].forEach((innerEl) => {
          if (innerEl.ratings.length) {
            userIds = innerEl.ratings
          }
        })
      }
    })

    return userIds
  }
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