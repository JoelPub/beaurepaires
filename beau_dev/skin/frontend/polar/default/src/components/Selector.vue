<template>
  <section class="cdp-selector">
    <h2 class="select-title">{{ selectorTitle }}</h2>
    <ul :class="['selector-tabs', 'tab-count-' + Object.keys(this.formData).length]" role="tablist">
      <SelectorTab
        v-if="formData['search-tyres-vehicle']"
        tab-id="search-tyres-vehicle"
        :tab-title="formData['search-tyres-vehicle'].label"
        :tab-title-short="formData['search-tyres-vehicle'].labelMobile"
        tab-type="full"
        :is-active="(this.activeTab === 'search-tyres-vehicle')"
        :tab-icon="formData['search-tyres-vehicle'].icon"
        :on-select="setActiveTab"
      />
      <SelectorTab
        v-if="formData['search-tyres-size']"
        tab-id="search-tyres-size"
        :tab-title="formData['search-tyres-size'].label"
        :tab-title-short="formData['search-tyres-size'].labelMobile"
        tab-type="full"
        :is-active="(this.activeTab === 'search-tyres-size')"
        :on-select="setActiveTab"
        :tab-icon="formData['search-tyres-size'].icon"
        ref="test2"
      />
      <SelectorTab
        v-if="formData['saved-vehicles']"
        tab-id="saved-vehicles"
        :tab-title="formData['saved-vehicles'].label"
        :tab-title-short="formData['saved-vehicles'].labelMobile"
        tab-type="small"
        :is-active="(this.activeTab === 'saved-vehicles')"
        :on-select="setActiveTab"
      />
    </ul>
    <div v-if="formData['search-tyres-vehicle']" id="search-tyres-vehicle-panel" :class="getPanelClasses('search-tyres-vehicle')" aria-labelledby="search-tyres-vehicle-tab" role="tabpanel">
      <form :action="formatFormAction('search-tyres-vehicle')" method="post">
        <input v-for="hiddenField in hiddenFields['search-tyres-vehicle']" type="hidden" :name="hiddenField.name" :value="hiddenField.value" />
        <div class="selector-fields">
          <SelectorSelect
            select-name="make-tyres"
            select-label="Select Make"
            :selected-value="optionValues['make-tyres']"
            :is-loading="false"
            :option-data="optionsData['make-tyres']"
            :on-select="onSelectChange"
            :group-options="false"
          />
          <SelectorSelect
            select-name="year-tyres"
            select-label="Select Year"
            :selected-value="optionValues['year-tyres']"
            :is-loading="false"
            :option-data="optionsData['year-tyres']"
            :on-select="onSelectChange"
            :group-options="false"
          />
          <SelectorSelect
            select-name="model-tyres"
            select-label="Select Model"
            :selected-value="optionValues['model-tyres']"
            :is-loading="whichIsLoading['model-tyres']"
            :option-data="optionsData['model-tyres']"
            :on-select="onSelectChange"
            :group-options="false"
          />
          <SelectorSelect
            select-name="series-tyres"
            select-label="Select Series"
            :selected-value="optionValues['series-tyres']"
            :is-loading="whichIsLoading['series-tyres']"
            :option-data="optionsData['series-tyres']"
            :on-select="onSelectChange"
            :group-options="true"
          />
          <div class="selector-button">
            <button type="submit" class="flat-button secondary" :disabled="(optionValues['series-tyres'] === '')">GO</button>
          </div>
        </div>
      </form>

    </div>
    <div v-if="formData['search-tyres-size']" id="search-tyres-size-panel" :class="getPanelClasses('search-tyres-size')" aria-labelledby="search-tyres-size-tab" role="tabpanel">

      <form :action="formatFormAction('search-tyres-size')" method="post">
        <input v-for="hiddenField in hiddenFields['search-tyres-size']" type="hidden" :name="hiddenField.name" :value="hiddenField.value" />
        <div class="selector-fields">
          <tyre-size-graphic
            :current-hover="currentHover"
          />
          <SelectorSelect
            select-name="width-tyres"
            select-label="Select Width"
            :selected-value="optionValues['width-tyres']"
            :is-loading="false"
            :option-data="optionsData['width-tyres']"
            :on-select="onSelectChange"
            :on-hover="setHover"
            :group-options="false"
          />
          <SelectorSelect
            select-name="profile-tyres"
            select-label="Select Profile"
            :selected-value="optionValues['profile-tyres']"
            :is-loading="whichIsLoading['profile-tyres']"
            :option-data="optionsData['profile-tyres']"
            :on-select="onSelectChange"
            :on-hover="setHover"
            :group-options="false"
          />
          <SelectorSelect
            select-name="diameter-tyres"
            select-label="Select Rim Diameter"
            :selected-value="optionValues['diameter-tyres']"
            :is-loading="whichIsLoading['diameter-tyres']"
            :option-data="optionsData['diameter-tyres']"
            :on-select="onSelectChange"
            :on-hover="setHover"
            :group-options="false"
          />
          <div class="selector-button">
            <button type="submit" class="flat-button secondary" :disabled="(optionValues['diameter-tyres'] === '')">GO</button>
          </div>
        </div>
      </form>

    </div>
    <div v-if="formData['saved-vehicles']" id="saved-vehicles-panel" :class="getPanelClasses('saved-vehicles')" aria-labelledby="saved-vehicles-tab" role="tabpanel">

      <form :action="formatFormAction('saved-vehicles')" method="post">
        <input v-for="hiddenField in hiddenFields['saved-vehicles']" type="hidden" :name="hiddenField.name" :value="hiddenField.value" />
        <div class="selector-fields">
          <SelectorSelect
            select-name="saved_vehicles"
            select-label="Select Vehicle"
            :selected-value="optionValues['saved_vehicles']"
            :is-loading="false"
            :option-data="optionsData['saved_vehicles']"
            :on-select="onSelectChange"
            :group-options="true"
          />
          <div class="selector-button">
            <button type="submit" class="flat-button secondary" :disabled="(optionValues['saved_vehicles'] === '')">GO</button>
          </div>
        </div>
      </form>

    </div>
    <ul class="selector-actions">
      <li><a :href="browseAllUrl">{{ browseAllLabel }}</a></li>
      <li v-if="clearSearchShow === 'true'"><a :href="clearSearchUrl" class="clear">Clear Search</a></li>
    </ul>
  </section>
</template>

<script>
import axios from 'axios'
import SelectorTab from './sub/SelectorTab'
import SelectorSelect from './sub/SelectorSelect'
import TyreSizeGraphic from './sub/TyreSizeGraphic'

const data = {
  activeTab: '',
  currentHover: '',
  optionsData: {},
  optionValues: {},
  hiddenFields: {},
  formActions: {},
  whichIsLoading: {
    'model-tyres': false,
    'series-tyres': false,
    'profile-tyres': false,
    'diameter-tyres': false
  },
  formData: {}
}

export default {
  name: 'selector',

  data: () => {
    return data
  },

  components: { SelectorTab, SelectorSelect, TyreSizeGraphic },

  props: [
    'optionsConfigId',
    'selectorTitle',
    'apiDomain',
    'apiKey',
    'defaultActiveTab',
    'browseAllUrl',
    'browseAllLabel',
    'clearSearchUrl',
    'clearSearchShow'
  ],

  beforeMount () {
    // load config settings from inline JSON
    const jsonData = JSON.parse(document.getElementById(this.optionsConfigId).innerHTML)
    this.optionsData = jsonData.options
    this.optionValues = jsonData.values
    this.activeTab = this.defaultActiveTab
    this.formData = this.indexFormData(jsonData.forms)

    // TODO: replace with formData
    this.formActions = this.formatActionsData(jsonData.forms)
    this.hiddenFields = this.formatHiddenFieldsData(jsonData.forms)
  },

  mounted () {
    this.updatePanel()
  },

  methods: {
    setActiveTab (newId) {
      this.activeTab = newId
      this.updatePanel()
    },

    getPanelClasses (id) {
      return {
        'selector-panel': true,
        'active': (this.activeTab === id)
      }
    },

    updatePanel () {
      switch (this.activeTab) {
        case ('search-tyres-vehicle'):
          if (
            this.optionValues['make-tyres'] !== '' &&
            this.optionValues['year-tyres'] !== '' &&
            this.optionsData['model-tyres'].length === 0
          ) {
            this.loadSelectHandler('model-tyres')
          }

          if (
            this.optionValues['make-tyres'] !== '' &&
            this.optionValues['year-tyres'] !== '' &&
            this.optionValues['model-tyres'] !== '' &&
            this.optionsData['series-tyres'].length === 0
          ) {
            this.loadSelectHandler('series-tyres')
          }

          break

        case ('search-tyres-size'):
          if (this.optionsData['width-tyres'].length === 0) {
            this.loadSelectHandler('width-tyres')
          }

          if (
            this.optionValues['width-tyres'] !== '' &&
            this.optionsData['profile-tyres'].length === 0
          ) {
            this.loadSelectHandler('profile-tyres')
          }

          if (
            this.optionValues['width-tyres'] !== '' &&
            this.optionValues['profile-tyres'] !== '' &&
            this.optionsData['diameter-tyres'].length === 0
          ) {
            this.loadSelectHandler('diameter-tyres')
          }

          break
      }
    },

    onSelectChange (e) {
      let optionValues = this.optionValues
      optionValues[e.target.name] = e.target.value
      this.optionValues = optionValues
      this.updateSelection(e.target.name)
    },

    updateSelection (selectId) {
      switch (selectId) {
        case ('make-tyres'):
        case ('year-tyres'):
          // load model
          if (
            this.optionValues['make-tyres'] !== '' &&
            this.optionValues['year-tyres'] !== ''
          ) {
            this.loadSelectHandler('model-tyres')
          }
          this.clearSelect('model-tyres', 'series-tyres')
          break

        case ('model-tyres'):
          // load series
          if (
            this.optionValues['model-tyres'] !== '' &&
            this.optionValues['year-tyres'] !== ''
          ) {
            this.loadSelectHandler('series-tyres')
          }
          this.clearSelect('series-tyres')
          break

        case ('width-tyres'):
          // load tyre profiles
          if (
            this.optionValues['width-tyres'] !== ''
          ) {
            this.loadSelectHandler('profile-tyres')
          } else {
            this.clearSelect('profile-tyres', 'diameter-tyres')
          }
          break

        case ('profile-tyres'):
          // load tyre Diameter
          if (
            this.optionValues['width-tyres'] !== '' &&
            this.optionValues['profile-tyres'] !== ''
          ) {
            this.loadSelectHandler('diameter-tyres')
          } else {
            this.clearSelect('diameter-tyres')
          }
          break

        default:
          // do nothing
      }
    },

    loadSelectHandler (id) {
      switch (id) {
        case ('model-tyres'):
          this.loadSelect(
            'model-tyres',
            '/vehicles/models',
            {
              vehiclemakeids: this.optionValues['make-tyres'],
              year: this.optionValues['year-tyres']
            }
          )
          break

        case ('series-tyres'):
          this.loadSelect(
            'series-tyres',
            '/vehicles/vehicles',
            {
              modelid: this.optionValues['model-tyres'],
              year: this.optionValues['year-tyres']
            }
          )
          break

        case ('width-tyres'):
          this.loadSelect(
            'width-tyres',
            '/tyres/sizes/sectionwidths',
            {}
          )
          break

        case ('profile-tyres'):
          this.loadSelect(
            'profile-tyres',
            '/tyres/sizes/aspectRatios',
            {
              sectionwidth: this.optionValues['width-tyres']
            }
          )
          break

        case ('diameter-tyres'):
          this.loadSelect(
            'diameter-tyres',
            '/tyres/sizes/rimDiameters',
            {
              sectionwidth: this.optionValues['width-tyres'],
              aspectratio: this.optionValues['profile-tyres']
            }
          )
          break
      }
    },

    loadSelect (selectName, path, requestParams) {
      this.whichIsLoading[selectName] = true

      axios.get(this.apiDomain + path, {
        headers: { 'Authorization': 'Bearer ' + this.apiKey },
        params: requestParams
      })
      .then((resp) => {
        let optionsData = this.optionsData
        let whichIsLoading = this.whichIsLoading

        whichIsLoading[selectName] = false
        optionsData[selectName] = this.mapResults(resp)

        this.optionsData = optionsData
        this.whichIsLoading = whichIsLoading
      })
      .catch(err => {
        console.error(err)
        let whichIsLoading = this.whichIsLoading
        whichIsLoading[selectName] = false
        this.whichIsLoading = whichIsLoading
      })
    },

    mapResults (result) {
      let newData = []

      for (let item of result.data.Items) {
        if (typeof item === 'object') {
          newData.push({ label: item.Name, value: item.Id })
        } else {
          newData.push({ label: item, value: item })
        }
      }

      return newData
    },

    clearSelect (...selects) {
      let optionsData = this.optionsData
      let optionValues = this.optionValues

      for (let select of selects) {
        optionsData[select] = []
        optionValues[select] = ''
      }
      this.optionsData = optionsData
      this.optionValues = optionValues
    },

    setHover (id) {
      this.currentHover = id
    },

    formatHiddenFieldsData (data) {
      let formattedData = {}

      for (let form of data) {
        formattedData[form.id] = form.hiddenFields
      }

      return formattedData
    },

    formatActionsData (data) {
      let formattedData = {}

      for (let form of data) {
        formattedData[form.id] = form.actionFormat
      }

      return formattedData
    },

    formatFormAction (id) {
      let action = this.formActions[id]

      // run format
      for (let key of Object.keys(this.optionValues)) {
        // value
        action = action.replace('[' + key + ':value]', this.cleanValue(this.optionValues[key]))

        // label
        if (this.optionValues[key] !== '') {
          for (let options of this.optionsData[key]) {
            if (options.value.toString() === this.optionValues[key]) {
              action = action.replace('[' + key + ':label]', this.cleanValue(options.label))
            }
          }
        } else {
          action = action.replace('[' + key + ':label]', '')
        }
      }

      return action
    },

    indexFormData (data) {
      let formattedData = {}

      for (let form of data) {
        formattedData[form.id] = form
      }

      return formattedData
    },

    cleanValue (value) {
      value = value.toString()
      value = value.replace(/[ \-.\\<>/'"]/g, '_') // replace bad chars with "_" char
      value = value.replace(/(_){2,}/g, '_') // replace more then one "_" in a row
      return value
    }
  }
}

</script>
