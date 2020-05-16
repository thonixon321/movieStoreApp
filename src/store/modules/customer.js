export const namespaced = true

export const state =  {
  customers: [],
  loggedInCustomer: {}
}

export const getters = {


  getLoggedInCustomerByEmail: state => (email) => {

    return state.customers.find( customer =>
      customer.email === email
    )
  },

}

export const mutations = {
  SET_CUSTOMERS(state, customers) {
    state.customers = customers
  },

  SET_LOGGED_IN_CUST(state, customer) {
    state.loggedInCustomer = customer
  },

  DELETE_CUST(state) {
    state.loggedInCustomer = {}
  }
}

export const actions = {
  fetchedCustomers({ commit }, customers) {
    commit('SET_CUSTOMERS', customers)
  },

  setLoggedInCustomer({ commit }, customer) {
    commit('SET_LOGGED_IN_CUST', customer)
  },

  deleteCustomer({ commit }) {
    commit('DELETE_CUST')
  }
}