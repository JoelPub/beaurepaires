<template>
  <div>
    <div class="overlay-header">
      <i aria-hidden="true" class="fa fa-search"></i>
      <span>Search</span>
      <button class="close-panel" v-on:click="close">
        <i class="fa fa-times" aria-hidden="true"></i>
      </button>
    </div>
    <div class="overlay-body">
      <div class="search-head">
        <form method="get" :action="action" v-on:submit.prevent>
          <input class="input-text search-input  UI-SEARCH" :name="inputName" autocomplete="off" :placeholder="label" maxlength="128" v-model='searchInput'>
          <label class="search-submit" for="submit-header-search">
            <i aria-hidden="true" class="fa fa-search"></i>
            <span class="show-for-sr">Submit</span>
          </label>
          <i class="fa fa-spinner fa-spin fa-pulse loader" aria-hidden="true" v-show="loading"></i>
          <input id="submit-header-search" type="submit" value="submit search" class="show-for-sr" @click="searchGlobal" :disabled="submitted">
        </form>
      </div>
      <div class="search-result" mode="out-in">
        <p v-show="showNoResult">{{noResult}}</p>
        <div v-show="showResult">
          <ul>
            <li v-for="result in limitedItems" :result="result">
              <a :href="result.url">
                <img :src="result.Image">
                <div class="details">
                  <h6>{{result.name}}</h6>
                  <p class="description">{{result.description}}</p>
                  <p class="price">{{result.price}}</p>
                </div>
              </a>
            </li>
          </ul>
          
          <a v-bind:href="searchViewAll+searchInput" class="view-all">View all results</a>
        </div>
        
      </div>
      
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import axios from 'axios'
import 'babel-polyfill'

const data = {
  searchInput: '',
  loading: false,
  submitted: false,
  showResult: false,
  showNoResult: false,
  limitResult: 3,
  noResult: '',
  results: []
}

export default {
  name: 'SearchPanel',
  props: [
    'label',
    'inputName',
    'action',
    'notFound',
    'conError',
    'searchApi',
    'searchViewAll'
  ],
  data: () => {
    return data
  },
  computed: {
    limitedItems () {
      return this.results.slice(0, this.limitResult)
    }
  },
  // search as you type
  watch: {
    searchInput: function (e) {
      if (this.searchInput.length === 0) {
        this.showResult = false
        this.showNoResult = false
      } else {
        this.searchGlobal()
      }
    }
  },
  methods: {
    close: function () {
      this.$emit('close')
    },
    searchGlobal: _.debounce(function () {
      var vm = this
      const uri = vm.searchApi
      vm.loading = true
      vm.submitted = true
      vm.showResult = false
      vm.noResult = ''
      axios.get(uri + vm.searchInput)
      .then(response => {
        if (response.data.items.length === 0) {
          vm.showResult = false
          vm.noResult = vm.notFound
          vm.results = []
          vm.showNoResult = true
        } else {
          vm.results = response.data.items
          vm.showResult = true
          vm.noResult = ''
        }
        vm.loading = false
        vm.submitted = false
      })
      .catch(() => {
        vm.loading = false
        vm.submitted = false
        vm.showResult = false
        vm.noResult = vm.conError
      })
    }, 300)
  }
}
</script>
