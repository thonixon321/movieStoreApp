export const namespaced = true

export const state =  {
  customers: []
}

export const getters = {
  getLoggedInCustomer: state => (email, password) => {

    return state.customers.find( customer =>
      customer.email === email &&
      customer.password === password
    )
  },


  getLoggedInCustomerByEmail: state => (email) => {

    return state.customers.find( customer =>
      customer.email === email
    )
  },

}

export const mutations = {
  SET_CUSTOMERS(state, customers) {
    state.customers = customers
  }
}

export const actions = {
  fetchedCustomers({ commit }, customers) {
    commit('SET_CUSTOMERS', customers)
  }
}