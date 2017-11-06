<template>
  <div class="product-stock">
    <div class="stock-msg" v-show="!isLoading">
      <p>
        <i class="fa" v-bind:class="msgIcon[msgIconCurrent]" aria-hidden="true"></i>
        <strong>{{ this.msgText[this.msgTextCurrent] }}</strong>
      </p>
    </div>
    <div class="stock-loading-msg" v-show="isLoading">
      <p>
        <i class="fa fa-spinner fa-pulse fa-fw"></i> Checking stock...
      </p>
    </div>
    <div class="location" v-show="(isAddressLoaded && addressData.title != null)">
      <strong>{{ addressData.title }}</strong><br>
      {{ addressData.street }}<br>
      {{ addressData.city }} {{ addressData.region }} {{ addressData.postal_code }}
    </div>
    <div class="location" v-show="!isAddressLoaded">
      <p>
        <i class="fa fa-spinner fa-pulse fa-fw"></i> Getting store details...
      </p>
    </div>
    <div v-show="newLoc">
      <SelectStore
        :get-stores-url="getStoresUrl"
        :set-store-url="setStoresUrl"
        :set-stock-store="setStore"
        :google-place-api-key="googlePlaceApiKey"
        v-show="newLoc"
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import $ from 'jquery'
import SelectStore from './SelectStore'

const data = {
  storeId: 1,
  msgIcon: {
    none: '',
    check: 'fa-check-circle',
    exclamation: 'fa-exclamation-triangle',
    marker: 'fa-map-marker'
  },
  msgIconCurrent: 'none',
  msgText: {
    none: '',
    available: 'This product is available at:',
    unknown: 'Stock availability unknown at:',
    notInStock: 'This product is not in stock at:',
    noLocation: 'Enter in a location for stock availability',
    error: 'Sorry, there was an error requesting stock levels'
  },
  msgTextCurrent: 'none',
  showStoreLocator: 'true',
  newLoc: true,
  isLoading: false,
  currentProductDetails: [],
  addressData: { title: null },
  isAddressLoaded: false
}

export default {
  name: 'ProductStock',

  components: { SelectStore },

  props: [
    'currentStoreId',
    'getStockUrl',
    'getStoresUrl',
    'setStoresUrl',
    'getStoreAddressUrl',
    'googlePlaceApiKey'
  ],

  data: () => {
    return data
  },

  mounted () {
    this.storeId = this.currentStoreId
    this.getProductDetails()
    if (this.storeId !== null && this.storeId !== '') {
      this.setAddress(this.storeId)
      this.query()
    } else {
      // if no store
      this.msgTextCurrent = 'noLocation'
      this.msgIconCurrent = 'marker'
      this.newLoc = true
      this.isAddressLoaded = true
    }

    const $sizeSelects = $('.select-size')

    $sizeSelects.on('change', () => {
      this.getProductDetails()
      this.query()
    })
  },

  methods: {
    setStore (...props) {
      this.storeId = props[0]
      this.setAddress(props[0])
      this.query()
    },

    getProductDetails () {
      const $sizeSelects = $('.select-size')

      this.currentProductDetails = []

      for (let select of $sizeSelects) {
        const $select = $(select)
        let $detailElem = []

        if ($select.is('select')) {
          $detailElem = $select.find('option:selected')
        } else {
          $detailElem = $select
        }

        if ($detailElem.length) {
          const attributesJson = $detailElem.data('attributes-json')
          const thisProductDetails = [
            attributesJson.sku,
            $detailElem.data('product-id'),
            1,
            $detailElem.data('sap-code')
          ]
          this.currentProductDetails.push(
            this.formatStockStatus(...thisProductDetails)
          )
        }
      }
    },

    query () {
      this.isLoading = true
      const formatProductParams = this.currentProductDetails.map(detail => '&sku%5B%5D=' + detail)
      const url = `${this.getStockUrl}?store_id=${this.storeId}${formatProductParams.join('')}`

      axios.get(url)
      .then(this.updateMessage)
      .catch(() => {
        this.isLoading = false
        this.msgTextCurrent = 'error'
        this.msgIconCurrent = 'exclamation'
      })
    },

    updateMessage (response) {
      this.isLoading = false
      switch (response.data.data[0].response) {
        case (0):
          this.msgTextCurrent = 'available'
          this.msgIconCurrent = 'check'
          this.newLoc = true
          break

        case (1):
          this.msgTextCurrent = 'unknown'
          this.msgIconCurrent = 'exclamation'
          this.newLoc = true
          break

        case (2):
          if (response.data.data.length > 1 && response.data.data[0].response === 0) {
            this.msgTextCurrent = 'available'
            this.msgIconCurrent = 'check'
            this.newLoc = true
          } else {
            this.msgTextCurrent = 'notInStock'
            this.msgIconCurrent = 'exclamation'
            this.newLoc = false
          }
          break

        case (3):
          this.msgTextCurrent = 'unknown'
          this.msgIconCurrent = 'exclamation'
          this.newLoc = true
          break

        default:
          this.msgTextCurrent = 'noLocation'
          this.msgIconCurrent = 'marker'
          this.newLoc = true
      }
    },

    setAddress (storeId) {
      const data = {
        'store_id': parseInt(storeId)
      }

      const updateAddressData = (result) => {
        this.addressData = result.data.data
        this.isAddressLoaded = true
      }

      this.isAddressLoaded = false

      axios.get(this.getStoreAddressUrl, { params: data })
      .then(updateAddressData)
      .catch(() => {
        console.error('Fail to get store address')
      })
    },

    formatStockStatus (...params) {
      const excape = (val) => val.replace('/', '%2F')
      return `stock-status-${excape(params[0])}__${params[1]}__${params[2]}__${params[3]}`
    }
  }
}

</script>
