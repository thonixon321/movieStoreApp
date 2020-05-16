export const namespaced = true

export const state =  {
  orders: [],
  ordersHistory: []
}

export const getters = {
  getOrderByProductId: state => productId => {
    return state.orders.find(order => order.product_id === productId)
  },

  getOrderHistoryByCustomerEmail: state => email => {
    if (state.ordersHistory.length) {
      return state.ordersHistory.filter(history => history.email === email)
    }
    else{
      return []
    }
  }
}

export const mutations = {
  SET_ORDER(state, order) {
    state.orders.push(order)
  },

  SET_HISTORY(state, orderHistory) {
    let organizedOrdersArr = []
    let orderIds = []

    state.ordersHistory = []
    console.log(orderHistory)
    orderHistory.forEach((el) => {
      let alreadyExists = orderIds.find((item) => {
        return item.order_id === el.order_id
      })
      console.log(alreadyExists)
      if (alreadyExists === undefined) {
        orderIds.push({order_id: el.order_id, email: el.email})
      }
    })
    console.log(orderIds)
    for (var i = 0; i < orderIds.length; i++) {
      var tempObj = {}

      tempObj[orderIds[i].order_id] = []

      tempObj.email = orderIds[i].email

      tempObj.orderItems = []

      organizedOrdersArr.push(tempObj)
    }

    orderHistory.forEach((el) => {

      organizedOrdersArr.forEach((innerEl) => {
        let newObj = {}
        let keyName = ''

        for (var key in innerEl) {
          if (key !== 'email' && key !== 'orderItems') {
            keyName = key
          }
        }
        //match the key name (innerEl) to the movie_id

        if (keyName == el.order_id) {
          newObj.order_id = el.order_id
          newObj.email = el.email
          newObj.price = el.price
          newObj.total_price = el.total_price
          newObj.type = el.type
          newObj.name = el.name
          newObj.first_name = el.first_name
          newObj.last_name = el.last_name
          newObj.order_date = el.order_date
          newObj.quantity = el.quantity
          newObj.image = el.image

          innerEl[keyName].push(newObj)
          innerEl.orderItems.push(newObj)
        }
      })

    })

    state.ordersHistory = organizedOrdersArr
  },

  EMPTY_CART(state) {
    state.orders = []
  },

  DELETE_ORDER_ITEM(state, item) {
    state.orders.forEach((el, index) => {
      if (el.product_id === item.product_id) {
        state.orders.splice(index, 1)
      }
    })
  }
}

export const actions = {
  addOrder({ commit }, order) {
    commit('SET_ORDER', order)
  },

  clearOrder({ commit }) {
    commit('EMPTY_CART')
  },

  setOrderHistory({ commit }, orderHistory) {
    commit('SET_HISTORY', orderHistory)
  },

  removeOrderItem({ commit }, item) {
    commit('DELETE_ORDER_ITEM', item)
  }
}