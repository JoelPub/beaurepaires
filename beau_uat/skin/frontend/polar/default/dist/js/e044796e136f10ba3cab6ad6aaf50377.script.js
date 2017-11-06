"use strict";

var beauAppComponents = {};

(function ($) {

    $(function () {

        // Init Foundation with option allowing FOundation equalizer to work on Block grid
        $(document).foundation({
            equalizer: {
                equalize_on_stack: true
            }
        });

        var beauComponents = {
            'analytics': {
                'clientId': '',
                'init': function (source) {

                    var sourceElem = $(source + '[data-ga-client-id]');

                    if (sourceElem.length && typeof ga !== 'undefined') {
                        this.clientId = $(source).attr('data-ga-client-id');
                        ga('create', this.clientId);
                    }
                },
                'send': function (sendData) {

                    if (this.clientId != '') {
                        ga('send', sendData);
                    } else {
                        console.error('GA clientId not set');
                    }
                },
                'sendEcom': function(sendData) {
                  dataLayer.push(sendData);
                }
            },
            'heroSlider': function (item) {
                $(item).slick({
                    arrows: false,
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
                                arrows: false,
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
                                arrows: false,
                                slidesToShow: 3,
                                slidesToScroll: 3,
                                infinite: true,
                                dots: true
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                arrows: false,
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
                    arrows: false,
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
                                arrows: false,
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
                            breakpoint: 1030,
                            settings: {
                                arrows: false,
                                slidesToShow: 4,
                                slidesToScroll: 4,
                                infinite: true,
                                dots: true
                            }
                        },
                        {
                            breakpoint: 1000,
                            settings: {
                                arrows: false,
                                slidesToShow: 3,
                                slidesToScroll: 3,
                                infinite: true,
                                dots: true
                            }
                        },
                        {
                            breakpoint: 769,
                            settings: {
                                arrows: false,
                                slidesToShow: 2,
                                slidesToScroll: 2,
                                infinite: true,
                                dots: true
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                arrows: false,
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: true
                            }
                        }
                    ]
                });
            },
            'wheelFinderSlider': function (item) {
                $(item).slick({
                    speed: 400,
                    centerMode: false,
                    centerPadding: '12px',
                    infinite: true,
                    slidesToShow: 5,
                    slidesToScroll: 5,
                    responsive: [
                        {
                            breakpoint: 1000,
                            settings: {
                                centerMode: false,
                                centerPadding: '12px',
                                infinite: true,
                                slidesToShow: 3,
                                slidesToScroll: 3
                            }
                        },
                        {
                            breakpoint: 769,
                            settings: {
                                centerMode: false,
                                centerPadding: '12px',
                                infinite: true,
                                slidesToShow: 2,
                                slidesToScroll: 2
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                centerMode: false,
                                centerPadding: '12px',
                                infinite: true,
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            },
            'staticBlockColumn': function (item) {
                for (var i=0, n=$(item).length; i<n; i++) {
                    var $item = $(item).eq(i),
                        columnCount = parseInt( $item.data('column-count') );

                    $item.slick({
                        arrows: false,
                        infinite: true,
                        dots: true,
                        speed: 400,
                        slidesToShow: columnCount,
                        slidesToScroll: columnCount,
                        responsive: [
                            {
                                breakpoint: 651,
                                settings: {
                                    arrows: false,
                                    slidesToShow: columnCount,
                                    slidesToScroll: columnCount,
                                    infinite: true,
                                    dots: true
                                }
                            },
                            {
                                breakpoint: 651,
                                settings: {
                                    arrows: false,
                                    slidesToShow: 2,
                                    slidesToScroll: 2,
                                    infinite: true,
                                    dots: true
                                }
                            },
                            {
                                breakpoint: 451,
                                settings: {
                                    arrows: false,
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    infinite: true,
                                    dots: true
                                }
                            }
                        ]
                    });
                }
            },
            'galleryZoom': function (item, wrapper, imgZoom) {
                var $thumbImgs = $(item),
                        $wrapper = $(wrapper),
                        $mainImage = $wrapper.find('img'),
                        wrapperHeight = $wrapper.outerHeight(),
                        zoomConfig = {
                            zoomType: 'lens',
                            lensShape: 'round',
                            lensSize: 200
                        };

                var updateMainImage = function () {
                    var $thisThumb = $(this),
                            thubSrc = $thisThumb.attr('src');

                    // remove old active
                    $(item + '.active').removeClass('active');

                    // set new active
                    $thisThumb.addClass('active');

                    // update main image to this thumb
                    $mainImage.attr('src', thubSrc).data('zoom-image', thubSrc);

                    // update wrapper height
                    if (wrapperHeight) {
                        $wrapper.css('height', wrapperHeight);
                    }

                    // update zoom (if not mobile)
                    if (!Modernizr.touch && $(window).width() > 640) {
                        $mainImage.elevateZoom(zoomConfig);
                    }
                }

                // merge conflict: do we need this?
                //}

                // bind events
                $thumbImgs.on('click', updateMainImage);
                $thumbImgs.first().trigger('click');
            },
            'scrollToName': function (from, to) {
                function scrollToAnchor(aid) {
                    var aTag = $("a[name='" + aid + "']"),
                        aTagOffsetTop = aTag.offset().top;

                    // hack for mobile compatibility
                    if ($(window).width() < 768 && (to === 'add-review' || to === 'customer-reviews')) {
                        aTag = $("a[name='" + aid + "']").eq(1);
                        aTagOffsetTop = aTag.offset().top;
                    }

                    $('html, body').animate({
                        scrollTop: aTagOffsetTop
                    }, 'slow');
                }

                // this function is for review form. links thats open an accordion
                function openAccordionAnchor() {
                    var $accordionsItems = $('.product-accordion').find('.accordion-navigation'),
                        $accordionReview = $accordionsItems.find('.review-accordion').parent();

                    function removeClass(){
                        $accordionsItems.removeClass('active');
                        $accordionsItems.children('.content').removeClass('active');
                    }

                    removeClass();

                    if (! $accordionsItems.hasClass('active')){
                        $accordionReview.addClass('active');
                        $accordionReview.find('.content').addClass('active');
                    }

                    scrollToAnchor(to);
                }

                $(from).on('click', openAccordionAnchor);
            },
            'accordionScroll': function() {
              var $productAccordion = $('.product-accordion'), $body, $sectionHead;

              var onToggle = function(event, accordion) {
                var scrollPos = $(document).scrollTop(),
                  activeSectionOffet = $('.product-accordion #'+accordion[0].id).offset();

                if ( scrollPos > activeSectionOffet.top ) { // only jump if section head if off-screen
                  $body.animate({scrollTop: ( activeSectionOffet.top - $sectionHead.outerHeight() )}, 100);
                }
              }

              if ( $productAccordion.length ) {
                $body = $('body, html');
                $sectionHead = $('.accordion-navigation:first > a');

                // bind jump function to accordian toggle
                $productAccordion.on('toggled', onToggle);
              }
            },
            'toggleShow': function (item, itemToReveal) {
                $(item).on('click', function () {
                    $(itemToReveal).toggle(0);
                });
            },
            'accordion': function (target, content) {
                var toggle = function () {
                    var $this = $(this);
                    $this.toggleClass('active').next(content).toggle(0);
                }
                $(target).on('click', toggle);
            },
            'datepicker': function (btn, input) {
                if (btn.length) {
                    $(btn).on('click', function () {
                        $(input).datepicker().focus();
                    });
                }
            },
            'googlePlacesFields': function (item) {
                var inputs = $(item),
                    $storeLocatorParent = $('.store-locator'),
                    countryCode = $storeLocatorParent.data('country-code');

                if (inputs.length) {

                    var options = {
                        types: ['(regions)'],
                        componentRestrictions: {country: countryCode}
                    };

                    $.each(inputs, function () {
                        var input = $(this),
                                autocompleteSearch = new google.maps.places.Autocomplete(input[0], options);

                        input.keydown(function (e) {
                            var thisInput = $(this),
                                    isSelected = $('.pac-item-selected'),
                                    pacFirstItem = $('.pac-container:visible .pac-item:first');

                            if (e.which == 13 && pacFirstItem.length && !isSelected.length) {
                                e.preventDefault();

                                var firstResult = pacFirstItem.text(),
                                        stringMatched = pacFirstItem.find('.pac-item-query').text();
                                firstResult = firstResult.replace(stringMatched, stringMatched + " ");

                                setTimeout(function () {
                                    thisInput.val(firstResult);
                                    thisInput.parents('form').submit();
                                }, 1);

                            }
                        });

                        input.blur(function (e) {
                            var thisInput = $(this),
                                    isSelected = $('.pac-item-selected'),
                                    pacFirstItem = $('.pac-container:visible .pac-item:first');

                            if (pacFirstItem.length && !isSelected.length) {
                                var firstResult = pacFirstItem.text(),
                                        stringMatched = pacFirstItem.find('.pac-item-query').text();
                                firstResult = firstResult.replace(stringMatched, stringMatched + " ");

                                thisInput.val(firstResult);
                            }
                        });

                    });

                }
            },
            // Forcing font-size to increase to 16px on forms' element for touch screen device in order to avoid the mobile auto-zoom to happen
            'formTouchScreen': function (item) {
                if (Modernizr.touch) {
                    $(item).css('font-size', '16px');
                }
            },
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
                    var quickFinderElem = $(item), $this = this,
                            $resetBtn = $('.reset-btn');

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
                                if($(this).val() !=""){
                                    paramArray[$(this).attr('name')] = $(this).find("option:selected").text();
                                }else{
                                    paramArray[$(this).attr('name')] = "";
                                }
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
                                    $this._checkSeriesStatus();
                                    break;

                                case 'model-tyres':
                                    // update tyres series
                                    $this._updateForm(
                                            {'modelid': $this.selects['model-tyres'], 'year': $this.selects['year-tyres']},
                                            '/vehicles/vehicles',
                                            'select.series-tyres',
                                            'select.series-tyres,.vehicle-type :submit'
                                            );
                                    $this._checkSeriesStatus();
                                    break;

                                case 'series-tyres':
                                    // check vrhicle type ready
                                    $this._finishForm('.vehicle-type :submit', 'series-tyres');

                                    if (this.value > 0) {
                                        $resetBtn.prop('disabled', false);
                                        $resetBtn.addClass("secondary");
                                    } else {
                                        $resetBtn.prop('disabled', true);
                                        $resetBtn.removeClass("secondary");
                                    }

                                    //$this._checkSeriesStatus();

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
                                    $this._checkSeriesStatus();
                                    break;

                                case 'model-wheels':
                                    // update wheels series
                                    $this._updateForm(
                                            {'modelid': $this.selects['model-wheels'], 'year': $this.selects['year-wheels']},
                                            '/vehicles/vehicles',
                                            'select.series-wheels',
                                            'select.series-wheels,select.brands-wheels,select.size-wheels'
                                            );
                                    $this._checkSeriesStatus();
                                    break;

                                case 'series-wheels':
                                    // check vrhicle wheels ready

                                    $this._finishForm('.vehicle-type :submit', 'series-wheels');
                                    if (this.value > 0) {
                                        $resetBtn.prop('disabled', false);
                                        $resetBtn.addClass("secondary");
                                    } else {
                                        $resetBtn.prop('disabled', true);
                                        $resetBtn.removeClass("secondary");
                                    }
                                    //$this._checkSeriesStatus();
                                    break;

                                case 'brands-wheels':
                                    // check vrhicle wheels ready
                                    $this._finishForm('.vehicle-type :submit', 'series-wheels');
                                    $this._checkSeriesStatus();
                                    break;

                                case 'size-wheels':
                                    // check vrhicle wheels ready
                                    $this._finishForm('.vehicle-type :submit', 'series-wheels');
                                    $this._checkSeriesStatus();
                                    break;

                                case 'saved_vehicles':
                                  $this._isFormActive(selectElem, '#find-saved :submit, #saved-vehicles :submit');
                                  break;

                            }


                        });

                        $this._setPreLoadedValue();

                        $('#find-saved select,#saved-vehicles select').trigger('change');

                        // doulbe check that the action has been writen on submit click
                        var vehicle_class = quickFinderElem.hasClass('vehicle');
                        if(!vehicle_class) {
                            quickFinderElem.find(':submit').on('click', function (e) {

                                e.preventDefault();
                                var form = $($(this).parents('form').get(0)),
                                    btn = $(this).attr('value'),
                                    locHref = $(this).attr('loc');

                                if (form.attr('action') == '') {
                                    quickFinderElem.find('.final-select').trigger('change');
                                    $(this).parents('form').submit();

                                } else if (form.attr('class') == 'ajax-call' && btn != 'Clear Result') {

                                    var $inputs = form.find("select, button, input");
                                    var serializedData = form.serialize();


                                    var request = $.ajax({
                                        url: form.attr('action'),
                                        type: "post",
                                        data: serializedData
                                    });

                                    $inputs.prop("disabled", true);

                                    request.done(function (response, textStatus, jqXHR) {

                                        location.href = form.attr('action')
                                    });

                                    request.fail(function (jqXHR, textStatus, errorThrown) {

                                        console.error(
                                            "The following error occurred: " +
                                            textStatus, errorThrown
                                        );
                                    });
                                } else if (btn == 'Clear Result') {
                                    location.href = locHref;
                                } else {
                                    $(this).parents('form').submit();
                                }
                            });
                        }
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
                        }
                    } else {
                        // not ready
                        submitButton.prop('disabled', true);
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
                        beauComponents.checkoutTyresDetails();

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


                }
                ,
                '_checkSeriesStatus': function () {

                    var $seriesTyresDp = $('.series-tyres'),
                            $seriesWheelsDp = $('.series-wheels'),
                            $resetBtn = $('.reset-btn');

                    if ($seriesTyresDp.length && $seriesTyresDp.val() <= 0) {
                        $resetBtn.removeClass("secondary");
                        $resetBtn.prop("disabled", true);
                    }

                    if ($seriesWheelsDp.length && $seriesWheelsDp.val() <= 0) {
                        $resetBtn.removeClass("secondary");
                        $resetBtn.prop("disabled", true);
                    }

                }
                ,
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
                },
                '_isFormActive': function($select, button) {
                  var $submit = $(button);

                  if  ( $select.val() == '' ) {
                    $submit.prop("disabled", true);
                  } else {
                    $submit.prop("disabled", false);
                  }
                }
            },
            'wheelFinder': {
                'requestDomain': '',
                'requestKey': '',
                'selectedVehicleId': 0,
                'selectedWheelKey': 0,
                'selectedWheelSize': 0,
                'results': {},
                'colours': [],
                'brands': [],
                'sizes': [],
                'wheelItems': [],
                'vehicleData': {},
                'isWheelStockLoaded': false,
                'wheelStockList': [],
                'wheelCodesUrl': '/wheelinfo/index/wheelcodes/',
                'init': function (target) {
                    var $this = this;

                    if ($(target).length) {
                        var item = '.quick-finder:visible';

                        // set domain for AJAX resquest
                        $this.requestDomain = beauComponents.quickFinderSearch.getRequestDomain(item);

                        // set API key for OAuth2
                        $this.requestKey = beauComponents.quickFinderSearch.getRequestKey(item);

                        // get list of wheels in stock
                        $this.loadStockList();
                    }
                },
                'loadStockList': function () {
                    var $this = this;

                    var request = $.ajax({
                        url: $this.wheelCodesUrl,
                        dataType: 'json'
                    }).done(function (data) {
                        $this.isWheelStockLoaded = true;
                        $this.wheelStockList = data;
                    }).fail(function (jqXHR, textStatus) {
                        console.error('Error with request: ', jqXHR);
                        $this.showNotice('Error getting stock list');
                    });
                },
                'loadWheels': function (vehicleId, optionalData) {
                    var $this = this, item = '.quick-finder:visible';

                    $this.selectedVehicleId = vehicleId;

                    // get wheels
                    $('.wheel-vehicle-demo').addClass('loading');
                    var requestUrl = '/vehicles/' + $this.selectedVehicleId + '/wheels';
                    $this._resetColourFilter();
                    $this._resetWheelSlider();
                    $this._getRequest(requestUrl, '_handleWheelData', optionalData);
                },
                '_getRequest': function (requestUrl, onSeccess, requestData) {
                    var $this = this;

                    // don't use requestData for now
                    requestData = {};

                    $('.wheel-list').show();
                    $('.wheels-notice').hide();

                    var request = $.ajax({
                        url: $this.requestDomain + requestUrl,
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('Authorization', 'Bearer ' + $this.requestKey);
                        },
                        data: requestData,
                        dataType: 'json',
                        success: $this[onSeccess]
                    }).fail(function (jqXHR, textStatus) {
                        console.error('Error with request: ', jqXHR);
                        $this.showNotice('Unable to get data on your selected vehicle');
                    });
                },
                'showNotice': function (message) {
                    $('.wheels-notice')
                            .show()
                            .html(message);

                    $('.wheel-list, .wheel-list-header, .wheel-vehicle-demo, .wheel-active').hide();
                },
                '_handleWheelData': function (data) {
                    var $this = beauComponents.wheelFinder, slider = $('.wheel-finder-slider'),
                        storeUrl = $('.wheels-notice').attr('data-store-url');

                    // check to see if stock list is loaded
                    if ($this.isWheelStockLoaded) {

                        // stock list is loaded
                        if (data.Items.length) {

                            // show things
                            $('.wheel-list, .wheel-list-header, .wheel-vehicle-demo').show();

                            // filter out items not in stock list
                            $this.wheelItems = [];
                            for (var i = 0, n = data.Items.length; i < n; i++) {
                                if ($this.isWheelInStock(data.Items[i])) {
                                    $this.wheelItems.push(data.Items[i]);
                                }
                            }

                            if ($this.wheelItems.length == 0) {
                                // all results filtered out
                                $this.showNotice('No wheels found for your selection');
                            } else {
                                $this._updateFilters();
                                $this._updateWheelSlider();

                                // get vehicle
                                var requestUrl = '/vehicles/' + $this.selectedVehicleId + '/wheelsonvehicle';
                                $this._getRequest(requestUrl, '_handleVehicleData', {});
                            }
                        } else {
                            // no results
                            var noResultMsg = $('.no-result-message');
                            $this.showNotice(noResultMsg.html());
                        }
                    } else {
                        // stock list isn't loaded, wait and try again
                        setTimeout(function () {
                            console.log('not loaded, waiting...');
                            $this._handleWheelData(data);
                        }, 1000);
                    }
                },
                '_resetColourFilter': function () {
                    var selectElem = $('.wheels-colour-filter');
                    selectElem.unbind().prop('disabled', true);
                    $(':not(:first)', selectElem).remove();
                },
                '_updateFilters': function () {
                    var $this = this,
                            colourSelect = $('.wheels-colour-filter'),
                            brandSelectElem = $('select.brands-wheels'),
                            sizesSelectElem = $('select.size-wheels');

                    $this.colours = [];
                    $this.brands = [];
                    $this.sizes = [];

                    // create array for filter values
                    $.each($this.wheelItems, function (n, item) {

                        var colour = item.Finish.Name,
                                brand = item.Brand.Name,
                                size = item.WheelFitments[0].FrontWheel.Size.RimDiameter;

                        // add colour
                        if ($.trim(colour) != '' && $.inArray(colour, $this.colours) < 0) {
                            $this.colours.push(colour);
                        }

                        // add brand
                        if ($.trim(brand) != '' && $.inArray(brand, $this.brands) < 0) {
                            $this.brands.push(brand);
                        }
                        // add size
                        if ($.trim(size) != '' && $.inArray(size, $this.sizes) < 0) {
                            $this.sizes.push(size);
                        }
                    });
                    $this.colours.sort();
                    $this.brands.sort();
                    $this.sizes.sort();

                    // update colour select
                    $this._createFilterOptions($this.colours, colourSelect);

                    // update brand select
                    var savedBrandVal = brandSelectElem.val();
                    beauComponents.quickFinderSearch._resetSelect(brandSelectElem);
                    $this._createFilterOptions($this.brands, brandSelectElem);
                    brandSelectElem.val(savedBrandVal);

                    // update size select
                    var savedSizeVal = sizesSelectElem.val();
                    beauComponents.quickFinderSearch._resetSelect(sizesSelectElem);
                    $this._createFilterOptions($this.sizes, sizesSelectElem);
                    sizesSelectElem.val(savedSizeVal);

                    // on filter change, update wheel slider (without AJAX request)
                    colourSelect.on('change', function () {
                        $this._resetWheelSlider();
                        $this._updateWheelSlider();
                    })

                    colourSelect.prop('disabled', false);
                },
                '_createFilterOptions': function (dataArr, selectElem) {

                    if (dataArr.length) {
                        for (var i = 0, c = dataArr.length; i < c; i++) {
                            selectElem.append(
                                    $('<option></option>')
                                    .html(dataArr[i])
                                    .attr('value', dataArr[i])
                                    );
                        }
                        selectElem.prop('disabled', false);
                    } else {
                        selectElem.prop('disabled', true);
                    }
                },
                '_resetWheelSlider': function () {
                    $('.wheel-finder-slider').remove();

                    var slider = $('<div />')
                            .addClass('wheel-finder-slider')
                            .addClass('loading')
                            .appendTo('.wheel-list');
                },
                '_updateWheelSlider': function ( ) {
                    var $this = this, slider = $('.wheel-finder-slider'), count = 0;

                    slider.removeClass('loading');

                    // write wheel slides
                    var documentFragment = $(document.createDocumentFragment());

                    // create fitlers
                    var filters = {
                        colour: $('.wheels-colour-filter').val(),
                        brand: $('select.brands-wheels').val(),
                        size: $('select.size-wheels').val()
                    }

                    $.each($this.wheelItems, function (n, item) {

                        var colour = item.Finish.Name,
                                brand = item.Brand.Name,
                                size = item.WheelFitments[0].FrontWheel.Size.RimDiameter,
                                isMatch = true;

                        if (filters.colour != 'All' && filters.colour != colour) {
                            isMatch = false;
                        }

                        if (filters.brand != '' && filters.brand != brand) {
                            isMatch = false;
                        }

                        if (filters.size != '' && filters.size != size) {
                            isMatch = false;
                        }

                        if (isMatch) {

                            // create slide elem
                            var slide = $('<div/>', {
                                addClass: 'item',
                                appendTo: documentFragment,
                                click: function () {
                                    $this.selectedWheelKey = n;
                                    $('.item.active').removeClass('active');
                                    $(this).addClass('active');
                                    $this._updateVehicleTyre(n, true);
                                }
                            });

                            // create slide image elem (with loading icon)
                            var slideImage = $('<div><i></i><img/></div>')
                                    .addClass('img-holder loading')
                                    .attr('data-color', colour)
                                    .find('i')
                                    .addClass('fa fa-refresh fa-spin fa-2x')
                                    .end()
                                    .find('img')
                                    .attr('src', $this._getWheelImageUrl(item, 'WheelAngleThumb'))
                                    .attr('alt', item.Name)
                                    .end()
                                    .appendTo(slide);

                            // create slide label elem
                            var slideLabel = $('<div/>', {
                                addClass: 'item-label',
                                html: item.Name,
                                appendTo: slide
                            });

                            count++;
                        }
                    });

                    slider.append(documentFragment);
                    beauComponents.wheelFinderSlider('.wheel-finder-slider');

                    // update counter
                    $this._updateCounter(count);

                    // wheel image loading
                    var imgLoad = imagesLoaded($('.wheel-finder-slider'));
                    imgLoad.on('progress', function (imgLoad, image) {
                        if (image.isLoaded) {
                            $(image.img).parent().removeClass('loading');
                        } else {
                            $(image.img).parent().addClass('broken');
                        }
                    });

                },
                'isWheelInStock': function (item) {
                    var $this = this,
                            isInStock = false,
                            itemCode = item.WheelFitments[0].FrontWheel.Code;

                    if ($.inArray(itemCode, $this.wheelStockList) > -1) {
                        isInStock = true;
                    }

                    return isInStock;
                },
                '_updateCounter': function (count) {
                    var counterElem = $('.wheel-list-count');

                    if (count == 0) {
                        counterElem.html('No results found');
                    } else if (count == 1) {
                        counterElem.html('Showing 1 wheel');
                    } else {
                        counterElem.html('Showing ' + count + ' wheels');
                    }
                },
                '_getWheelImageUrl': function (wheelData, imageType) {
                    var url = "";

                    for (var i = 0, w = wheelData.Images.length; i < w; i++) {
                        if (wheelData.Images[i].Type == imageType) {
                            url = wheelData.Images[i].Url;
                            break;
                        }
                    }
                    return url;
                },
                '_showActiveWheelItem': function (wheelData) {
                    var $this = this,
                            wheelActiveElem = $('.wheel-active'),
                            wheelCode = wheelData.WheelFitments[0].FrontWheel.Code,
                            WheelPCD = wheelData.WheelFitments[0].FrontWheel.PCD,
                            WheelOffset = wheelData.WheelFitments[0].FrontWheel.Offset,
                            requestUrl = '/wheelinfo/index/index/',
                            requestData = {'sku': wheelCode},
                            brokenImgSrc = '/skin/frontend/polar/default/images/imagecomingsoon_5.png',
                            detailBtn = $('.view-detials'),
                            wheelSizes,
                            selectedSize;

                    wheelActiveElem.show().removeClass('no-data').addClass('loading');

                    var request = $.ajax({
                        url: requestUrl + '?rnd=' + Math.random(),
                        data: requestData,
                        dataType: 'json',
                        success: function (data) {

                            data.pageType = 'wheels';
                            beauComponents.addProductModal.productDetails = data;

                            wheelActiveElem.removeClass('loading');

                            if (data.Error) {
                                // error, no wheel data
                                wheelActiveElem.addClass('no-data');
                            } else {
                                // seccess, wheel data

                                // image
                                $('.product-image a', wheelActiveElem)
                                        .attr('tile', data.Name)
                                        .find('img')
                                        .attr('src', data.ProductImage)
                                        .attr('alt', data.Name);

                                // badge
                                if (data.Badge.Name != false) {

                                  var badgeTypeClass;

                                  switch(data.Badge.Name) {
                                    case('On Sale'):
                                      badgeTypeClass = 'onsale';
                                      break;

                                    case('New Arrival'):
                                      badgeTypeClass = 'new';
                                      break;

                                      case('Best Seller'):
                                        badgeTypeClass = 'bestseller';
                                        break;

                                    case('Coming Soon'):
                                      badgeTypeClass = 'comingsoon';
                                      break;
                                  }

                                    $('.product-badge-block', wheelActiveElem)
                                            .html(data.Badge.Name)
                                            .attr('class','product-badge-block small '+badgeTypeClass)
                                            .show();
                                } else {
                                    $('.product-badge-block', wheelActiveElem).hide();
                                }

                                // brand
                                $('.product-brand', wheelActiveElem)
                                        .attr('src', data.Brand.Image)
                                        .attr('alt', data.Brand.Name);

                                // description
                                var $productDescription = wheelActiveElem.find('.product-description'),
                                  $descContainer = $productDescription.find('.desc-container');

                                if ( data.SortDescription == '' ) {
                                  $descContainer.html('');
                                  $productDescription.hide();
                                } else {
                                  $descContainer.html(data.SortDescription);
                                  $productDescription.show();
                                }

                                // name
                                $('.product-title', wheelActiveElem)
                                        .html(data.Name);

                                        // $('.product-title a', wheelActiveElem)
                                        //         .html(data.Name);

                                $('.add-product-button', wheelActiveElem)
                                        .attr('data-product-id', data.Id);

                                // attr icons
                                var $productFeatures = wheelActiveElem.find('.product-features'),
                                  $iconContainer = wheelActiveElem.find('.product-features > div'),
                                  $iconItems =  $iconContainer.find('li');


                                $iconContainer.attr('class','attr-count-'+data.AttributeIcons.length);

                                if ( data.AttributeIcons.length == 0 ) {
                                  $productFeatures.hide();
                                } else {
                                  $productFeatures.show();
                                  for ( var i=0,n=data.AttributeIcons.length; i<n; i++ ) {
                                    var $icon = $iconItems.eq(i),
                                      $i = $icon.find('i'),
                                      $p = $icon.find('p');

                                    $i.attr('class','fa fa-2x '+data.AttributeIcons[i].class);
                                    $p.html(data.AttributeIcons[i].text);
                                    Foundation.libs.tooltip.getTip($i).html(data.AttributeIcons[i].tooltip);
                                  }
                                }


                                // size
                                var $wheelSize = wheelActiveElem.find('.product-size'),
                                  $sizeSelect = $wheelSize.find('select'),
                                  sizeData = $this._formatSizeData(wheelData.WheelFitments);

                                  var sizeSlected = function() {
                                    var $selected = $(this).find(':selected');

                                    $this.setWheelSize( $selected.data('diameter') );
                                    $this._updateVehicleTyre($this.selectedWheelKey, false);
                                    $this._setViewDeatilsUrl();
                                    $this._cartButtonDisplay();
                                    alert('test!');
                                  }

                                  $sizeSelect.empty();

                                  for (var i=0,n=sizeData.length; i<n; i++) {
                                    var sizeKey = i,
                                      sizeVal = sizeData[i],
                                      className = '';

                                    var thisSizeUrl = [
                                      data.UrlKey,
                                      '?diameter=',
                                      sizeVal.diameter,
                                      ',',
                                      sizeVal.rearDiameter,
                                      '&width=',
                                      sizeVal.width,
                                      ',',
                                      sizeVal.rearWidth
                                    ].join('');

                                    var currentSize = [
                                      sizeVal.diameter,
                                      'x',
                                      sizeVal.width
                                    ].join('');

                                    if (currentSize in data.sizeOptions) {
                                        className = 'has-option';
                                    }

                                    var $sizeOption = $('<option />')
                                      .html(sizeVal.label)
                                      .attr('value',sizeVal.diameter)
                                      .attr('class',className)
                                      .attr('data-diameter', sizeVal.diameter)
                                      .attr('data-width', sizeVal.width)
                                      .attr('data-url',thisSizeUrl)
                                      .attr('data-key', sizeVal.key)
                                      .appendTo($sizeSelect);

                                  }

                                  $sizeSelect
                                    .val($this.selectedWheelSize)
                                    .on('change',sizeSlected);

                                  $this._cartButtonDisplay();

                                $this._setViewDeatilsUrl();

                                wheelActiveElem.show();

                                // handle product image
                                var imgLoad = imagesLoaded($('.product-image a', wheelActiveElem));
                                imgLoad.on('progress', function (imgLoad, image) {
                                    if (!image.isLoaded) {
                                        $('.product-image a img', wheelActiveElem).attr('src', brokenImgSrc);
                                    }
                                });
                            }
                        }
                    }).fail(function (jqXHR, textStatus) {
                        console.error(jqXHR.responseText);
                    });

                },
                '_cartButtonDisplay': function(){
                    var activeSize = $('.product-size .has-option:selected');
                    var cartButton = $('.product-item .add-product-button');
                    // hide add to cart button if size option is empty
                    if(activeSize.length){
                        cartButton.toggle(true);
                    }else{
                        cartButton.toggle(false);
                    }
                },
                '_setViewDeatilsUrl': function() {
                  //var activeSize = $('.select-wheel-size .selected');
                  var activeSize = $('.product-size select :selected')
                  $('.product-item .view-detials, .product-item .w-buy-now').attr('href', activeSize.attr('data-url'));
                },
                '_handleVehicleData': function (data) {
                    var $this = beauComponents.wheelFinder,
                            demoElem = $('.wheel-vehicle-demo'),
                            holderElem = $('.vehicle-holder'),
                            colourSWitchElem = $('.colour-switch'),
                            setVehicleSusension = $('.set-vehicle-susension'),
                            seriesText = $('select.series-wheels :selected').text(),
                            makeText = $('select.make-wheels :selected').text(),
                            vehicleTitle = $.trim(seriesText.substring(makeText.length));

                    // make sure there are wheels
                    if ($this.wheelItems.length) {

                        $this.vehicleData = data;

                        // clear current
                        holderElem.empty().addClass('loading');
                        colourSWitchElem.empty();
                        demoElem.removeClass('loading');

                        // add vehicle img (all colours)
                        $.each(data.VehicleImages, function (key, imageData) {

                            var vehicleImg = $('<img/>', {
                                'src': imageData.Image.Url,
                                'data-colour-name': imageData.Colour.Name,
                                'alt': vehicleTitle,
                                addClass: 'vehicle-img-main',
                                appendTo: holderElem
                            });

                            var colourElem = $('<li/>', {
                                'title': imageData.Colour.Name,
                                attr: {
                                  'style': 'background-color: #' + imageData.Colour.HexValue
                                },
                                click: function () {
                                    $this._setVehicleColour(imageData.Colour.Name);
                                    $(this).addClass('active').siblings().removeClass('active');
                                },
                                appendTo: colourSWitchElem
                            });

                        });
                        colourSWitchElem.find('li:first').addClass('active');

                        // handle susension buttons
                        setVehicleSusension.unbind().on('click', function () {
                            var marginVal = 0, thisButton = $(this);

                            if (thisButton.attr('data-susension-state') == 'lower') {
                                marginVal = data.LowerSuspensionPixels + 'px';
                            }

                            $('.vehicle-img-main').css('margin-top', marginVal);
                        });

                        // add title

                        var title = $('<h2>', {
                            html: makeText,
                            appendTo: holderElem
                        });
                        var subtitle = $('<h3>', {
                            html: vehicleTitle,
                            appendTo: holderElem
                        });

                        // add shadow
                        holderElem.append(
                                $this._createVehicleImage(
                                        data.VehicleShadowImageUrl,
                                        data.VehicleShadowImageSize,
                                        data.VehicleShadowImageOrigin,
                                        'vehicle-img-shadow',
                                        vehicleTitle
                                        )
                                );

                        // add front tyres shadow
                        holderElem.append(
                                $this._createVehicleImage(
                                        data.TyreShadowImageUrl,
                                        data.TyreShadowImageSize,
                                        data.FrontTyreShadowImageOrigin,
                                        'vehicle-img-tyre-shadow',
                                        vehicleTitle
                                        )
                                );

                        // add rear tyres shadow
                        holderElem.append(
                                $this._createVehicleImage(
                                        data.TyreShadowImageUrl,
                                        data.TyreShadowImageSize,
                                        data.RearTyreShadowImageOrigin,
                                        'vehicle-img-tyre-shadow',
                                        vehicleTitle
                                        )
                                );

                        // add front tyres
                        holderElem.append(
                                $this._createVehicleImage(
                                        data.TyreImageUrl,
                                        data.TyreImageSize,
                                        data.FrontTyreImageOrigin,
                                        'vehicle-img-tyre',
                                        vehicleTitle
                                        )
                                );

                        // add rear tyres
                        holderElem.append(
                                $this._createVehicleImage(
                                        data.TyreImageUrl,
                                        data.TyreImageSize,
                                        data.RearTyreImageOrigin,
                                        'vehicle-img-tyre',
                                        vehicleTitle
                                        )
                                );

                        $('.item[data-slick-index="0"]').trigger('click');

                        // set active wheel
                        //$this._showActiveWheelItem($this.wheelItems[0]);

                        // vehicle image loading
                        var imgLoad = imagesLoaded(holderElem);
                        imgLoad.on('always', function (instance) {
                            holderElem.removeClass('loading');
                        });

                        // show first vehicale
                        $('.vehicle-img-main:first').addClass('active');

                    }
                },
                'getWheelImageData': function () {
                    var $this = this,
                            key = 0,
                            wheelPos = $this.vehicleData.WheelImagePositions;

                    // get selected size (if any)
                    var selectedWheelSize = $this.getWheelSize();

                    if (selectedWheelSize != '') {
                        for (var i = 0, n = wheelPos.length; i < n; i++) {
                            if (wheelPos[i].RimDiameter == selectedWheelSize) {
                                key = i;
                                break;
                            }
                        }
                    }

                    return wheelPos[key];
                },
                'getWheelSize': function () {
                    var $this = this,
                            selectSizeFieldVal = $('select.size-wheels').val();

                    var getDiameter = function (fitment) {
                        return fitment.FrontWheel.Size.RimDiameter;
                    }

                    // if there is no selected size then try using quick finders value
                    if ($this.selectedWheelSize == 0 && selectSizeFieldVal != '') {
                        $this.selectedWheelSize = selectSizeFieldVal;
                    }

                    // check if this wheel support this size
                    var selectedFitments = $this.wheelItems[ $this.selectedWheelKey ].WheelFitments,
                            isAvailable = false;

                    for (var i = 0, n = selectedFitments.length; i < n; i++) {
                        if (getDiameter(selectedFitments[i]) == $this.selectedWheelSize) {
                            isAvailable = true;
                            break;
                        }
                    }

                    if (!isAvailable) { // size not available, set to first size
                        $this.selectedWheelSize = getDiameter(selectedFitments[0]);
                    }

                    return $this.selectedWheelSize;
                },
                'setWheelSize': function (newSize) {
                    this.selectedWheelSize = newSize;
                },
                '_formatSizeData': function (wheelFitments) {
                    var sizeData = [];

                    for (var i = 0, n = wheelFitments.length; i < n; i++) {
                        var frontSize = wheelFitments[i].FrontWheel.Size.Description,
                                rearSize = wheelFitments[i].RearWheel.Size.Description,
                                thisSize = {};

                        if (frontSize == rearSize) {
                            thisSize.label = frontSize;
                        } else {
                            thisSize.label = frontSize + ' [front]' + rearSize + ' [rear]';
                        }

                        thisSize.diameter = wheelFitments[i].FrontWheel.Size.RimDiameter;
                        thisSize.width = wheelFitments[i].FrontWheel.Size.RimWidth;
                        thisSize.rearDiameter = wheelFitments[i].RearWheel.Size.RimDiameter;
                        thisSize.rearWidth = wheelFitments[i].RearWheel.Size.RimWidth;
                        thisSize.key = i;

                        sizeData.push(thisSize);
                    }

                    sizeData.sort(function(a,b){
                        return a.diameter - b.diameter;
                    });

                    return sizeData;
                },
                '_updateVehicleTyre': function (key, isActiveUpdated) {
                    var $this = this,
                            holderElem = $('.vehicle-holder');

                    // remove old wheels
                    $('.vehicle-img-wheel').remove();

                    // add new front wheel
                    var wheelImage = $this.getWheelImageData();
                    holderElem.append(
                            $this._createVehicleImage(
                                    $this._getWheelImageUrl($this.wheelItems[key], 'WheelWebOnCar'),
                                    wheelImage.WheelImageSize,
                                    wheelImage.FrontWheelImageOrigin,
                                    'vehicle-img-wheel',
                                    $this.wheelItems[key].Name + ' (front)'
                                    )
                            );

                    // add new rear wheel
                    holderElem.append(
                            $this._createVehicleImage(
                                    $this._getWheelImageUrl($this.wheelItems[key], 'WheelWebOnCar'),
                                    wheelImage.WheelImageSize,
                                    wheelImage.RearWheelImageOrigin,
                                    'vehicle-img-wheel',
                                    $this.wheelItems[key].Name + ' (rear)'
                                    )
                            );

                    // set active wheel
                    if (isActiveUpdated) {
                        $this._showActiveWheelItem($this.wheelItems[key]);
                    }

                },
                '_createVehicleImage': function (url, sizeData, posData, imgClass, altText) {
                    return $('<img/>', {
                        'src': url,
                        'alt': altText,
                        css: {
                            'height': sizeData.Height,
                            'width': sizeData.Width,
                            'left': posData.Left,
                            'top': posData.Top
                        },
                        addClass: imgClass
                    });
                },
                '_setVehicleColour': function (colourName) {
                    $('.vehicle-img-main.active').removeClass('active');
                    $('.vehicle-img-main[data-colour-name="' + colourName + '"]').addClass('active');
                }
            },
            'getVehicleModel': function (item) {
                // rushed out for release, should be cleaner
                var selectElem = $(item);

                if (selectElem.length) {

                    var $this = this,
                            requestUrl = selectElem.attr('data-api-domain') + "/vehicles/models",
                            requestKey = selectElem.attr('data-api-key'),
                            targetSelect = $('.get-vehicle-model'),
                            repopulateModel = function(selectedMakeId){
                                targetSelect.find('option:not([value=""])').remove();
                                targetSelect.prop('disabled', true);

                                if (selectedMakeId) {
                                    var requestParam = {'vehiclemakeids': selectedMakeId};

                                    // send ajax request
                                    var request = $.ajax({
                                        url: requestUrl,
                                        beforeSend: function (xhr) {
                                            xhr.setRequestHeader('Authorization', "Bearer " + requestKey);
                                        },
                                        data: requestParam,
                                        dataType: 'json'
                                    }).done(function (data) {

                                        // fill select with options
                                        if (data.Items.length)
                                        {
                                            selectElem.prop('disabled', false);

                                            // loop through data and create options
                                            $.each(data.Items, function (i, item) {
                                                var item = this;

                                                var optionElem = $('<option />')
                                                        //.attr('value', item.Name)
                                                        .attr('value', item.Id)
                                                        .html(item.Name)
                                                        .appendTo(targetSelect);

                                            });

                                            targetSelect.prop('disabled', false);

                                            if(targetSelect.attr("data-pre-selected"))
                                            {
                                                var preSelected = targetSelect.attr("data-pre-selected");

                                                targetSelect.val(preSelected);
                                            }
                                        }

                                    }).fail(function (jqXHR, textStatus) {

                                    });

                                }
                            };

                    if(selectElem.val() !== ""){
                        var id = $(':selected', selectElem).attr('data-value-id');
                        repopulateModel(id);
                    }

                    selectElem.on('change', function () {
                        var selectedMakeId = $(':selected', selectElem).attr('data-value-id');

                        repopulateModel(selectedMakeId);
                    });
                }
            },
            'productFormModal': {
                'storesCache': null,
                'init': function (item) {
                    var $this = beauComponents.productFormModal,
                            modalElem = $(item),
                            openModelButton = $('.product-modal-open');

                    if (modalElem.length) {
                        // do things!

                        // setup close buttons
                        modalElem.find('.close-modal-button').click(function () {
                            modalElem.foundation('reveal', 'close');
                        });

                        // set up disable/enamble based on required fields
                        var requiredFields = $('.product-options .required-entry');

                        if (requiredFields.length) {
                            requiredFields.on('change', function () {
                                if (requiredFields.val() != '') {
                                    openModelButton.prop("disabled", false);
                                } else {
                                    openModelButton.prop("disabled", true);
                                }
                            });
                            requiredFields.trigger('change');
                        } else {
                            // no required fields: just enable
                            openModelButton.prop("disabled", false);
                        }

                        $(document).on('open.fndtn.reveal', '[data-reveal]', function () {
                            var thisModal = $(this);
                            // reset model state when opened
                            $this.setModalState(thisModal, '');

                            $this.sendPageView(thisModal, 'open');

                        }).on('opened.fndtn.reveal', '[data-reveal]', function () {
                            var thisModal = $(this);

                            // if form has a store finder
                            var storeFinderElem = thisModal.find('.store-finder.render-finder');
                            if (storeFinderElem.length) {
                                storeFinderElem.removeClass('render-finder');
                                $this.handleStoreFinder(thisModal, storeFinderElem);
                            }

                            // if form has a datepicker
                            var datePickerElem = $('.fit-date[data-min-date]');
                            if (datePickerElem.length) {

                                datePickerElem.datepicker({
                                    minDate: datePickerElem.attr('data-min-date'),
                                    dateFormat: 'dd/mm/yy'
                                });

                            }

                        });

                        modalElem.find('form')
                                .on('valid.fndtn.abide', function () {
                                    // form submited with valid data
                                    $this.setModalState(modalElem, 'loading');

                                    // send post request
                                    $this._sendFormData($(this));
                                });
                    }

                },
                'setModalState': function (modalElem, state) {
                    var $this = this;

                    // remove all other state classes
                    modalElem.removeClass('show-loading show-result show-error');

                    if (state != '') {
                        // add new state class
                        modalElem.addClass('show-' + state);
                    }
                },
                '_sendFormData': function (formElem) {
                    var $this = this;

                    var request = $.ajax({
                        type: 'POST',
                        url: formElem.attr('action'),
                        data: formElem.serializeArray(),
                        dataType: 'json',
                        success: function (data) {
                            var modalElem = $('.product-form-modal:visible');
                            if (modalElem.length) {

                                // update modal state to "result"
                                $this.setModalState(modalElem, 'result');

                                // send page view
                                $this.sendPageView(modalElem, 'submitted');

                                if ( data.analytics ) {
                                  beauComponents.analytics.sendEcom(data.analytics);
                                }

                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            var modalElem = $('.product-form-modal:visible');
                            if (modalElem.length) {

                                // update modal state to "error"
                                $this.setModalState(modalElem, 'error');

                                // update error message
                                var message = '[' + jqXHR.status + '] ' + jqXHR.statusText;
                                modalElem.find('.popup-error .error-msg').html(message);
                            }
                        }
                    });
                },
                'handleStoreFinder': function (modalElem, inputElem) {
                    var $this = this,
                            fieldElem = inputElem.parent(),
                            storeIdElem = $('.store-finder-id');

                    // set up stores autocomplete
                    inputElem.autocomplete({
                        source: function (request, response) {
                            // on store search start : add loading indicator
                            fieldElem.addClass('loading');

                            // get response from google AutocompleteService
                            $this._getPlacePredictions(request, response);
                        },
                        appendTo: modalElem,
                        minLength: 4,
                        select: function (event, ui) {
                            // on store selected : update hidden input
                            if (ui.item) {
                                storeIdElem.val(ui.item.id);
                            } else {
                                storeIdElem.val('');
                            }
                        }
                    }).on('autocompleteresponse', function (event, ui) {
                        // on store search finish : remove loading indicator
                        fieldElem.removeClass('loading');
                    }).on('keydown', function (e) {
                        if (e.keyCode != 13) {
                            storeIdElem.val('');
                        }
                    });
                },
                '_getPlacePredictions': function (request, response) {
                    var $this = this,
                            placeIdCache = null,
                            geocoder = new google.maps.Geocoder(),
                            service = new google.maps.places.AutocompleteService();

                    // get a lat/lng from google and pass it to our store finder API
                    service.getPlacePredictions({
                        input: request.term,
                        componentRestrictions: {country: 'au'}
                    }, function (predictions, status) {

                        if (status == google.maps.places.PlacesServiceStatus.OK) {

                            if (placeIdCache != predictions[0].place_id) {

                                // cache results
                                placeIdCache = predictions[0].place_id;

                                geocoder.geocode({
                                    'placeId': predictions[0].place_id
                                }, function (results, status) {

                                    var loc = {
                                        'lat': results[0].geometry.location.lat(),
                                        'lng': results[0].geometry.location.lng(),
                                        'area': 'cart'
                                    }

                                    // match lat/lng to stores
                                    $this._getStore(loc, response);
                                });

                            } else {
                                response($this.storesCache);
                            }

                        } else {
                            // failed
                            response(false);
                        }
                    });

                },
                '_getStore': function (loc, response) {
                    var $this = this;

                    $.ajax({
                        url: "/pickupinstore/index/storesbylatlng/",
                        dataType: "json",
                        data: loc,
                        success: function (data) {
                            $this.storesCache = data;
                            response(data);
                        }
                    });
                },
                'sendPageView': function (modalElem, pageViewType) {
                    // For Web Tracking with Google analytics
                    // https://developers.google.com/analytics/devguides/collection/analyticsjs/pages
                    // https://developers.google.com/analytics/devguides/collection/analyticsjs/single-page-applications


                    // no linger in use
                    // if (modalElem.is('[data-page-view-' + pageViewType + ']') && modalElem.attr('[data-page-view-' + pageViewType + ']') != '') {
                    //     var $this = this,
                    //             sendData = {'hitType': 'pageview', 'page': '', 'title': ''};
                    //
                    //     sendData.page = modalElem.attr('data-page-view-' + pageViewType);
                    //
                    //     // set page view title from the modal's title
                    //     sendData.title = modalElem.find('.modalTitle').text();
                    //
                    //     beauComponents.analytics.send(sendData);
                    // }
                }
            },
            'addProductModal': {
                'requestUrl': '',
                'modalElem': null,
                'currentProductId': 0,
                'init': function (item) {
                    var $this = beauComponents.addProductModal;

                    $this.modalElem = $(item);

                    if ($this.modalElem.length) {
                        var $addProductButton = $('.add-product-button');

                        $this.requestUrl = $this.modalElem.attr('data-rquest-url');

                        $(document).on('click', '.add-product-button', function (e) {
                            $this.currentProductId = $(this).attr('data-product-id');
                            //check if product-details exist
                            if( $(this).data("product-details") !== undefined) {
                                $this.productDetails = $(this).data('product-details');
                            }
                        });

                        $(document)
                                .on('open.fndtn.reveal', '[data-reveal]', $this._openModal)
                                .on('opened.fndtn.reveal', '[data-reveal]', $this._loadProductOptions);
                    }
                },
                '_openModal': function () {
                    var $this = beauComponents.addProductModal;

                    $this.setModalState('loading');
                    $this.modalElem.find('.select-size').empty();
                },
                'setModalState': function (state) {
                    var $this = this;

                    // remove all other state classes
                    $this.modalElem.removeClass('show-form show-error show-loading');

                    if (state != '') {
                        // add new state class
                        $this.modalElem.addClass('show-' + state);
                    }
                },
                '_loadSizePrice' : function(price){
                    $('.total-price').html('<i class="fa fa-refresh fa-spin"></i>');
                    $(".total-price").html(beauComponents.formatMoney(price));
                    $(".total-price").attr('data-initprice', price);
                },
                '_loadProductOptions' : function() {
                     var $this = beauComponents.addProductModal,
                         selectSizeElem = $this.modalElem.find('.select-size'),
                         sizeFieldElem = $this.modalElem.find('.field-size'),
                         valuesButton = $this.productDetails,
                         modalImage = $('.cart-modal-image'),
                         productName = $('.cart-modal-product-name'),
                         modalLogo = $('.cart-modal-logo');

                    $(".select-quantity").prop('selectedIndex', 0);
                    $('.model-field-product-id').val($this.currentProductId);

                    if($this.productDetails.Type == 'configurable'){

                        var getLowest = function(bool){
                            if(bool == 1){
                                return "selected";
                            }
                        };

                        var getOptionSize = function(option){
                            $.each(option, function () {
                                var size = this;
                                selectSizeElem.append(
                                    $('<option />')
                                        .html(size.Title)
                                        .attr('data-sku', size.Sku)
                                        .attr('data-final-price', size.OnlinePrice)
                                        .attr('data-free-product', size.IsFreeProduct)
                                        .attr('selected', getLowest(size.isLowestPriceLowestSize))
                                        .val(size.Id)
                                );
                            });
                        };

                        if($this.productDetails.pageType == 'wheels'){

                            var returnSizeElem = $('.select-wheel-size'),
                                selectedSize = returnSizeElem.find('li.selected'),
                                sizeVar = [selectedSize.data('diameter'),'x',selectedSize.data('width')].join('');

                            $.each($this.productDetails.sizeOptions, function (val) {
                                var sizeObject = this;
                                if(val == sizeVar){
                                    getOptionSize(sizeObject);
                                }
                            });
                        }else {
                            getOptionSize($this.productDetails.sizeOptions);
                        }
                        var defaultOption = $(selectSizeElem).find('option:selected');
                        $this._loadSizePrice(defaultOption.data('final-price'));
                        sizeFieldElem.toggle(true);
                    }else{
                        sizeFieldElem.toggle(false);
                        $this._loadSizePrice($this.productDetails.Price.OnlinePrice);
                    }

                    // brand / badge image
                    if(valuesButton.Badge.Image.length){
                        // for wheels
                        modalLogo.attr('src', valuesButton.Badge.Image);
                    } else{
                        // for tyres
                        modalLogo.attr('src', valuesButton.Brand.Image);
                    }
                    // product image
                    modalImage.attr('src', valuesButton.ProductImage);
                    //product name
                    productName.text(valuesButton.Name);

                    $this.setModalState('form');
                },

                // this was replaced by _loadProductOptions to reduced server request
                '_loadProduct': function () {

                    var current_url = '';
                    var size = '';
                    if ($('.size-wheels').length > 0) {
                        size = '/size/' + $('.size-wheels').val();
                    } else {
                        var str = $('.t-size').text();
                        str = str.replace('Front: ', '').replace('Rear: ', '').replace(', ', ' ').replace('/', '-').replace('/', '-').replace('Currently displaying all tyre sizes', '');
                        size = '/size/' + str;
                    }
                    current_url = '?c_url=' + window.location.pathname;
                    var $this = beauComponents.addProductModal,
                            requestUrl = $this.requestUrl + $this.currentProductId + size + current_url;

                    var request = $.ajax({
                        type: 'GET',
                        url: requestUrl,
                        dataType: 'json',
                        cache: false,
                        success: function (data) {
                            var selectSizeElem = $this.modalElem.find('.select-size'),
                                    sizeFieldElem = $this.modalElem.find('.field-size');

                            if (typeof data.Sizes !== 'undefined' && data.Sizes != null) {

                                $.each(data.Sizes, function () {
                                    var size = this;

                                    selectSizeElem.append(
                                            $('<option />')
                                            .html(size.label)
                                            .attr('data-sku', size.p_id)
                                            .val(size.id)
                                            );
                                });

                                beauComponents.loadPrice(data.Sizes[0].p_id);

                                sizeFieldElem.show();
                            } else {
                                sizeFieldElem.hide();


                                $(".total-price").html(beauComponents.formatMoney(data.product_price));
                                $(".total-price").attr('data-initprice', data.product_price);
                            }
                            $(".select-quantity").prop('selectedIndex', 0);
                            $('.model-field-product-id').val($this.currentProductId);

                            $this.setModalState('form');
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // update modal state to "error"
                            $this.setModalState('error');

                            // update error message
                            var message = '[' + jqXHR.status + '] ' + jqXHR.statusText;
                            $this.modalElem.find('.popup-error .error-msg').html(message);
                        }
                    });
                },
            },
            // this was replaced by _loadSizePrice to reduced server request
            'loadPrice': function (id) {
                if (typeof id !== "undefined") {
                    $('.total-price').html('<i class="fa fa-refresh fa-spin"></i>');
                    $.ajax({
                        method: 'GET',
                        url: '/searchtyre/index/getprice/product_id/' + id,
                    }).done(function (price) {
                        $(".total-price").html(beauComponents.formatMoney(price));
                        $(".total-price").attr('data-initprice', price);
                    });
                }
            },
            'formatMoney': function (amount) {
                var new_amount = parseFloat(amount).toFixed(2);
                var parts = new_amount.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return '$' + parts.join(".");
            },
            'formatPrice': function (item, dontBind) {
                var $priceElem = $(item);

                if ($priceElem.length) {

                    // update each of the prices
                    $priceElem.each(function () {
                        var newPriceText = '$',
                                $thisPrice = $(this),
                                priceTxt = $thisPrice.text(),
                                matches = priceTxt.match(/(\$|)([0-9,]+)(\.|)(\d{2})(\/|)(\w*)/);

                        if (matches != null) {
                            newPriceText += matches[2] + '<sup>.' + matches[4] + '</sup>';

                            if (matches[6] != '') {
                                newPriceText += '<sub>/' + matches[6] + '</sub>';
                            }

                            $thisPrice.html(newPriceText);
                        }
                    });

                    // Create new bind event 'update-price' to run this function
                    if (!dontBind) {
                        $('body').on('update-price', function () {
                            beauComponents.formatPrice(item, true);
                        });
                    }
                }
            },
            'BatteriesPrice': function () {
                // not in use
                console.error('BatteriesPrice function should not be in use');
            },
            'WheelsFunctions': function () {
                // not in use
                console.error('WheelsFunctions function should not be in use');
                /*
                 if ($("#wheel-front").size() > 0) {

                 $( "#wheel-front , #select-front-qty , #wheel-rear , #select-rear-qty" ).change(function() {
                 $("#rear-prod").val($("#wheel-rear").val());
                 $("#rear-qty").val($("#select-rear-qty").val());
                 $("#front-prod").val($("#wheel-front").val());
                 $("#front-qty").val($("#select-front-qty").val());
                 });

                 $("#product-addtocart-button").click(function() {
                 $( "#frontrearform").submit();
                 });

                 $( "#wheel-front").trigger("change");
                 $("#product-addtocart-button").removeAttr("disabled");
                 $("#product_addtocart_form").attr("action",$("#front_rear_action_url").val());
                 }
                 */
            },
            'showPageNotice': function (pageAlert, alertSource) {
                var $this = this,
                        $pageAlert = $(pageAlert),
                        $alertSource = $(alertSource),
                        $alertElems = $(alertSource).children('li');

                if ($pageAlert.length && $alertSource.length && $alertElems.length) {

                    var writePageNotice = function (key, li) {

                        var iconTxt = '<i class="fa fa-arrow-circle-right"></i> ';

                        var noticeText = $(li).find('span').html();

                        var $newAlertBox = $('<div />')
                                .attr('data-alert', true)
                                .addClass('alert-box success radius')
                                .html(iconTxt + noticeText)
                                .appendTo($pageAlert);

                        var $closeButton = $('<a />')
                                .attr('href', '#')
                                .addClass('close')
                                .html('<i class="fa fa-times"></i>')
                                .appendTo($newAlertBox);

                        $(document).foundation('alert', 'reflow');
                    }

                    $alertElems.each(writePageNotice);
                }
            },
            'fullPageLoadIndicator': {
                'loadingInterval': null,
                'show': function () {
                    // create full page load indictator and append to body

                    var $loadingHolder = $('<div/>')
                            .addClass('page-loading-holder')
                            .appendTo('body');

                    var $messgae = $('<div>')
                            .html('<i class="fa fa-refresh fa-spin fa-2x"></i>Loading, Please Wait')
                            .appendTo($loadingHolder);
                },
                'hide': function () {
                    var $this = this;

                    // fade out and delete load indictator
                    $('.page-loading-holder').fadeOut(300, function () {
                        $(this).remove();
                    });

                    // stop interval
                    clearInterval($this.loadingInterval);
                },
                'autoShow': function (startItem, endItem) {
                    var $this = this,
                            $startElem = $(startItem);

                    // if there is a elemend with the startItem class then show load indictator
                    if ($startElem.length) {
                        $this.show();

                        var checkForEndLoading = function () {
                            if ($(endItem).length) {
                                $this.hide();
                            }
                        }

                        // run interval to look for endItem
                        $this.loadingInterval = setInterval(checkForEndLoading, 1000);
                    }
                }
            },
            'pricingVisibility': function ($items, isFreeProduct) {
                var priceLabels = {},
                    priceValues = {};
                    //isFreeProduct = $('.super-attribute-select').find('option:selected').attr('data-free-product');
                // console.log('$items: ', $items)
                if ($items.length == 2) {
                    var assignElems = function (key, elem) {

                        if ($(elem).is('.rrp-price')) {
                            var type = 'rrp';
                        } else {
                            var type = 'online';
                        }


                        var cleanPrice = $(elem).text() !== 'N/A'? parseFloat($(elem).text().replace(/[^0-9]/g, "")) : 'N/A';
                        priceLabels[type] = $(elem);
                        priceValues[type] = cleanPrice;

                        // console.log('------', $(elem).text())
                        // console.log('label: ', priceLabels[type])
                        // console.log('type: ', priceValues[type])
                    }

                    // assign each label passed in $items
                    $items.each(assignElems);

                    this.productPricing.const.$sectionCartTotal.show();
                    this.productPricing.const.$sectionMidCart.show();
                    this.productPricing.const.$sectionStoreAndStockAvailability.show();

                    //console.log("priceValues['online']: ", priceValues['online'])
                    priceLabels['online'].parent().find('.online-price').hide();
                    priceLabels['online'].parent().find('p.call-for-pricing').hide();
                    priceLabels['online'].parent().find('p.free-product').hide();
                    priceLabels['online'].hide();
                    priceLabels['rrp'].parent().hide();
                    if (isFreeProduct) {
                        priceLabels['online'].parent().find('p.free-product').show();
                    } else if (priceValues['online'] === 'N/A') {
                        priceLabels['online'].parent().find('.online-price').show();
                    } else if (priceValues['online'] > 0) {
                        priceLabels['online'].show();

                        //console.log(priceValues['rrp'], priceValues['online']);
                        if (priceValues['rrp'] > priceValues['online']) { // rrp is more then online price: show rrp
                            priceLabels['rrp'].parent().show();
                        }
                    } else {
                        priceLabels['online'].parent().find('p.call-for-pricing').show();
                    }

                    // update Add To Cart button (disable if price hidden)
                    this.productPricing.updateAddToCart();
                }

            },
            'productPricing': {
                'const': {},
                'totalQty': 0,
                'totalValue': 0,
                'frontPrice': 0,
                'rearPrice': 0,
                'isBatteryCart': false,
                'PDPType': '',
                'init': function (item) {
                    var $this = this,
                        $cart = $(item);

                    if ($cart.length) {
                        // cache re-used DOM elements into this.const
                        $this.setConst($cart);


                        if ($this.const.$mainPrice.length) {
                            // its battery page
                            $this.isBatteryCart = true;
                        }

                        // checking what PDP type
                        $this.PDPType = $cart.attr('data-pdp-type');

                        //console.log('$this.isBatteryCart: ', $this.isBatteryCart)

                        // wrap functions for binds
                        var updateSizes = function () {
                            $this.updateSizes($(this));
                            $this.updateSpecificationsTable();
                        },
                        updateQty = function () {
                            $this.updateQty($(this));
                        },
                        updateExtra = function () {
                            $this.updateExtra($(this));
                        }

                        // bind events to DOM elements
                        $this.const.$selectSizes.on('change', updateSizes);
                        $this.const.$selectQtys.on('change', updateQty);
                        $this.const.$extras.on('click', updateExtra);
                        $this.const.$addToCartButton.on('click', $this.addToCart);
                        $this.const.$window.on('scroll', $this.handleScroll).scroll();

                        // set starting Add To Cart state
                        $this.updateAddToCart();

                        // trigger 'change' on the selects to setup onload values
                        $this.const.$selectSizes.each(function(key, elem){
                            $this.updateSizes($(elem))
                            $this.updateSpecificationsTable();
                        })
                    }
                },
                'setConst': function ($cart) {
                    var $specsTable = $('.specification-table');

                    this.const = {
                        // for cached DOM elements
                        '$window': $(window),
                        '$cart': $cart,
                        '$selectSizes': $cart.find('.select-size'),
                        '$selectQtys': $cart.find('.select-qty'),
                        '$showPrices': $cart.find('.show-price'),
                        '$priceTotal': $cart.find('.price-total-value'),
                        '$extras': $cart.find('.extras-checkbox'),
                        '$mainSpecificationsTable': $('#main-specifications-table'),
                        '$specificationsContainer': $('.specifications-container'),
                        '$specsTable': $specsTable,
                        '$specsTableBody': $specsTable.find('tbody'),
                        '$productAddToCartForm': $cart.find('#product_addtocart_form'),
                        '$cartLoading': $cart.find('#cart-loading'),
                        '$checkoutContainer': $cart.find('#checkout-container'),
                        '$anchoredBtns': $cart.find('#anchored-ctas'),
                        '$productBtns': $cart.find('.product-ctas'),
                        '$addToCartButton': $cart.find('.btn-cart'),
                        '$checkoutButton': $cart.find('.btn-checkout'),
                        '$stockMessage': $cart.find('.stock-msg'),
                        '$callForStockButton': $cart.find('.btn-call-for-stock'),
                        '$requestPriceButton': $cart.find('.btn-request-price'),
                        '$bookFittingButton': $cart.find('.btn-book-a-fitting'),
                        '$sectionCartTotal': $cart.find('.cart-total'),
                        '$sectionStoreAndStockAvailability': $cart.find('.store-and-stock-availability'),
                        '$sectionMidCart': $cart.find('.mid-cart'),
                        '$frontSizeDropDown': $cart.find('#wheel-front'),
                        '$rearSizeDropDown': $cart.find('#wheel-rear'),
                        '$frontQtyDropDown': $cart.find('#select-front-qty').length? $cart.find('#select-front-qty') : $cart.find('#qty'), // if not seen then its for battery page
                        '$rearQtyDropDown': $cart.find('#select-rear-qty').length? $cart.find('#select-rear-qty') : $cart.find('#qty'), // if not seen then its for battery page
                        '$mainPrice': $cart.find('.select-size[data-target-price="main-price"]'),
                        // config
                        'showPriceSuffix': '/tyre'
                    }
                },
                'handleScroll': function() {
                    var $this = beauComponents.productPricing,
                        productBtnsOffsetTop = $this.const.$productBtns.offset().top,
                        wScroll = $this.const.$window.scrollTop(),
                        winHeight = $this.const.$window.height(),
                        targetTop = productBtnsOffsetTop - (winHeight - 50);

                    if (wScroll > targetTop) {
                        $this.const.$anchoredBtns.addClass('hide');
                    } else {
                        $this.const.$anchoredBtns.removeClass('hide');
                    }
                },
                'addToCart': function (event) {
                    event.preventDefault();
                    var $this = beauComponents.productPricing,
                        $productAddToCartForm = $this.const.$productAddToCartForm,
                        $cartLoading = $this.const.$cartLoading,
                        postUrl = $productAddToCartForm.attr('data-add-to-cart-url'),
                        totalQty = $this.getTotalQty();

                    //console.log('totalQty: ',totalQty);

                    $.ajax({
                        url: postUrl,
                        type: 'post',
                        data: $productAddToCartForm.serialize(),
                        cache: false,
                        beforeSend: function (res) {
                            $cartLoading.show();
                            $productAddToCartForm.find(':input').prop('disabled', true);
                            $this.const.$addToCartButton.html('<i class="fa fa-refresh fa-spin"></i> Adding...');
                        },
                        success: function (res) {
                            $cartLoading.hide();
                            $this.const.$addToCartButton.hide();
                            $this.const.$callForStockButton.hide();
                            $this.const.$requestPriceButton.hide();
                            $this.const.$bookFittingButton.hide();
                            $this.const.$checkoutContainer.show();
                            $this.const.$checkoutButton.prop('disabled', false);

                            // update anchored CTA's
                            $this.const.$anchoredBtns.find('.anchored-btn').addClass('hide');
                            $this.const.$anchoredBtns.find('.anchored-btn').attr('style', '');
                            $this.const.$anchoredBtns.removeClass('hide');
                            $this.const.$anchoredBtns.find('.btn-checkout').removeClass('hide');
                            $this.handleScroll();

                            // update checkout button text and its title
                            // by default the text: Product added to Cart
                            setTimeout(function(){
                                var updatedText = 'Checkout';
                                $this.const.$checkoutButton.html(updatedText).attr('title', updatedText);
                            }, 2000)

                            // update header Cart badge
                            window.incrementBadge('Cart', res.count);
                        },
                        error: function(res) {
                            $cartLoading.hide();
                            $productAddToCartForm.find(':input').prop('disabled', false);
                            $this.const.$addToCartButton.html('Add to Cart');
                        }
                    });
                },
                'getTotalQty': function () {
                    var $this = this,
                            total = 0;

                    // private function: add qty to total count
                    var getQty = function (key, elem) {
                        total += parseInt($(elem).val());
                    }

                    // loop through all qty selects
                    $this.const.$selectQtys.each(getQty);
                    // cache total
                    $this.totalQty = total;

                    return total;
                },
                'updateSizes': function ($field) {
                    var $this = beauComponents.productPricing,
                        targetSelector = $field.attr('data-target-price'),
                        $targetPrices = $('.' + targetSelector),
                        $rrpPrice = $targetPrices.parent().find('.rrp-price'),
                        $onlinePrice = $targetPrices.parent().find('.online-price'),
                        $qty = null,
                        qtyID = '',
                        onlinePrice = 0,
                        optionsLength = 0,
                        isFreeProduct = false;

                        if (targetSelector === 'front-price') {
                            qtyID = '#select-front-qty';
                        } else if (targetSelector === 'rear-price') {
                            qtyID = '#select-rear-qty';
                        } else {
                            // battery
                            qtyID = '#qty';
                        }

                        if (targetSelector === 'front-price') {
                            qtyID = '#select-front-qty';
                        } else if (targetSelector === 'rear-price') {
                            qtyID = '#select-rear-qty';
                        } else {
                            // battery
                            qtyID = '#qty';
                        }

                        $qty = $(qtyID);

                    if ($field.is('select')) {
                        if ($field.find('option:selected').length) { // when there's an option selected, sometimes its empty
                            onlinePrice = parseFloat($field.find('option:selected').attr('data-online-price'));
                        }
                        isFreeProduct = ($field.find('option:selected').attr('data-free-product')  == '1' && onlinePrice <= 0)? true : false;
                    } else {
                        onlinePrice = parseFloat($field.attr('data-online-price'));
                        isFreeProduct = ($field.attr('data-free-product')  == '1' && onlinePrice <= 0)? true : false;
                    }

                    // private function to get price form data attr and format with suffix (eg "/ea")
                    var getPriceValue = function(dataName) {
                        var price = 'N/A',
                            $dataElem = $field.parent().find('[data-' + dataName + ']:selected');

                        if ($dataElem.length) {
                            price = $dataElem.data(dataName);
                        } else {
                            $dataElem = $field.parent().find('[data-' + dataName + ']');
                            if ($dataElem.length) {
                                price = $dataElem.data(dataName);
                            }
                        }

                        return price;
                    }

                    var formatPriceSuffix = function (price) {
                        if ($this.PDPType === "tyres") {
                            price += '/tyre';
                        } else if ($this.PDPType === "wheels") {
                            price += '/wheel';
                        }

                        return price;
                    }

                    var getPrice = function (dataName) {
                        var price = getPriceValue(dataName)

                        if (dataName === 'rrp-price' && price !== 'N/A') {
                            var rrpPrice = parseFloat(getPriceValue(dataName)),
                                onlinePrice = parseFloat(getPriceValue('online-price')),
                                priceDiscounted = rrpPrice - onlinePrice;

                            price = 'Save $' + priceDiscounted + ', was $' + rrpPrice;
                        } else if (price !== 'N/A') {
                            price = formatPriceSuffix(price);
                        }

                        return price;
                    }

                    // private function to update stock Quantity
                    var updateQty = function (dataName) {
                        var qty = 0,
                            $dataElem = $field.parent().find('[data-' + dataName + ']:selected');

                        if ($dataElem.length) {
                            qty = $dataElem.data(dataName);
                            $qty.find('option').remove();

                            if (qty > 0) {
                                if (qty > 6) {
                                    qty = 6;
                                }

                                for (var i = 0; i <= qty; i++) {
                                    $qty.append(
                                        $("<option></option>")
                                            .attr("value", i)
                                            .text(i)
                                    );
                                }
                                $qty.prop('selectedIndex', 1);
                            } else {
                                $qty.append(
                                    $("<option></option>")
                                        .attr("value", 0)
                                        .text(0)
                                );
                            }
                        }
                    }

                    // update quantity from total from size stock quantity
                    updateQty('stock-qty');

                    if ($field.is('select')) {
                        onlinePrice = parseFloat($field.find('option:selected').attr('data-online-price')),
                        isFreeProduct = ($field.find('option:selected').attr('data-free-product')  == '1' && onlinePrice <= 0)? true : false;
                        optionsLength = $field.find('option').length;

                        if (optionsLength === 0) {
                            // BFT-2519: disable select box when no sizes and make 0 the quantity and disable it
                            $field.prop('disabled', true);
                            $qty.find('option')
                                .remove()
                                .end()
                                .append('<option value="0">0</option>')
                                .prop('disabled', true);
                        }
                    } else {
                        onlinePrice = parseFloat($field.attr('data-online-price')),
                        isFreeProduct = ($field.attr('data-free-product')  == '1' && onlinePrice <= 0)? true : false;
                    }

                    if ($field.is('select')) {
                        onlinePrice = parseFloat($field.find('option:selected').attr('data-online-price')),
                        isFreeProduct = ($field.find('option:selected').attr('data-free-product')  == '1' && onlinePrice <= 0)? true : false;
                        optionsLength = $field.find('option').length;

                        if (optionsLength === 0) {
                            // BFT-2519: disable select box when no sizes and make 0 the quantity and disable it
                            $field.prop('disabled', true);
                            $qty.find('option')
                                .remove()
                                .end()
                                .append('<option value="0">0</option>')
                                .prop('disabled', true);
                        }
                    } else {
                        onlinePrice = parseFloat($field.attr('data-online-price')),
                        isFreeProduct = ($field.attr('data-free-product')  == '1' && onlinePrice <= 0)? true : false;
                    }

                    // update shown RRP price
                    if ($rrpPrice.length) {
                        $rrpPrice.html(getPrice('rrp-price'));
                    }

                    // update shown online price
                    if ($onlinePrice.length) {
                        if (targetSelector == 'front-price')
                            $this.frontPrice = getPrice('online-price')

                        if (targetSelector == 'rear-price')
                            $this.rearPrice = getPrice('online-price')

                        if (targetSelector == 'main-price') {
                            // for battery
                            $this.frontPrice = getPrice('online-price')
                            $this.rearPrice = getPrice('online-price')
                        }

                        $onlinePrice.html(getPrice('online-price'));
                        $('body').trigger('update-price');
                    }

                    // update total
                    $this.updateTotal();

                    // update pricing visibility
                    //beauComponents.pricingVisibility($targetPrices, isFreeProduct);
                    $this.pricingVisibility($field, targetSelector, isFreeProduct);
                },
                'pricingVisibility': function($field, targetSelector, isFreeProduct) {
                    var $this = beauComponents.productPricing,
                        $priceRows = $('#' + targetSelector + '-row'),
                        onlinePrice = 0,
                        rrpPrice = 0;

                    if ($field.is('select')) {
                        if ($field.find('option:selected').length) { // when there's an option selected, sometimes its empty
                            onlinePrice = parseFloat($field.find('option:selected').attr('data-online-price'));
                            rrpPrice = parseFloat($field.find('option:selected').attr('data-rrp-price'));
                        } else {
                            onlinePrice = 'N/A';
                        }
                    } else {
                        onlinePrice = parseFloat($field.attr('data-online-price'));
                        rrpPrice = parseFloat($field.attr('data-rrp-price'));
                    }

                    $this.const.$sectionCartTotal.show();
                    $this.const.$sectionMidCart.show();
                    $this.const.$sectionStoreAndStockAvailability.show();

                    $priceRows.find('.online-price').hide();
                    $priceRows.find('p.call-for-pricing').hide();
                    $priceRows.find('p.free-product').hide();
                    $priceRows.find('.price-discounted').hide();

                    if (isFreeProduct) {
                        $priceRows.find('p.free-product').show();
                    } else if (onlinePrice === 'N/A') {
                        $priceRows.find('.online-price').show();
                    } else if (onlinePrice > 0) {
                        $priceRows.find('.online-price').show();
                        if (rrpPrice > onlinePrice) { // rrp is more then online price: show rrp
                            $priceRows.find('.price-discounted').show();
                        }
                    } else {
                        $priceRows.find('p.call-for-pricing').show();
                    }

                    // update Add To Cart button (disable if price hidden)
                    $this.updateAddToCart();
                },
                'updateQty': function ($select) {
                    var $this = beauComponents.productPricing;

                    // just update total
                    $this.updateTotal();

                    // update Add To Cart button
                    $this.updateAddToCart();
                },
                'updateExtra': function ($input) {
                    var $this = beauComponents.productPricing,
                        extraPrice = parseFloat($input.data('price'));

                    if (extraPrice > 0) {
                        $this.updateTotal();
                    }
                },
                'isFreeProduct': function (type, price) {
                    var $this = this,
                        isFree = false;

                    if ($this.isBatteryCart) {
                        // no rear and front in battery page
                        if (type === 'front-price') {
                            if ($this.const.$mainPrice.length) {
                                return ($this.const.$mainPrice.attr('data-free-product') == '1' && price <= 0)? true : false
                            }
                        }
                    } else {
                        if (type === 'front-price') {
                            if ($this.const.$frontSizeDropDown.length) {
                                return ($this.const.$frontSizeDropDown.find('option:selected').attr('data-free-product')  == '1' && price <= 0)? true : false
                            }


                        } else if (type === 'rear-price') {
                            if ($this.const.$rearSizeDropDown.length) {
                                return ($this.const.$rearSizeDropDown.find('option:selected').attr('data-free-product') == '1' && price <= 0)? true : false
                            }
                        }
                    }

                    return false
                },
                'isEligibleTo': function(type, response) {
                    response = response || 'cart';

                    var $this = this,
                        totalVal = 0,
                        frontPrice = parseFloat($this.frontPrice),
                        frontIsFree = $this.isFreeProduct(type, frontPrice),
                        frontQtyDropDownValue = parseInt($this.const.$frontQtyDropDown.val()),
                        frontIsEligibleToCartBtn = (frontIsFree && frontQtyDropDownValue > 0)? true : (frontPrice > 0 && frontQtyDropDownValue > 0),
                        frontIsEligibleToRequestPriceBtn = !frontIsFree && frontPrice <=0 && $this.frontPrice !== 'N/A',
                        rearPrice = parseFloat($this.rearPrice),
                        rearIsFree = $this.isFreeProduct(type, rearPrice),
                        rearQtyDropDownValue = parseInt($this.const.$rearQtyDropDown.val()),
                        rearIsEligibleToCartBtn = (rearIsFree && rearQtyDropDownValue > 0)? true : (rearPrice > 0 && rearQtyDropDownValue > 0),
                        rearIsEligibleToRequestPriceBtn = !rearIsFree && rearPrice <=0 && $this.rearPrice !== 'N/A';

                    //console.log('a1: ', $this.frontPrice, frontPrice, frontIsFree, frontQtyDropDownValue, frontIsEligibleToCartBtn, frontIsEligibleToRequestPriceBtn)
                    //console.log('a2: ', $this.rearPrice, rearPrice, rearIsFree, rearQtyDropDownValue, rearIsEligibleToCartBtn, rearIsEligibleToRequestPriceBtn)

                    if (response === 'request') {
                        if (type === 'front-price') {
                            return frontIsEligibleToRequestPriceBtn;
                        } else if (type === 'rear-price') {
                            if ($this.isBatteryCart) {
                                // if this exist then it means that we are in the battery page cart
                                // if battery, then lets make cart is not eligble since no rear nad font size in battery
                                return false
                            }

                            return rearIsEligibleToRequestPriceBtn;
                        } else if (type === 'main-price') {

                        }
                    } else {
                        if (type === 'front-price') {
                            return frontIsEligibleToCartBtn;
                        } else if (type === 'rear-price') {
                            if ($this.isBatteryCart) {
                                // if this exist then it means that we are in the battery page cart
                                // if battery, then lets make cart is not eligble since no rear nad font size in battery
                                return false
                            }

                            return rearIsEligibleToCartBtn;
                        } else if (type === 'main-price') {

                        }
                    }

                    return false
                },
                'updateAddToCart': function () {
                    var $this = this,
                        totalValue = parseFloat($this.totalValue),
                        qtyCount = this.getTotalQty(),
                        frontIsEligibleToCartBtn = $this.isEligibleTo('front-price'),
                        rearIsEligibleToCartBtn = $this.isEligibleTo('rear-price'),
                        frontIsEligibleToRequestPriceBtn = $this.isEligibleTo('front-price', 'request'),
                        rearIsEligibleToRequestPriceBtn = $this.isEligibleTo('rear-price', 'request');

                    //console.log('Request: ', frontIsEligibleToRequestPriceBtn, rearIsEligibleToRequestPriceBtn)

                    // // This is breaking the product stock component
                    // if (qtyCount) {
                    //     $this.const.$stockMessage.toggle(false);
                    // } else {
                    //     $this.const.$stockMessage.toggle(true);
                    // }

                    // hide first
                    $this.const.$cart.find('.cta-button').attr('style', '');
                    $this.const.$anchoredBtns.addClass('hide');
                    $this.const.$anchoredBtns.find('.anchored-btn').attr('style', '');
                    $this.const.$anchoredBtns.find('.anchored-btn').addClass('hide');

                    if (frontIsEligibleToCartBtn && rearIsEligibleToCartBtn) {
                        // both front and rear have price then show only 1 button(cart)
                        $this.const.$addToCartButton.removeClass('hide');
                        $this.const.$callForStockButton.addClass('hide');
                        $this.const.$requestPriceButton.addClass('hide');
                        $this.const.$bookFittingButton.addClass('hide');

                        // update anchored CTA's
                        $this.const.$anchoredBtns.find('.anchored-btn').attr('style', '');
                        $this.const.$anchoredBtns.find('.anchored-btn').addClass('hide');
                        $this.const.$anchoredBtns.removeClass('hide').find('.btn-cart').removeClass('hide');

                    } else if (frontIsEligibleToCartBtn || rearIsEligibleToCartBtn) {
                        // either both frotn and rear are eligible to cart btn
                        if (frontIsEligibleToRequestPriceBtn || rearIsEligibleToRequestPriceBtn) {
                            $this.const.$addToCartButton.removeClass('hide');
                            $this.const.$callForStockButton.removeClass('hide');
                            $this.const.$requestPriceButton.removeClass('hide');
                            $this.const.$bookFittingButton.removeClass('hide');

                            // update anchored CTA's
                            $this.const.$anchoredBtns.find('.anchored-btn').attr('style', '');
                            $this.const.$anchoredBtns.find('.anchored-btn').addClass('hide');
                            $this.const.$anchoredBtns.removeClass('hide').find('.btn-cart').removeClass('hide');
                        } else {
                            $this.const.$addToCartButton.removeClass('hide');
                            $this.const.$callForStockButton.addClass('hide');
                            $this.const.$requestPriceButton.addClass('hide');
                            $this.const.$bookFittingButton.addClass('hide');

                            // update anchored CTA's
                            $this.const.$anchoredBtns.find('.anchored-btn').attr('style', '');
                            $this.const.$anchoredBtns.find('.anchored-btn').addClass('hide');
                            $this.const.$anchoredBtns.removeClass('hide').find('.btn-cart').removeClass('hide');
                        }
                    } else {
                        if (frontIsEligibleToRequestPriceBtn || rearIsEligibleToRequestPriceBtn) {
                            $this.const.$addToCartButton.addClass('hide');
                            $this.const.$callForStockButton.removeClass('hide');
                            $this.const.$requestPriceButton.removeClass('hide');
                            $this.const.$bookFittingButton.removeClass('hide');
                            $this.const.$sectionCartTotal.hide() // BCC-500 - hide total when total is 0.00
                            $this.const.$sectionStoreAndStockAvailability.hide() // BFT-2494 - hide 'store-and-stock-availability' hide when total is 0.00

                            // update anchored CTA's
                            $this.const.$anchoredBtns.find('.anchored-btn').attr('style', '');
                            $this.const.$anchoredBtns.find('.anchored-btn').addClass('hide');
                            $this.const.$anchoredBtns.removeClass('hide').find('.btn-request-price').removeClass('hide');
                        } else {
                            // not eligible to cart for both front and rear, show 3 buttons(except cart)
                            $this.const.$addToCartButton.addClass('hide');
                            $this.const.$requestPriceButton.addClass('hide');
                            $this.const.$bookFittingButton.addClass('hide');

                            if ($this.frontPrice === 'N/A' && $this.rearPrice === 'N/A')
                                $this.const.$callForStockButton.addClass('hide');
                            else
                                $this.const.$callForStockButton.removeClass('hide');
                        }
                    }
                },
                'updateTotal': function () {
                    var $this = this,
                        totalVal = 0,
                        frontIsEligibleToCartBtn = $this.isEligibleTo('front-price', 'cart'),
                        rearIsEligibleToCartBtn = $this.isEligibleTo('rear-price', 'cart'),
                        frontQtyDropDownValue = parseInt($this.const.$frontQtyDropDown.val()),
                        rearQtyDropDownValue = parseInt($this.const.$rearQtyDropDown.val());

                    //console.log('Cart1: ', frontIsEligibleToCartBtn, rearIsEligibleToCartBtn)
                    // private function to get the price values from a size/qty select
                    var getSelectVals = function (key, elem) {
                        if ($(elem).length) {
                            // get size select
                            var $sizeSelect = $(elem),
                            targetPrice = $sizeSelect.data('target-price'),
                            sizeVal = 0;

                            if ($sizeSelect.is('select')) {
                                // for selects (tyres and wheels)
                                if ($sizeSelect.find('option:selected').length) {
                                    var $selectedOption = $sizeSelect.find('option:selected')
                                    sizeVal = parseFloat($selectedOption.data('online-price'));
                                }
                            } else {
                                // for non-selects (batteries)
                                sizeVal = parseFloat($sizeSelect.data('online-price'));
                            }

                            // get matching qty select
                            var $qtySelect = $('.select-qty[data-target-price="' + targetPrice + '"]'),
                                qtyVal = parseInt($qtySelect.val());
                            var subtotal = sizeVal * qtyVal;
                            totalVal += subtotal;
                        }
                    }

                    // private function to get the price of extras
                    var getExtraVals = function (key, elem) {
                        var $input = $(elem),
                            extraValPrice = parseFloat($input.data('price')),
                            extraValFront = 0,
                            extraValRear = 0,
                            extraValOnce = 0,
                            multiplyQty = $input.data('multiply-qty'),
                            totalQty = $this.getTotalQty();

                        if (multiplyQty === 'yes') {
                            if (frontIsEligibleToCartBtn) {
                                extraValFront = extraValPrice * frontQtyDropDownValue;
                            }
                            if (rearIsEligibleToCartBtn) {
                                extraValRear = extraValPrice * rearQtyDropDownValue;
                            }
                        } else {
                            if (frontIsEligibleToCartBtn || rearIsEligibleToCartBtn) {
                                // if either in both is eligble to cart then add the value
                                extraValOnce += extraValPrice;
                            }
                        }

                        if (extraValPrice > 0 && $input.is(':checked')) {
                            totalVal += (extraValFront + extraValRear + extraValOnce);
                        }
                    }

                    // loop through the selects and get values
                    $this.const.$selectSizes.each(getSelectVals);

                    // loop through extas checkboxes and get values
                    $this.const.$extras.each(getExtraVals);

                    // show total
                    $this.totalValue = totalVal;
                    $this.const.$priceTotal.html('$' + totalVal.toFixed(2));
                    $('body').trigger('update-price');

                    //check if tyre is compatible with vehicle
                    $this.updateCompatibilitySection();
                },
                'updateSpecificationsTable': function () {
                    var $this = beauComponents.productPricing;
                    $this.writeSpecTable();
                },
                'updateCompatibilitySection': function () {
                    var $this = this,
                        $compatibilityParent = $('.compatibility');

                    if ($compatibilityParent.length) {
                        var $superAttributeSelect = $('.super-attribute-select'),
                            $sizes = $compatibilityParent.data('selected-sizes'),
                            matchSizeText = "",
                            $sizeText = "",
                            $qtyText = "",
                            sku = "",
                            $matchedContainer = $compatibilityParent.find('.matched-size'),
                            $compatible = $compatibilityParent.find('.compatible'),
                            $nonCompatible = $compatibilityParent.find('.not-compatible'),
                            sizesArray = $sizes.split(','),
                            sizesCount = sizesArray.length-2,
                            isActive = false,
                            i=0,
                            $sizeValueBooking = $('#selectedSizeValue-book'),
                            $sizeValueRequestPrice = $('#selectedSizeValue'),
                            $qtyValueBooking = $('#selectedQuantity-book'),
                            $qtyValueRequestPrice = $('#selectedQuantity'),
                            $skuValueBooking = $('#selectedProductSKU-book'),
                            $configurableAttributeId = $('#configurableAttributeId'),
                            $configurableAttributeValueId = $('#configurableAttributeValueId'),
                            $configurableAttributeIdBook = $('#configurableAttributeId-book'),
                            $configurableAttributeValueIdBook = $('#configurableAttributeValueId-book'),
                            $configurableAttributeValueIdRear = $('#configurableAttributeValueIdRear'),
                            $configurableAttributeValueIdRearBook = $('#configurableAttributeValueId-book-rear'),
                            $skuValueRequestPrice = $('#selectedProductSKU'),
                            $frontSizeDropDown = $this.const.$frontSizeDropDown,
                            $rearSizeDropDown = $this.const.$rearSizeDropDown,
                            selectedId = '',
                            frontValue = 0,
                            rearValue = 0,
                            frontIsEligibleToCartBtn = $this.isEligibleTo('front-price'),
                            rearIsEligibleToCartBtn = $this.isEligibleTo('rear-price');

                        if ($superAttributeSelect.length) {
                            $sizeText = $('.super-attribute-select option:selected').map(function () {
                                return $(this).text();
                            }).get().join(', ');

                            matchSizeText = $this.getMatchPriceText();

                            $qtyText = $('.select-qty option:selected').map(function () {
                                return $(this).text();
                            }).get().join(', ');


                            //check if there is compatible size
                            // TODO: missing in batteries this logic
                            for(i=0;i<=sizesCount;i++) {
                                if($sizeText.indexOf(sizesArray[i])!== -1) {
                                    isActive = true;
                                    break;
                                }
                            }

                            selectedId = $('.super-attribute-select').prop('id').substring(9);
                            frontValue = $frontSizeDropDown.find('option:selected').length? $frontSizeDropDown.find('option:selected').val() : 0;
                            rearValue = $rearSizeDropDown.find('option:selected').length? $rearSizeDropDown.find('option:selected').val() : 0;

                            if (frontIsEligibleToCartBtn)
                                frontValue = 0;

                            if (rearIsEligibleToCartBtn)
                                rearValue = 0;

                            // get the sku from array
                            if ($superAttributeSelect.find('option:selected').length) {
                                var skuValue = $superAttributeSelect.find('option:selected').attr('data-attributes-json'),
                                    obj = jQuery.parseJSON(skuValue);

                                sku = obj.sku;
                            }
                        } else {
                            var skuValue =$this.const.$mainPrice.attr('data-attributes-json'),
                                obj = jQuery.parseJSON(skuValue);

                            sku = obj.sku;
                        }

                        // appending value of selected size, quantity and sku
                        $sizeValueBooking.attr('value', $sizeText);
                        $qtyValueBooking.attr('value', $qtyText);
                        $sizeValueRequestPrice.attr('value', $sizeText);
                        $qtyValueRequestPrice.attr('value', $qtyText);
                        $skuValueBooking.attr('value', sku);
                        $skuValueRequestPrice.attr('value', sku);

                        $configurableAttributeId.val(selectedId);
                        $configurableAttributeValueId.val(frontValue);
                        $configurableAttributeIdBook.val(selectedId);
                        $configurableAttributeValueIdBook.val(frontValue);
                        $configurableAttributeValueIdRear.val(rearValue);
                        $configurableAttributeValueIdRearBook.val(rearValue);

                        //console.log('compatibility: ', selectedId, frontValue, rearValue, frontIsEligibleToCartBtn, rearIsEligibleToCartBtn, $sizeText, $qtyText, sku)

                        if (isActive) {
                            $matchedContainer.text(matchSizeText);
                            $compatible.toggle(true);
                            $nonCompatible.toggle(false);
                        } else {
                            $nonCompatible.toggle(true);
                            $compatible.toggle(false);
                        }
                    }
                },
                'writeSpecTable': function () {
                    var $this = this,
                        docFrag = document.createDocumentFragment(),
                        $tableParent = $this.const.$specsTable.parent();

                    // private function to create <tr> rows with content
                    var writeRow = function (thCont, tdContArr, isStrong) {
                        var $tr = $('<tr/>');
                        $tr.append(
                                $('<th/>', {scope: 'row', html: thCont})
                                );

                        for (var i = 0, n = tdContArr.length; i < n; i++) {
                            var tdText = (isStrong) ? '<strong>' + tdContArr[i] + '</strong>' : tdContArr[i];

                            if (tdText) {
                                $tr.append(
                                        $('<td/>', {class: 'td-value', html: tdText})
                                        );
                            } else {
                                $tr.append(
                                        $('<td/>', {class: 'td-empty', html: '<em>N/A</em>'})
                                        );
                            }
                        }
                        return $tr;
                    }

                    // private function to get spec label from specificationsTableFields
                    var getSpecLabel = function (key) {
                        var label = key;
                        if (key in specificationsTableFields) {
                            // if found in fields array, use value
                            label = specificationsTableFields[key]
                        } else {
                            // if not, use key and replace underscores and capitalize
                            label = label.replace(/_/g, ' ');
                            label = label.replace(/^(.)|\s(.)/g, function ($1) {
                                return $1.toUpperCase( );
                            })
                        }
                        return label;
                    }

                    // private function to get json values from data attr
                    var getJson = function ($field) {
                        var json = null;

                        if ($field.is('select')) {
                            if ($field.find('option:selected').length) {
                                json = $field.find('option:selected').data('attributes-json');
                            }
                        } else {
                            json = $field.data('attributes-json');
                        }

                        return json;
                    }

                    // move table to document fragment and clear
                    $this.const.$specsTable.appendTo(docFrag);
                    $this.const.$specsTableBody.empty();

                    // create a list of spec attributes
                    var arrAttrs = [];

                    if ($this.const.$selectSizes.length > 1) {
                        var selectTitles = [];

                        $this.const.$selectSizes.each(function (key, elem) {
                            var json = getJson($(elem));
                            if (json) {
                                selectTitles.push($(elem).attr('title'));
                                arrAttrs.push(json);
                            }
                        });

                        $this.const.$specsTableBody.append(writeRow('&nbsp;', selectTitles, true));
                    } else {
                        // if just one then just use that
                        arrAttrs.push(getJson($this.const.$selectSizes));
                    }

                    if (arrAttrs.length) {
                        // loop through the list of spec attributes, based on the fists
                        $.each(arrAttrs[0], function (key, val) {
                            var tdVals = [];
                            for (var i = 0, n = arrAttrs.length; i < n; i++) {
                                tdVals.push(arrAttrs[i][key]);
                            }
                            var row = writeRow(getSpecLabel(key), tdVals, false); // create table row
                            if ($(row).find('.td-value').length) { // only add row if it has at least one value
                                $this.const.$specsTableBody.append(row);
                            }
                        });


                        $this.const.$specificationsContainer.empty(); // clear all .specification-container
                        $this.const.$specsTable.prependTo($tableParent); // move back to DOM
                        $this.const.$mainSpecificationsTable.prependTo($this.const.$specificationsContainer); // append to all .specification-container
                    }

                },
                'getMatchPriceText': function() {
                    var $this = this,
                        matchSizeText = "";

                    if (!$this.isBatteryCart) {
                        var frontText = $this.const.$frontSizeDropDown.find('option:selected').length? $this.const.$frontSizeDropDown.find('option:selected').text() : 'N/A',
                            rearText = $this.const.$rearSizeDropDown.find('option:selected').length? $this.const.$rearSizeDropDown.find('option:selected').text() : 'N/A';
                        matchSizeText = "Front: " + frontText + ", Rear: " + rearText;
                    }

                    return matchSizeText;
                }
            },
            'cartItemCount': function (item) {
                var $cartCount = $(item),
                        requestUrl;

                if ($cartCount.length) {

                    // get request URL from the DOM
                    var requestUrl = $cartCount.data('request-url');

                    // private function: update cartCount with result
                    var updateCart = function (result) {
                        $cartCount.html(result);
                    }

                    // send request for count
                    $.ajax({
                        url: requestUrl,
                        cache: false,
                        context: document.body
                    }).done(updateCart);
                }
            },
            'newsLetterForm': function () {
                if (!$('form.newsletter').length) {
                    return;
                }
                // cache
                var $form = $('form.newsletter'),
                    url = $form.attr("action"),
                    method = $form.attr("method"),
                    $result = $form.find(".result"),
                    $email = $form.find("#email"),
                    emailPattern = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/,
                    havePendingAjaxRequest = false;

                // bind listener
                $form.on("submit", function (event) {
                    event.preventDefault();

                    var email = $email.val();
                    if (email != "" && emailPattern.test(email)) {
                        $result.html("");

                        // make sure that dont have a pending ajax request
                        if (havePendingAjaxRequest == false)
                        {
                            $.ajax({
                                url: url,
                                type: method,
                                data: {email: email},
                                cache: false,
                                dataType: 'json',
                                beforeSend: function () {
                                    havePendingAjaxRequest = true;
                                }
                            })
                            .done(function (res) {
                                havePendingAjaxRequest = false;
                                $email.val("");
                                $result.html(res[0].message);
                            })
                            .fail(function () {
                                havePendingAjaxRequest = false;
                                $email.val("")
                            });
                        }
                    } else
                    {
                        $result.html("Please enter a valid email.");
                    }
                });
            },
            'promoBarMessages': function () {
                if (!$('ul.promo-bar-msgs').length) {
                    return;
                }

                // cache
                var $window = $(window),
                    $msgs = $('ul.promo-bar-msgs'),
                    count = $msgs.find("li").length,
                    fadeTime = 1200,
                    delay = 8000,
                    fade = function () {
                        $msgs.find("li:first")
                                .fadeOut(fadeTime, function () {
                                    var that = this;
                                    $(that).next().fadeIn(fadeTime, function () {
                                        $(that).appendTo('.promo-bar-msgs');
                                    });
                                });
                    };

                if (count > 1) {
                    setInterval(fade, delay);
                }
            },
            'geoLocation': function(){
                var store_id = $('.location .store').attr('data-store_id');
                var default_store_id = $('.location .store').attr('data-default_store_id');

                if (default_store_id == '' && store_id == '') {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            var pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };

                            beauComponents.locateNearestStore(pos);
                        });
                    }
                } else {
                    beauComponents.locateNearestStore(null);
                }
            },
            'locateNearestStore': function (coordinate) {

                var location = '';
                var $window = $(window);
                var windowsize = $window.width();

                if (coordinate != null && (!isNaN(coordinate.lat) || !isNaN(coordinate.lng))) {
                      location = "?loc=" + coordinate.lat + ',' + coordinate.lng;
                 }

                $.ajax({
                    method: 'GET',
                    dataType: 'json',
                    url: '/slocator/json/neareststore' + location,
                    cache: false,
                    beforeSend: function () {
                        $('.location .store').html('<i class="fa fa-refresh fa-spin"></i>');
                        $('.location .link').hide();
                    },
                    success: function (data) {
                        var anchor = '';

                        if (data.error == 0) {
                            var title = '';
                            var textLength = data.title.length;
                            if (textLength > 30 && windowsize <= 650) {
                                title = data.title.substring(0, 30) + '...';
                            } else {
                                title = data.title;
                            }

                            anchor = '<a href="' + data.url + '">' + title + '</a>';
                            $('.location .store').html(anchor);
                            $('.location .link').show();
                            $('.location .link').html('Change').addClass("change");
                        } else {

                            $('.location .link').show();
                            $('.location .fa-spin').hide();
                            $('.location .link').html('Find the Nearest Store').removeClass("change");
                        }

                    }
                });
            },
            'setDefaultStore': function () {

                var setStore = function (event) {
                    event.preventDefault();

                    var id = $(this).data("id"),
                        url = $(this).attr("href");

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {id: id},
                        cache: false,
                        //dataType: 'json',
                        beforeSend: function () {
                            $('#set-your-store-btn-' + id).addClass("show-loader");
                        }
                    })
                    .done(function(res){
                        $('#set-your-store-btn-'+id).removeClass("show-loader");

                        if(res){
                            $('.your-store-btn').removeClass("show");
                            $('.set-your-store-btn').addClass("show");
                            $('#your-store-btn-'+id).addClass("show");
                            $('#set-your-store-btn-'+id).removeClass("show");
                            beauComponents.locateNearestStore(null);
                        }
                    })
                    .fail(function(){
                        $('#set-your-store-btn-'+id).removeClass("show-loader");
                    });
                };

                // bind listener
                $('body').on("click", 'a.set-your-store-btn', setStore);
            },
            'giftCardForn': function(item) {
              var $form = $(item);

              if ( $form.length ) {
                var $codesSelect = $form.find('.gccodes');

                var onSelectChange = function() {
                  var dataArray = $codesSelect.find(':selected').val().split(',');
                  $('#gc_code').val(dataArray[0]);
                  $('#redeem_amt').val(dataArray[1]);
                }

                $codesSelect.on('change', onSelectChange);
              }
            },

        'customerMobility': function(){
            var iFrame =  jQuery('#vehicleIframe');

            if(iFrame.length) {
                var vehicleEmpty = jQuery('.vehicle-empty');
                var vehicleSelector = jQuery('#vehicleSelector');
                var defaultOption = vehicleSelector.find('option:not(:disabled)');
                var loader = jQuery('.load-indicator');

                //Auto display if one option
                if (defaultOption.length == 1) {
                    loader.show();
                    iFrame.attr('src', defaultOption.val());
                    vehicleSelector.val(defaultOption.val());
                }

                vehicleSelector.change(function () {
                    loader.show();
                    if (this.value != "") {
                        vehicleEmpty.hide();
                        iFrame.attr('src', this.value);
                    } else {
                        vehicleEmpty.show();
                        iFrame.attr('src', '');
                    }
                });

                iFrame.on('load', function () {
                    loader.hide();
                });
            }
            },
            'checkoutTyresDetails': function(){

                var paramArray = {};
                var quickFinderElem = $('.quick-finder.checkout');
                if(quickFinderElem.length){
                    quickFinderElem.find('select').each(function () {
                        if($(this).val() !=""){
                            paramArray[$(this).attr('name')] = $(this).find("option:selected").text();
                        }else{
                            paramArray[$(this).attr('name')] = "";
                        }
                    });
                    quickFinderElem.find('.details-tyres').val(JSON.stringify(paramArray));
                }
            },
            'guestUserQuickFinderSearch': function(guestClass){
               if($(guestClass).length){
                   beauComponents.quickFinderSearch.init('.quick-finder');
               }
            },
            'showMore':function(){

                    $('.showOther').on('click',function(){
                        var ul = $(this).parent().find('.description-list');
                        for(var x=6;x<=ul.children().length;x++){
                            $('.description-list li.item-'+x).slideToggle();
                        }
                    });
            },
            'autoPopulateVehicle': {
                'init': function (item) {
                    var formWrapper = $(item), $this = this,
                        selectVehicle = $('#existing-vehicle'),
                        vehicleSelector = formWrapper.find('.quick-finder'),
                        emailInput = formWrapper.find('.email'),
                        registrationNumber = formWrapper.find('#registration-number'),
                        detailsTyres = $('.details-tyres');

                    //Check if vehicle selector exist on checkout
                    if (formWrapper.find('.quick-finder').length) {

                        if (selectVehicle.length) {
                            selectVehicle.on('change', function (e) {
                                var option = $(this);
                                //toggle vehicle selector and rego field
                                var displayElem = function (isEnable) {
                                    vehicleSelector.toggle(isEnable);
                                    registrationNumber.parent('div').toggle(isEnable);
                                }
                                switch (option.val()) {
                                    case 'new':
                                        displayElem(true);
                                        beauComponents.quickFinderSearch.init('.quick-finder');
                                        registrationNumber.val('');
                                        break;
                                    default:
                                        displayElem(false);
                                        registrationNumber.val(option.find(':selected').attr('data-rego'));
                                        detailsTyres.val(option.find(':selected').attr('data-vehicle'));
                                }
                            });
                        }

                        registrationNumber.on('keyup', function () {
                            if ($(this).val().length >= 6 && vehicleSelector.attr('data-reg-search')==1) {
                                $this._findVehicle(vehicleSelector.attr('data-vehicle-url'),
                                    emailInput.val(),
                                    $(this).val()
                                )
                            }
                        });

                    }
                },
                '_findVehicle': function (requestUrl, email, rego) {
                    var $this = this, quickFinderForm = $('.quick-finder'),
                        msgElem = $('.guest-msg');

                    $this._clearSelect(quickFinderForm);

                    var request = $.ajax({
                        url: requestUrl,
                        type: "post",
                        data: {'email': email, 'rego': rego},
                        dataType: 'json',
                        beforeSend: function () {
                            msgElem.parent().find('.load-indicator').toggle(true);
                            msgElem.toggle(true).html(msgElem.attr('data-msg-beforesend'));
                        },
                    });

                    request.done(function (data) {

                        if (data.error == 0) {
                            msgElem.toggle(true).html(msgElem.attr('data-msg-result'));
                            var result = $.parseJSON(data.value);
                            for (var property in result) {
                                quickFinderForm.find('select[name=' + property + ']').attr('data-pre-load', result[property]);
                            }
                        } else {
                            msgElem.toggle(false);
                        }
                        msgElem.parent().find('.load-indicator').toggle(false);
                        beauComponents.quickFinderSearch._setPreLoadedValue();
                    });
                },
                '_clearSelect':function(quickFinderForm){
                    var $this = this;
                    //reset value _resetSelect
                    quickFinderForm.find('select').each(function(){
                        var thisSelect = $(this);
                        thisSelect.val('');

                    });

                    beauComponents.quickFinderSearch._resetSelect($('select[name=model-tyres]'));
                    beauComponents.quickFinderSearch._resetSelect($('select[name=series-tyres]'));

                },
            },
            'convertImgToSvg': function(item) {
              var getThisSvg = function() {
                var $img =  $(this),
                  imgID = $img.attr('id'),
                  imgClass = $img.attr('class'),
                  imgURL = $img.attr('src').replace(/^(http|https):\/\/([^/]+)\//, '/');

                $.ajax({
                  method: 'GET',
                  url: imgURL,
                  dataType: 'xml'
                }).done(function(data) {
                  var $svg = $(data).find('svg');

                  if (typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                  }
                  if (typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass+' replaced-svg');
                  }
                  $svg = $svg.removeAttr('xmlns:a');
                  $img.replaceWith($svg);
                })
              }

              $(item).each(getThisSvg);
            },
            'handleTyreSizeForm': function(item) {
              var $form = $(item);

              if ( $form.length ) {
                var $graphicHolder = $form.find('.type-size-graphic'),
                  $select = $form.find('.width-tyres,.profile-tyres,.diameter-tyres');

                var showActive = function() {
                  var showActiveClass = $(this).data('show-active');
                  $graphicHolder.attr('class','type-size-graphic show-active-'+showActiveClass);
                }

                var removeActive = function() {
                  $graphicHolder.attr('class','type-size-graphic');
                }

                $select.on('click mouseenter', showActive);
                $select.on('change mouseleave', removeActive);
              }
            },
            'getCookies': function() {
                var $vehicleTypeInputs = $('.vehicle-type, .tyre-size').find('select');

                var selectedCookies = function() {
                    $(this).each(function (){
                        var attrName = $(this).attr('name'),
                            attrVal = $(this).val(),
                            d = new Date();
                            d.setTime(d.getTime() + (36135*24*60*60*1000));
                            var expires = "expires="+ d.toUTCString();

                        $(this).prop(document.cookie = attrName + "=" + attrVal + ';' + expires)
                    });
                };

                $vehicleTypeInputs.on('change', selectedCookies);

            },
            'reviewVotes': function(){

                var votePanel = $('.vote-review-panel'),
                    reviewCont = $('.reviews-container'),
                    reviewVote = votePanel.find('button');

                var populateVote = function(upvote,downVote){
                    var totalVote = upvote + downVote;
                    var msg = "";
                    if(upvote == 1 && totalVote == 1){
                        msg = upvote + " person found this helpful.";
                    }else if(upvote >= 1 && totalVote != 1){
                        msg = upvote +" of " + totalVote + " people found this helpful.";
                    }
                    return msg;
                };

                reviewCont.find('.review-item').each(function(){
                    var item = $(this);
                    var upVote = item.find('.accounts-voted').data('upvote');
                    var downVote = item.find('.accounts-voted').data('downvote');
                    item.find('.accounts-voted').html(populateVote(upVote,downVote));
                });

                var getVote = function(e){

                    e.preventDefault();

                    var btn = $(this),
                        btnParent = btn.closest('.review-item'),
                        reviewVoteId = btnParent.data('review-id'),
                        reviewVotes =  btn.attr('value'),
                        upVote = btnParent.find('.accounts-voted').data('upvote'),
                        downVote = btnParent.find('.accounts-voted').data('downvote');

                        // this condition is just to make voting real time
                        if(btn.hasClass('yes-btn')){
                            btnParent.find('.accounts-voted').html(populateVote(upVote+1,downVote));
                        } else {
                            btnParent.find('.accounts-voted').html(populateVote(upVote,downVote+1));
                        }

                    var request = $.ajax({
                        method: "GET",
                        dataType: 'JSON',
                        url: reviewCont.data('review-vote-url'),
                        cache:false,
                        data: {
                            review_id: reviewVoteId,
                            vote: reviewVotes
                        }
                    });
                    btn.parent().find('button').addClass('hide');
                    btn.parent().find('.voted-submit').addClass('submitted');


                };

                reviewVote.on('click', getVote);
            },
            'triggerAdroll': function(){

                var adrollRPriceButton = $('.adroll-request-price-button'),
                    adrollRPriceForm = $('.adroll-rprice-form'),
                    adrollBookFittingButton = $('.adroll-bookfitting-button'),
                    adrollBookFittingForm = $('.adroll-bookfitting-form'),
                    adrollsearchForm = $('.adroll-search-form');

                //Request Price Click Button
                if(adrollRPriceButton.length){
                    adrollRPriceButton.on('click',function(){
                        // console.log('adroll:546640fb');
                        try{
                            __adroll.record_user({"adroll_segments": "546640fb"});
                        } catch(err) {}
                    });
                }

                //Request Price Submit Form
                if(adrollRPriceForm.length){
                    adrollRPriceForm.on('valid.fndtn.abide', function() {
                        // console.log('adroll:eece00a4');
                        try{
                            __adroll.record_user({"adroll_segments": "eece00a4"});
                        } catch(err) {}
                    });
                }

                //Book A Fitting Click button
                if(adrollBookFittingButton.length){
                    adrollBookFittingButton.on('click',function(){
                        // console.log('adroll:ec437849');
                        try{
                            __adroll.record_user({"adroll_segments": "ec437849"});
                        } catch(err) {}
                    });
                }

                //Book A Fitting Submit Form
                if(adrollBookFittingForm.length){
                    adrollBookFittingForm.on('valid.fndtn.abide', function() {
                        // console.log('adroll:ca9bfb32');
                        try{
                            __adroll.record_user({"adroll_segments": "ca9bfb32"});
                        } catch(err) {}
                    });
                }

                // Search Submit Form
                if(adrollsearchForm.length){
                    adrollsearchForm.on('submit',function(){
                        try{
                            __adroll.record_user({"adroll_segments": "324bc162"});
                        } catch(err) {}
                    });
                }

            },
            'mygiftStoreLocator':function(elem){
                var secElem = $(elem);
                var inputElem = secElem.find('.store-finder.render-finder');
                if(secElem.length){
                    beauComponents.productFormModal.handleStoreFinder(secElem, inputElem);
                };
            },
            'homePromoBars': function(elem) {
              // Renders content for home-prom-bars used on Goodyear site
              // CMS content is not to be trusted!
              var $promoBarsHolder = $(elem);

              if ($promoBarsHolder.length) {
                var $bars = $promoBarsHolder.find('li:not(.rendered)');

                // loop through non-rendered bars
                for (var i=0,n=$bars.length; i<n; i++) {
                  var $bar = $bars.eq(i),
                    title = $bar.find('h1, h2, h3, h4, p').text(),
                    imageUrl = $bar.find('img').attr('src'),
                    buttonUrl = $bar.find('a').attr('href'),
                    buttonText = $bar.find('a').html();

                  // empty bar and render new contents
                  $bar
                    .empty()
                    .css('background-image', 'url(' + imageUrl + ')')
                    .append( $('<h2 />').html(title) )
                    .append(
                      $('<a />')
                        .addClass('radius button')
                        .attr('href', buttonUrl)
                        .html(buttonText)
                    )
                    .addClass('rendered');
                }
              }
            },
            'init': function () {
                beauComponents.analytics.init('.ga-source');
                beauComponents.heroSlider('.hero-slider');
                beauComponents.promoSlider('.promo-slider');
                beauComponents.productSlider('.product-slider');
                beauComponents.detailProductSlider('.detail-product-slider');
                beauComponents.relatedProductSlider('.related-product-slider');
                //beauComponents.wheelFinderSlider('.wheel-finder-slider');
                beauComponents.staticBlockColumn('.static-block-slider');
                beauComponents.galleryZoom('.item-to-append', '.main-photo-wrapper', '.magnify-source');
                beauComponents.scrollToName('.add-review', 'add-review');
                beauComponents.scrollToName('.customer-reviews', 'customer-reviews');
                beauComponents.toggleShow('.js-toggle-click', '.top-search-form, .top-link-inner-link');
                beauComponents.accordion('.filterdt', '.filtercont');
                beauComponents.formTouchScreen('input, textarea, option, select');
                beauComponents.datepicker('.select_date', '.fit_date');
                beauComponents.googlePlacesFields('#quickLocateAddress,.get-google-places');
                beauComponents.quickFinderSearch.init('.quick-finder:visible');
                beauComponents.guestUserQuickFinderSearch('.checkoutGuestCheckbox');
                beauComponents.wheelFinder.init('.wheel-list');
                beauComponents.getVehicleModel('.get-vehicle-make');
                beauComponents.productFormModal.init('.product-form-modal');
                beauComponents.addProductModal.init('.add-product-modal');
                beauComponents.formatPrice('.format-price');
                beauComponents.showPageNotice('.page-notice:visible', '.my-account ul.messages');
                beauComponents.fullPageLoadIndicator.autoShow('.start-page-load-indicator', '.end-page-load-indicator');
                beauComponents.productPricing.init('.cart-component');
                beauComponents.cartItemCount('.cart-item-count');
                beauComponents.newsLetterForm();
                beauComponents.promoBarMessages();
                beauComponents.setDefaultStore();
                beauComponents.triggerAdroll();
                beauComponents.geoLocation();
                beauComponents.giftCardForn('.gift-card-form');
                beauComponents.customerMobility();
                beauComponents.showMore();
                beauComponents.autoPopulateVehicle.init('#checkout-step-billing');
                beauComponents.convertImgToSvg('img.svg');
                beauComponents.handleTyreSizeForm('.tyre-size');
                beauComponents.accordionScroll();
                beauComponents.getCookies();
                beauComponents.reviewVotes();
                beauComponents.mygiftStoreLocator('.cms-mygiftcard');
                beauComponents.mygiftStoreLocator('.page-empty');
                beauComponents.homePromoBars('.home-promo-bars');
            }
        };


        // Initialising beauComponents
        beauComponents.init();

        // make global
        beauAppComponents = beauComponents;
    });

})(jQuery);
