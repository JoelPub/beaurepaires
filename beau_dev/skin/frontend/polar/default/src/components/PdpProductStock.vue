<template>
  <ProductStock
    :current-store-id="currentStoreId"
    :get-stock-url="getStockUrl"
    :get-stores-url="getStoresUrl"
    :set-stores-url="setStoreUrl"
    :get-store-address-url="getStoreAddressUrl"
    :google-place-api-key="googlePlaceApiKey"
  />
</template>

<script>
// This is a container for the ProductStock compontent for use on the PDP page
import ProductStock from './sub/ProductStock'

// const data = {}

export default {
  name: 'pdp-product-stock',
  components: { ProductStock },
  props: [
    'currentStoreId',
    'getStockUrl',
    'getStoresUrl',
    'setStoreUrl',
    'getStoreAddressUrl',
    'googlePlaceApiKey'
  ]
}

</script>
