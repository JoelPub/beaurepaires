import $ from 'jquery'

/*
  Enable button once checkbox is checked, check in styleguide page for example usage. It used in checkout page
*/

class ButtonCheckboxDependencies {
  constructor () {
    this.$checkOutBtn = $('[data-button-checkbox-dependencies]')
    this.$requiredCheckbox = $('[data-required-checkbox]')
    this.$checkOutBtn.prop('disabled', true)
    $('body').on('change', '[data-required-checkbox]', this.handleCheckBoxChange)
    this.check()
  }

  handleCheckBoxChange = () => {
    this.check()
  }

  check () {
    let $checkOutBtn = $('[data-button-checkbox-dependencies]')
    let allChecked = $('[data-required-checkbox]').not(':checked').length === 0
    if (allChecked) {
      $checkOutBtn.prop('disabled', false)
    } else {
      $checkOutBtn.prop('disabled', true)
    }
  }
}

export default new ButtonCheckboxDependencies()
