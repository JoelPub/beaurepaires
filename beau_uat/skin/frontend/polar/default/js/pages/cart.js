"use strict";

function gocheckout() {
	var $cartForm = jQuery('.shopping-cart-options'),
		$availableTimesSelect =  jQuery('.available-times'),
		$fitDate = jQuery('.fit_date'),
		checkoutUrl = $cartForm.attr('data-checkout-url'),
		storeLocVal = jQuery('#cartaddress-id').val(),
		dateVal = $fitDate.val(),
		availableTimesVal = $availableTimesSelect.val();

	if ( storeLocVal == '' || dateVal == '' || availableTimesVal == '' || availableTimesVal == null ) {
		// missing required values, show errors
		jQuery('.cart-fields-form').submit(); // submit forces error check
	}
	else {
		// required values set, save session and load checkout when ready
		$cartForm.on('session-saved', function() {
			window.location = checkoutUrl;
		});

		cartApp.saveSession(true);
	}
}


function savesessiondate() {
	ajaxdate();
	cartApp.getAvailableTimes();
}

function ajaxdate() {
	var posturl = '/pickupinstore/index/save';
	var storeloc = jQuery('#cartaddress-id').val();
	var ddate = jQuery('.fit_date').val();
	var dtime = jQuery('.available-times').val();
	var duration = jQuery('.fit-duration').val();
	var sessionDateError = jQuery('.sessiondate-error');

	if (/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/.test(ddate)) {
		cartApp.updateSpinnerDiv('#savesessiondate', true, 'Saving...'); //jQuery('#savesessiondate').show();

		jQuery.post(posturl, {ddate: ddate, storeloc: storeloc, dtime: dtime, duration: duration}, function (result) {
			cartApp.updateSpinnerDiv('#savesessiondate', false, 'Date selected'); // 	jQuery('#savesessiondate').hide();
		});
	}
	else {
		cartApp.updateSpinnerDiv('#savesessiondate', false, '');
		sessionDateError.show();
	}
}
;



// New cart!

var cartApp = {};

(function($){
    $(function(){

		cartApp = {

			'saveSession': function( gocheckout ) {
				var $this = this,
					posturl = '/pickupinstore/index/save',
					storeloc = $('#cartaddress-id').val(),
					ddate = $('.fit_date').val(),
					dtime = $('.available-times').val(),
					duration = $('.fit-duration').val(),
					$cartForm = $('.shopping-cart-options'),
					$checkoutButton = $('.btn-proceed-checkout');

				if (storeloc != "") {

					var handleSuccess = function() {
						$this.updateSpinnerDiv('#savesession', false, 'Store selected');
						$cartForm.trigger('session-saved');
					}

					$this.updateSpinnerDiv('#savesession', true, 'Saving...'); //jQuery('#savesession').show();

					var requestData = { ddate: ddate, storeloc: storeloc, dtime: dtime, duration: duration };

					var request = $.ajax({
						url: posturl,
						data: requestData,
						type: 'POST',
						success: handleSuccess
					});

					if (!gocheckout) {
						cartApp.getAvailableTimes();
					}
					else {
						$checkoutButton
							.html('<i class="fa fa-refresh fa-spin"></i> Loading...')
							.prop('disabled', true);
					}

				}

                if( !gocheckout ) {
                    $this.getStockLevels();
                }
			},

			'getAvailableTimes': function() {
				var fitDateInput = $('input.fit_date'),
					addressInput = $('input.cartaddress-id'),
					durationInput = $('input.fit-duration'),
					timeSelect = $('.available-times'),
					dataselected = timeSelect.attr('data-select');

				// private function for padding zeros on dates
				function pad(str, max) {
					str = str.toString();
					return str.length < max ? pad("0" + str, max) : str;
				}

				// both date and store most be set
				if (fitDateInput.val() != '' && addressInput.val() != '' && durationInput.val() != '') {

					var handleSuccess = function(data) {
						var optionCount = 0;

						timeSelect.removeClass('loading');

						if (typeof data.unavailable != 'undefined') {
							for (var h = 0; h < 24; h++) {

								for (var m = 0; m < 60; m += 15) {

									var timeVal = pad(h, 2) + pad(m, 2),
										timeInt = parseInt(timeVal),
										isAvailable = true;

									for (var b = 0, l = data.unavailable.length; b < l; b++) {
										var timeSplit = data.unavailable[b].split('-');

										if (timeInt >= parseInt(timeSplit[0]) && timeInt <= parseInt(timeSplit[1])) {
											isAvailable = false;
											break;
										}
									}

									if (isAvailable) {

										// format time, eg: 9:00am, 1:30pm
										if (h > 12) { // PM
											var formatedTime = (h - 12) + ':' + pad(m, 2);
										}
										else { // AM
											var formatedTime = h + ':' + pad(m, 2);
										}

										if (h > 11) { // PM
											formatedTime += ' pm';
										}
										else { // AM
											formatedTime += ' am';
										}

										// create time option
										var optionElem = jQuery('<option/>')
												.html(formatedTime)
												.val(pad(h, 2) + ':' + pad(m, 2))
												.appendTo(timeSelect);

										optionCount++;
									}
								}

							}

							timeSelect.val(dataselected);
						}

						if (optionCount == 0) { //  if no option found then create "None" option
							var optionElem = jQuery('<option/>')
									.html('None')
									.val('')
									.appendTo(timeSelect);

						}
						else if ( timeSelect.val() == null ) { // if options but not selected then select first
							timeSelect.val( timeSelect.find('option:first').val() );
						}
					}

					timeSelect.html(''); // This is important.
					timeSelect.addClass('loading');


					var requestUrl = timeSelect.attr('data-get-times-url');
					var requestData = {
						store_id: addressInput.val(),
						date: fitDateInput.val(),
						for_minutes: durationInput.val()
					}


					// send request to get avilable times
					var request = jQuery.ajax({
						type: 'GET',
						url: requestUrl,
						data: requestData,
						dataType: 'json',
						cache: false,
						success: handleSuccess,
						error: function (a, b) {
							console.error('request failed', a, b);
						}
					});

				}

			},

			'updateSpinnerDiv': function(id, spinner, message) {
				var spinnerDiv = $(id),
					spinnerHtml = '';

				if (spinner) {
					spinnerHtml = '<i class="fa fa-refresh fa-spin"></i> ';
				}

				spinnerHtml += message;
				spinnerDiv.html(spinnerHtml).show();
			},

			'getStockLevels': function() {
				var $this = this,
					arrSku = [],
					storeId,
					requestParams = {},
					$cartAddress = $('#cartaddress-id'),
					$stockStatus = $('.stock-status'),
					$mobileStockStatus = $('.mobile-stock-status'),
					$stockErrorMessage = $('#stock-error-message'),
					$proceedButton = $('.btn-proceed-checkout'),
					$stockErrorMessage = $('#stock-error-message'),
					stockLevelsUrl = $('.all-items-in-cart').data('stock-levels-url');

				if ( $cartAddress != '' && $cartAddress.val() && $stockStatus.length ) {

					// private function: on request seccess
					var requestSuccess = function(response) {
						var isOutOfStock = false;
						var data = response.data;

						if ( response.success === false ) {
							isOutOfStock = true;

							$stockErrorMessage
								.empty()
								.append( $('<p/>').html(response.error) )
								.show();
						}

						// update datepicker with holidays
						$this.createDatePicker(response.publicHoliday)

						// loop through data
						for( var i=0,n=data.length; i<n; i++ ) {
							var selectorDesktop = ('#stock-status-'+data[i].sku+'__'+data[i].item+'__'+data[i].qty+'__'+data[i].sapcode).replace( /\//g, "\\/" );
							var selectorMobile = ('#mobile-stock-status-'+data[i].sku+'__'+data[i].item+'__'+data[i].qty+'__'+data[i].sapcode).replace( /\//g, "\\/" );

							var $status = $(selectorDesktop),
								$statusMobile = $(selectorMobile);

							if ($status.length === 0 && $statusMobile.length === 0) {
								console.error('Error: stock item not found: ', selectorDesktop, selectorMobile);
								return false;
							}

							$status.removeClass('status-loading');
							$statusMobile.removeClass('status-loading');

							// 1. create new status element
							var $newStatus = $('<div />', {
								'class': 'stock-status',
								'id': $status.attr('id'),
								'html': data[i].message
							});

							var $newMobStatus = $('<span />', {
								'class': 'mobile-stock-status',
								'id': $statusMobile.attr('id'),
								'html': data[i].message
							});

							// 2. replace old status with new one
							$status.replaceWith($newStatus);
							$statusMobile.replaceWith($newMobStatus);

							// 3. add tooltip if there is a status messaage
							if ( data[i].status != '' ) {
								$newStatus
									.addClass('has-tip')
									.attr('title', data[i].status)
									.attr('data-tooltip','')
									.attr('aria-haspopup', true);

								$newMobStatus
									.addClass('has-tip')
									.attr('title', data[i].status)
									.attr('data-tooltip','')
									.attr('aria-haspopup', true);
							}

							// 4. is out of stock?
							if ( data[i].message == 'Out of Stock' ) {
								isOutOfStock = true;
								$newStatus.addClass('stock-error');
								$newMobStatus.addClass('stock-error');
							}

						}

						// show errors on any outstading items
						$('.status-loading')
							.empty()
							.removeClass('status-loading')
							.append(
								$('<i/>').addClass('fa fa-exclamation-triangle').attr('aria-haspopup', true)
							)

						// re-cache status elements
						$stockStatus = $('.stock-status');
						$mobileStockStatus = $('.mobile-stock-status');

						// re-run tooltips
						$(document).foundation();
						$(document).foundation('tooltip', 'reflow');

						// update card status
						if( isOutOfStock ) {
					    	$stockErrorMessage.show();
					    	$proceedButton.prop('disabled', true);
					    }
						else {
							$stockErrorMessage.hide();
					    	$proceedButton.prop('disabled', false);
						}
					}

					$stockErrorMessage
						.empty()
						.hide();

					// get array of all SKUs in cart
					for ( var i=0, n=$stockStatus.length; i<n; i++ ) {
						arrSku.push( $($stockStatus[i]).attr('id') );
					}

					// added loading
					$stockStatus.removeClass('has-tip stock-error').addClass('status-loading').empty().append(
						$('<i>').addClass('fa fa-refresh fa-spin')
					);
					$mobileStockStatus.removeClass('has-tip stock-error').addClass('status-loading').empty().append(
						$('<i>').addClass('fa fa-refresh fa-spin')
					);

					// set request params
					requestParams = {
						'store_id': $cartAddress.val(),
						'sku': arrSku
					}

					// send request
					var request = jQuery.ajax({
						type: 'GET',
						url: stockLevelsUrl,
						cache: false,
						data: requestParams,
						dataType: 'json',
						success: requestSuccess
					});

				}
			},
			'applyCoupon': function() {
				var $form = $('#discount-coupon-form'),
					$couponCode = $('#coupon_code'),
					$alertError = $('#coupon_alert_error'),
					postUrl = $form.attr('action'),
					seccessUrl = $form.data('seccess-url');
				if ( $couponCode.length ) {
					// private function: handle results
					var handleResults = function(result) {

						if ( result.valid == 'failed' ) {
							$alertError.html(result.message);
						}
						else {
							location.href = seccessUrl;
						}
					}

					// format request data
					var requestData = {
						coupon_code: $couponCode.val()
					}

					// send requst
					var request = $.ajax({
						url: postUrl,
						type: 'POST',
						cache: false,
						data: requestData,
					}).done(handleResults);
				}
			},
			'clearCouponError': function( $input ) {
				var  $alertError = $('#coupon_alert_error');

				if ( $input.val() == '' ) {
					$alertError.empty();
				}
			},
			'updateProduct': function( $link ) {
				var productId = $link.data('product_id'),
					$productElem = $('#item-'+productId),
					productValue = $productElem.val(),
					requestUrl = $link.data('update-url')+productValue;
				if ( parseInt(productValue) ) {

					var handleResults = function() {
						window.location.reload();
					}

					var request = $.ajax({
						url: requestUrl,
						type: 'GET'
					}).done(handleResults);
				}
			},

			'createDatePicker': function(holidayData) {
				var $fitDatePicker = $('.fit_date'),
					holidayData = holidayData || [],
					holidayIndexed = {};

				if ($fitDatePicker.length) {

					// function: check for holidays
					var disableSpecificDates = function (date) {
				    var thisDate = jQuery.datepicker.formatDate('yy-mm-dd', date);
						if (holidayIndexed.hasOwnProperty(thisDate)) {
							return [false, 'date-holiday', holidayIndexed[thisDate].title]
						} else {
							return [true]
						}
				  }

					// index holiday data
					for (var i=0; i < holidayData.length; i++) {
						if (holidayData[i].is_available === "0") {
							holidayIndexed[holidayData[i].date] = holidayData[i]
						}
					}

					// remove old datepick (if any)
					if ($fitDatePicker.hasClass('hasDatepicker')) {
						$fitDatePicker.datepicker( "destroy" );
						$fitDatePicker.removeClass("hasDatepicker").removeAttr('id');
					}

					// create new datepick
					$fitDatePicker.datepicker({
						minDate: $fitDatePicker.attr('data-min-date'),
						dateFormat: 'dd/mm/yy',
						beforeShowDay: disableSpecificDates
					});
				}
			},

			'init': function() {

				// *** on load functions ***
				this.getStockLevels();


				// *** bind events ***

				// cache
				var $this = this,
					$sessionDate = $('#sessiondate'),
					$availableTimes = $('.available-times'),
					$btnProceedCheckout = $('.btn-proceed-checkout'),
					$cartAddress = $('#cartaddress'),
					$cartAddressId = $('.cartaddress-id'),
					$saveSession = $('#savesession'),
					$cartFieldsForm = $('.cart-fields-form'),
					$applyCoupon = $('.apply_coupon'),
					$cancelCoupon = $('.cancel_coupon'),
					$enterCoupon = $('#discount-coupon-form'),
					$couponCode = $('#coupon_code'),
					$qty = $('.qty'),
					$linkEdit = $('.link-edit');

				// wraps
				var focusAddressId = function() { $cartAddress.focus(); };
				var focusAddress = function() {
					$cartAddress.val('');
					$cartAddressId.val('');
					$saveSession.hide();
				}
				var submitFieldsForm = function() { return false; }
				var applyCoupon = function(event) { event.preventDefault(); $this.applyCoupon(); }
				var clearCouponError = function() { $this.clearCouponError( $couponCode ); }
				var enterOnQty = function(event) {
					var $keycode = (event.keyCode ? event.keyCode : event.which),
						$update = $(".btn-update");

					if($keycode == '13'){
						$update.trigger('click');
						return false;
					}
				}
				var enterCoupon = function(event) {
					var $keycode = (event.keyCode ? event.keyCode : event.which),
						$update = $(".apply_coupon");

					if($keycode == '13'){
						$update.trigger('click');
						return false;
					}
				}
				var updateProduct = function() { $this.updateProduct( $( this ) ); };

				var cancelCouponCode = function() {
					$('#remove-coupone').val(1);
					$('#discount-coupon-form').submit();
				}

				// create datepicker
				this.createDatePicker();

				// binds
				$sessionDate.on('change', savesessiondate);
				$availableTimes.on('change', ajaxdate);
				$btnProceedCheckout.on('click', gocheckout);
				$cartAddressId.on('focus', focusAddressId);
				$cartAddress.on('focus', focusAddress);
				$cartFieldsForm.on('submit', submitFieldsForm);
				$applyCoupon.on('click', applyCoupon);
				$enterCoupon.on('keypress', enterCoupon);
				$cancelCoupon.on('click', cancelCouponCode);
				$couponCode.on('blur', clearCouponError);
				$qty.on('keypress', enterOnQty);
				$linkEdit.on('click',updateProduct);

			}

		}

		cartApp.init();

	});
})(jQuery);



jQuery(function () {

	var timeSelect= jQuery('.available-times'),
		datataselected = timeSelect.attr('data-select');

		if(datataselected!='') {
			cartApp.getAvailableTimes();
		}

	// var disableddates = ['2017-05-24', '11/05/2017'];
	//
	// function DisableSpecificDates(date) {
  //   var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
	// 	console.log('date', date);
  //   return [disableddates.indexOf(string) == -1, 'css-class-to-highlight', 'tooltip text'];
  // }
	//
	// fitDate.datepicker({
	// 	minDate: fitDate.attr('data-min-date'),
	// 	dateFormat: 'dd/mm/yy',
	// 	beforeShowDay: DisableSpecificDates
	// });

	var cartAddress = jQuery('#cartaddress'),
		cartAddressId = jQuery('.cartaddress-id'),
		storesLoading = jQuery('#storesLoading');


	// This sets up the autocomplete for select a store
	// It uses google maps API to guess a lat and lng for whatever customer enters
	// then Ajax calls something like /pickupinstore/index/storesbylatlng/?lat=123&lng=456 to get the 5 closest stores to that lat/lng

	var placeIdCache;
	var storesCache;
	var geocoder = new google.maps.Geocoder();
	var service = new google.maps.places.AutocompleteService();
	var pacInput = document.getElementById('cartaddress');

	cartAddress.autocomplete({
		source: function (request, response) {
			storesLoading.show();

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

							jQuery.ajax({
								url: '/pickupinstore/index/storesbylatlng/',
								dataType: 'json',
								data: {
									lat: lat,
									lng: lng
								},
								success: function (data) {
									storesCache = data;
									storesLoading.hide();
									response(data); // autocomplete callback?
								}
							});
						});
					}
					else {
						storesLoading.hide();
						response(storesCache);
					}

				}

			});
		},
		minLength: 4,
		select: function (event, ui) {
			if (ui.item) {
				cartAddressId.val(ui.item.id);
				cartApp.saveSession();
			}
			else {
				cartAddressId.val('');
			}
		},
	});


});



// Prototype code
// For coupons
var discountForm = new VarienForm('discount-coupon-form');
discountForm.submit = function (isRemove) {
    if (isRemove) {
        $('coupon_code').removeClassName('required-entry');
        $('remove-coupone').value = "1";
    } else {
        $('coupon_code').addClassName('required-entry');
        $('remove-coupone').value = "0";
    }
    return VarienForm.prototype.submit.bind(discountForm)();
}
