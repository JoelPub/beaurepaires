<template>
  <div class="selector-field">
    <select
      ref="selectInput"
      :name="selectName"
      :aria-label="selectLabel"
      :disabled="(isLoading || !optionData || optionData.length === 0)"
      @change="onSelect"
      @mouseenter="onMouseIn"
      @mouseleave="onMouseOut"
      @focus="onMouseIn"
      @blur="onMouseOut"
    >
      <option value="">{{ selectLabel }}</option>
      <option
        v-for="option in optionData"
        :value="option.value"
        :selected="(selectedValue == option.value)"
      >
        {{ option.label }}
      </option>
    </select>
    <div class="field-loader" v-if="isLoading">
      <span role="alertdialog" aria-busy="true">
        <i class="fa fa-spinner fa-pulse fa-fw"></i>
        <span class="show-for-sr">Loading options...</span>
      </span>
    </div>
  </div>
</template>

<script>

export default {
  name: 'SelectorSelect',

  props: [
    'selectName',
    'selectLabel',
    'selectedValue',
    'isLoading',
    'optionData',
    'onSelect',
    'onHover',
    'groupOptions'
  ],

  updated () {
    if (this.groupOptions && this.optionData.length) {
      // TODO: this
      // this.sortIntoGroups()
    }
  },

  methods: {
    onMouseIn () {
      if (typeof this.onHover === 'function') {
        this.onHover(this.selectName)
      }
    },

    onMouseOut () {
      if (typeof this.onHover === 'function') {
        this.onHover('')
      }
    },

    sortIntoGroups () {
      // ^(([^ ]+)( |$)){3}
      let pat = new RegExp('^(([^ ]+)( |$)){3}')
      const firstVal = this.optionData[0].label
      let match = firstVal.match(pat)
      console.log('test', firstVal, match)
      // console.log('firstVal', firstVal)
    }
  }

}

</script>
