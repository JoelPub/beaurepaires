import $ from 'jquery'

/*
  Filter Panel - CDP
*/

class FilterPanel {
  constructor () {
    if (!$('#product-filter-panel').length) return
    this.$panel = $('#product-filter-panel')
    this.$filterHeader = this.$panel.find('[data-filter-accordion-nav]')
    this.$filterContent = this.$panel.find('[data-filter-accordion-content]')
    this.$categoryTitle = this.$panel.find('.categories .category-title')
    this.$checkboxes = this.$panel.find('input[type="checkbox"]')
    this.$applyFilterBtn = this.$panel.find('button')
    this.$filterHeader.on('click', this.handleFilterAccordion)
    this.$categoryTitle.on('click', this.handleCategoryAccordion)
    this.$checkboxes.on('change', this.handleCheckboxChange)
  }

  handleFilterAccordion = () => {
    this.$panel.toggleClass('active')
  }

  handleCategoryAccordion () {
    $(this).next('.category-content').toggleClass('active')
  }

  handleCheckboxChange = () => {
    let checkedCount = this.$panel.find('input[type="checkbox"]:checked').length
    console.log(checkedCount)
    if (checkedCount) {
      this.$applyFilterBtn.prop('disabled', false)
    } else {
      this.$applyFilterBtn.prop('disabled', true)
    }
  }
}

export default new FilterPanel()
