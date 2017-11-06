<template>
  <li>
      <img :src="item.img" alt="">
      <div class="prod-name"><a :href="item.url">{{ item.name }}</a></div>
      <div class="qty">
          <label for="prod-qty">Qty</label>
          <input type="number" min="1" max="6" name="prod-qty" ref="qty" :value="item.qty" @change="showUpdateLink($event)">
          <a @click="updateQty(index, $refs.qty.value, $event)" class="update-this">Update</a>
      </div>
      <a @click="removeItemFromList(index)">
        <i class="fa fa-times-circle" aria-hidden="true"></i>
        <span class="show-for-sr">Remove {{ item.name }} from cart</span>
      </a>
  </li>
</template>

<script>

import $ from 'jquery'
const data = {
  isActive: false
}
export default {
  name: 'CartItem',
  props: [
    'item',
    'index',
    'type',
    'removeItemFromList',
    'updateItemCount'
  ],
  data: () => {
    return data
  },
  methods: {
    updateQty (index, updatedQty, event) {
      this.updateItemCount(index, updatedQty)
      $(event.currentTarget).removeClass('active')
      console.log(index, updatedQty)
    },
    showUpdateLink (event) {
      console.log($(event.currentTarget).next('.update-this'))
      $(event.currentTarget).next('.update-this').addClass('active')
    }
  }
}
</script>
