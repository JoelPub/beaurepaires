import $ from 'jquery'
import 'slick-carousel'

class HomeObj {
  init () {
    this.staticLandingSelect('.home-static-landing')
    this.homePromoBars('.home-promo-bars-static')
    this.testimonialsSlider('.home-testimonials .slider')
  }

  staticLandingSelect (elem) {
    const $select = $('select', elem)

    if ($select.length === 0) { return false }

    const goToSelected = () => {
      const $selected = $select.find('option:selected')
      if ($selected.length === 1 && $selected.attr('value') !== undefined && $selected.attr('value') !== '') {
        window.location.href = $selected.attr('value')
      }
    }

    $select.on('change', goToSelected)
    $('button', elem).on('click', goToSelected)
  }

  homePromoBars (elem) {
    // Renders content for home-prom-bars (copied over from the on Goodyear site)
    // CMS content is not to be trusted!
    const $promoBarsHolder = $(elem)

    if ($promoBarsHolder.length) {
      const $bars = $promoBarsHolder.find('li:not(.rendered)')

      // loop through non-rendered bars
      for (let i = 0; i < $bars.length; i++) {
        let $bar = $bars.eq(i)
        const data = {
          title: $bar.find('h1, h2, h3, h4, p').text(),
          imageUrl: $bar.find('img').attr('src'),
          buttonUrl: $bar.find('a').attr('href'),
          buttonText: $bar.find('a').html()
        }

        // empty bar and render new contents
        $bar
          .empty()
          .css('background-image', 'url(' + data.imageUrl + ')')
          .append($('<h2 />').html(data.title))
          .append(
            $('<a />')
              .addClass('radius button')
              .attr('href', data.buttonUrl)
              .html(data.buttonText)
          )
          .addClass('rendered')
      }
    }
  }

  testimonialsSlider (elem) {
    $(elem).slick()
  }
}

// export
export default new HomeObj()
