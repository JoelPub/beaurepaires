import $ from 'jquery'

// Header functions
export class HeaderClass {
  constructor (headerSelector) {
    this.$header = $(headerSelector)
    this.lastScrollPos = $(window).scrollTop()
  }

  init () {
    this.setHeaderState('full')

    const onScroll = () => { this.handleScroll() }
    $(window).on('scroll', onScroll)
  }

  setHeaderState (newState) {
    const headerStatesArr = [
      'header-state-full',
      'header-state-mini'
    ]
    this.$header.removeClass(headerStatesArr.join(' ')).addClass('header-state-' + newState)
  }

  handleScroll () {
    const thisScrollPos = $(window).scrollTop()

    if (thisScrollPos <= 58) { // scroll at top
      this.setHeaderState('full')
    } else if (thisScrollPos < this.lastScrollPos && thisScrollPos <= 172) { // scrolling up (near top)
      // Don't change within this range
    } else if (thisScrollPos === this.lastScrollPos) { // scrolled but didn't move (IE fix)
      // also no change
    } else if (thisScrollPos < this.lastScrollPos) { // scrolling up
      this.setHeaderState('mini')
    } else { // scrolling down
      this.setHeaderState('full')
    }

    this.lastScrollPos = thisScrollPos
  }

}
// End Header functions
