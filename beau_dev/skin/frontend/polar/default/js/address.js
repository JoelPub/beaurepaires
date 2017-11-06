var ADDRESSES = ADDRESSES || {};

(function($){
  'use strict';

  var root,
    addressRequest = null;

  ADDRESSES = {
    isOn: true,
    cache: {
      $form: null,
      $streetField: null,
      $cityField: null,
      $postcodeField: null
    },
    fieldDataMap: {
      street: {
        field: '$streetField',
        data: 'street',
        charsCount: 3
      },
      city: {
        field: '$cityField',
        data: 'city',
        charsCount: 3
      },
      postcode: {
        field: '$postcodeField',
        data: 'zip',
        charsCount: 1
      }
    },
    init: function(){
      // Init paths
      root = this;

      //address-validtion
      this.validateAddress();
    },
    validateAddress: function() {
      root.cache.$form = $('.address-validtion');

      if ( root.cache.$form.length ) {
        root.cache.$streetField = root.cache.$form.find('.validate-street');
        root.cache.$cityField = root.cache.$form.find('.validate-city');
        root.cache.$postcodeField = root.cache.$form.find('.validate-postcode');

        var streetChange = function() { root.getResults('street'); },
          cityChange  = function() { root.getResults('city'); },
          postcodeChange  = function() { root.getResults('postcode'); }

        var fieldBlur = function() {
          setTimeout(function(){
            root.reset();
          }, 200);
        }

        root.cache.$streetField.on('keyup', streetChange).on('blur',fieldBlur);
        root.cache.$cityField.on('keyup', cityChange).on('blur',fieldBlur);
        root.cache.$postcodeField.on('keyup', postcodeChange).on('blur',fieldBlur);
      }
    },
    getResults: function(level) {
      var $activeInput = root.cache['$'+level+'Field'],
        $activeParent = $activeInput.parent(),
        url = root.cache.$form.data('address-url'),
        data = {};

      var handleSuccess = function(data) {
        var results = [];

        $activeParent.removeClass('loading');

        if ( data.data ) {
          results = data.data;
        }

        root.createSuggestPanel(results, level, $activeParent);
      }

      var handleError = function() {
        // nothing
      }

      if ( root.isOn && $activeInput.val().length >= root.fieldDataMap[level].charsCount ) {

        switch(level) {
          case('postcode'):
            data.postcode = root.cache.$postcodeField.val();

          case('city'):
            data.city = root.cache.$cityField.val();

          case('street'):
          default:
            data.street = root.cache.$streetField.val();
        }

        $activeParent.addClass('loading');

        if ( addressRequest != null ) {
          addressRequest.abort();
        }

        addressRequest = $.ajax({
            type: 'GET',
            dataType: 'JSONP',
            data: data,
            url: url,
            success: handleSuccess,
            error: handleError
        });
      } else {
        root.reset();
        if ( $activeInput.val().length == 0 ) {
          root.isOn = true;
        }
      }
    },
    createSuggestPanel: function( data, level, $attachToMe ) {
      var $suggestPanel = $('<ul />').addClass('address-suggest'),
        dataKey = root.fieldDataMap[level].data;

      var onClick = function() {
        $attachToMe.find('input').val( $(this).html() );
        $suggestPanel.remove();
      }

      var switchManual = function() {
        root.isOn = false;
        root.reset();
      }

      for (var i = 0, count = data.length; i < count; i++) {
        var $li = $('<li />')
          .html( data[i][dataKey] )
          .attr('tabindex',1)
          .on('click', onClick)
          .appendTo($suggestPanel);
      }

      var manualOption = $('<li />')
        .html('<strong>Enter Manually</strong>')
        .attr('tabindex',1)
        .on('click',switchManual)
        .appendTo($suggestPanel);

      $('.address-suggest').remove();
      $suggestPanel.appendTo($attachToMe);
    },
    reset: function() {
      $('.address-suggest').remove();
      $('.address-validtion .loading').removeClass('loading');
      if ( addressRequest != null ) {
        addressRequest.abort();
      }
    }
  };

  $(function(){
    ADDRESSES.init();
  });

})(jQuery);
