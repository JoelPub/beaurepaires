<template>
  <ul class="cart-item">
      <li v-for="item in cartItems">
          <img src="/static/img/battery250.0fd1de8.png" alt="">
          <div class="prod-name">{{item.name}}</div>
          <div class="qty">
              <label for="prod-qty">Qty</label>
              <input type="text" name="prod-qty" :value="item.qty">
          </div>
          <i class="fa fa-times-circle" aria-hidden="true" @click="deleteCartItem(item)"></i>
      </li>
  </ul>
</template>

<script>
const data = {
  // sample data, todo: cartItem API
  cartItems: [
    {
      prodId: 1,
      name: 'OZ RACING ULTRALEGGERA HLT CL MATT GRAPHITE SILVER',
      qty: 1,
      img: '/static/img/battery250.0fd1de8.png'
    },
    {
      prodId: 2,
      name: 'EAGLE RS-A',
      qty: 2,
      img: '/static/img/battery250.0fd1de8.png'
    },
    {
      prodId: 3,
      name: 'GRANDTREK TG35',
      qty: 4,
      img: '/static/img/battery250.0fd1de8.png'
    }
  ]
}

export default {
  name: 'CartItem',
  props: [],
  mounted () {
  },
  watch: {
    // todo watch cartItem length and pass to parent
  },
  data: () => {
    return data
  },
  methods: {
    deleteCartItem (itm) {
      console.log(itm)
      this.cartItems.splice(itm, 1)
      console.log(this.cartItems)
    }
  }
}
</script>
