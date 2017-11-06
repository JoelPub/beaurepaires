<template>
  <div class="container-minicart">
    <div class="overlay-header">
      <i aria-hidden="true" class="fa fa-shopping-cart"></i>
      <span>My Cart</span>
      <button class="close-panel" v-on:click="close">
        <i class="fa fa-times" aria-hidden="true"></i>
        <span class="show-for-sr">Close cart</span>
      </button>
    </div>
    <div class="overlay-body">
      <div v-show="isCart">
        <transition-group name="fade" class="cart-item" tag="ul" v-if="cartState === 'ready'">
          <CartItem
            v-for="(item, index) in limitCartItem"
            :item="item"
            :index="index"
            type="mini"
            :remove-item-from-list="remove"
            :update-item-count="update"
            :key="item"
          />
        </transition-group>
        <div v-if="items.length > 3" class="show-more">
            <span>{{this.items.length - 3}} more items</span>
        </div>
        <div class="state-loading mc-wrap" v-else-if="cartState === 'loading'">
          <i class="fa fa-spinner fa-pulse fa-fw"></i>
          <span class="">Loading...</span>
        </div>
        <div class="state-empty mc-wrap" v-else-if="cartState === 'empty'">
          <strong>Your cart is empty</strong>
        </div>
        <div class="state-error mc-wrap" v-else-if="cartState === 'error'">
          <strong>There was an error loading you cart</strong>
        </div>
        <div class="mc-wrap view-cart" v-if="items.length > 0" >
          <a class="flat-button regular tertiary checkout" :href="cartCheckoutUrl">Checkout</a>
          <a :href="cartLandingUrl">View Shopping Cart</a>
        </div>
        <div class="mc-wrap your-store" v-if="items.length > 0 && storeName">
          <!-- todo: reuse reuse product stock component -->
            <p><i class="fa fa-map-marker" aria-hidden="true"></i> <strong>Your store:</strong></p>
            <p>{{storeName}}</p>
            <p>{{storeAddr}}</p>
            <p class='change'><a :href="cartStoreUrl">Change</a></p>
        </div>
        <p class="hide">{{badgeCartQty}}</p>
      </div>
      
    </div>
  </div>
</template>

<script>
import $ from 'jquery'
import CartItem from './CartItem'
import axios from 'axios'

const data = {
  isCart: true,
  cartState: 'loading',
  items: [],
  limitItem: 3,
  formKey: null,
  storeName: null,
  storeAddr: null
}

export default {
  name: 'MiniCart',
  props: [
    'cartDisplayUrl',
    'cartUpdateUrl',
    'cartDeleteUrl',
    'cartCheckoutUrl',
    'cartLandingUrl',
    'cartStoreUrl',
    'badgeCartQty'

  ],
  components: {CartItem},
  mounted () {
    this.updateCartItems()
  },
  data: () => {
    return data
  },
  computed: {
    limitCartItem () {
      return this.items.slice(0, this.limitItem)
    }
  },
  watch: {
    badgeCartQty: function () {
      console.log('UPDATING CART ITEMS...')
      this.updateCartItems()
    }
  },
  methods: {
    // for updating badge item qty
    updateQtyItems (content, qty) {
      this.items = content
      this.cartState = 'ready'
      this.addClassBadge()
      this.updateBadge(qty)
    },
    // pass updated badge to parent
    updateBadge (totQty) {
      this.$emit('update', totQty)
    },
    updateCartItems () {
      var vm = this
      const getUrl = vm.cartDisplayUrl
      // const getUrl = 'https://api.myjson.com/bins/1797uh'
      // null store
      // const getUrl = 'https://api.myjson.com/bins/pznax'
      axios.get(getUrl)
      .then(response => {
        if (response.data.content.length > 0) {
          vm.updateQtyItems(response.data.content, response.data.qty)
          // get form key on first display
          if (response.data.store_location) {
            vm.storeName = response.data.store_location.name
            vm.storeAddr = response.data.store_location.address
          }
          vm.formKey = response.data.form_key
          vm.cartState = 'ready'
        } else {
          vm.cartState = 'empty'
        }
      })
      .catch(() => {
        vm.cartState = 'error'
      })
    },
    // remove cart item
    remove (itemIndex) {
      const vm = this
      // /checkout/cart/ajaxDelete/id/{id}/form_key/{form_key}
      const deleteUrl = `${this.cartDeleteUrl}/id/${this.items[itemIndex].item_id}/form_key/${this.formKey}`
      vm.items.splice(itemIndex, 1)
      vm.cartState = 'loading'
      axios.post(deleteUrl)
      .then(response => {
        vm.updateQtyItems(response.data.content, response.data.qty)
      })
    },
    // update qty of cart item
    update (itemIndex, updatedQty) {
      const vm = this
      // checkout/cart/ajaxUpdate/qty/{qty}/id/{id}/form_key/{form_key}
      const UpdateUrl = `${this.cartUpdateUrl}/qty/${updatedQty}/id/${this.items[itemIndex].item_id}/form_key/${this.formKey}`
      vm.cartState = 'loading'
      axios.post(UpdateUrl)
      .then(response => {
        vm.updateQtyItems(response.data.content, response.data.qty)
      })
    },
    addClassBadge () {
      const cartEl = $('.nav-actions').find('li.Cart')
      if (this.items.length > 0) {
        cartEl.addClass('isCartItem')
        this.cartState = 'ready'
      } else {
        cartEl.removeClass('isCartItem')
        this.cartState = 'empty'
      }
    },
    close () {
      this.$emit('close')
    }
  }
}
</script>
