"use strict"; 

(function($) {
  
  $(function() {
    
    // Init Foundation with option allowing FOundation equalizer to work on Block grid
    $(document).foundation({
      equalizer: {
        equalize_on_stack: true
      }
    });

      var beauComponents = {
          'heroSlider': function (item) {
              $(item).slick({
                  infinite: true,
                  dots: true,
                  autoplay: true,
                  speed: 1200,
                  autoplaySpeed: 8000,
                  slidesToShow: 1,
                  slidesToScroll: 1
              });

          },
          'promoSlider': function (item) {
              $(item).slick({
                  infinite: true,
                  dots: true,
                  speed: 400,
                  slidesToShow: 3,
                  slidesToScroll: 3,
                  responsive: [
                      {
                          breakpoint: 680,
                          settings: {
                              slidesToShow: 2,
                              slidesToScroll: 2,
                              infinite: true,
                              dots: true
                          }
                      },
                      {
                          breakpoint: 480,
                          settings: {
                              slidesToShow: 1,
                              slidesToScroll: 1,
                              infinite: true,
                              dots: true
                          }
                      }
                  ]
              });
          },
          'productSlider': function (item) {
              $(item).slick({
                  infinite: true,
                  dots: true,
                  speed: 400,
                  slidesToShow: 4,
                  slidesToScroll: 4,
                  responsive: [
                      {
                          breakpoint: 680,
                          settings: {
                              slidesToShow: 3,
                              slidesToScroll: 3,
                              infinite: true,
                              dots: true
                          }
                      },
                      {
                          breakpoint: 480,
                          settings: {
                              slidesToShow: 2,
                              slidesToScroll: 2,
                              infinite: true,
                              dots: true
                          }
                      }
                  ]
              });
          },
          'detailProductSlider': function (item) {
              $(item).slick({
                  infinite: false,
                  dots: true,
                  speed: 400,
                  slidesToShow: 3,
                  slidesToScroll: 3,
                  responsive: [
                      {
                          breakpoint: 680,
                          settings: {
                              slidesToShow: 4,
                              slidesToScroll: 4,
                              infinite: true,
                              dots: true
                          }
                      },
                      {
                          breakpoint: 480,
                          settings: {
                              slidesToShow: 3,
                              slidesToScroll: 3,
                              infinite: true,
                              dots: true
                          }
                      }
                  ]
              });
          },
          'relatedProductSlider': function (item) {
              $(item).slick({
                  infinite: true,
                  dots: true,
                  speed: 400,
                  slidesToShow: 4,
                  slidesToScroll: 4,
                  responsive: [
                      {
                          breakpoint: 1000,
                          settings: {
                              slidesToShow: 3,
                              slidesToScroll: 3,
                              infinite: true,
                              dots: true
                          }
                      },
                      {
                          breakpoint: 769,
                          settings: {
                              slidesToShow: 2,
                              slidesToScroll: 2,
                              infinite: true,
                              dots: true
                          }
                      },
                      {
                          breakpoint: 480,
                          settings: {
                              slidesToShow: 1,
                              slidesToScroll: 1,
                              infinite: true,
                              dots: true
                          }
                      }
                  ]
              });
          },
          'galleryZoom': function (item, wrapper, imgZoom) {
              var $item = $(item),
                  $wrapper = $(wrapper),
                  wrapperHeight = $wrapper.outerHeight();
              // Init Zoom
              var initZoom = function () {
                    $(imgZoom).elevateZoom({
                    zoomType: "lens", 
                    lensShape : "round",
                    lensSize : 200
                  });
              };
              if (!Modernizr.touch && $(window).width() > 640) {
                  initZoom();
              }
              // Carousel
              $item.first().addClass('active');
              var imageEvent = function() {
                  $(this).addClass('active').parent().siblings().find(item).removeClass('active');
                  var $thisSrc = $(this).attr('src'),
                      newImage = '<img src="'+ $thisSrc +'" class="magnify-source expand" data-zoom-image="'+ $thisSrc +'" alt="">';
                  $wrapper.css('height', wrapperHeight).html(newImage);
                
                  if (!Modernizr.touch && $(window).width() > 640) {
                    initZoom();
                  }
              };
              $item.on('click', imageEvent);    
          },
          'scrollToName': function(from, to){
              function scrollToAnchor(aid){
                  var aTag = $("a[name='"+ aid +"']");
                  $('html, body').animate({scrollTop: aTag.offset().top}, 'slow');
              }
              $(from).on('click', function() {
                  scrollToAnchor(to);
              });
          },
          'toggleShow': function(item, itemToReveal) {
              $(item).on('click', function() {
                  $(itemToReveal).toggle(0);
              });
          },
          'accordion': function(target, content){
              var toggle = function() {
                  var $this = $(this);
                  $this.toggleClass('active').next(content).toggle(0);
              }
              $(target).on('click', toggle);
          },
          'datepicker': function(btn, input){
              $(btn).on('click', function(){
                  $(input).datepicker().focus();
              });
          },
		   'htmlShim': function(){
				$.setCustomInputTypeValidator( "Date", "Please enter a valid date", function() {
					var pattern = /^\d{2,4}(-|\/)\d{2,4}(-|\/)\d{2,4}$/i;
					return !$( this ).val() || pattern.test( $( this ).val() );
				}, function( control ) {
					control.isShimRequired() && control.degrade().boundingBox.datepicker({ 
						dateFormat: 'dd/mm/yy' 
					});
				});
          },
          // Forcing font-size to increase to 16px on forms' element for touch screen device in order to avoid the mobile auto-zoom to happen 
          'formTouchScreen': function(item){
              if (Modernizr.touch) {
                  $(item).css('font-size', '16px');
              }
          },
          'init': function () {
              beauComponents.heroSlider('.hero-slider');
              beauComponents.promoSlider('.promo-slider');
              beauComponents.productSlider('.product-slider');
              beauComponents.detailProductSlider('.detail-product-slider');
              beauComponents.relatedProductSlider('.related-product-slider');
              beauComponents.galleryZoom('.item-to-append', '.main-photo-wrapper', '.magnify-source');
              beauComponents.scrollToName('.add-review', 'add-review');
              beauComponents.scrollToName('.customer-reviews', 'customer-reviews');
              beauComponents.toggleShow('.js-toggle-click', '.top-search-form, .top-link-inner-link');
              beauComponents.accordion('.filterdt', '.filtercont');
              beauComponents.formTouchScreen('input, textarea, option, select');
              beauComponents.datepicker('.select_date', '.fit_date');
			  beauComponents.htmlShim();
          }
      };

      // Initialising beauComponents
      beauComponents.init();
  });

})(jQuery);