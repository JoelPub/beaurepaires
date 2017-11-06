"use strict";

var windowLoadedFlag = false;
window.onload = function () {
	windowLoadedFlag = true;
};
					
// use: jQueryWaiter.execute(function(){ // safe jQuery code here });
var jQueryWaiter = function () {
	var functions = [];
	var timer = function() {
		if( typeof window.jQuery != 'undefined') {
			while (functions.length) {
				functions.shift()(window.jQuery);
			}
		} else {
			window.setTimeout(timer, 100);
		}
	};
	timer();
	return {
		execute: function(onJQueryReady) {
			if (window.jQuery) {
				onJQueryReady(window.jQuery);
			} else {
				functions.push(onJQueryReady);
			}
		}
	};
}();

window.SgyIAS = {
	debug: false,
	_log: function(object) {
		if(this.debug) {
			console.log(object);
		}
	},
	init: function(){
		jQuery.getScript(lazyValues.scriptUrl, function() {
				jQuery(function($) {
					var config = {
						item: lazyValues.mode,
						container : lazyValues.container,
						next: lazyValues.next,
						pagination: lazyValues.pagination,
						delay: 600,
						negativeMargin: lazyValues.negativeMargin,
						history: {
							prev: '.prev'
						},
						noneleft: {
							text: lazyValues.noneleftText,
							html: '<div class="ias-noneleft text-center">{text}</div>'
						},
						spinner: {
							src: lazyValues.loaderImage,
							html: '<div class="ias-spinner text-center"> <i class="fa fa-refresh fa-spin fa-lg"></i> '+lazyValues.loadingText+'</div>'
						},
						trigger: {
							text: lazyValues.triggerText,
							html: '<div class="ias-trigger ias-trigger-next text-center"><span>{text}</span></div>',
							textPrev: lazyValues.triggerTextPrev,
							htmlPrev: '<div class="ias-trigger ias-trigger-prev text-center"><span>{text}</span></div>',
							offset: lazyValues.triggerOffset
						}
					};

					if (window.ias_config){
						$.extend(config, window.ias_config);
					}

					SgyIAS._log({extension: 'ias', config: config});
					window.ias = $.ias(config);

					SgyIAS._log({extension: 'paging'});
					window.ias.extension(new IASPagingExtension());

					SgyIAS._log({extension: 'spinner'});
					window.ias.extension(new IASSpinnerExtension(config.spinner));

					SgyIAS._log({extension: 'noneleft'});
					window.ias.extension(new IASNoneLeftExtension(config.noneleft));

					SgyIAS._log({extension: 'trigger'});
					window.ias.extension(new IASTriggerExtension(config.trigger));

					if ( lazyValues.isMemoryActive ) {
						SgyIAS._log({extension: 'history'});
						window.ias.extension(new IASHistoryExtension(config.history));
					}

					// debug events
					window.ias.on('scroll', function(scrollOffset, scrollThreshold){
						SgyIAS._log({eventName: 'scroll', scrollOffset: scrollOffset, scrollThreshold: scrollThreshold});
					});
					window.ias.on('load', function(event){
						SgyIAS._log({eventName:'load', event: event});
					});
					window.ias.on('loaded', function(data, items){
						SgyIAS._log({eventName: 'loaded', data: data, items: items});
						
						setTimeout(function(){ 
                            // reload tooltips for new content
							$(document).foundation('tooltip', 'reflow');
                            // update price formating on new content
                            jQuery('body').trigger('update-price');
						}, 100);

					});
					window.ias.on('render', function(items){
						SgyIAS._log({eventName: 'render', items: items});
					});
					window.ias.on('rendered', function(items){
						SgyIAS._log({eventName: 'render', items: items});
						// Kick off foundation equalizer when new Dom element render
						Foundation.libs.equalizer.reflow();
					});
					window.ias.on('noneLeft', function(){
						SgyIAS._log({eventName: 'noneLeft'});
					});
					window.ias.on('next', function(url){
						SgyIAS._log({eventName: 'next', url: url});
					});
					window.ias.on('ready', function(){
						SgyIAS._log({eventName: 'ready'});
					});
					
					if ( lazyValues.hideToolbar ) {
						$(lazyValues.toolbarSelector).hide();
					}
					else {
						$(lazyValues.toolbarSelector).show();
					}

					if(windowLoadedFlag){
						$(window).load();
					}
                    
					SgyIAS._log('Done loading IAS.');

				});
		});
	}
};
// this will prevent executing the infinite scroll before jQuery is loaded even when jQuery is loaded after this code
jQueryWaiter.execute(function(){
	SgyIAS.init();
});
