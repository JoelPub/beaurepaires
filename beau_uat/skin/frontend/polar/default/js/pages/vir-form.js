(function($){
  'use strict'
  
  var root,
      App = {
        init: function() {	
          // init path
          root = this
          
          // cache dom
          root.$postcode = $('#vir-postcode')
          root.$postcodeID = $('#vir-postcode-id')
          root.$postcodeLoading = $('#vir-postcode-loading')
          root.$bookingDate = $('#vir-bookingdate')
          root.$datepicker = $('.vir-datepicker')
          root.$availableTimes = $('#vir-available-times')
          root.$availableTimesLoading = $('#vir-available-times-loading')
          root.$fitDuration = $('#fit-duration')

          // global variables
          root.holidayIndexed = {}

          // bind events
          root.$postcode.on('focus', root.focusPoscode)
          root.$bookingDate.on('change', root.handleBookingDateChange)

          // render
          root.renderGooglePlace()
          root.createDatePicker()

          if (root.$availableTimes.attr('data-select') != '') {
            root.getAvailableTimes()
          }
        },
        focusPoscode: function() {
          root.$postcode.val('')
          root.$postcodeID.val('')
        },
        handleBookingDateChange: function() {
          root.getAvailableTimes()
        },
        getAvailableTimes: function() {
          var fitDateInput = root.$datepicker,
              addressInput = root.$postcodeID,
              durationInput = root.$fitDuration,
              timeSelect = root.$availableTimes,
              dataselected = timeSelect.attr('data-select')

          // both date and store most be set
				  if (fitDateInput.val() != '' && addressInput.val() != '' && durationInput.val() != '') {
            timeSelect.html(''); // This is important.

            var requestUrl = timeSelect.attr('data-get-times-url'),
                requestData = {
                  store_id: addressInput.val(),
                  date: fitDateInput.val(),
                  for_minutes: durationInput.val()
                }  

            root.$availableTimesLoading.show()
            
            // send request to get avilable times
            var handleSuccess = function(data) {
              root.$availableTimesLoading.hide()

              var optionCount = 0

              if (typeof data.unavailable != 'undefined') {
                for (var h = 0; h < 24; h++) {  
                  for (var m = 0; m < 60; m += 15) {  
                    var timeVal = root.padZero(h, 2) + root.padZero(m, 2),
                        timeInt = parseInt(timeVal),
                        isAvailable = true
  
                    for (var b = 0, l = data.unavailable.length; b < l; b++) {
                      var timeSplit = data.unavailable[b].split('-')  
                      if (timeInt >= parseInt(timeSplit[0]) && timeInt <= parseInt(timeSplit[1])) {
                        isAvailable = false
                        break
                      }
                    }
  
                    if (isAvailable) {  
                      // format time, eg: 9:00am, 1:30pm
                      if (h > 12) { // PM
                        var formatedTime = (h - 12) + ':' + root.padZero(m, 2);
                      } else { // AM
                        var formatedTime = h + ':' + root.padZero(m, 2);
                      }
  
                      if (h > 11) { // PM
                        formatedTime += ' pm';
                      } else { // AM
                        formatedTime += ' am';
                      }
  
                      // create time option
                      var optionElem = $('<option/>')
                          .html(formatedTime)
                          .val(root.padZero(h, 2) + ':' + root.padZero(m, 2))
                          .appendTo(timeSelect)

                      optionCount++
                    }
                  }  
                }
  
                timeSelect.val(dataselected)
              }
  
              if (optionCount == 0) { //  if no option found then create "None" option
                var optionElem = jQuery('<option/>')
                    .html('None')
                    .val('')
                    .appendTo(timeSelect);  
              } else if ( timeSelect.val() == null ) { // if options but not selected then select first
                timeSelect.val(timeSelect.find('option:first').val())
              }
            }
            var request = $.ajax({
              type: 'GET',
              url: requestUrl,
              data: requestData,
              dataType: 'json',
              cache: false,
              success: handleSuccess,
              error: function (a, b) {
                console.error('request failed', a, b)
              }
            })
          }
        },
        renderGooglePlace: function() {        
          // This sets up the autocomplete for select a store
          // It uses google maps API to guess a lat and lng for whatever customer enters
          // then Ajax calls something like /pickupinstore/index/storesbylatlng/?lat=123&lng=456 to get the 5 closest stores to that lat/lng
          var placeIdCache
          var storesCache
          var geocoder = new google.maps.Geocoder()
          var service = new google.maps.places.AutocompleteService()
          var pacInput = root.$postcode
          
          root.$postcode.autocomplete({
            source: function (request, response) {
              root.$postcodeLoading.show();
              
              service.getPlacePredictions({
                input: request.term,
                componentRestrictions: {country: 'au'}
              }, function (predictions, status) {        
                // *Callback from async google places call
                if (status != google.maps.places.PlacesServiceStatus.OK || predictions == null) {
                  // show that this address is an error
                  response(false);
                }
        
                // Show a successful return
                // pacInput.className = 'success';
                // pacInput.value = predictions[0].description - the first predicted address
                if (predictions != null) {
                  // If it's different to current predicted address
                  // geocode to get the lat and long
                  if (placeIdCache != predictions[0].place_id) {
                    placeIdCache = predictions[0].place_id;
                    geocoder.geocode({
                      'placeId': predictions[0].place_id
                    }, function (results, status) {
                      var lat = results[0].geometry.location.lat();
                      var lng = results[0].geometry.location.lng();
        
                      $.ajax({
                        url: '/pickupinstore/index/storesbylatlng/',
                        dataType: 'json',
                        data: {
                          lat: lat,
                          lng: lng
                        },
                        success: function (data) {
                          storesCache = data;
                          root.$postcodeLoading.hide();
                          response(data); // autocomplete callback?
                        }
                      });
                    });
                  } else {
                    root.$postcodeLoading.hide();
                    response(storesCache);
                  }
                }
              });
            },
            minLength: 4,
            select: function (event, ui) {
              if (ui.item) {
                root.$postcodeID.val(ui.item.id);
                //cartApp.saveSession();
              } else {
                root.$postcodeID.val('');
              }

              root.getAvailableTimes()
            },
          })
        },
        createDatePicker: function(holidayData) {
          var holidayData = holidayData || []
  
          if (root.$datepicker.length) {  
            // index holiday data
            for (var i=0; i < holidayData.length; i++) {
              if (holidayData[i].is_available === "0") {
                root.holidayIndexed[holidayData[i].date] = holidayData[i]
              }
            }
  
            // remove old datepick (if any)
            if (root.$datepicker.hasClass('hasDatepicker')) {
              root.$datepicker.datepicker( "destroy" );
              root.$datepicker.removeClass("hasDatepicker").removeAttr('id');
            }
            
            // create new datepick
            root.$datepicker.datepicker({
              minDate: root.$datepicker.attr('data-min-date'),
              dateFormat: 'dd/mm/yy',
              beforeShowDay: root.disableSpecificDates
            });
          }
        },
        disableSpecificDates: function(date) { // function: check for holidays
          var thisDate = $.datepicker.formatDate('yy-mm-dd', date)

          if (root.holidayIndexed.hasOwnProperty(thisDate)) {
            return [false, 'date-holiday', root.holidayIndexed[thisDate].title]
          } else {
            return [true]
          }
        },
        padZero: function(str, max) {
					str = str.toString()
					return str.length < max ? root.padZero("0" + str, max) : str
				}
      }

  $(function() {
    App.init()
  });
})(jQuery);