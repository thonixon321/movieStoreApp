<template>
  <div class="flex flex-col">
    <div class="mx-12" v-for="(order, index) in history" :key="index">
      <div v-for="(item, i) in order.orderItems" :key="i">
        <h2 class="text-3xl font-bold underline" v-if="i == 0">Order Id: {{ item.order_id }}</h2>
        <h4 class="font-bold p-2" v-if="i == 0">Order Date: {{ item.order_date }}</h4>
        <h4 class="font-bold p-2" v-if="i == 0">Total Price: ${{ item.total_price }}.00</h4>
        <h4 class="text-2xl font-bold p-2" v-if="i == 0">Products:</h4>
        <ul class="mb-4 pl-4 flex">
          <li> <span class="font-bold"> Product Name: </span>{{ item.name }},</li>
          <li> Quantity:  {{ item.quantity }}, </li>
          <li> Product Price:  ${{ item.price }}, </li>
          <li>  Type:  {{ item.type }}</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrderHistory',

  props: {
    history: Array
  }

}
</script>

<style scoped>
 #app ul li {
    margin-right: .5em
  }
</style>