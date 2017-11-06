
(function($){

	var root,
		beauComponents = {
		

			'getData': function (dataLabel, target, type) {
                var elem = $(target + '[data-' + dataLabel + ']'), result = false;
                if (elem.length) {

                    var dataVal = elem.attr('data-' + dataLabel);

                    switch (type) {
                        case 'url':
                            var regex = new RegExp("^(http[s]?:\\/\\/(www\\.)?|ftp:\\/\\/(www\\.)?|www\\.){1}([0-9A-Za-z-\\.@:%_\+~#=]+)+((\\.[a-zA-Z]{2,3})+)(/(.)*)?(\\?(.)*)?");
                            if (regex.test(dataVal)) {
                                result = dataVal;
                            }
                            break;

                        case 'string':
                            var regex = new RegExp(".+");
                            if (regex.test(dataVal)) {
                                result = dataVal;
                            }
                            break;

                        default:
                            result = dataVal;
                    }
                }
                return result;
            },

			'quickFinderSearch': {
	            'selects': {},
	            'requestDomain': '',
	            'requestKey': '',
	            'init': function (item) {
	                var quickFinderElem = $(item), $this = this; 
	                if (quickFinderElem.length) {

	                    // set domain for AJAX resquest
	                    $this.requestDomain = $this.getRequestDomain(item);

	                    // set API key for OAuth2
	                    $this.requestKey = $this.getRequestKey(item);

	                    // handle select ready (load defaults)
	                    quickFinderElem.find('select').each(function () {
	                        switch ($(this).attr('name')) {
	                            case('width-tyres'):
	                                // load widths
	                                $this._getRequest('/tyres/sizes/sectionwidths', {}, $('select[name="width-tyres"]'));
	                                break;
	                        }
	                    })


	                    // run onload for customer vehicle details
	                    var makeTyres = quickFinderElem.find('select.make-tyres').attr('data-selected-value');
	                    var yearTyres = quickFinderElem.find('select.year-tyres').attr('data-selected-value');
	                    var modelTyres = quickFinderElem.find('select.model-tyres').attr('data-selected-value');
	                    var seriesTyres = quickFinderElem.find('select.series-tyres').attr('data-selected-value');

	                   if((makeTyres && makeTyres != "") && (yearTyres && yearTyres != "")){
	                       $this._updateForm(
	                           {'vehiclemakeids': makeTyres, 'year': yearTyres},
	                           '/vehicles/models',
	                           'select.model-tyres',
	                           'select.model-tyres,select.series-tyres,.vehicle-type :submit'
	                       );
	                   }
	                    if((modelTyres && modelTyres != "") && (yearTyres && yearTyres != "")){
	                        $this._updateForm(
	                            {'modelid': modelTyres, 'year': yearTyres},
	                            '/vehicles/vehicles',
	                            'select.series-tyres',
	                            'select.series-tyres,.vehicle-type :submit'
	                        );
	                    }


	                    // handle select change
	                    quickFinderElem.find('select').change(function (e) {
	                        var selectElem = $(e.currentTarget);

	                        // get all Selects and there current values
	                       var paramArray = {};
	                        quickFinderElem.find('select').each(function () {
	                            $this.selects[ $(this).attr('name') ] = $(this).val();

	                           $(this).removeAttr('data-selected-value');
	                            paramArray[$(this).attr('name')] = $(this).find("option:selected").text();
	                        })

	                        quickFinderElem.find('.details-tyres').val(JSON.stringify(paramArray));


	                        switch (selectElem.attr('name')) {

	                            // --- Tyres : Vehicle Type ---

	                            case 'make-tyres':
	                            case 'year-tyres':
	                                // update tyres model
	                                $this._updateForm(
	                                        {'vehiclemakeids': $this.selects['make-tyres'], 'year': $this.selects['year-tyres']},
	                                        '/vehicles/models',
	                                        'select.model-tyres',
	                                        'select.model-tyres,select.series-tyres,.vehicle-type :submit'
	                                        );
	                                break;

	                            case 'model-tyres':
	                                // update tyres series
	                                $this._updateForm(
	                                        {'modelid': $this.selects['model-tyres'], 'year': $this.selects['year-tyres']},
	                                        '/vehicles/vehicles',
	                                        'select.series-tyres',
	                                        'select.series-tyres,.vehicle-type :submit'
	                                        );
	                                break;

	                            case 'series-tyres':
	                                // check vrhicle type ready
	                                $this._finishForm('.vehicle-type :submit', 'series-tyres');
	                                break;

	                                // --- Tyres : Tyre Size ---

	                            case 'width-tyres':
	                                // update profile (aspect ratio)
	                                $this._updateForm(
	                                        {'sectionwidth': $this.selects['width-tyres']},
	                                        '/tyres/sizes/aspectRatios',
	                                        'select.profile-tyres',
	                                        'select.profile-tyres, select.diameter-tyres,.tyre-size :submit'
	                                        );
	                                break;

	                            case 'profile-tyres':
	                                // update rim diameter
	                                $this._updateForm(
	                                        {'sectionwidth': $this.selects['width-tyres'], 'aspectratio': $this.selects['profile-tyres']},
	                                        '/tyres/sizes/rimDiameters',
	                                        'select.diameter-tyres',
	                                        'select.diameter-tyres,.tyre-size :submit'
	                                        );
	                                break;

	                            case 'diameter-tyres':
	                                // check tyre size ready
	                                $this._finishForm('.tyre-size :submit', 'diameter-tyres');
	                                break;

	                                // --- Wheels : Vehicle Type ---

	                            case 'make-wheels':
	                            case 'year-wheels':
	                                // update wheels model
	                                $this._updateForm(
	                                        {'vehiclemakeids': $this.selects['make-wheels'], 'year': $this.selects['year-wheels']},
	                                        '/vehicles/models',
	                                        'select.model-wheels',
	                                        'select.model-wheels,select.series-wheels,select.brands-wheels,select.size-wheels'
	                                        );
	                                break;

	                            case 'model-wheels':
	                                // update wheels series
	                                $this._updateForm(
	                                        {'modelid': $this.selects['model-wheels'], 'year': $this.selects['year-wheels']},
	                                        '/vehicles/vehicles',
	                                        'select.series-wheels',
	                                        'select.series-wheels,select.brands-wheels,select.size-wheels'
	                                        );
	                                break;

	                            case 'series-wheels':
	                                // check vrhicle wheels ready
	                                $this._finishForm('.vehicle-type :submit', 'series-wheels');
	                                break;

	                            case 'brands-wheels':
	                                // check vrhicle wheels ready
	                                $this._finishForm('.vehicle-type :submit', 'series-wheels');
	                                break;

	                            case 'size-wheels':
	                                // check vrhicle wheels ready
	                                $this._finishForm('.vehicle-type :submit', 'series-wheels');
	                                break;

	                        }
	                    });

	                    $this._setPreLoadedValue();

	                    // doulbe check that the action has been writen on submit click
	                    quickFinderElem.find(':submit').on('click', function (e) { 
	                    	
	                    	if($this.validation())
	                    	{
		                        var form = $($(this).parents('form').get(0));
		                        if (form.attr('action') == '') {
		                            e.preventDefault();
		                            quickFinderElem.find('.final-select').trigger('change');
		                            $(this).parents('form').submit();
		                        }
	                    	}
	                    	else
	                    	{
	                    		e.preventDefault();
	                    	}
	                    });

	                } else {
	                    // on mobile quick-finders can be loaded in hidden
	                    // The 'trigger-on-open' class is used to trigger this script like it was just loaded
	                    $('.trigger-on-open').on('click', function () {
	                        setTimeout(function () {
	                            $('.trigger-on-open').unbind('click').removeClass('.trigger-on-open');
	                            $this.init(item);
	                        }, 10);

	                    });
	                }
	            },

	            'validation': function(form){
	            	$('.error').hide();

	            	var errorFound = 0;
	            	$('#form-validate').find(".required-field").each(function(i, el){
	            		var value = $.trim($(el).val()),
	            			name = $(el).attr('name');

	            		if(value == ''){
	            			$('.error.error-'+name).show();
	            			errorFound ++;
	            		}
	            	});

	            	if(errorFound <= 0){
	            		return true;
	            	}

	            	return false;
	            },

	            'getRequestDomain': function (item) {
	                var $this = this, domain = '';

	                domain = beauComponents.getData('api-domain', item, 'url');

	                if (domain === false) {
	                    // no domain
	                    domain = '';
	                } else {
	                    // Remove trailing "/"
	                    domain = domain.replace(/\/$/, "");
	                }

	                return domain;
	            },
	            'getRequestKey': function (item) {
	                var $this = this, key = '';

	                key = beauComponents.getData('api-key', item, 'string');
	                if (key === false) {
	                    // no key
	                    key = '';
	                    console.error('No API key found for quick-finder');
	                }
	                return key;
	            },
	            '_updateForm': function (data, request, target, reset) {
	                var $this = this, isntEmpty = true;

	                // make sure none of the data is empty
	                $.each(data, function (key, val) {
	                    if (val == '') {
	                        return isntEmpty = false;
	                    }
	                });

	                $this._resetSelect($(reset));
	                if (isntEmpty) {
	                    // Not empty: send request to target selct
	                    $this._getRequest(request, data, $(target));
	                }

	            },
	            '_finishForm': function (button, check) {
	                var $this = this, submitButton = $(button);

	                if ($this.selects[check] != '') {
	                    // ready to submit
	                    var form = $('.' + check).parents('form[data-action-format]');

	                    var select = $('.' + check + ':visible');

	                    if ($(form).attr('data-action-format')) {
	                        // update form action
	                        $this._updateFormAction(form, select);
	                    }

	                    if (check == 'series-wheels' && $('.wheel-list').length) {
	                        var optionalData = {};

	                        if ($this.selects['brands-wheels'] != '') {
	                            optionalData.wheelbrandids = [$this.selects['brands-wheels']];
	                        }

	                        if ($this.selects['size-wheels'] != '') {
	                            optionalData.rimdiameters = [$this.selects['size-wheels']];
	                        }

	                        // quick-finder has Wheel Demo, load wheels
	                        beauComponents.wheelFinder.loadWheels(select.val(), optionalData);

	                        $this.updateBrowserHistory($(form));
	                    } else {
	                        // enamble submit button
	                        submitButton.prop('disabled', false);
	                        submitButton.removeClass('disabled');
	                    }

	                } else {
	                    // not ready
	                    submitButton.prop('disabled', true);
	                    submitButton.addClass('disabled');
	                }

	            },
	            '_updateFormAction': function (formElem, finalSelectElem) {
	                var selectedText = finalSelectElem.find(':selected').text();

	                selectedText = selectedText.replace(/[ \-.\\\/<>'"]/g, '_'); // replace bad chars with "_" char
	                selectedText = selectedText.replace(/(_){2,}/g, '_'); // replace more then one "_" in a row

	                var formAction = $(formElem).attr('data-action-format');

	                if (formAction == '/tyresize/[tyre-size]') {
	                    var tyreSize = [
	                        $("select[name='width-tyres']:visible").val(),
	                        $("select[name='profile-tyres']:visible").val(),
	                        $("select[name='diameter-tyres']:visible").val()
	                    ];
	                    formAction = formAction.replace('[tyre-size]', tyreSize.join('_'));
	                } else {
	                    formAction = formAction.replace('[vehicle-name]', selectedText);
	                    formAction = formAction.replace('[vehicle-id]', finalSelectElem.val());
	                    var theYear = $("select[name='year-tyres']:visible").val();
	                    if (typeof theYear == 'undefined') {
	                        theYear = $("select[name='year-wheels']:visible").val();
	                    }
	                    formAction = formAction.replace('[vehicle-year]', theYear);
	                }
	                $(formElem).attr('action', formAction);
	            },
	            '_getRequest': function (requestUrl, requestData, targetSelect) {
	                var $this = this;

	                // start loading indicator
	                targetSelect.prop('disabled', true).addClass('loading');

	                // send ajax request
	                var request = $.ajax({
	                    url: $this.requestDomain + requestUrl + '?rnd=' + Math.random(),
	                    beforeSend: function (xhr) {
	                        xhr.setRequestHeader('Authorization', "Bearer " + $this.requestKey);
	                    },
	                    data: requestData,
	                    dataType: 'json'
	                }).done(function (data) {
	                    // stop loading indicator
	                    targetSelect.removeClass('loading');
	                    // fill select with options
	                    $this._writeOptions(data, targetSelect);

	                    var dataAttr = targetSelect.attr('data-selected-value');
	                    if(dataAttr && dataAttr != ""){
	                       targetSelect.val(targetSelect.attr('data-selected-value'));
	                    }

	                }).fail(function (jqXHR, textStatus) {
	                    // request failed, show error
	                    $('form:visible .request-error').show();
	                });
	            },
	            '_writeOptions': function (data, selectElem) {
	                var $this = this;

	                $this._resetSelect(selectElem);

	                if (data.Items.length) {
	                    selectElem.prop('disabled', false);

	                    // loop through data and create options
	                    $.each(data.Items, function (i, item) {
	                        var item = this;

	                        if (item.Id && item.Name) {
	                            // data has ID/Name, 
	                            // create <options value="[ID]">[Name]</options>
	                            var optionElem = $('<option />')
	                                    .attr('value', item.Id)
	                                    .html(item.Name)
	                                    .appendTo(selectElem);
	                        } else {
	                            // data is just a value, 
	                            // create <options value="[value]">[value]</options>
	                            var optionElem = $('<option />')
	                                    .attr('value', item)
	                                    .html(item)
	                                    .appendTo(selectElem);
	                        }

	                    });

	                    $this._setPreLoadedValue();
	                }
	            },
	            '_resetSelect': function (selectElem) {
	                // remove all options from select and disable it
	                selectElem.find('option:not([value=""])').remove();
	                selectElem.prop('disabled', true);

	                var parentElem = selectElem.parents('.content');
	                $(parentElem[0]).find(':submit').prop('disabled', true).addClass('disabled');
	            },
	            '_setPreLoadedValue': function () {
	                // set any preset values into the select
	                $('select[data-pre-load]').each(function () {
	                    var thisSelect = $(this), preLoadVal = thisSelect.attr('data-pre-load'), isUpdated = false;

	                    if (thisSelect.find('option[value="' + preLoadVal + '"]').length) {
	                        thisSelect.val(preLoadVal).removeAttr('data-pre-load');
	                        thisSelect.trigger("change");
	                    }

	                });
	            },
	            'updateBrowserHistory': function (formElem) {
	                var $this = this,
	                        domain = window.location.protocol + '//' + window.location.host,
	                        searchUrl = domain + formElem.attr('action'),
	                        requestUrl = '/wheelinfo/index/savecar';

	                var searchData = {
	                    'y': formElem.find('.year-wheels').val(),
	                    'id': formElem.find('.series-wheels').val()
	                }

	                var request = $.ajax({
	                    method: 'POST',
	                    url: requestUrl,
	                    data: searchData
	                });

	                history.pushState('', 'Search Result | Beaurepaires', searchUrl);
	            }
	        },
	        
	}

	$(function(){
		beauComponents.quickFinderSearch.init('.quick-finder');
	});

})(jQueryIWD);
