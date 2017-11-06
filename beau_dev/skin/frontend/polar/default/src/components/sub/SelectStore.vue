<template>
  <div class="select-store">
    <input type="text" placeholder="Select a new store" v-model="searchLocation" v-bind:readonly="!isReady" aria-label="Select a new store">
    <i v-bind:class="{ 'fa fa-refresh fa-spin': true, 'loading': isLoading }" ></i>
    <ul v-if="results.length > 0" v-show="searchResult">
      <li @click="setStore(result.id,result.value,result.label)" v-for="result in results">{{result.label}}</li>
    </ul>
    <div v-show="isShowingNoResults">No Results</div>
    <div v-show="isShowingError">Error getting request</div>
  </div>
</template>

<script>
import GoogleMapsApiLoader from 'google-maps-api-loader'
import axios from 'axios'
import _ from 'lodash'

const data = {
  isReady: false,
  isShowingNoResults: false,
  isShowingError: false,
  isLoading: false,
  isQuery: true,
  results: [],
  searchLocation: '',
  searchResult: false
}

export default {
  name: 'SelectStore',

  props: [
    'getStoresUrl',
    'setStoreUrl',
    'setStockStore',
    'googlePlaceApiKey'
  ],

  data: () => {
    return data
  },

  beforeMount () {
    // load google maps libraries
    GoogleMapsApiLoader({
      libraries: ['places', 'geocoder'],
      apiKey: this.googlePlaceApiKey
    })
    .then((googleApi) => {
      this.autocomplete = new googleApi.maps.places.AutocompleteService()
      this.geocoder = new googleApi.maps.Geocoder()
      data.isReady = true
      // console.log('ready', this.autocomplete, this.geocoder)
    })
  },
  watch: {
    searchLocation () {
      this.isShowingNoResults = false
      this.isShowingError = false
      if (this.searchLocation.length > 2 && data.isReady && this.isQuery === true) {
        this.getResults(this.searchLocation)
        this.isLoading = true
      } else {
        this.searchResult = false
        this.isQuery = true
      }
    }
  },

  methods: {
    getResults (searchTxt) {
      const data = {
        input: searchTxt,
        componentRestrictions: {country: 'au'}
      }

      this.autocomplete.getPlacePredictions(data, (predictions, status) => {
        switch (status) {
          case ('ZERO_RESULTS'):
            this.isShowingNoResults = true
            this.isLoading = false
            break

          case ('OK'):
            // console.log('predictions', predictions[0], predictions[0].place_id)
            this.getLatLng(predictions[0].place_id)
            break

          default:
            this.isShowingError = true
            this.isLoading = false
        }
      })
    },

    getLatLng (placeId) {
      this.geocoder.geocode({'placeId': placeId}, (results, status) => {
        let latLng = {
          lat: results[0].geometry.location.lat(),
          lng: results[0].geometry.location.lng()
        }
        this.getStores(latLng)
      })
    },

    setStore (id, val, label) {
      this.setStockStore(id, val, label)
      this.saveStore(id)
      this.searchLocation = val
      this.searchResult = false
      this.isQuery = false
    },

    saveStore (storeId) {
      const url = `${this.setStoreUrl}?ddate=&storeloc=${storeId}&dtime=&duration=120`
      axios.post(url).then((response) => {
        // console.log(storeId, 'saved')
      })
    },

    getStores: _.debounce(function (latLng) {
      const url = `${this.getStoresUrl}?lat=${latLng.lat}&lng=${latLng.lng}`

      axios.get(url).then((response) => {
        this.results = response.data
        this.isLoading = false
        this.searchResult = true
      })
    }, 500)
  }
}

</script>
