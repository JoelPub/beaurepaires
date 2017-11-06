import Vue from 'vue'
import MainHeader from './components/MainHeader'
import Selector from './components/Selector'
import ProgressBar from './components/ProgressBar'
import PdpProductStock from './components/PdpProductStock'
import Setup from './components/Setup'
import { HeaderClass } from './scripts/Common.js'
import { Home } from './scripts/pages'

// For testing post-build only
// Vue.config.devtools = true

window.isBeauBooted = false

const bootBeau = () => {
// document.addEventListener('DOMContentLoaded', () => {
  const doesElemexists = (selector) => (document.getElementsByClassName(selector).length > 0)

  let components = {}

  components.header = new Vue({
    el: '.main-header',
    components: { Setup, MainHeader }
  })

  if (doesElemexists('cdp-selector')) {
    components.cart = new Vue({
      el: '.cdp-selector',
      components: { Selector }
    })
  }

  if (doesElemexists('cart-progress-bar')) {
    components.cart = new Vue({
      el: '.cart-progress-bart',
      components: { ProgressBar }
    })
  }

  if (doesElemexists('store-and-stock-availability')) {
    components.cart = new Vue({
      el: '.store-and-stock-availability',
      components: { PdpProductStock }
    })
  }

  // start header
  let headerHandler = new HeaderClass('.sticky-header')
  headerHandler.init()

  // start Home page
  Home.init()

  window.isBeauBooted = true

  return { components } // used to get around linting
// })
}

window.bootBeau = bootBeau
document.addEventListener('DOMContentLoaded', bootBeau)
