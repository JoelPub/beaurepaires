"use strict";

var checkoutApp = {};

var billing, billingForm;
				
(function($){ 
    $(function(){
		
		checkoutApp = {
			'cache': {
				'$guestCheckbox': 		$('.checkoutGuestCheckbox'),
				'$checkoutSetMethod': 	$('.checkout-set-method'),
				'$billingSaveButton':	$('.billing-save'),
				'$createAccountButton': $('.create_an_account'),
				'$loginRegister': 		$('.login-register'),
				'$loginGuest': 			$('.login-guest'),
				'$customerPassword': 	$('#register-customer-password'),
				'$coBillingForm':	 	$('#co-billing-form')
			},
			
			'guestCheckout': function() {
				var $loginRegisterRadio = $('.login-register'),
					$loginGuestRadio = $('.login-guest');
				
				if ( checkoutApp.cache.$guestCheckbox.prop('checked') ) {
					$loginRegisterRadio.prop('checked',false);
					$loginGuestRadio.prop('checked',true);
				}
				else {
					$loginRegisterRadio.prop('checked',false);
					$loginGuestRadio.prop('checked',false);
				}
			},
			
			'guestRegister': function() {
				var $this = checkoutApp;
					
				if ( $this.cache.$createAccountButton.prop('checked') ) {
					$this.cache.$loginRegister.prop('checked', true);
					$this.cache.$loginGuest.prop('checked', false);
					$this.cache.$customerPassword.show();
				}
				else {
					$this.cache.$loginRegister.prop('checked', false);
					$this.cache.$loginGuest.prop('checked', true);
					$this.cache.$customerPassword.hide();
				}
			},
			
			'autoRegister': function() {
				var $this = checkoutApp,
					isStepPayment = $('form[data-checkout-step="payment"]').length,
					getAddress =  $this.cache.$coBillingForm.data('get-address'),
					saveBilling =  $this.cache.$coBillingForm.data('save-billing');
				
				billing = new Billing('co-billing-form', getAddress, saveBilling);
				billingForm = new VarienForm('co-billing-form');
				
				if ( isStepPayment ) {
					$this.cache.$loginRegister.prop('checked', false);
					$this.cache.$loginGuest.prop('checked', true);
					$this.cache.$guestCheckbox.prop('checked', true);
					checkout.setMethod();
					
					$this.guestRegister();
					
					billing.save();
				}
			},
			
			'init': function() {
				var $this = this;
				
				$this.autoRegister();
				
				// *** binds ***
				
				// wraps
				var guestCheckout = function() { $this.guestCheckout();  },
					checkoutSetMethod = function() { checkout.setMethod(); },
					guestRegister = function() { $this.guestRegister(); },
					billingSave = function() { billing.save(); }
				
				// binds
				$this.cache.$guestCheckbox.on('click', guestCheckout);
				$this.cache.$checkoutSetMethod.on('click', checkoutSetMethod);
				$this.cache.$createAccountButton.on('click', guestRegister);
				$this.cache.$billingSaveButton.on('click', billingSave);
			}
		}
		
		checkoutApp.init();
		
	}); 
})(jQuery); 

